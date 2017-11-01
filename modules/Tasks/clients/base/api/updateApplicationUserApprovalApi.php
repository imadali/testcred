<?php

class updateApplicationUserApprovalApi extends SugarApi {

    public function registerApiRest() {
        return array(
            'updateApplicationUserApproval' => array(
                'reqType' => 'POST',
                'path' => array('Tasks', 'PopulateApplication',),
                'pathVars' => array('', '',),
                'method' => 'updateApplicationUserApproval',
                'shortHelp' => 'This api will update the user approval of the application selected in the task',
                'longHelp' => '',
            ),
        );
    }

    /*
    * Update the user approval of the application selected in the task
    * This will execute only in case of Workflow 04 - 04 Antrag einreichen when application is selected in the task
    */
    public function updateApplicationUserApproval(ServiceBase $api, array $args) {
        $task_id = $args['task_id'];
        $set_application_approval_user = $args['app_approval_rule'];
        $taskBean = BeanFactory::getBean('Tasks', $task_id);
        $applicationBean = BeanFactory::getBean('Opportunities', $taskBean->application_name_c);
		$app_user_approval = $applicationBean->user_id_c;
        if ($set_application_approval_user){
            if(empty($applicationBean->user_id_c)){
                require_once('custom/modules/Opportunities/applicationAssignment.php');
                $appHandler = new applicationAssignment();
                $app_user_approval = $appHandler->setApprovalUser($applicationBean,'','');
                $GLOBALS['log']->debug("Approval user assigned to application after application is selected in task: $app_user_approval");
                //update application approval user
                $app_update_query = "UPDATE opportunities_cstm SET user_id_c='$app_user_approval' WHERE id_c='$applicationBean->id'";
               $GLOBALS['db']->query($app_update_query);
	
                //update approval user in the task
                $task_update_query = "UPDATE tasks_cstm SET user_id_c='$app_user_approval' WHERE id_c='$task_id'";
                $GLOBALS['db']->query($task_update_query);
            }
        }
		$GLOBALS['db']->query("UPDATE tasks SET assigned_user_id='$app_user_approval' WHERE id='$task_id'");
        insertRecordInAuditTable('Tasks', 'assigned_user_id', $taskBean->assigned_user_id, $app_user_approval, $task_id, 'id');
    }

}

?>