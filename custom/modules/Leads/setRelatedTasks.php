<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class setRelatedTasks {
    /*
     * Set all related tasks lead status same as Lead status
     */

    function setStatus($bean, $event, $arguments) {
        global $app_list_strings, $timedate;

        if ($bean->credit_request_status_id_c != $bean->fetched_row['credit_request_status_id_c']) {
            if ($bean->load_relationship("tasks")) {
                $relatedTasks = $bean->tasks->getBeans();
                foreach ($relatedTasks as $task) {
                    $GLOBALS['db']->query("UPDATE tasks_cstm SET lead_status_c='$bean->credit_request_status_id_c' WHERE id_c='$task->id'");
                    insertRecordInAuditTable('Tasks', 'lead_status_c', $task->lead_status_c, $bean->credit_request_status_id_c, $task->id, 'enum');
                }
            }
        }
        
        /*
         * CRED-319: When the Lead's status is set to 'Status 10' and is saved then the system will set Bankstatus of the related applications (which have 'Bankstatus' 'bewilligt') to aktiv.
         */
        /*if ($bean->fetched_row['credit_request_status_id_c'] != '10_active' && $bean->credit_request_status_id_c == '10_active') {
            $granted_app_id = '';
            $contract_app_id = '';
            $approved_app_count = 0;
            if ($bean->load_relationship('contracts_leads_1')) {
                $relatedContracts = $bean->contracts_leads_1->getBeans();
                foreach ($relatedContracts as $contract) {
                    $contract_app_id = $contract->opportunity_id;
                }
            }

            if ($bean->load_relationship("leads_opportunities_1")) {
                $relatedApps = $bean->leads_opportunities_1->getBeans();
                foreach ($relatedApps as $relatedApp) {
                    if ($relatedApp->provider_status_id_c == 'granted') {
                        $granted_app_id = $relatedApp->id;
                        $approved_app_count++;
                    }

                    if ($relatedApp->id != $contract_app_id) {
                        $GLOBALS['db']->query("UPDATE opportunities_cstm SET provider_status_id_c='abandon' WHERE id_c='$relatedApp->id'");
                        insertRecordInAuditTable('Opportunities', 'provider_status_id_c', $relatedApp->provider_status_id_c, 'abandon', $relatedApp->id, 'enum');
                        $bank = $app_list_strings['dotb_credit_provider_list'][$relatedApp->provider_id_c];
                        $dueDate = new DateTime($timedate->nowDb());
                        $dueDate = $dueDate->format('Y-m-d H:i:s');

                        $task = new Task();
                        $task->name = "Verzicht melden bei $bank";
                        $task->assigned_user_id = $relatedApp->user_id_c;
                        $task->date_due = $dueDate;
                        $task->parent_type = 'Leads';
                        $task->parent_id = $bean->id;
                        $task->load_relationship("leads");
                        $task->leads->add($bean->id);
                        $task->save();
                    }
                }
            }

            if (empty($contract_app_id)) {
                if ($approved_app_count == 1)
                $GLOBALS['db']->query("UPDATE opportunities_cstm SET provider_status_id_c='active' WHERE id_c='$granted_app_id'");
                insertRecordInAuditTable('Opportunities', 'provider_status_id_c', 'granted', 'active', $granted_app_id, 'enum');
            } else {
                $GLOBALS['db']->query("UPDATE opportunities_cstm SET provider_status_id_c='active' WHERE id_c='$contract_app_id'");
                insertRecordInAuditTable('Opportunities', 'provider_status_id_c', 'granted', 'active', $contract_app_id, 'enum');
            }
        }*/
    }
}

?>