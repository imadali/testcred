<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class autoAssign {

    function autoAssignUser($bean, $event, $arguments) {
        global $current_user, $sugar_config;
        if (!empty($_REQUEST['__sugar_url']) && !isset($_REQUEST['viewed'])) {
            $check = explode('/', $_REQUEST['__sugar_url']);
            if (isset($check[0]) && isset($check[4]) && $check[0] == "v10" && $check[4] == "leads") {
                //if ($check[0] == "v10" && $check[1] == "Leads") {
                if ($sugar_config['auto_assign']) {
                    if (empty($bean->dotb_correspondence_language_c)) {
                        $GLOBALS['log']->debug("The Correspondence Language is empty so auto assigning is skipped ");
                    } else {
                        $id_user = $this->selectUser($bean->birthdate, $bean->dotb_correspondence_language_c, $bean->lead_type_id_c, $bean->first_name, $bean->last_name);
                        $bean->assigned_user_id = $id_user;
                        $GLOBALS['log']->debug("User was assigned to the Lead successfully. User id= $id_user");
                    }
                } else {
                    $GLOBALS['log']->debug("Lead Auto Assignment is disabled. Please set the auto_assign flag in the config file.");
                }
            } else {
                $GLOBALS['log']->debug("Lead API conditions did not match");
            }
        }
    }

    /**
     * 
     * @param string $language Langue du contact
     * @param int $weekday jour de la semaine (0=Sun , 6=Sat)
     * @param string $lead_type Categorie de la CR (A, B ou NULL))
     * @return string Id de l'utilisateur
     */
    private function selectUser($birthdate, $language, $lead_type, $first_name, $last_name) {
        global $sugar_config, $timedate;
        $birthdate = trim($birthdate);
        $language = trim($language);
        $lead_type = trim($lead_type);
        $first_name = trim($first_name);
        $last_name = trim($last_name);
        $selected_user_id = null;
        $contact_assigned_user_id = null;

        $db = DBManagerFactory::getInstance();
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
        $GLOBALS['log']->debug("Select user on the basis of birthdate: $birthdate, language: $language, lead_type: $lead_type, first_name: $first_name, last_name: $last_name");
        if (empty($birthdate)) {
            $duplicate_contact_query = "SELECT id, assigned_user_id FROM contacts WHERE first_name = '".$first_name."' AND last_name = '".$last_name."' AND deleted=0 ORDER BY date_entered DESC LIMIT 0,2";
        } else {
            $birthdate = new DateTime($birthdate);
            $birthdate = $timedate->asDbDate($birthdate);
            $duplicate_contact_query = "SELECT id, assigned_user_id FROM contacts WHERE first_name = '".$first_name."' AND last_name = '".$last_name."' AND birthdate='".$birthdate."' AND deleted=0 ORDER BY date_entered DESC LIMIT 0,2";
        }
		
        $GLOBALS['log']->debug("Contact retrieval query: $duplicate_contact_query");
        $duplicate_contact_result = $db->query($duplicate_contact_query);
        if($duplicate_contact_result->num_rows > 1) {
            while ($contact_row = $db->fetchByAssoc($duplicate_contact_result)) {
                $contact_assigned_user_id = $contact_row['assigned_user_id'];
            }
        }		
        
        if (!empty($contact_assigned_user_id)) {
            $myUser = new User();
            $myUser->retrieve($contact_assigned_user_id);
            if ($myUser->status == 'Active' && $myUser->dotb_is_active) {
                $selected_user_id = $contact_assigned_user_id;
                $GLOBALS['log']->debug("The contact aleady exsit for Auto Assignement Lead. Lead Name: $first_name $last_name");
            }
        }

        if (empty($selected_user_id)) {
            $GLOBALS['log']->debug("The contact does not aleady exsit for Auto Assignement Lead. Lead Name: $first_name $last_name");
            $query = "SELECT id as user_id FROM users WHERE status='Active' AND dotb_is_active = 1 AND dotb_working_days LIKE '%^" . $day . "^%' AND dotb_spoken_languages LIKE '%^" . $language . "^%' AND deleted=0";
            $rs = $db->query($query);
            /*
             * If there is one assign to him, if there is noone assign to services account.
             */
            if ($rs->num_rows == 1) {
                $row = $db->fetchByAssoc($rs);
                $selected_user_id = $row['user_id'];
            } else {
                /*
                 * If there is more than one, check number of assigned credit request of same lead type to each employee on that day (period starting at 18:00 the day before/ending at 17:59) and assign the new credit request to the employee with min. value.
                 */
                $users_leads_arr = array();
                while ($row = $db->fetchByAssoc($rs)) {
                    $user_id = $row['user_id'];
                    if (empty($lead_type)) {
                        $lead_type_check = "(leads_cstm.lead_type_id_c='' OR leads_cstm.lead_type_id_c IS NULL)";
                    } else {
                        $lead_type_check = "leads_cstm.lead_type_id_c='$lead_type'";
                    }
                    $q = "SELECT * FROM leads JOIN leads_cstm ON leads.id=leads_cstm.id_c WHERE $lead_type_check AND leads.date_entered BETWEEN '$from' AND '$to'  AND leads.deleted=0 AND leads.assigned_user_id ='$user_id'";
                    $count_leads = $db->query($q);
                    $users_leads_arr[$user_id] = $count_leads->num_rows;
                }
                $users_leads_arr = array_keys($users_leads_arr, min($users_leads_arr));
                if (count($users_leads_arr) == 1) {
                    $selected_user_id = $users_leads_arr[0];
                } else {
                    /* If numbers are minimum and same for more than one user, check same number for other lead type of credit requests (if new credit request is A check for B, and vice versa) and assign to the minimum.
                     * As many users have same count of assigned lead so check which user have least lead count for other lead type
                     */
                    $users_leads_other_type_arr = array();
                    foreach ($users_leads_arr as $key => $user_id) {
                        if ($lead_type == 'a') {
                            $lead_type = 'b';
                        }
                        if ($lead_type == 'b') {
                            $lead_type = 'a';
                        }
                        $q = "SELECT * FROM leads JOIN leads_cstm ON leads.id=leads_cstm.id_c WHERE leads_cstm.lead_type_id_c='$lead_type' AND leads.date_entered BETWEEN '$from' AND '$to' AND leads.deleted=0 AND leads.assigned_user_id ='$user_id'";
                        $new_count_leads = $db->query($q);
                        $users_leads_other_type_arr[$user_id] = $new_count_leads->num_rows;
                    }
                    $users_leads_other_type_arr = array_keys($users_leads_other_type_arr, min($users_leads_other_type_arr));
                    /*
                     * If still the same randomly assign to one employee from that final subset
                     */
                    $random_number = rand(0, count($users_leads_other_type_arr) - 1);
                    $selected_user_id = $users_leads_other_type_arr[$random_number];
                }
            }
        }
        /*
         * In any case when Lead-Assignment does not have a user to assign a lead to, it will pick a user randomly.
         */
        $last_option=array(1,2,3);
        foreach ($last_option as $key => $value) {
            if (empty($selected_user_id)) {
                $GLOBALS['log']->debug("Lead-Assignment does not have a user to assign to the lead so it will pick a user randomly. Lead Name: $first_name $last_name");
                if ($value == 1) {
                    $all_users_q = "SELECT id as user_id FROM users WHERE  status='Active' AND dotb_is_active = 1  AND dotb_working_days LIKE '%^" . $day . "^%' AND dotb_spoken_languages LIKE '%^" . $language . "^%' AND deleted=0";
                } elseif ($value == 2) {
                    $all_users_q = "SELECT id as user_id FROM users WHERE  status='Active' AND dotb_is_active = 1  AND dotb_spoken_languages LIKE '%^" . $language . "^%' AND deleted=0";
                } elseif ($value == 3) {
                    $all_users_q = "SELECT id as user_id FROM users WHERE  status='Active' AND dotb_is_active = 1  AND deleted=0";
                }
                $allUsers = $db->query($all_users_q);
                $all_user_ids = array();
                while ($row = $db->fetchByAssoc($allUsers)) {
                    $all_user_ids[] = $row['user_id'];
                }
                $random_number = rand(0, count($all_user_ids) - 1);
                $selected_user_id = $all_user_ids[$random_number];
            }
        }
        $GLOBALS['log']->debug("User ID $selected_user_id selected for Auto Assignment to the Lead $first_name $last_name");
        return $selected_user_id;
    }

}

