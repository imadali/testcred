<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
    
class savePreviousStatus
{

    function saveStatus($bean, $event, $arguments)
    {
        /**
         * Saving Previous Status Value
         */
        if ($bean->fetched_row['credit_request_status_id_c'] != $bean->credit_request_status_id_c) {

            $bean->dotb_status_dup_c = $bean->fetched_row['credit_request_status_id_c'];
        }

        /**
         * CRED-772 : Show column "Kundenbetreuer" in Task-List-View
         * Updating Related Leads when Assigned To Changes in Lead
         */
        if ($bean->fetched_row['assigned_user_id'] != $bean->assigned_user_id) {

            if ($bean->load_relationship("tasks")) {
                $relatedTasks = $bean->tasks->getBeans();
                foreach ($relatedTasks as $task) {
                    $task->customer_contact_id = $bean->assigned_user_id;
                    $task->save();
                }
            }
        }
    }

}
