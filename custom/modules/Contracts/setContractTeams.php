<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class setContractTeams {

    public static $appAssignedUserChanged = false;

    public function checkAssignedUser($bean, $event, $arguments) {
        if ($bean->fetched_row['assigned_user_id'] != $bean->assigned_user_id) {
            self::$appAssignedUserChanged = true;
        }
        
        if (empty($bean->fetched_row['id'])){
            if(empty($bean->contracts_leads_1leads_idb)){
                $bean->assigned_user_id = null;
            }else{
                $lead = BeanFactory::getBean('Leads', $bean->contracts_leads_1leads_idb);
                $bean->assigned_user_id=$lead->assigned_user_id;
                $bean->team_id=$lead->team_id;
            }
        }
    }

    function setTeamsInContract($bean, $event, $arguments) {
        /*
         * Check If assigned user is changed
         */
        if (self::$appAssignedUserChanged && !empty($bean->assigned_user_id)) {
            global $sugar_config;
            require_once('modules/Teams/TeamSet.php');
            $teamSetBean = new TeamSet();
            $primaryTeam = '';
            
            /*
             * Deciding Primary Team 
             */
                $myUser = new User();
                $myUser->retrieve($bean->assigned_user_id);
                $teams = $teamSetBean->getTeams($myUser->team_set_id);
                $primaryTeam = $myUser->default_team;
            /*
             * Setting Primary Team in Lead
             */
            $bean->team_id = $primaryTeam;

            /*
             * Setting Secondary Teams in Lead
             */
            $team_id = array();
            foreach ($teams as $key => $team) {
                $team_id[] = $key;
            }
            if (empty($team_id))
                $team_id[] = $primaryTeam;

            $bean->load_relationship('teams');
            $bean->teams->replace($team_id);
            $bean->processed = true;
            $bean->save();
        }
    }

}

?>