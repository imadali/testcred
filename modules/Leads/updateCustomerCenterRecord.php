<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
/**
 * CRED-914: Linking Customer Center with SugarCRM@credaris
 * CRED-963: Same trigger for status 04
 */
require_once 'include/SugarQueue/SugarJobQueue.php';
class updateCustomerCenterRecord 
{
    function updateCCRecord($bean, $event, $arguments) 
    {
        global $current_user;
        if (!empty($bean->cc_id) && (($bean->credit_request_status_id_c == '00_pendent_geschlossen' && $bean->fetched_row['credit_request_status_id_c'] != '00_pendent_geschlossen') || ($bean->credit_request_status_id_c == '11_closed' && $bean->fetched_row['credit_request_status_id_c'] != '11_closed') || ($bean->credit_request_status_id_c == '04_documents_received' && $bean->fetched_row['credit_request_status_id_c'] != '04_documents_received'))) {
            $job = new SchedulersJob();
            $job->name = "Update the Lead in Customer Center";
            $job->data = $bean->cc_id;
            $job->target = "function::updateCustomerCenterJob";
            $job->assigned_user_id = $current_user->id;

            //Push the job in the queue
            $jobQueue = new SugarJobQueue();
            $jobid = $jobQueue->submitJob($job);
        }
    }
}
