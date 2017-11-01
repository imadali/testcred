<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class MassCloseTasksAPI extends SugarApi {

    public function registerApiRest() {
        return array(
            'CloseTasks' => array(
                'reqType' => 'POST',
                'noLoginRequired' => false,
                'path' => array('MassClose', 'CloseTasks'),
                'pathVars' => array('', ''),
                'method' => 'closeTasks',
                'shortHelp' => 'close Tasks',
                'longHelp' => '',
            ),
        );
    }

    public function closeTasks($api, $args) {
        $tasks_ids = implode("','", $args['tasks_ids']);
        $tasks_ids = "'" . $tasks_ids . "'";
        $hide_tasks = implode("','", $args['hide_tasks']);
        $hide_tasks = "'" . $hide_tasks . "'";
        $GLOBALS['db']->query("UPDATE tasks SET status ='closed'  WHERE id IN($tasks_ids)");
        $GLOBALS['db']->query("UPDATE tasks SET hide = 1  WHERE id IN($hide_tasks)");
        foreach($args['tasks_ids'] as $key => $task_id) {
            insertRecordInAuditTable('Tasks', 'status', '', 'closed', $task_id,'enum');
        }
        $parentBean = BeanFactory::getBean($args['parent_module'], $args['parent_id']);
        $all_closed = true;
        $lead_id=null;
        if ($parentBean->load_relationship("tasks")) {
            $relatedTasks = $parentBean->tasks->getBeans();
            foreach ($relatedTasks as $task) {
                if ($task->status != 'closed') {
                    $all_closed = false;
                    $lead_id=$task->parent_id;
                }
            }
        }
        /* 
         * Updating the field no_of_open_tasks in the Lead
         */
        if($lead_id){
        $result = $GLOBALS['db']->query("SELECT COUNT(*) number_of_open_tasks FROM tasks WHERE parent_type = 'Leads' AND parent_id = '$lead_id' AND status <> 'closed' AND deleted=0");
        $result = $GLOBALS['db']->fetchByAssoc($result);
        $number_of_open_tasks = $result['number_of_open_tasks'];
        $GLOBALS['db']->query("UPDATE leads SET number_of_open_tasks =$number_of_open_tasks WHERE id = '$lead_id'");
        }
        return $all_closed;
    }

}

?>