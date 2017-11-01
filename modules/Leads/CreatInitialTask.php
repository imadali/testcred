<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class CreatInitialTask {

    function create($bean, $event, $arguments) {
        global $current_user, $timedate,$db,$sugar_config;
        if(isset($sugar_config['auto_task']))
        if (empty($bean->fetched_row['id']) && $sugar_config['auto_task']==true) {
			if($bean->credit_request_status_id_c == '13_customer_center'){
				$task = BeanFactory::getBean("Tasks");
				$task->name = "Nachfassen: Kundencenter > Hinweis Dokument hochladen";
				$task->status = "open";
				$dueDate = new DateTime($timedate->nowDb());
				date_add($dueDate, date_interval_create_from_date_string('5 days'));
				$dueDate = $dueDate->format('Y-m-d H:i:s');
				$task->date_due = $dueDate;
				$task->parent_type = "Leads";
                                $task->parent_module = "Leads";
				$task->parent_id = $bean->id;
				
				$task->assigned_user_id = '';
				$task->team_id = getModuleTeams('Leads', $bean->id, 'primary');
                                $task->created_from_workflow = true;
                                $task->save();
                                
                                $secondary_teams = getModuleTeams('Leads', $bean->id, 'secondary');
                                
                                $team = new Team();
				if ($team->retrieve_by_string_fields(array('name' => 'Customer Care'))) {
					$secondary_teams[] = $team->id;
				}
				
                                $task->load_relationship('teams');
                                $task->teams->replace($secondary_teams);
				$task->load_relationship("leads");
				$task->leads->add($bean->id);
				
			} else {
				$task = BeanFactory::getBean("Tasks");
				$task->name = "Kunde kontaktieren";
				$task->status = "open";
				$dueDate = new DateTime($timedate->nowDb());
				$dueDate = $dueDate->format('Y-m-d H:i:s');
				$task->date_due = $dueDate;
				$task->parent_type = "Leads";
                                $task->parent_module = "Leads";
				$task->parent_id = $bean->id;
				$task->team_id = getModuleTeams('Leads', $bean->id, 'primary');
				/*
				 * Assigning task to customer contact
				 */
				$task->assigned_user_id = $bean->assigned_user_id;
                                $task->created_from_workflow = true;
				$task->save();
                                $secondary_teams = getModuleTeams('Leads', $bean->id, 'secondary');
                                $task->load_relationship('teams');
                                $task->teams->replace($secondary_teams);
				$task->load_relationship("leads");
				$task->leads->add($bean->id);
			}
        }
    }

}
