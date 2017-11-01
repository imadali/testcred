<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
class SetRelatedTasks
{
    /**
     * Set all related tasks lead status same as Lead status
     * 
     *  @return Update all tasks with same status as related Leads
     */
    function setStatus($bean, $event, $arguments)
    {
        global $app_list_strings, $timedate;
        if ($bean->credit_request_status_id_c != $bean->fetched_row['credit_request_status_id_c']) {
            if ($bean->load_relationship("tasks")) {
                $relatedTasks = $bean->tasks->getBeans();
                foreach ($relatedTasks as $task) {
                    /* $GLOBALS['db']->query("UPDATE tasks_cstm SET lead_status_c='$bean->credit_request_status_id_c' WHERE id_c='$task->id'");
                    insertRecordInAuditTable('Tasks', 'lead_status_c', $task->lead_status_c, $bean->credit_request_status_id_c, $task->id, 'enum'); */
                    /**
                    * CRED-798 -> updated task status using bean instead of query because query was not executing for tasks created manually in Activities subpanel
                    */
                    $task->lead_status_c = $bean->credit_request_status_id_c;
                    $task->processed = true;
                    $task->save();
                }
            }
            /**
             * CRED-940 : Update all calls with same status as related Leads
             */
            if ($bean->load_relationship("calls")) {
                $relatedCalls = $bean->calls->getBeans();
                foreach ($relatedCalls as $call) {
                    $call->lead_status_c = $bean->credit_request_status_id_c;
                    $call->processed = true;
                    $call->save();
                }
            }
        }
        /**
         * CRED-389/770 : When the Lead's status is set to 'Status 10'
         *  and is saved then the  system will set Bankstatus of the
         *  related applications (which have 'Bankstatus' 'bewilligt') to aktiv.
         */
        if ($bean->fetched_row['credit_request_status_id_c'] != '10_active' && $bean->credit_request_status_id_c == '10_active') {
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
                }
            }
            if (empty($contract_app_id)) {
                if ($approved_app_count == 1) {
                    $GLOBALS['db']->query("UPDATE opportunities_cstm SET provider_status_id_c='active' WHERE id_c='$granted_app_id'");
                    insertRecordInAuditTable('Opportunities', 'provider_status_id_c', 'granted', 'active', $granted_app_id, 'enum');
                }
            } else {
                $GLOBALS['db']->query("UPDATE opportunities_cstm SET provider_status_id_c='active' WHERE id_c='$contract_app_id'");
                insertRecordInAuditTable('Opportunities', 'provider_status_id_c', 'granted', 'active', $contract_app_id, 'enum');
            }
        }
    }
}
?>