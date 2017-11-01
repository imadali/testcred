<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

/**
** set approval user according to the the assignment rules mentioned in CRED-459
**/
class applicationAssignment 
{
    function setApprovalUser($bean, $event, $arguments) 
    {
        global $timedate;
        $db = DBManagerFactory::getInstance();
        $application_approval_user = null;
        $approval_user = null;

        $today = new DateTime($timedate->nowDb());
        $today = $today->format('Y-m-d');
        $yesterday = date('Y-m-d', (strtotime('-1 day', strtotime($timedate->nowDb()))));
        $from = $yesterday . ' 18:00:00';
        $to = $today . ' 17:59:00';
        $day = date('N');
        if (date('H') > 17) {
            $day += 1;
        }
        if ($day == '7') {
            $day = '0';
        }

        //Trigger: when saving a new Application.
        // if(empty($bean->fetched_row['id'])){
            //check that an existing application exists
            $lead_id = $bean->leads_opportunities_1leads_ida;
            $application_provider = $bean->provider_id_c;

            $linked_application_query = "SELECT app.id,app_cstm.user_id_c FROM opportunities app LEFT JOIN opportunities_cstm app_cstm ON app.id = app_cstm.id_c LEFT JOIN leads_opportunities_1_c lead_app on app.id = lead_app.leads_opportunities_1opportunities_idb WHERE lead_app.leads_opportunities_1leads_ida= '".$lead_id."' AND lead_app.deleted=0 AND app.deleted=0  ORDER BY app.date_entered DESC LIMIT 0, 1";
            $GLOBALS['log']->debug("Existing application query: $linked_application_query");
            $linked_application_result = $db->query($linked_application_query);

            if($linked_application_result->num_rows > 0){
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
			
            // either no application exists or the approval user do not work on that day
            if(empty($application_approval_user)){
                //number of applications assigned to User who is active and has Application-Assignment checkbox checked and works today
                $application_user_query = "SELECT u.id, COUNT(app.id) AS app_count FROM users u"
                                            . " LEFT JOIN opportunities_cstm app_cstm ON u.id = app_cstm.user_id_c"
                                            . " LEFT JOIN opportunities app ON app_cstm.id_c = app.id AND app.deleted=0 AND app.date_entered BETWEEN '" . $from . "' AND '" . $to . "'"   
                                            . " WHERE u.status = 'Active' AND u.application_assignment = 1 AND u.dotb_working_days LIKE '%^" . $day . "^%' AND u.application_provider LIKE '%^" . $application_provider . "^%' AND u.deleted=0 GROUP BY u.id ORDER BY app_count";
                $GLOBALS['log']->debug("Application count per user query: $application_user_query");
                $application_user_result = $db->query($application_user_query);
                $users_applications = array();
                while ($user_app = $db->fetchByAssoc($application_user_result)) {
                    $users_applications[$user_app['id']] = $user_app['app_count'];
                }
                //check that all users have same number of applications on that day
                if (count(array_unique($users_applications)) > 1) {
                    //assign user with least number of applications
                    reset($users_applications);
                    $application_approval_user = key($users_applications);
                    $GLOBALS['log']->debug("Approval user with least number of applications is assigned. User Id: $application_approval_user");
					
                } else {
                    //assign application to user with fewest applications corresponding to provider on application
                    $application_user_provider_query = "SELECT u.id, COUNT(app.id) AS app_count FROM users u"
                                            . " LEFT JOIN opportunities_cstm app_cstm ON u.id = app_cstm.user_id_c AND app_cstm.provider_id_c = '" . $application_provider . "'"
                                            . " LEFT JOIN opportunities app ON app_cstm.id_c = app.id AND app.deleted=0 AND app.date_entered BETWEEN '" . $from . "' AND '" . $to . "'"   
                                            . " WHERE u.status = 'Active' AND u.application_assignment = 1 AND u.dotb_working_days LIKE '%^" . $day . "^%' AND u.application_provider LIKE '%^" . $application_provider . "^%' AND u.deleted=0 GROUP BY u.id ORDER BY app_count";

                    $GLOBALS['log']->debug("Application count per user per provider query: $application_user_provider_query");
                    $application_user_provider_result = $db->query($application_user_provider_query);
                    $users_applications_provider = array();
                    while ($user_app_provider = $db->fetchByAssoc($application_user_provider_result)) {
                        $users_applications_provider[$user_app_provider['id']] = $user_app_provider['app_count'];
                    }

					//check that all users have same number of applications per provider on that day
                    if (count(array_unique($users_applications_provider)) > 1) {
                        //assign user with least number of applications per provider
                        reset($users_applications_provider);
                        $application_approval_user = key($users_applications_provider);
                        $GLOBALS['log']->debug("Approval user with least number of application per provider is assigned. User id: $application_approval_user");	
                    } else {
                        //assign randomly based on working day and provider
                        $user_query = "SELECT id as user_id FROM users WHERE status='Active' AND application_assignment = 1 AND dotb_working_days LIKE '%^" .$day . "^%' AND application_provider LIKE '%^" . $bean->provider_id_c . "^%' AND deleted=0";
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
        // }  
    }
}

?>