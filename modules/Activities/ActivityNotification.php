<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarQueue/SugarJobQueue.php';

class ActivityNotification
{
    /**
     * CRED-666 : Adding Comments on Article and notifying the Assigned User
     * 
     * @param  array  $bean       Bean Object
     * @param  array  $event      Events Array
     * @param  array  $arguments  Arguments Array
     *
     * @return None
     */
    public function sendNotification($bean, $event, $arguments)
    {
        global $current_user;
        $recepient = array();
        $last_comment = '';
        $tags = array();
        
        /**
         * CRED-867 : 666 - Refactoring - Remove multiple function calls
         * 
         */
        if ($bean->module_name == 'Activities' && isset($bean->activity_type) && $bean->parent_type == 'KBContents') {
            if ($bean->activity_type == 'update') {
                $last_comment = json_decode($bean->last_comment);
                $tags = $last_comment->data->tags;
                $last_comment = $last_comment->data;
            }
            if ($bean->activity_type == 'post') {
                if (empty($bean->last_comment_bean->id)) {
                    $last_comment = json_decode($bean->data);
                    $tags = $last_comment->tags;
                } else {
                    $last_comment = json_decode($bean->last_comment);
                    $tags = $last_comment->data->tags;
                    $last_comment = $last_comment->data;
                }
            }

            $result = $this->getCommentDetails($last_comment, $tags, $bean->parent_id);
            $recepient = $result['emails'];
        }

        if (!empty($recepient) && !empty($result['message'])) {
            $recepient = array_unique($recepient);
            $data = array('email' => $recepient, 'subject' => $result['subject'], 'message' => $result['message']);
            
            $job = new SchedulersJob();
            $job->name = "Send Email Notification";
            $job->data = json_encode($data);
            $job->target = "function::activityNotificationJob";
            $job->assigned_user_id = $current_user->id;

            //Push the job in the queue
            $jobQueue = new SugarJobQueue();
            $jobid = $jobQueue->submitJob($job);
        }
    }

    public function getCommentDetails($details, $tags, $parent_id)
    {
        global $current_user,$sugar_config;
        $subject = "";
        $message = "";
        $count = 0;
        $address = array();
        
        $query = "SELECT id, name, assigned_user_id"
                . " FROM kbcontents WHERE id = '".$parent_id."'"
                . " AND deleted = 0";
        
        $result = $GLOBALS['db']->query($query);
        $row = $GLOBALS['db']->fetchByAssoc($result);
        $subject = $current_user->first_name. ' ' .$current_user->last_name.' hat den Artikel '.$row['name'].' kommentiert';
        if (isset($details->value)) {
            $message = $details->value;
            $count = substr_count($details->value, "@");
            for ($i = 0; $i < $count; $i++) {
                $message = preg_replace('/(\@)\[\w+\:[a-f0-9-]{36}\:([\w ]+)\]/u', '$1$2', $message);
            }
            $message = preg_replace('/(\@)\[\w+\:[a-f0-9-]{1}\:([\w ]+)\]/u', '$1$2', $message);
            $link = $sugar_config['site_url'] . '/#KBContents/' . $parent_id;
            $message .= "<br><br> <b> Link to KB Article:</b> <a href =" . $link . ">" . $row['name'] . "</a>";

        }       
        $userBean = BeanFactory::getBean("Users", $row['assigned_user_id']);
        
        if(!empty($userBean->notification_email)){
             $address[] = $userBean->notification_email;
        }

        if (!empty($tags)) {
            foreach ($tags as $user) {
                $userBean = BeanFactory::getBean("Users", $user->id);
                $email = $userBean->notification_email;
                $address[] = $email;
            }
        }

        return array('subject' => $subject, 'message' => $message, 'emails' => $address);
    }

}
