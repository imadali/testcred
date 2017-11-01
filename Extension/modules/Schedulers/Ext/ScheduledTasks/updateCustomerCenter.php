<?php

array_push($job_strings, 'updateCustomerCenterJob');

/*
 * CRED-914: Job to call api and update the record at Customer Center
 *
 * @return boolean
 */
function updateCustomerCenterJob($jobData)
{
    global $sugar_config;
    if (!empty($jobData->data)) {
        $lead_ccid = $jobData->data;
        $url = $sugar_config['customer_center_url'] . $lead_ccid;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        // Extraction du JSON dans un tableau
        $result_data = json_decode($result, true);

        /**
         * CRED-1040 : Scheduler to update Customer Center (Updated scheduler message in case of success and failure)
         */
        if ($result_data['Success']) {
            $GLOBALS['log']->debug("Record Updated successfully at customer center with CC-ID: " . $lead_ccid);
            $jobData->message = "Record Updated successfully at customer center with CC-ID: " . $lead_ccid;
            return true;
        } else {
            $GLOBALS['log']->debug("Error Message: " . $result_data['ErrorMessage']);
            $jobData->message = "Error Message: " . $result_data['ErrorMessage'];
            return true;
        }
    } else {
        return true;
    }
}