/*
 * How to get user from record by email address.? Please do not delete the following code
 * 
 * $existingSql = "SELECT c.assigned_user_id assigned_user_id FROM contacts c
  JOIN users u ON u.id=c.assigned_user_id AND u.status='Active' AND u.dotb_is_active=1
  WHERE c.id = (
  SELECT eabr.bean_id
  FROM email_addr_bean_rel AS eabr
  INNER JOIN email_addresses AS ea ON eabr.email_address_id = ea.id AND ea.deleted = 0 AND ea.email_address = '$email'
  WHERE eabr.deleted = 0 AND eabr.bean_module = 'Contacts' AND eabr.date_created=(
  SELECT MAX(eabr2.date_created) FROM email_addr_bean_rel AS eabr2
  INNER JOIN email_addresses AS ea2 ON eabr2.email_address_id = ea2.id AND ea2.deleted = 0 AND ea2.email_address = '$email'
  WHERE eabr2.deleted = 0 AND eabr2.bean_module = 'Contacts'))";
  $existingSql="";
  $executed = $db->query($existingSql);
  $row = $db->fetchByAssoc($executed);
  $assigned_user_id = $row['assigned_user_id'];
 * ********************************************
  Getting random user from team
  $GLOBALS['log']->debug('contact user is not active');
  include_once('modules/Teams/Team.php');
  $team = new Team();
  $team->retrieve($myUser->default_team);
  $team_members = $team->get_team_members(true);
  $sales_team_members = array();
  foreach ($team_members as $user) {
  $sales_team_members[] = $user->id;
  }
  if (count($sales_team_members)) {
  $random_number = rand(0, count($sales_team_members) - 1);
  $selected_user_id = $sales_team_members[$random_number];
  }
  } */
?>