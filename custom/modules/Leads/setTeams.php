<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class setTeams {

    public static $leadAssignedUserChanged = false;
    public static $contactAssignedUserChanged = false;
    public static $newLead = false;
    public static $newPartner = false;
    public static $updateTeamsInPartner = true;

    public function checkAssignedUser($bean, $event, $arguments) {
        if ($bean->fetched_row['assigned_user_id'] != $bean->assigned_user_id) {
            self::$leadAssignedUserChanged = true;
        }
        if (!isset($bean->fetched_row['id']) && $bean->created_from_contact){
            self::$newLead = true;
        }
    }

    public function checkContactIsNew($bean, $event, $arguments) {
        if ($bean->fetched_row['assigned_user_id'] != $bean->assigned_user_id) {
            self::$contactAssignedUserChanged = true;
        }
        if (!isset($bean->fetched_row['id']))
            self::$newPartner = true;
    }

    function setTeamsInLead($bean, $event, $arguments) {
        global $sugar_config;
        require_once('modules/Teams/TeamSet.php');
        require_once 'custom/modules/Contacts/syncContact.php';
        $teamSetBean = new TeamSet();
        $primaryTeam = '';
        /*
         * Check If assigned user is changed
         */
        if (self::$leadAssignedUserChanged || (isset($_REQUEST['__sugar_url']) && strpos($_REQUEST['__sugar_url'], 'MassUpdate') !== false)) {
            /*
             * Deciding Primary Team 
             */
            if ($bean->assigned_user_id) {
                $myUser = new User();
                $myUser->retrieve($bean->assigned_user_id);
                $teams = $teamSetBean->getTeams($myUser->team_set_id);
                if ($myUser->default_team == '1') {
                    $primaryTeam = $sugar_config['dotb']['credaris_team_id'];
                } else {
                    $primaryTeam = $myUser->default_team;
                }
            } else {
                $primaryTeam = $sugar_config['dotb']['credaris_team_id'];
                $teams = array();
            }
        } else {
            $teams = $teamSetBean->getTeams($bean->team_set_id);
            /*
             * Deciding Primary Team 
             */
            if ($bean->team_id == '1') {
                $primaryTeam = $sugar_config['dotb']['credaris_team_id'];
            } else {
                $primaryTeam = $bean->team_id;
            }
        }

        /*
         * Setting Primary Team in Lead
         */
        $bean->team_id = $primaryTeam;

        /*
         * Setting Secondary Teams in Lead
         */
        $team_id = array();
        foreach ($teams as $key => $team) {
            if ($key != '1') {
                $team_id[] = $key;
            }
        }
        if (empty($team_id))
        $team_id[] = $primaryTeam;
        $bean->load_relationship('teams');
        $bean->processed = true;
        $bean->save();
        $bean->teams->replace($team_id);
        /*
         * Syncing Secondry Teams With Related Contact. Primary is being synced in syncLead hook after executing this hook.
         */
        if (!empty($bean->contact_id) && self::$newLead == false && syncContact::$triggeredFromContact == false) {
            $contact = BeanFactory::getBean("Contacts", $bean->contact_id);
            if (!empty($contact->id)) {
                $contact->team_id = $bean->team_id;
                $contact->processed = true;
                $contact->save();
                $contact->load_relationship('teams');
                $contact->teams->replace($team_id);
            }
        }
        /*
         * Syncing Teams With Lead's Partner
         */
        if ($bean->load_relationship("leads_contacts_1")) {
            $partners = $bean->leads_contacts_1->getBeans();
            foreach ($partners as $partner) {
                $partner->load_relationship('teams');
                $partner->team_id = $bean->team_id;
                $partner->processed = true;
                $partner->save();
                $partner->teams->replace($team_id);
            }
        }

        /*
         * Global team must be there in each activity teams
         */
        $activities_team_id = $team_id;
        $activities_team_id[] = '1';

        /*
         * Syncing Teams with Open Tasks
         */
        $presist_teams = array('Vertrag', 'Posteingang', 'BANK-now Car', 'Auszahlung', 'BANK-now Casa', 'SOKO', 'Kundencenter', 'Duplikat', 'Provision', 'Abklaerungen', 'Global', 'Provision', 'Call Center', 'Sales', 'Approval', 'Customer Care');
        if ($bean->load_relationship("tasks")) {
            $relatedTasks = $bean->tasks->getBeans();
            foreach ($relatedTasks as $task) {
                if ($task->status != 'closed') {
                    $task_activities_team_id = $activities_team_id;
                    $result = $GLOBALS['db']->query("SELECT name FROM teams WHERE id='$task->team_id'");
                    $result = $GLOBALS["db"]->fetchByAssoc($result);
                    $p_team_name = $result['name'];
                    $p_team_name = trim($p_team_name);
                    if (!in_array($p_team_name, $presist_teams)) {
                        $task->team_id = $primaryTeam;
                        $task->processed = true;
                        $task->save();
                    }
                    $task_secondary_teams = $teamSetBean->getTeams($task->team_set_id);
                    foreach ($task_secondary_teams as $key => $task_secondary_team) {
                        if (empty($task_secondary_team->name_2)) {
                            $s_team_name = $task_secondary_team->name;
                        } else {
                            $s_team_name = $task_secondary_team->name . ' ' . $task_secondary_team->name_2;
                        }
                        $s_team_name = trim($s_team_name);
                        if (in_array($s_team_name, $presist_teams)) {
                            $task_activities_team_id[] = $key;
                        }
                    }
                    $task->load_relationship('teams');
                    $task->teams->replace($task_activities_team_id);
                }
            }
        }

        /**
         * Syncing Teams with Notes
         *
          if ($bean->load_relationship("notes")) {
          $relatedNotes = $bean->notes->getBeans();
          foreach ($relatedNotes as $note) {
          $note->load_relationship('teams');
          $note->team_id = $primaryTeam;
          $note->processed = true;
          $note->save();
          $note->teams->replace($activities_team_id);
          }
          } */
        /**
         * Syncing Teams with Emails
         *
          if ($bean->load_relationship("emails")) {
          $relatedEmails = $bean->emails->getBeans();
          foreach ($relatedEmails as $email) {
          $email->load_relationship('teams');
          $email->team_id = $primaryTeam;
          $email->processed = true;
          $email->save();
          $email->teams->replace($activities_team_id);
          }
          } */
        /**
         * Syncing Teams with Calls
         *
          if ($bean->load_relationship("calls")) {
          $relatedCalls = $bean->calls->getBeans();
          foreach ($relatedCalls as $call) {
          $call->load_relationship('teams');
          if ($call->status != 'Planned') {
          $call->load_relationship('teams');
          $call->team_id = $primaryTeam;
          $call->processed = true;
          $call->save();
          $call->teams->replace($activities_team_id);

          }
          }
          } */
    }

    function setTeamsInContact($bean, $event, $arguments) {
        global $sugar_config;
        require_once('modules/Teams/TeamSet.php');
        require_once 'custom/modules/Contacts/syncContact.php';
        $teamSetBean = new TeamSet();
        $primaryTeam = '';
        $updateTeams = true;

        /*
         * If Partner is added in Lead or Contact
         */
        if (!empty($bean->relative_type_c)) {
           $updateTeams =  self::updateTeamsInPartner($bean);
           $updateTeams = false;
        }
        /*
         * Check If assigned user is changed
         */
        if (($updateTeams && self::$contactAssignedUserChanged) || (isset($_REQUEST['__sugar_url']) && strpos($_REQUEST['__sugar_url'], 'MassUpdate') !== false)) {
            /*
             * Deciding Primary Team 
             */
            if ($bean->assigned_user_id) {
                $myUser = new User();
                $myUser->retrieve($bean->assigned_user_id);
                $teams = $teamSetBean->getTeams($myUser->team_set_id);
                if ($myUser->default_team == '1') {
                    $primaryTeam = $sugar_config['dotb']['credaris_team_id'];
                } else {
                    $primaryTeam = $myUser->default_team;
                }
            } else {
                $primaryTeam = $sugar_config['dotb']['credaris_team_id'];
                $teams = array();
            }
        } else {
            $teams = $teamSetBean->getTeams($bean->team_set_id);
            /*
             * Deciding Primary Team 
             */
            if ($bean->team_id == '1') {
                $primaryTeam = $sugar_config['dotb']['credaris_team_id'];
            } else {
                $primaryTeam = $bean->team_id;
            }
        }

        /*
         * Setting Primary Team in Lead
         */
        $bean->team_id = $primaryTeam;

        /*
         * Setting Secondary Teams in Lead
         */
        $team_id = array();
        foreach ($teams as $key => $team) {
            if ($key != '1') {
                $team_id[] = $key;
            }
        }
        if (empty($team_id))
            $team_id[] = $primaryTeam;

        $bean->load_relationship('teams');
        $bean->processed = true;
        $bean->save();
        $bean->teams->replace($team_id);

        /*
         * Syncing Teams With Partner
         */
        if ($bean->load_relationship("contacts_contacts_1")) {
            $partners = $bean->contacts_contacts_1->getBeans();
            foreach ($partners as $partner) {
                $partner->load_relationship('teams');
                $partner->team_id = $bean->team_id;
                $partner->processed = true;
                $partner->save();
                $partner->teams->replace($team_id);
            }
        }

        /*
         * Global team must be there in each activity teams
         */
        $activities_team_id = $team_id;
        $activities_team_id[] = '1';

        /*
         * Syncing Teams with Open Tasks
         */
        $presist_teams = array('Vertrag', 'Posteingang', 'BANK-now Car', 'Auszahlung', 'BANK-now Casa', 'SOKO', 'Kundencenter', 'Duplikat', 'Provision', 'Abklaerungen', 'Global', 'Provision', 'Call Center', 'Sales', 'Approval', 'Customer Care');
        if ($bean->load_relationship("tasks")) {
            $relatedTasks = $bean->tasks->getBeans();
            foreach ($relatedTasks as $task) {
                if ($task->status != 'closed') {
                    $task_activities_team_id = $activities_team_id;
                    $result = $GLOBALS['db']->query("SELECT name FROM teams WHERE id='$task->team_id'");
                    $result = $GLOBALS["db"]->fetchByAssoc($result);
                    $p_team_name = $result['name'];
                    $p_team_name = trim($p_team_name);
                    if (!in_array($p_team_name, $presist_teams)) {
                        $task->team_id = $primaryTeam;
                        $task->processed = true;
                        $task->save();
                    }
                    $task_secondary_teams = $teamSetBean->getTeams($task->team_set_id);
                    foreach ($task_secondary_teams as $key => $task_secondary_team) {
                        if (empty($task_secondary_team->name_2)) {
                            $s_team_name = $task_secondary_team->name;
                        } else {
                            $s_team_name = $task_secondary_team->name . ' ' . $task_secondary_team->name_2;
                        }
                        $s_team_name = trim($s_team_name);
                        if (in_array($s_team_name, $presist_teams)) {
                            $task_activities_team_id[] = $key;
                        }
                    }
                    $task->load_relationship('teams');
                    $task->teams->replace($task_activities_team_id);
                }
            }
        }

        /**
         * Syncing Teams with Notes
         *
          if ($bean->load_relationship("notes")) {
          $relatedNotes = $bean->notes->getBeans();
          foreach ($relatedNotes as $note) {
          $note->load_relationship('teams');

          $note->team_id = $primaryTeam;
          $note->processed = true;
          $note->save();
          $note->teams->replace($activities_team_id);
          }
          } */
        /**
         * Syncing Teams with Emails
         *
          if ($bean->load_relationship("emails")) {
          $relatedEmails = $bean->emails->getBeans();
          foreach ($relatedEmails as $email) {
          $email->load_relationship('teams');

          $email->team_id = $primaryTeam;
          $email->processed = true;
          $email->save();
          $email->teams->replace($activities_team_id);
          }
          } */
        /**
         * Syncing Teams with Calls
         *
          if ($bean->load_relationship("calls"))
          $relatedCalls = $bean->calls->getBeans();
          foreach ($relatedCalls as $call) {
          $call->load_relationship('teams');
          if ($call->status != 'Planned') {
          $call->load_relationship('teams');
          $call->team_id = $primaryTeam;
          $call->processed = true;
          $call->save();
          $call->teams->replace($activities_team_id);
          }
          }
          } */
    }
    
    
    /*
     * If Partner is Linked in Lead or Contact it will inherit teams from parent
     */
    function setTeamsInPartner($bean, $event, $arguments) {
        if (!empty($bean->relative_type_c) && self::$newPartner == false) {
            self::updateTeamsInPartner($bean);
        }
    }

    function updateTeamsInPartner(& $bean) {
        global $sugar_config;
        require_once('modules/Teams/TeamSet.php');
        $updateTeams = true;
        if (self::$updateTeamsInPartner) {
            if ($bean->load_relationship("leads_contacts_1")) {
                $parentLeads = $bean->leads_contacts_1->getBeans();
                foreach ($parentLeads as $parentLead) {
                    $updateTeams = false;
                    //$bean->team_id = $parentLead->team_id;
                    $teamSetBean = new TeamSet();
                    $teams = $teamSetBean->getTeams($parentLead->team_set_id);


                    $team_id = array();
                    foreach ($teams as $key => $team) {
                        if ($key != '1') {
                            $team_id[] = $key;
                        }
                    }
                    if (empty($team_id))
                        $team_id[] = $parentLead->team_id;

                    //$bean->processed = true;
                    //$bean->save();
                    $bean->load_relationship('teams');
                    $bean->teams->replace($team_id);
                    $GLOBALS['db']->query("UPDATE contacts SET team_id = '$parentLead->team_id' WHERE id = '$bean->id'");
                    break;
                }
            }
            if ($updateTeams) {
                if ($bean->load_relationship("contacts_contacts_1")) {
                    $parentContacts = $bean->contacts_contacts_1->getBeans();
                    foreach ($parentContacts as $parentContact) {
                        $updateTeams = false;
                        //$bean->team_id = $parentContact->team_id;
                        $teamSetBean = new TeamSet();
                        $teams = $teamSetBean->getTeams($parentContact->team_set_id);

                        $team_id = array();
                        foreach ($teams as $key => $team) {
                            if ($key != '1') {
                                $team_id[] = $key;
                            }
                        }
                        if (empty($team_id))
                            $team_id[] = $parentContact->team_id;

                        //$bean->processed = true;
                        //$bean->save();
                        $bean->load_relationship('teams');
                        $bean->teams->replace($team_id);
                        $GLOBALS['db']->query("UPDATE contacts SET team_id = '$parentLead->team_id' WHERE id = '$parentContact->id'");
                        break;
                    }
                }
            }
        }
    }

}

?>