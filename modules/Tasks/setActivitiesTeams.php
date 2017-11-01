<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class setActivitiesTeams
{

    public static $parentChanged = false;
    public static $parentType = null;
    public static $parentId = null;

    public function checkParentChanged($bean, $event, $arguments)
    {
        if (!empty($bean->parent_id) && $bean->fetched_row['parent_id'] != $bean->parent_id) {
            self::$parentChanged = true;
            self::$parentType = $bean->parent_type;
            self::$parentId = $bean->parent_id;
        }
        if (isset($bean->contact_id)) {
            if (!empty($bean->contact_id) && $bean->fetched_row['contact_id'] != $bean->contact_id) {
                self::$parentType = 'Contacts';
                self::$parentId = $bean->contact_id;
            }
        }
    }

    function setTeamsInActivities($bean, $event, $arguments)
    {
        /*
         * Check If Parent is changed is changed
         */
        if (self::$parentChanged && !empty(self::$parentType) && !empty(self::$parentId) && !$bean->created_from_workflow && !$bean->created_from_app) {
            global $sugar_config;
            require_once('modules/Teams/TeamSet.php');
            $teamSetBean = new TeamSet();
            $primaryTeam = '';
            $teamFromPA = '';
            $parent = BeanFactory::getBean(self::$parentType, self::$parentId);
            if (isset($parent->id)) {
                $parent_teams = $teamSetBean->getTeams($parent->team_set_id);

                /**
                 * CRED-925 : Tasks created from PA should inherit linked leads teams
                 * Setting Customer Care as primary Team for Tasks Created via PA
                 * CRED-954 : Updated condition that task name is 'Nachfassen: Kundencenter > Hinweis Dokument hochladen' and Customer Care team is present then only set CC as primary. 
                 */
                $team = new Team();
                $team->retrieve($bean->team_id);
                if ($bean->module_name == 'Tasks' && $team->name == 'Customer Care' && $bean->name == 'Nachfassen: Kundencenter > Hinweis Dokument hochladen') {
                    $bean->team_id = $team->id;
                    $teamFromPA = $parent->team_id;
                } else {
                    /**
                     * Setting Primary Team in Activity
                     */
                    $bean->team_id = $parent->team_id;
                }
               
                /*
                 * Setting Secondary Teams in Lead
                 */
                $team_id = array();
                foreach ($parent_teams as $key => $team) {
                    $team_id[] = $key;
                }
                /*
                 * CRED-940: Additional teams should be saved for calls
                 */
                if ($bean->module_name == 'Tasks' || $bean->module_name == 'Calls') { 
                    $activity_teams = $teamSetBean->getTeams($bean->team_set_id);
                    foreach ($activity_teams as $key => $team) {
                        $team_id[] = $key;
                    }
                }


                /**
                 * CRED-925 : Tasks created from PA should inherit linked leads teams
                 * Setting Customer Care as primary Team for Tasks Created via PA
                 */
                if (!empty($teamFromPA)) {
                    $team_id[] = $teamFromPA;
                }
                if (empty($team_id)) {
                    $team_id[] = $parent->team_id;
                }

                /*
                 * Adding Global team in All activities
                 */
                $team_id[] = '1';

                $bean->load_relationship('teams');
                $bean->teams->replace($team_id);
                $bean->processed = true;
                $bean->save();
            }

            /*
             * Updating the field no_of_open_tasks in the Lead
             */
            if ($bean->module_name == 'Tasks' && $bean->parent_type == 'Leads' && !empty($bean->parent_id)) {
                $result = $GLOBALS['db']->query("SELECT COUNT(*) number_of_open_tasks FROM tasks WHERE parent_type = 'Leads' AND parent_id = '$bean->parent_id' AND status <> 'closed' AND deleted=0");
                $result = $GLOBALS['db']->fetchByAssoc($result);
                $number_of_open_tasks = $result['number_of_open_tasks'];
                $GLOBALS['db']->query("UPDATE leads SET number_of_open_tasks =$number_of_open_tasks WHERE id = '$bean->parent_id'");
            }
        }
    }

}

?>