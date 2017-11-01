<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once ('include/TimeDate.php');

class addTask {

    protected static $fetchedRow = array();

    /**
     * Called as before_save logic hook to grab the fetched_row values
     */
    public function add($bean, $event, $arguments) {
        global $timedate, $sugar_config;
        $CurrenrDateTime = $timedate->getInstance()->nowDb();
        if ($bean->auto_assign_task && !$bean->auto_task_created) {
            $vertrag_team = BeanFactory::getBean('Teams')->retrieve_by_string_fields(array('name' => 'Vertrag'));
            $vertrag_id = $vertrag_team->id;
            if (empty($vertrag_id))
                $vertrag_id = 1;

            /*
             * Adding Task in application: commented tempararily
             *
              $task = new Task();
              $task->name = 'Vertrag erstellen';
              $task->category_c = 'vertrag_erstellen';
              $task->date_due = $CurrenrDateTime;
              // $task->assigned_user_id = $bean->assigned_user_id;
              $task->team_id = $vertrag_id;
              //same status as lead
              $task->lead_status_c = '07_creating_contract';
              $task->save();
              $task->load_relationship('teams');
              $task->teams->add($vertrag_id);
              $bean->load_relationship('tasks');
              $bean->tasks->add($task->id);


              /*
             * Duplicating this task to show in Lead's Activities Subpanel 
             */
            if (!empty($bean->leads_opportunities_1leads_ida)) {
                //set Leads status to 07_creating_contract
                $leads_status_query = "UPDATE leads_cstm SET credit_request_status_id_c='07_creating_contract' WHERE id_c='$bean->leads_opportunities_1leads_ida';";
                $GLOBALS['db']->query($leads_status_query);

                insertRecordInAuditTable('Leads', 'credit_request_status_id_c', '', '07_creating_contract', $bean->leads_opportunities_1leads_ida, 'enum');
                
                $task = new Task();
                $task->name = 'Vertrag erstellen';
                $task->category_c = 'vertrag_erstellen';
                $task->date_due = $CurrenrDateTime;
                $task->assigned_user_id = null;
                $task->team_id = $vertrag_id;
                $task->parent_id = $bean->leads_opportunities_1leads_ida;
                $task->parent_type = "Leads";
                $task->parent_module = "Leads";
                $task->lead_amount_c = $bean->contract_credit_amount;
                //$task->bank_c = $bean->provider_id_c;
                $task->created_from_app = true;
                $task->application_user_approval_c = $bean->dotb_user_approval_c;
                $task->application_provider_c = $bean->provider_id_c;
                $task->user_id_c = $bean->user_id_c;
                $task->save();
                $task->load_relationship('teams');
                $secondary_teams = getModuleTeams('Leads', $bean->leads_opportunities_1leads_ida, 'secondary');
                $secondary_teams[] = '1';
                $task->teams->replace($secondary_teams);
                $task->load_relationship("leads");
                $task->leads->add($bean->leads_opportunities_1leads_ida);

                if (!empty($bean->leads_opportunities_1leads_ida)) {
                    $lead = BeanFactory::getBean("Leads", $bean->leads_opportunities_1leads_ida);
                    if ($lead->load_relationship("tasks")) {
                        $relatedTasks = $lead->tasks->getBeans();
                        foreach ($relatedTasks as $task) {
                            $GLOBALS['db']->query("UPDATE tasks_cstm SET lead_status_c='07_creating_contract' WHERE id_c='$task->id'");
                            insertRecordInAuditTable('Tasks', 'lead_status_c', $task->lead_status_c, '07_creating_contract', $task->id, 'enum');
                        }
                    }
                    
                    /**
                     * CRED-940 : Update all calls with same status as related Leads
                     */
                    if ($lead->load_relationship("calls")) {
                        $relatedCalls = $lead->calls->getBeans();
                        foreach ($relatedCalls as $call) {
                            $GLOBALS['db']->query("UPDATE calls SET lead_status_c='07_creating_contract' WHERE id='$call->id'");
                            insertRecordInAuditTable('Calls', 'lead_status_c', $call->lead_status_c, '07_creating_contract', $call->id, 'enum');
                        }
                    }
                }
            }
            $bean->auto_task_created = true;
        }
        if ($bean->create_approval_task && !$bean->approval_task_created) {
            $approval_team = BeanFactory::getBean('Teams')->retrieve_by_string_fields(array('name' => 'Approval'));
            $approval_team_id = $approval_team->id;
            if (empty($approval_team_id))
                $approval_team_id = 1;
			
            //calling application user approval assignment if user approval is not already set
            if(empty($bean->user_id_c)){
                require_once('custom/modules/Opportunities/applicationAssignment.php');
                $appHandler = new applicationAssignment();
                $app_user_approval = $appHandler->setApprovalUser($bean,'','');
                $GLOBALS['log']->debug("Approval user assigned to application as create_approval_task is checked: $app_user_approval");
                $bean->user_id_c = $app_user_approval;
            }
			
			
            /*
             * Adding Task in application: commented tempararily
             *
              $task = new Task();
              $task->name = 'Antrag bei Bank einreichen';
              $task->category_c = 'antrag_bei_bank_einreichen';
              $task->date_due = $CurrenrDateTime;
              //$task->assigned_user_id = $bean->assigned_user_id;
              $task->team_id = $approval_team_id;
              $task->save();
              $task->load_relationship('teams');
              $task->teams->add($approval_team_id);
              $bean->load_relationship('tasks');
              $bean->tasks->add($task->id);
             *
             * Duplicating this task to show in Lead's Activities Subpanel
             */
            if (!empty($bean->leads_opportunities_1leads_ida)) {
                $task = new Task();
                $task->name = 'Antrag bei Bank einreichen';
                $task->category_c = 'antrag_bei_bank_einreichen';
                $task->date_due = $CurrenrDateTime;
                $task->assigned_user_id = $bean->user_id_c;
                $task->team_id = $approval_team_id;
                $task->parent_id = $bean->leads_opportunities_1leads_ida;
                $task->parent_type = "Leads";
                $task->parent_module = "Leads";
                $task->created_from_app = true;
                $task->application_user_approval_c = $bean->dotb_user_approval_c;
                $task->application_provider_c = $bean->provider_id_c;
                $task->user_id_c = $bean->user_id_c;
                //$task->bank_c = $bean->provider_id_c;
                $task->save();
                $task->load_relationship('teams');
                $secondary_teams = getModuleTeams('Leads', $bean->leads_opportunities_1leads_ida, 'secondary');
                $secondary_teams[] = '1';
                $task->teams->replace($secondary_teams);
                $task->load_relationship("leads");
                $task->leads->add($bean->leads_opportunities_1leads_ida);
            }
            $bean->approval_task_created = true;
        }
    }

}
