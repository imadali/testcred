<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * CRED-873 : CTI Basic: RT Action Items
 */
class WorkflowViaCall
{
    /**
     * Execution of Workflows specific to Lead and Call Status 
     * 
     * @param Object $bean
     * @param Array  $event
     * @param Array  $arguments
     */
    public function executeWorkflow($bean, $event, $arguments)
    {
        $leadStatus = '';
        $next_best_step = '';
        $statusReached = array('01_new', '2a_not_reached_first_round', '2b_not_reached_second_round',
            '2c_not_reached_third_round', '3a_document_sent_first_round', '3b_document_sent_second_round');
        $result = array();

        if (!isset($bean->fetched_row['id']) && $bean->parent_type == 'Leads' && !empty($bean->parent_id)) {
            $query = "SELECT credit_request_status_id_c FROM leads_cstm WHERE id_c = '$bean->parent_id'";
            $result = $GLOBALS['db']->query($query);

            $row = $GLOBALS['db']->fetchByAssoc($result);
            $leadStatus = $row['credit_request_status_id_c'];

            $workflowobject = new CustomWorkFlowAPI();
            if (in_array($leadStatus, $statusReached)) {
                if ($bean->status == 'Not Reached') {
                    switch ($leadStatus) {
                        case '01_new':
                            $next_best_step = 'not_reached_mail_round1';
                            break;
                        case '2a_not_reached_first_round':
                            $next_best_step = 'not_reached_mail_round_2';
                            break;
                        case '2b_not_reached_second_round':
                            $next_best_step = 'not_reached_mail_round_3';
                            break;
                        case '2c_not_reached_third_round':
                            $next_best_step = 'conclude';
                            break;
                        case '3a_document_sent_first_round':
                            $next_best_step = 'send_documents_reminder_round_1';
                            break;
                        case '3b_document_sent_second_round':
                            $next_best_step = 'conclude';
                            break;
                    }
                    $args = array('id' => $bean->parent_id, 'nextBestStep' => $next_best_step, 'status' => $leadStatus, 'task' => 'Callback', 'user_approval' => $bean->user_id_c, 'provider' => $bean->application_provider_c);
                    $result = $workflowobject->autoExecute($api = array(), $args);
                    
                    if (isset($result['new_lead_status'])) {
                        $bean->lead_status_c = $result['new_lead_status'];
                    }
                }
                if ($bean->status == 'Reached') {
                    switch ($leadStatus) {
                        case '01_new':
                            $next_best_step = 'send_documents';
                            break;
                        case '2a_not_reached_first_round':
                            $next_best_step = 'send_documents';
                            break;
                        case '2b_not_reached_second_round':
                            $next_best_step = 'send_documents';
                            break;
                        case '2c_not_reached_third_round':
                            $next_best_step = 'send_documents';
                            break;
                        case '3a_document_sent_first_round':
                            $next_best_step = 'reached_kd_sends_ul_mail';
                            break;
                        case '3b_document_sent_second_round':
                            $next_best_step = 'send_documents';
                            break;
                    }
                    $args = array('id' => $bean->parent_id, 'nextBestStep' => $next_best_step, 'status' => $leadStatus, 'task' => 'Callback', 'user_approval' => $bean->user_id_c, 'provider' => $bean->application_provider_c);
                    $result = $workflowobject->autoExecute($api = array(), $args);
                    
                    if (isset($result['new_lead_status'])) {
                        $bean->lead_status_c = $result['new_lead_status'];
                    }
                }
            }
        }
    }
}