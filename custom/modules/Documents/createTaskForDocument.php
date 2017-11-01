<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class CreateTaskForDocument {

    function createNewTask($bean, $event, $arguments) {
        if (isset($_REQUEST['__sugar_url'])) {
            $url = explode('/', $_REQUEST['__sugar_url']);
            $teamBean = BeanFactory::getBean('Teams')->retrieve_by_string_fields(array('name' => 'Kundencenter'));
            $team_id = $teamBean->id;

            $query = "SELECT id,name,status FROM tasks WHERE name = 'Dokumentenprüfung'"
                    . " AND status = 'open' AND deleted = 0 AND parent_type = 'Leads' AND parent_id = '" . $arguments['related_id'] . "'";

            $result = $GLOBALS['db']->query($query);
            $row = $GLOBALS['db']->fetchByAssoc($result);

            if ($url[0] == 'v10' && $url[1] == 'Leads' && $url[2] == $arguments['related_id'] && $url[3] == 'link') {
                if ($arguments['related_module'] == "Leads" && $arguments['link'] == 'leads_documents_1' && !isset($row['id'])) {

                    $task = BeanFactory::getBean("Tasks");
                    $task->name = "Dokumentenprüfung";
                    $task->status = "open";
                    $task->team_id = $team_id;
                    $task->parent_type = "Leads";
                    $task->parent_id = $arguments['related_id'];
                    $newDate = new DateTime();
                    $task->date_due = $newDate->format('Y-m-d H:i:s');
                    $task->save();
                }
            }
        }
    }

}
