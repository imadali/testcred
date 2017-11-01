<?php

if (!defined('sugarEntry'))
    define('sugarEntry', true);
// chdir(realpath(dirname(__FILE__)));
require_once('include/entryPoint.php');
require_once('modules/Teams/TeamSet.php');
global $timedate, $sugar_config;
$credaris_team_id = $sugar_config['dotb']['credaris_team_id'];
$creditum_team_id = $sugar_config['dotb']['creditum_team_id'];
if (isset($credaris_team_id) && isset($creditum_team_id) && !empty($credaris_team_id) && !empty($creditum_team_id)) {

    /*     * *************************************
     * Removing Global Teams From Contacts *
     * ************************************* */
    $sql = "SELECT id,team_id,team_set_id,assigned_user_id FROM contacts where deleted=0 AND global_removed =0 limit 5000";
    $result = $GLOBALS["db"]->query($sql);
    echo "<b>Removing Global Teams From Contacts:</b> <br>";
    $contact_count = 0;
    while ($contact = $GLOBALS["db"]->fetchByAssoc($result)) {
        $contact_id = $contact['id'];
        $team_id = $contact['team_id'];
        $team_set_id = $contact['team_set_id'];
        $assigned_user_id = $contact['assigned_user_id'];
        $replace_team = false;
        $bean = new Contact();
        $bean->retrieve($contact_id);
         if ($bean->id) {
//            echo "<br>$bean->id";
//            continue;

        if ($team_id == '1') {
            $primary_team_id = '';
            if (empty($assigned_user_id)) {
                $primary_team_id = $credaris_team_id;
            } else {
                $qsql = "SELECT id FROM team_memberships where user_id='$assigned_user_id' AND team_id='$creditum_team_id' AND deleted=0";
                $qresult = $GLOBALS["db"]->query($qsql);
                $membership = $GLOBALS["db"]->fetchByAssoc($qresult);
                $membership_id = $membership['id'];
                if (empty($membership_id)) {
                    $primary_team_id = $credaris_team_id;
                } else {
                    $primary_team_id = $creditum_team_id;
                }
            }
            $replace_team = true;
        } else if (empty($team_id)) {
            $replace_team = true;
            $primary_team_id = $credaris_team_id;
        } else {

            $primary_team_id = $team_id;
        }


        if ($team_set_id == '1') {
            $replace_team = true;
        } else {
            $global_in_team_set = $GLOBALS["db"]->query("SELECT count(*) count FROM team_sets_teams  WHERE team_id='1' AND team_set_id='$team_set_id'");
            $global_in_team_set = $GLOBALS["db"]->fetchByAssoc($global_in_team_set);
            $global_in_team_set = (int) $global_in_team_set['count'];
            if ($global_in_team_set > 0)
                $replace_team = true;
        }

        $team_id_arr = array();
        $bean->team_id = $primary_team_id;
        $bean->processed = true;
        $bean->global_removed = true;
        $bean->save();
        $teamSetBean = new TeamSet();
        $teams = $teamSetBean->getTeams($team_set_id);
        $bean->load_relationship('teams');
        foreach ($teams as $key => $team) {
            if ($key != '1') {
                $team_id_arr[] = $key;
            }
        }
        $team_id_arr[] = $primary_team_id;

        $bean->teams->replace($team_id_arr);

        $contact_count++;
    }
    }
    echo "<br> Global Team has been removed from $contact_count contacts";



    /*     * **********************************
     * Removing Global Teams From Leads *
     * ********************************** */
    $sql = "SELECT id,team_id,team_set_id,assigned_user_id FROM leads where deleted=0 AND global_removed=0 limit 5000";
    $result = $GLOBALS["db"]->query($sql);
    echo "<br><br><br> <b>Removing Global Teams From Leads:</b> <br>";
    $lead_count = 0;
    while ($lead = $GLOBALS["db"]->fetchByAssoc($result)) {
        $lead_id = $lead['id'];
        $team_id = $lead['team_id'];
        $team_set_id = $lead['team_set_id'];
        $assigned_user_id = $lead['assigned_user_id'];
        $bean = new Lead();
        $bean->retrieve($lead_id);
        if ($bean->id) {
//            echo "<br>$bean->id";
//            continue;
            if ($team_id == '1') {
                $primary_team_id = '';
                if (empty($assigned_user_id)) {
                    $primary_team_id = $credaris_team_id;
                } else {
                    $qsql = "SELECT id FROM team_memberships where user_id='$assigned_user_id' AND team_id='$creditum_team_id' AND deleted=0";
                    $qresult = $GLOBALS["db"]->query($qsql);
                    $membership = $GLOBALS["db"]->fetchByAssoc($qresult);
                    $membership_id = $membership['id'];
                    if (empty($membership_id)) {
                        $primary_team_id = $credaris_team_id;
                    } else {
                        $primary_team_id = $creditum_team_id;
                    }
                }
            } else if (empty($team_id)) {
                $primary_team_id = $credaris_team_id;
            } else {
                $primary_team_id = $team_id;
            }

            $team_id_arr = array();
            $bean->team_id = $primary_team_id;
            $bean->processed = true;
            $bean->global_removed = true;
            $bean->save();
            $teamSetBean = new TeamSet();
            $teams = $teamSetBean->getTeams($team_set_id);
            $bean->load_relationship('teams');
            foreach ($teams as $key => $team) {
                if ($key != '1') {
                    $team_id_arr[] = $key;
                }
            }
            $team_id_arr[] = $primary_team_id;

            $bean->teams->replace($team_id_arr);
            $lead_count++;
        }
    }
    echo "<br> Global Team has been removed from $lead_count leads";
} else {
    echo "Please set the following parameters in the config.php in the 'dotb' array, <br/>1) credaris_team_id<br/>2) creditum_team_id";
}
exit;
