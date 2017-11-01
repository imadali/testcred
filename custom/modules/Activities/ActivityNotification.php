<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once('include/SugarQueue/SugarJobQueue.php');

class ActivityNotification {

    public function sendNotification($bean, $event, $arguments) {

        if ($bean->module_name == 'Activities') {

            global $current_user;
            $subject = "";
            $last_comment = json_decode($bean->last_comment);

            $query = "SELECT id,name,assigned_user_id FROM kbcontents WHERE id = '" . $bean->parent_id . "' AND deleted = 0";

            $result = $GLOBALS['db']->query($query);
            $row = $GLOBALS['db']->fetchByAssoc($result);

            if (!empty($last_comment->data)) {
                $data = $last_comment->data;
                $subject = $current_user->first_name . ' ' . $current_user->last_name . ' added a comment on the article ' . $row['name'];
                $message = preg_replace('/(\@)\[\w+\:[a-f0-9-]{36}\:([\w ]+)\]/u', '$1$2', $data->value);
            }


            if (isset($row['id'])) {
                $userBean = BeanFactory::getBean("Users", $row['assigned_user_id']);
                $email = $userBean->email1;

                $data = array('email' => $email, 'subject' => $subject, 'message' => $message);
                $job = new SchedulersJob();
                $job->name = "Send Email Notification";
                $job->data = json_encode($data);
                $job->target = "function::ActivityNotificationJob";
                $job->assigned_user_id = $current_user->id;

                //Push the job in the queue
                $jobQueue = new SugarJobQueue();
                $jobid = $jobQueue->submitJob($job);
            }
        }
    }

}
