<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
    
class CreateTaskForDocument
{
    /**
     * CRED-695 : Upon arrival of a new Document on the Lead, create a task
     * 
     * @param object $bean      The API Object
     * @param array  $event     The Arguments Array
     * @param array  $arguments The Events Array
     *
     * @return Nothing
     */
    function createNewTask($bean, $event, $arguments)
    {
        if (isset($_REQUEST['__sugar_url'])) {
            $url = explode('/', $_REQUEST['__sugar_url']);
            $teamBean = BeanFactory::getBean('Teams')->retrieve_by_string_fields(array('name' => 'Kundencenter'));
            $team_id = $teamBean->id;

            $query = "SELECT id,name,status FROM tasks WHERE name = 'Unterlagen direkt aus dem Kundencenter'"
                    . " AND status = 'open' AND deleted = 0 AND parent_type = 'Leads' AND parent_id = '" . $arguments['related_id'] . "'";

            $result = $GLOBALS['db']->query($query);
            $row = $GLOBALS['db']->fetchByAssoc($result);
            /**
            ** CRED-899 : Check on document name 'DeltavistaResponsePdf' (check $bean->name)
            */
            if ($url[0] == 'v10' && $url[1] == 'Leads' && $url[2] == $arguments['related_id'] && $url[3] == 'link') {
                if ($arguments['related_module'] == "Leads" && $arguments['link'] == 'leads_documents_1' && !isset($row['id']) && $bean->name != 'DeltavistaResponsePdf') {
                    /**
                    * CRED-764/CRED-779 : First Reception: Lead is in Status 13 - change Lead-Status to 04
                    */
                    $check_document_query = "SELECT id FROM tasks WHERE name = 'Unterlagen direkt aus dem Kundencenter'"
                            . " AND deleted = 0 AND parent_type = 'Leads' AND parent_id = '" . $arguments['related_id'] . "'";
                    $check_document_result = $GLOBALS['db']->query($check_document_query);

                    // No task with name 'Unterlagen direkt aus dem Kundencenter' exist means that document is received first time from Customer Center.
                    if (!($check_document_result->num_rows > 0)) {
                        //check status of lead if Status 13 then change status to 04
                        $lead_status_query = "SELECT id_c, credit_request_status_id_c FROM leads_cstm WHERE id_c='" . $arguments['related_id'] . "'";
                        $lead_status_result = $GLOBALS['db']->query($lead_status_query);
                        $lead_row = $GLOBALS['db']->fetchByAssoc($lead_status_result);
                        if ($lead_row['credit_request_status_id_c'] == "13_customer_center") {
                            $leads_cstm_query = "UPDATE leads_cstm SET credit_request_status_id_c='04_documents_received' WHERE id_c='" . $arguments['related_id'] . "'";
                            $GLOBALS['db']->query($leads_cstm_query);
                            insertRecordInAuditTable('Leads', 'credit_request_status_id_c', $lead_row['credit_request_status_id_c'], '04_documents_received', $arguments['related_id'], 'enum');
                        }
                    }

                    $task = BeanFactory::getBean("Tasks");
                    $task->name = "Unterlagen direkt aus dem Kundencenter";
                    $task->status = "open";
                    $task->team_id = $team_id;
                    $task->parent_type = "Leads";
                    $task->parent_module = "Leads";
                    $task->parent_id = $arguments['related_id'];
                    $newDate = new DateTime();
                    $task->date_due = $newDate->format('Y-m-d H:i:s');
                    $task->save();
					
                }
            }
        }
    }

}
