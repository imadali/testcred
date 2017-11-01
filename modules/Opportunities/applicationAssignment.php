<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

/**
** set approval user according to the the assignment rules mentioned in CRED-921
**/
class applicationAssignment 
{
    function setApprovalUser($bean, $event, $arguments) 
    {
        global $timedate, $current_user;
        $db = DBManagerFactory::getInstance();
        $application_approval_user = null;
        $approval_user = null;

        $today = TimeDate::getInstance()->getNow(true);
        $day = date('w', strtotime($today));
        if (date('H', strtotime($today)) > 15) {
            if ($day == '5') {
                $day = '1';
            } else {
                $day = $day + 1;
            }
        }
        $GLOBALS['log']->debug("Day after checking hours: " . $day);

        //check that an existing application exists
        $lead_id = $bean->leads_opportunities_1leads_ida;
        $application_provider = $bean->provider_id_c;

        $linked_application_query = "SELECT app.id,app_cstm.user_id_c FROM opportunities app LEFT JOIN opportunities_cstm app_cstm ON app.id = app_cstm.id_c LEFT JOIN leads_opportunities_1_c lead_app on app.id = lead_app.leads_opportunities_1opportunities_idb WHERE lead_app.leads_opportunities_1leads_ida= '".$lead_id."' AND lead_app.deleted=0 AND lead_app.leads_opportunities_1opportunities_idb <> '".$bean->id."' AND app.deleted=0  ORDER BY app.date_modified DESC LIMIT 0, 1";
        $GLOBALS['log']->debug("Existing application query: $linked_application_query");
        $linked_application_result = $db->query($linked_application_query);

        if($linked_application_result->num_rows > 0) {
            while ($application_row = $db->fetchByAssoc($linked_application_result)) {
                $approval_user = $application_row['user_id_c'];
            }
        }

        if (!empty($approval_user)) {
            $myUser = new User();
            $myUser->retrieve($approval_user);
            if ($myUser->status == 'Active' && $myUser->application_assignment) {
                $user_working_days = $myUser->dotb_working_days;
                $user_application_provider = $myUser->application_provider;
                if (strpos($user_working_days, $day) !== false && strpos($user_application_provider, '^' .$application_provider . '^') !== false) {
                    $application_approval_user = $approval_user;
                    $GLOBALS['log']->debug("Existing application approval user to be used for Application-Assignment. User Id: $approval_user");
                }
            }
        }

        // either no application exists or the approval user do not full fil the criteria
        if(empty($application_approval_user)) {
            /** Check the following criterias:
            * tasks with subject 'Antrag bei Bank einreichen' and status 'Open' assigned to user which is active has Application-Assignment checked Application Provider selected and works today(after 3 p.m. check user is working tomorrow)
            */
            $task_user_query = "SELECT u.id, COUNT(t.id) AS task_count FROM users u"
                                . " LEFT JOIN tasks t ON t.assigned_user_id = u.id AND t.name LIKE 'Antrag bei Bank einreichen' AND t.status = 'open' AND t.deleted = 0"
                                ." WHERE u.status = 'Active' AND u.application_assignment = 1 AND u.application_provider LIKE '%^" . $application_provider . "^%' AND u.dotb_working_days LIKE '%^" . $day . "^%' AND u.deleted=0 GROUP BY u.id ORDER BY task_count";
            $GLOBALS['log']->debug("Tasks count per user: $task_user_query");
            $task_user_result = $db->query($task_user_query);
            $users_tasks = array();
            while ($user_task = $db->fetchByAssoc($task_user_result)) {
                $users_tasks[$user_task['id']] = $user_task['task_count'];
            }
            //check that all users have same number of tasks on that day
            if (count(array_unique($users_tasks)) > 1) {
                //assign user with least number of applications
                reset($users_tasks);
                $application_approval_user = key($users_tasks);
                $GLOBALS['log']->debug("Approval user with least number of open tasks is assigned. User Id: $application_approval_user");
            } else {
                // Then check number of tasks with subject 'Antrag bei Bank einreichen' with status 'Open' which have the same 'Provider' as the application
                $task_user_provider_query = "SELECT u.id, COUNT(t.id) AS task_count FROM users u"
                                . " LEFT JOIN tasks t ON t.assigned_user_id = u.id AND t.name LIKE 'Antrag bei Bank einreichen' AND t.status = 'open' AND t.deleted = 0"
                                . " LEFT JOIN tasks_cstm t_cstm ON t.id = t_cstm.id_c"
                                ." WHERE u.status = 'Active' AND u.application_assignment = 1 AND u.application_provider LIKE '%^" . $application_provider . "^%' AND u.dotb_working_days LIKE '%^" . $day . "^%' AND u.deleted=0 AND  t_cstm.application_provider_c = '" . $application_provider . "' GROUP BY u.id ORDER BY task_count";

                $GLOBALS['log']->debug("Task count per user per provider query: $task_user_provider_query");
                $task_user_provider_result = $db->query($task_user_provider_query);
                $users_task_provider = array();
                while ($user_task_provider = $db->fetchByAssoc($task_user_provider_result)) {
                    $users_task_provider[$user_task_provider['id']] = $user_task_provider['task_count'];
                }

                //check that all users have same number of applications per provider on that day
                if (count(array_unique($users_task_provider)) > 1) {
                    //assign user with least number of applications per provider
                    reset($users_task_provider);
                    $application_approval_user = key($users_task_provider);
                    $GLOBALS['log']->debug("Approval user with least number of tasks per provider is assigned. User id: $application_approval_user");	
                } else {
                    //assign randomly based on working day and provider
                    $user_query = "SELECT id as user_id FROM users WHERE status='Active' AND application_assignment = 1 AND dotb_working_days LIKE '%^" .$day . "^%' AND application_provider LIKE '%^" . $application_provider . "^%' AND deleted=0";
                    $GLOBALS['log']->debug("Users query working on that day and for the provider: $user_query");
                    $user_query_result = $db->query($user_query);
                    $all_user_ids = array();
                    while ($user_row = $db->fetchByAssoc($user_query_result)) {
                        $all_user_ids[] = $user_row['user_id'];
                    }
                    $random_number = rand(0, count($all_user_ids) - 1);
                    $application_approval_user = $all_user_ids[$random_number];
                    $GLOBALS['log']->debug("Random user assigned. User id= $application_approval_user");
                }
            }
        }
        $GLOBALS['log']->debug("Approval user assigned to application. User id= $application_approval_user");
        return $application_approval_user;  
    }
}

?>