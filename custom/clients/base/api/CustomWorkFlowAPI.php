<?php

require_once('include/SugarQuery/SugarQuery.php');
include_once('include/workflow/alert_utils.php');
require_once('custom/modules/Emails/clients/base/api/customParserApi.php');

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class CustomWorkFlowAPI extends SugarApi {

    public static $tasks_updated = false;
    public static $task_credit_amount = 0;
    public static $old_lead_status = '';
    public static $insert_lead_audit = true;
    public static $subjectMapping = array(
        '01_new_not_reached_mail_round1' => 'Initial contact - Test 2',
        '01_new_send_documents' => 'Documents follow up - Experiment 1',
        '01_new_closed_only_manually' => '',
        '01_new_pendent_due_plausi_tests_only_manually' => 'Potential clarification with customer',
        '' => '',
    );
    public static $return_arr=array();
    public static $next_best_steps='';
    public static $activation_status='';
    public static $set_application_user_approval = false;

    /**
     *  emailNameMapping contains email template names for
     *  each workflow, and tells if email sending is 
     *  required or not. 
     */
    public static $emailNameMapping = array(
        '01_new_not_reached_mail_round1' => array(
            "send_email" => true,
            "email_template_name" => 'Status 01 - Unterlagenmail Kunde nicht erreicht'
        ),
        '01_new_send_documents' => array(
            "send_email" => true,
            "email_template_name" => 'Status 01 - Unterlagenmail Kunde erreicht'
        ),
        '01_new_conclude' => array(
            "send_email" => false,
            "email_template_name" => 'No Template'
        ),
        '2a_not_reached_first_round_not_reached_mail_round_2' => array(
            "send_email" => true,
            "email_template_name" => 'Status 02a - Runde 1 - Kunde nicht erreicht'
        ),
        '2a_not_reached_first_round_send_documents' => array(
            "send_email" => true,
            "email_template_name" => 'Status 01 - Unterlagenmail Kunde erreicht'
        ),
        '2a_not_reached_first_round_receive_documents' => array(
            "send_email" => true,
            "email_template_name" => 'Status 04a - Unterlagen erhalten'
        ),
        '2a_not_reached_first_round_conclude' => array(
            "send_email" => false,
            "email_template_name" => 'No Template'
        ),
        '2b_not_reached_second_round_not_reached_mail_round_3' => array(
            "send_email" => true,
            "email_template_name" => 'Status 02b - Runde 2 - Kunde nicht erreicht'
        ),
        '2b_not_reached_second_round_send_documents' => array(
            "send_email" => true,
            "email_template_name" => 'Status 01 - Unterlagenmail Kunde erreicht'
        ),
        '2b_not_reached_second_round_receive_documents' => array(
            "send_email" => true,
            "email_template_name" => 'Status 04a - Unterlagen erhalten'
        ),
        '2b_not_reached_second_round_conclude' => array(
            "send_email" => false,
            "email_template_name" => 'No Template'
        ),
        '2c_not_reached_third_round_send_documents' => array(
            "send_email" => true,
            "email_template_name" => 'Status 01 - Unterlagenmail Kunde erreicht'
        ),
        '2c_not_reached_third_round_receive_documents' => array(
            "send_email" => true,
            "email_template_name" => 'Status 04a - Unterlagen erhalten'
        ),
        '2c_not_reached_third_round_closing_mail' => array(
            "send_email" => true,
            "email_template_name" => 'Status 02c - Runde 3 - Schlussmail'
        ),
        '2c_not_reached_third_round_conclude' => array(
            "send_email" => false,
            "email_template_name" => 'No Template'
        ),
        '2c_not_reached_third_round_pendent_schliessen' => array(
            "send_email" => true,
            "creat_task" => false,
            "email_template_name" => 'Status 02c - Runde 3 - Schlussmail'
        ),
        '3a_document_sent_first_round_send_documents_reminder_round_1' => array(
            "send_email" => true,
            "email_template_name" => 'Status 3a - Runde 1 - Kunde nicht erreicht'
        ),
        '3a_document_sent_first_round_receive_documents' => array(
            "send_email" => true,
            "email_template_name" => 'Status 04a - Unterlagen erhalten'
        ),
        '3a_document_sent_first_round_reached_kd_sends_ul_mail' => array(
            "send_email" => true,
            "email_template_name" => 'Status 01 - Unterlagenmail Kunde erreicht'
        ),
        '3a_document_sent_first_round_conclude' => array(
            "send_email" => false,
            "email_template_name" => 'No Template'
        ),
        '3b_document_sent_second_round_send_documents' => array(
            "send_email" => true,
            "email_template_name" => 'Status 01 - Unterlagenmail Kunde erreicht'
        ),
        '3b_document_sent_second_round_receive_documents' => array(
            "send_email" => true,
            "email_template_name" => 'Status 04a - Unterlagen erhalten'
        ),
        '3b_document_sent_second_round_closing_mail' => array(
            "send_email" => true,
            "email_template_name" => 'Status 03b - Runde 2 - Schlussmail'
        ),
        '3b_document_sent_second_round_conclude' => array(
            "send_email" => false,
            "email_template_name" => 'No Template'
        ),
        '3b_document_sent_second_round_pendent_schliessen' => array(
            "send_email" => true,
            "creat_task" => false,
            "email_template_name" => 'Status 03b - Runde 2 - Schlussmail'
        ),
        '13_customer_center_receive_documents' => array(
            "send_email" => true,
            "email_template_name" => 'Status 04a - Unterlagen erhalten'
        ),
        '13_customer_center_pendent_schliessen' => array(
            "send_email" => false,
            "creat_task" => false,
            "email_template_name" => 'No Template'
        ),
        '04_documents_received_for_more_documents' => array(
            "send_email" => true,
            "email_template_name" => 'Status 04a -Weitere Unterlagen erhalten'
        ),
        '04_documents_received_nachfasscall_consultation' => array(
            "send_email" => true,
            "email_template_name" => 'Status 04b - Offerte besprechen - Kunde nicht erreicht'
        ),
        '04_documents_received_delay_meeting_credit_offer' => array(
            "send_email" => true,
            "email_template_name" => 'Status 04c - Offerte im Verzug - Entschuldigung'
        ),
        '04_documents_received_discuss_credit_offer' => array(
            "send_email" => false,
            "email_template_name" => ''
        ),
        '04_documents_received_closing_mail' => array(
            "send_email" => true,
            "email_template_name" => 'Status 4d - Schlussmail'
        ),
        '04_documents_received_kd_reached_more_ul_required_email_manually' => array(
            "send_email" => false,
            "email_template_name" => 'Status 04 - Abklarungen'
        ),
        '04_documents_received_further_clarifications' => array(
            "send_email" => false,
            "email_template_name" => 'No Template'
        ),
        '04_documents_received_pendent_schliessen' => array(
            "send_email" => true,
            "creat_task" => false,
            "email_template_name" => 'Status 4d - Schlussmail'
        ),
        '05_checking_request_clarifications' => array(
            "send_email" => false,
            "email_template_name" => 'Status 05 - Abklarungen'
        ),
        '05_checking_request_get_debt' => array(
            "send_email" => false,
            "email_template_name" => 'Status 05 - Betreibungen'
        ),
        '06_sales_conversation_sales_pitch' => array(
            "send_email" => true,
            "email_template_name" => 'Status 06a - Ruckrufmail - Kunde nicht erreicht'
        ),
        '06_sales_conversation_shipment_of_the_contract' => array(
            "send_email" => false,
            "email_template_name" => 'No Template'
        ),
        '06_sales_conversation_closing_mail' => array(
            "send_email" => true,
            "email_template_name" => 'Status 06b - Schlussmail'
        ),
        '06_sales_conversation_pendent_schliessen' => array(
            "send_email" => true,
            "creat_task" => false,
            "email_template_name" => 'Status 06b - Schlussmail'
        ),
        '07_creating_contract_contract_with_customer_email_manual' => array(
            "send_email" => false,
            "email_template_name" => 'Status 07 Vertragsversand E-Mail'
        ),
        '07_creating_contract_contract_with_the_customer' => array(
            "send_email" => true,
            "email_template_name" => 'Status 07 Vertragsmail Postversand'
        ),
        '08_contract_at_customer_contract_with_the_customer_follow_up_documents' => array(
            "send_email" => true,
            "email_template_name" => 'Status 08 - Kd nicht erreicht - Nachfassmail'
        ),
        '08_contract_at_customer_payout_documents_completely' => array(
            "send_email" => true,
            "email_template_name" => 'Status 08a - Auszahlung'
        ),
        '08_contract_at_customer_payout_documents_not_completely' => array(
            "send_email" => false,
            "email_template_name" => 'Status 09 - Fehlende Unterlagen - 14 Tage'
        ),
        '08_contract_at_customer_closing_mail' => array(
            "send_email" => true,
            "email_template_name" => 'Status 08b - Schlussmail'
        ),
        '08_contract_at_customer_contract_creation' => array(
            "send_email" => false,
            "email_template_name" => ''
        ),
         '08_contract_at_customer_contract_creation_renewed' => array(
             "send_email" => false,
             "email_template_name" => ''
         ),
        '08_contract_at_customer_pendent_schliessen' => array(
            "send_email" => true,
            "creat_task" => false,
            "email_template_name" => 'Status 08b - Schlussmail'
        ),
        '09_payout_activation_contract' => array(
            "send_email" => true,
            "email_template_name" => 'Status 10 - Information Auszahlung'
        ),
        '09_payout_contract_creation' => array(
            "send_email" => false,
            "email_template_name" => ''
        ),
        '09_payout_payout_documents_completely' => array(
            "send_email" => true,
            "email_template_name" => 'Status 09 - Auszahlung'
        ),
    );

    public function registerApiRest() {
        return array(
            'AutoExecute' => array(
                'reqType' => 'POST',
                'noLoginRequired' => false,
                'path' => array('AutoExecuteWF', 'AutoExecute'),
                'pathVars' => array('', ''),
                'method' => 'autoExecute',
                'shortHelp' => 'Auto Execute workflow selected by user',
                'longHelp' => '',
            ),
        );
    }

    /**
     * Execute workflows based on status and next best step dropdown value
     */
    public function autoExecute($api, $args) {
        global $app_list_strings;
        global $timedate;
        global $current_user;
        $leadObj = BeanFactory::getBean("Leads", $args['id']);
        $sea = new SugarEmailAddress;
        $lead_new_status = '';
        $customer_contact_id = $leadObj->assigned_user_id;
        self::$next_best_steps=$args['nextBestStep'];
        /**
         *  Grab the primary address for the given record represented by the $bean object
         */
        $email_address = $sea->getPrimaryAddress($leadObj);
        $subject = '';
        self::$old_lead_status = $args['status'];
        if (isset($app_list_strings['workflow_tasks_subject_mapping'][$args['status'] . "_" . $args['nextBestStep']]))
            $subject = $app_list_strings['workflow_tasks_subject_mapping'][$args['status'] . "_" . $args['nextBestStep']];
        $templateObj = new EmailTemplate ();

        if ($args['status'] == '01_new' && $args['nextBestStep'] == 'not_reached_mail_round1') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P2D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "2a_not_reached_first_round";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '01_new' && $args['nextBestStep'] == 'send_documents') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P7D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "3a_document_sent_first_round";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '01_new' && $args['nextBestStep'] == 'pendent_due_plausi_tests_only_manually') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '01_new' && $args['nextBestStep'] == 'conclude') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "01_new";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Call Center", $lead_new_status, true);
        } else if ($args['status'] == '01_new' && $args['nextBestStep'] == 'task_and_substatus_manual') {
            $lead_new_status = "11_closed";
        }
        /**
         * Initial status 2a
         */ else if ($args['status'] == '2a_not_reached_first_round' && $args['nextBestStep'] == 'not_reached_mail_round_2') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P7D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "2b_not_reached_second_round";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '2a_not_reached_first_round' && $args['nextBestStep'] == 'send_documents') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P7D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "3a_document_sent_first_round";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '2a_not_reached_first_round' && $args['nextBestStep'] == 'receive_documents') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "04_documents_received";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Customer Care", $lead_new_status, true);
        } else if ($args['status'] == '2a_not_reached_first_round' && $args['nextBestStep'] == 'pendent_due_plausi_tests_only_manually') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '2a_not_reached_first_round' && $args['nextBestStep'] == 'conclude') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "2a_not_reached_first_round";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Call Center", $lead_new_status, true);
        } else if ($args['status'] == '2a_not_reached_first_round' && $args['nextBestStep'] == 'task_and_substatus_manual') {
            $lead_new_status = "11_closed";
        }
        /**
         * Initial status 2b
         */ else if ($args['status'] == '2b_not_reached_second_round' && $args['nextBestStep'] == 'not_reached_mail_round_3') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P7D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "2c_not_reached_third_round";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '2b_not_reached_second_round' && $args['nextBestStep'] == 'send_documents') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P7D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "3a_document_sent_first_round";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '2b_not_reached_second_round' && $args['nextBestStep'] == 'receive_documents') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "04_documents_received";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Customer Care", $lead_new_status, true);
        } else if ($args['status'] == '2b_not_reached_second_round' && $args['nextBestStep'] == 'pendent_due_plausi_tests_only_manually') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '2b_not_reached_second_round' && $args['nextBestStep'] == 'conclude') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "2b_not_reached_second_round";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Call Center", $lead_new_status, true);
        } else if ($args['status'] == '2b_not_reached_second_round' && $args['nextBestStep'] == 'task_and_substatus_manual') {
            $lead_new_status = "11_closed";
        }

        /**
         * Initial status 2c
         */ else if ($args['status'] == '2c_not_reached_third_round' && $args['nextBestStep'] == 'send_documents') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P7D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "3a_document_sent_first_round";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '2c_not_reached_third_round' && $args['nextBestStep'] == 'closing_mail') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '2c_not_reached_third_round' && $args['nextBestStep'] == 'receive_documents') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "04_documents_received";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Customer Care", $lead_new_status, true);
        } else if ($args['status'] == '2c_not_reached_third_round' && $args['nextBestStep'] == 'pendent_due_plausi_tests_only_manually') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '2c_not_reached_third_round' && $args['nextBestStep'] == 'conclude') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "2c_not_reached_third_round";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Call Center", $lead_new_status, true);
        } else if ($args['status'] == '2c_not_reached_third_round' && $args['nextBestStep'] == 'pendent_schliessen') {
            $lead_new_status = "00_pendent_geschlossen";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, 'closing_date', "Leads", $args['id'], "Call Center", $lead_new_status, false);
        } else if ($args['status'] == '2c_not_reached_third_round' && $args['nextBestStep'] == 'task_and_substatus_manual') {
            $lead_new_status = "11_closed";
        }

        /**
         * Initial status 3a
         */ else if ($args['status'] == '3a_document_sent_first_round' && $args['nextBestStep'] == 'send_documents_reminder_round_1') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P7D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "3b_document_sent_second_round";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '3a_document_sent_first_round' && $args['nextBestStep'] == 'receive_documents') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "04_documents_received";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Customer Care", $lead_new_status, true);
        } else if ($args['status'] == '3a_document_sent_first_round' && $args['nextBestStep'] == 'reached_kd_sends_ul_mail') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P7D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "3b_document_sent_second_round";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '3a_document_sent_first_round' && $args['nextBestStep'] == 'pendent_due_plausi_tests_only_manually') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '3a_document_sent_first_round' && $args['nextBestStep'] == 'conclude') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "3a_document_sent_first_round";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Call Center", $lead_new_status, true);
        } else if ($args['status'] == '3a_document_sent_first_round' && $args['nextBestStep'] == 'task_and_substatus_manual') {
            $lead_new_status = "11_closed";
        }

        /**
         * Initial status 3b
         */ else if ($args['status'] == '3b_document_sent_second_round' && $args['nextBestStep'] == 'send_documents') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P7D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "3b_document_sent_second_round";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '3b_document_sent_second_round' && $args['nextBestStep'] == 'receive_documents') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "04_documents_received";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Customer Care", $lead_new_status, true);
        } else if ($args['status'] == '3b_document_sent_second_round' && $args['nextBestStep'] == 'closing_mail') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '3b_document_sent_second_round' && $args['nextBestStep'] == 'pendent_due_plausi_tests_only_manually') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '3b_document_sent_second_round' && $args['nextBestStep'] == 'conclude') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "3b_document_sent_second_round";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Call Center", $lead_new_status, true);
        } else if ($args['status'] == '3b_document_sent_second_round' && $args['nextBestStep'] == 'pendent_schliessen') {
            $lead_new_status = "00_pendent_geschlossen";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, 'closing_date', "Leads", $args['id'], "Call Center", $lead_new_status, false);
        } else if ($args['status'] == '3b_document_sent_second_round' && $args['nextBestStep'] == 'task_and_substatus_manual') {
            $lead_new_status = "11_closed";
        }


        /**
         * Initial status 13
         */ else if ($args['status'] == '13_customer_center' && $args['nextBestStep'] == 'no_incoming_documents_round_1') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P2D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "13_customer_center";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '13_customer_center' && $args['nextBestStep'] == 'no_incoming_documents_round_2') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P5D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "13_customer_center";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '13_customer_center' && $args['nextBestStep'] == 'no_incoming_documents_round_3') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P7D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "13_customer_center";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '13_customer_center' && $args['nextBestStep'] == 'no_document_input_closing_mail') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '13_customer_center' && $args['nextBestStep'] == 'closed_only_manually') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '13_customer_center' && $args['nextBestStep'] == 'pendent_due_plausi_tests_only_manually') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '13_customer_center' && $args['nextBestStep'] == 'receive_documents') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "04_documents_received";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Customer Care", $lead_new_status, true);
        } else if ($args['status'] == '13_customer_center' && $args['nextBestStep'] == 'pendent_schliessen') {
            $lead_new_status = "00_pendent_geschlossen";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, 'closing_date', "Leads", $args['id'], "Call Center", $lead_new_status, false);
        }


        /**
         * Initial status 04
         */ else if ($args['status'] == '04_documents_received' && $args['nextBestStep'] == 'for_more_documents') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "04_documents_received";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '04_documents_received' && $args['nextBestStep'] == 'submit_application') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "04_documents_received";
			self::$set_application_user_approval = true;
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Approval", $lead_new_status, true);
            //Task id is not empty so multiple applications exist
            if(!empty(self::$return_arr['task_id'])){
                self::$return_arr['app_approval_rule'] = true;
            }
        } else if ($args['status'] == '04_documents_received' && $args['nextBestStep'] == 'follow_up_decision_from_bank') {
            $date = $timedate->getTimePart($timedate->now());
            $dueDate = new DateTime($timedate->nowDb());
            if (strtotime($date) > strtotime('16:00')) {
                $dueDate->add(new DateInterval('P2D'));
            } else {
                $dueDate->add(new DateInterval('P1D'));
            }
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "05_checking_request";
            $app_user_approval_id = self::getApplicationUser($leadObj, 'user_id_c');
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $app_user_approval_id, $dueDate, "Leads", $args['id'], "Approval", $lead_new_status, true);
        } else if ($args['status'] == '04_documents_received' && $args['nextBestStep'] == 'kd_reached_more_ul_required_email_manually') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P4D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "04_documents_received";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $current_user->id, $dueDate, "Leads", $args['id'], null, $lead_new_status, true);
        } else if ($args['status'] == '04_documents_received' && $args['nextBestStep'] == 'nachfasscall_consultation') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P1D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "04_documents_received";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '04_documents_received' && $args['nextBestStep'] == 'delay_meeting_credit_offer') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P1D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "04_documents_received";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '04_documents_received' && $args['nextBestStep'] == 'discuss_credit_offer') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "04_documents_received";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '04_documents_received' && $args['nextBestStep'] == 'further_clarifications') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "04_documents_received";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $leadObj->assigned_user_id, $dueDate, "Leads", $args['id'], null, $lead_new_status, true);
        } else if ($args['status'] == '04_documents_received' && $args['nextBestStep'] == 'closed_only_manually') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '04_documents_received' && $args['nextBestStep'] == 'closing_mail') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '04_documents_received' && $args['nextBestStep'] == 'pendent_due_plausi_tests_only_manually') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '04_documents_received' && $args['nextBestStep'] == 'pendent_schliessen') {
            $lead_new_status = "00_pendent_geschlossen";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, 'closing_date', "Leads", $args['id'], "Call Center", $lead_new_status, false);
        }

        /**
         * Initial status 05
         */ else if ($args['status'] == '05_checking_request' && $args['nextBestStep'] == 'request_for_examination_potential_clarification') {
            $date = $timedate->getTimePart($timedate->now());
            $dueDate = new DateTime($timedate->nowDb());
            if (strtotime($date) > strtotime('16:00')) {
                $dueDate->add(new DateInterval('P2D'));
            } else {
                $dueDate->add(new DateInterval('P1D'));
            }
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "05_checking_request";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $leadObj->assigned_user_id, $dueDate, "Leads", $args['id'], "Approval", $lead_new_status, true);
        } else if ($args['status'] == '05_checking_request' && $args['nextBestStep'] == 'clarifications') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P3D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "05_checking_request";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '05_checking_request' && $args['nextBestStep'] == 'get_debt') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P7D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "05_checking_request";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Customer Care", $lead_new_status, true);
        } else if ($args['status'] == '05_checking_request' && $args['nextBestStep'] == 'sales_pitch') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "06_sales_conversation";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '05_checking_request' && $args['nextBestStep'] == 'request_for_examination_rejection_this_week') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "05_checking_request";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '05_checking_request' && $args['nextBestStep'] == 'closed_only_manually') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '05_checking_request' && $args['nextBestStep'] == 'pendent_due_plausi_tests_only_manually') {
            $lead_new_status = "11_closed";
        }

        /**
         * Initial status 06
         */ else if ($args['status'] == '06_sales_conversation' && $args['nextBestStep'] == 'kd_reached_not_sold_consider_alternative') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "04_documents_received";
            $app_user_approval_id = self::getApplicationUser($leadObj, 'user_id_c');
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $app_user_approval_id, $dueDate, "Leads", $args['id'], "Approval", $lead_new_status, true);
        } else if ($args['status'] == '06_sales_conversation' && $args['nextBestStep'] == 'sales_pitch') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P1D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "06_sales_conversation";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        }  else if ($args['status'] == '06_sales_conversation' && $args['nextBestStep'] == 'shipment_of_the_contract') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "07_creating_contract";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Vertrag", $lead_new_status, true);
            self::generatePaymentPdf($leadObj->id);
        } else if ($args['status'] == '06_sales_conversation' && $args['nextBestStep'] == 'contract_creation') {

            if ($leadObj->load_relationship('leads_opportunities_1')) {
                $relatedApplications = $leadObj->leads_opportunities_1->getBeans();
                $non_rejected_app = 0;
                $task_credit_amount = '';
                foreach ($relatedApplications as $application) {
                    /*
                     * Old Requirements
                     */
                    if ($application->provider_status_id_c == 'granted' && (empty($application->contract_credit_amount) || empty($application->contract_credit_duration) || empty($application->contract_interest_rate))) {
                        self::$return_arr['response']='app_has_empty_values';
                         return self::$return_arr;
                    }
                    /*
                     * New Requirements
                     */
                    if ($application->provider_status_id_c != 'rejected') {
                        self::$task_credit_amount = $application->contract_credit_amount;
                        $non_rejected_app++;
                    }
                }
            }

            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = '07_creating_contract';
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Vertrag", $lead_new_status, true);
            if ($non_rejected_app > 1) {
                self::$return_arr['response']='non_rejected_app';
                return self::$return_arr;
            }
        } else if ($args['status'] == '06_sales_conversation' && $args['nextBestStep'] == 'closing_mail') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '06_sales_conversation' && $args['nextBestStep'] == 'closed_only_manually') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '06_sales_conversation' && $args['nextBestStep'] == 'pendent_due_plausi_tests_only_manually') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '06_sales_conversation' && $args['nextBestStep'] == 'pendent_schliessen') {
            $lead_new_status = "00_pendent_geschlossen";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, 'closing_date', "Leads", $args['id'], "Call Center", $lead_new_status, false);
        }


        /**
         * Initial status 07
         */ else if ($args['status'] == '07_creating_contract' && $args['nextBestStep'] == 'contract_with_the_customer') {

            if ($leadObj->load_relationship('contracts_leads_1')) {
                $relatedContracts = $leadObj->contracts_leads_1->getBeans();
                $relatedContract = false;
                if (!empty($relatedContracts)) {
                    reset($relatedContracts);
                    $relatedContract = current($relatedContracts);
                    if (!empty($relatedContract->paying_date_c)) {
                        $dueDate = new DateTime($timedate->nowDb());
                        $dueDate->add(new DateInterval('P7D'));
                        $dueDate = $dueDate->format('Y-m-d H:i:s');
                        $lead_new_status = "08_contract_at_customer";
                        self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
                    } else {
                        self::$return_arr['response']='no_due_date';
                        return self::$return_arr;
                    }
                } else {
                    self::$return_arr['response']='no_due_date';
                    return self::$return_arr;
                }
            }
        } else if ($args['status'] == '07_creating_contract' && $args['nextBestStep'] == 'contract_with_customer_email_manual') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P7D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "08_contract_at_customer";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);

        }

        /**
         * Initial status 08
         */ else if ($args['status'] == '08_contract_at_customer' && $args['nextBestStep'] == 'contract_with_the_customer_follow_up_documents') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate->add(new DateInterval('P3D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "08_contract_at_customer";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '08_contract_at_customer' && $args['nextBestStep'] == 'payout_documents_completely') {
            // Need to apply below formula on due date
            //CONTRACT / contract_date + 8 days, if CONTRACT / PROVIDER_ID = Cembra, CONTRACT / contract_date + 10 days
//            $dueDate = new DateTime($timedate->nowDb());
//            $dueDate->add(new DateInterval('P8D'));
//            $dueDate = $dueDate->format('Y-m-d H:i:s');
//            $lead_new_status = "09_payout";
//            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Approval", $lead_new_status,true);
            if ($leadObj->load_relationship('contracts_leads_1')) {
                $relatedContracts = $leadObj->contracts_leads_1->getBeans();
                $relatedContract = false;
                if (!empty($relatedContracts)) {
                    reset($relatedContracts);
                    $relatedContract = current($relatedContracts);
                    if (!empty($relatedContract->paying_date_c)) {
                        $dueDate = new DateTime($timedate->fromUserDate($relatedContract->paying_date_c));
                        /* if ($relatedContract->provider_id_c == 'cembra') {
                          $dueDate->add(new DateInterval('P10D'));
                          } else {
                          $dueDate->add(new DateInterval('P8D'));
                          } */
                        $lead_new_status = "09_payout";
                        self::$activation_status = "08_contract_at_customer";
                        $dueDate = $dueDate->format('Y-m-d H:i:s');
                        //$app_user_approval_id = self::getApplicationUser($leadObj,'assigned_user_id');
                        self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Auszahlung", $lead_new_status, true);
                    } else {
                        self::$return_arr['response']='no_due_date';
                         return self::$return_arr;
                    }
                } else {
                    self::$return_arr['response']='no_due_date';
                    return self::$return_arr;
                }
            }
        } else if ($args['status'] == '08_contract_at_customer' && $args['nextBestStep'] == 'contract_creation') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "07_creating_contract";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Vertrag", $lead_new_status, true);
        } else if ($args['status'] == '08_contract_at_customer' && $args['nextBestStep'] == 'contract_creation_renewed') {
             $dueDate = new DateTime($timedate->nowDb());
             $dueDate = $dueDate->format('Y-m-d');
             $dueDate = $dueDate . ' 12:00:00';
             $lead_new_status = "07_creating_contract";
             self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Vertrag", $lead_new_status, true);
        } else if ($args['status'] == '08_contract_at_customer' && $args['nextBestStep'] == 'payout_documents_not_completely') {
            $dueDate = new DateTime($timedate->nowDb());
            //$dueDate->add(new DateInterval('P4D'));
            $dueDate = $dueDate->format('Y-m-d H:i:s');
            $lead_new_status = "09_payout";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Abklaerungen", $lead_new_status, true);
        } else if ($args['status'] == '08_contract_at_customer' && $args['nextBestStep'] == 'kd_reached_waiver') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "11_closed";
            $app_user_approval_id = self::getApplicationUser($leadObj, 'user_id_c');
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $app_user_approval_id, $dueDate, "Leads", $args['id'], "Approval", $lead_new_status, true);
        } else if ($args['status'] == '08_contract_at_customer' && $args['nextBestStep'] == 'closing_mail') {
            if (empty($leadObj->credit_request_substatus_id_c)) {
                self::$return_arr['response']='add_substatus';
                return self::$return_arr;
            } else {
                $dueDate = new DateTime($timedate->nowDb());
                $dueDate = $dueDate->format('Y-m-d');
                $dueDate = $dueDate . ' 12:00:00';
                $lead_new_status = "11_closed";
                self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $leadObj->assigned_user_id, $dueDate, "Leads", $args['id'], "Customer Care", $lead_new_status, true);
            }
        } else if ($args['status'] == '08_contract_at_customer' && $args['nextBestStep'] == 'do_without') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "08_contract_at_customer";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, $customer_contact_id, $dueDate, "Leads", $args['id'], "Sales", $lead_new_status, true);
        } else if ($args['status'] == '08_contract_at_customer' && $args['nextBestStep'] == 'pendent_due_plausi_tests_only_manually') {
            $lead_new_status = "11_closed";
        } else if ($args['status'] == '08_contract_at_customer' && $args['nextBestStep'] == 'pendent_schliessen') {
            $lead_new_status = "00_pendent_geschlossen";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, 'closing_date', "Leads", $args['id'], "Call Center", $lead_new_status, false);
        }


        /**
         * Initial status 09
         */ else if ($args['status'] == '09_payout' && $args['nextBestStep'] == 'activation_contract') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 00:00:00';
            $lead_new_status = "10_active";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Provision", $lead_new_status, true);
             //Copy All open tasks to contact
            if (!empty($leadObj->contact_id)) {
                $contactObj = BeanFactory::getBean("Contacts", $leadObj->contact_id);
                if ($leadObj->load_relationship("tasks")) {
                    $contactObj->load_relationship('tasks');
                    $relatedTasks = $leadObj->tasks->getBeans();
                    foreach ($relatedTasks as $task) {
                        if ($task->status != 'closed') {
                            $task->parent_type = 'Contacts';
                            $task->parent_id = $contactObj->id;
                            $task->contact_id = $contactObj->id;
                            $task->processed = true;
                            $task->save();
                            $contactObj->tasks->add($task->id);
                        }
                    }
                }
            }
        } else if ($args['status'] == '09_payout' && $args['nextBestStep'] == 'contract_creation') {
            $dueDate = new DateTime($timedate->nowDb());
            $dueDate = $dueDate->format('Y-m-d');
            $dueDate = $dueDate . ' 12:00:00';
            $lead_new_status = "07_creating_contract";
            self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Vertrag", $lead_new_status, true);
        } else if ($args['status'] == '09_payout' && $args['nextBestStep'] == 'payout_documents_completely') {
            $lead_new_status = "09_payout";
            self::$activation_status = "09_payout";
            if ($leadObj->load_relationship('contracts_leads_1')) {
                $relatedContracts = $leadObj->contracts_leads_1->getBeans();
                $relatedContract = false;
                if (!empty($relatedContracts)) {
                    reset($relatedContracts);
                    $relatedContract = current($relatedContracts);
                    if (!empty($relatedContract->paying_date_c)) {
                        $dueDate = new DateTime($timedate->fromUserDate($relatedContract->paying_date_c));
                        /* if ($relatedContract->provider_id_c == 'cembra') {
                          $dueDate->add(new DateInterval('P10D'));
                          } else {
                          $dueDate->add(new DateInterval('P8D'));
                          } */
                        $dueDate = $dueDate->format('Y-m-d H:i:s');
                        /*
                         * Client asked to change this user to null and team Auszahlung
                         * $user = BeanFactory::getBean('Users')->retrieve_by_string_fields(array('user_name' => 'albert.schirmer'));
                         */
                        self::$return_arr['task_id']=self::createTaskAndUpdateLeadStatus($subject, null, $dueDate, "Leads", $args['id'], "Auszahlung", $lead_new_status, true);
                    } else {
                        self::$return_arr['response']='no_due_date';
                        return self::$return_arr;
                    }
                } else {
                    self::$return_arr['response']='no_due_date';
                    return self::$return_arr;
                }
            }
        } else if ($args['status'] == '09_payout' && $args['nextBestStep'] == 'closed_only_manually') {
            $lead_new_status = "11_closed";
        }
        //$leadObj->save();
        if ($lead_new_status != '11_closed' && $lead_new_status != '00_pendent_geschlossen') {
            $next_best_steps=self::$next_best_steps;
            $cstm_query = "UPDATE leads_cstm SET credit_request_status_id_c='$lead_new_status',lq_next_best_steps_c='$next_best_steps' WHERE id_c='$leadObj->id'";
            $GLOBALS['db']->query($cstm_query);
            self::insertDataInAuditTable($leadObj->id, $lead_new_status);
        }
        if (!self::$tasks_updated) {
            self::setTasksStatus($leadObj->id, $lead_new_status, true);
            self::$tasks_updated = true;
        }

        /**
         *  Check if sending of email is required or not
         */
        global $sugar_config;

        if (isset(self::$emailNameMapping[$args['status'] . "_" . $args['nextBestStep']]['send_email']) && isset($sugar_config['auto_email']))
            if (self::$emailNameMapping[$args['status'] . "_" . $args['nextBestStep']]['send_email'] == true && $sugar_config['auto_email'] == true) {
                /**
                 *  Prepare and send Email
                 */
                if (!empty($leadObj->dotb_correspondence_language_c)) {
                    $lang = $leadObj->dotb_correspondence_language_c;
                } else {
                    $lang = 'de'; // Use German template as default
                }
                $templateObj->retrieve_by_string_fields(array('name' => $lang . '_' . self::$emailNameMapping[$args['status'] . "_" . $args['nextBestStep']]['email_template_name'], 'type' => 'workflow'));
                $htmlBody = $templateObj->body_html;
                $htmlBody = parse_alert_template($leadObj, $htmlBody);
                $signatureObj = new customParserApi();
                $lead_status = '';
                if(isset($args['status']) && !empty($args['status'])){
                    $lead_status = $args['status']; 
                } else{
                    $lead_status = $leadObj->credit_request_status_id_c;
                }
                $htmlBody = $signatureObj->getUserSignature($leadObj->assigned_user_id, $htmlBody, $lang, true, $lead_status);
                //$htmlBody=  str_replace('%2B', '+', $htmlBody);
                $emailSent = self::sendEmail($email_address, $templateObj->subject, $htmlBody, $leadObj, $signatureObj);
            }
        // if status is closed call hook so that linked records are copied in Contact
        if ($lead_new_status == '11_closed') {
            require_once('custom/modules/Leads/CreateContact.php');
            $lead_bean = BeanFactory::getBean("Leads", $leadObj->id);
            $lead_hook = new CreateContact();
            $lead_hook->create($lead_bean, '', '');
            self::$return_arr['response']='closed';
        }
        if ($lead_new_status == '00_pendent_geschlossen') {
            self::$return_arr['response']='00_pendent_geschlossen';
        }
        if(!isset(self::$return_arr['response']))
            self::$return_arr['response']='';
        return self::$return_arr;
    }
    
    public static function getApplicationUser($leadObj, $name_user_id) {
        $app_user_approval_id = null;
        if ($leadObj->load_relationship('leads_opportunities_1')) {
            $relatedApps = $leadObj->leads_opportunities_1->getBeans();
            $count = 0;
            $date_entered = '';
            foreach ($relatedApps as $appObj) {
                if ($appObj->provider_status_id_c == "granted") {
                    if ($count == 0) {
                        $app_user_approval_id = $appObj->$name_user_id;
                        $date_entered = $appObj->date_entered;
                    } else if (strtotime($appObj->date_entered) > strtotime($date_entered)) {
                        $app_user_approval_id = $appObj->$name_user_id;
                        $date_entered = $appObj->date_entered;
                    }
                    $count++;
                }
            }
        }
        return $app_user_approval_id;
    }
    
    public static function insertDataInAuditTable($parentId, $lead_status) {
        if (self::$insert_lead_audit) {
            
            insertRecordInAuditTable('Leads', 'credit_request_status_id_c', self::$old_lead_status, $lead_status, $parentId, 'enum');
            
            self::$old_lead_status = '';
            self::$insert_lead_audit = false;
        }
    }

    public static function createTaskAndUpdateLeadStatus($subject, $assingedTo, $dueDate, $parentType, $parentId, $assingedToTeam, $lead_status, $creat_task) {
        global $app_list_strings, $timedate;
        $return_task_id = '';
        //set leads status

        if ($dueDate == 'closing_date') {
            $closing_date = new DateTime($timedate->nowDb());
            $closing_date->add(new DateInterval('P30D'));
            $closing_date = $closing_date->format('Y-m-d');
            $leads_cstm_query = "UPDATE leads_cstm SET closing_date_c='$closing_date' WHERE id_c='$parentId'";
            $GLOBALS['db']->query($leads_cstm_query);
        }
        if ($lead_status != '11_closed' && $lead_status != '00_pendent_geschlossen') {
            $next_best_steps=self::$next_best_steps;
            $leads_cstm_query = "UPDATE leads_cstm SET credit_request_status_id_c='$lead_status',lq_next_best_steps_c='$next_best_steps' WHERE id_c='$parentId'";
            $GLOBALS['db']->query($leads_cstm_query);
            self::insertDataInAuditTable($parentId, $lead_status);
        }
       
        if ((self::$activation_status == '08_contract_at_customer' || self::$activation_status == '09_payout') && self::$next_best_steps == 'payout_documents_completely') {
            $leadObj = BeanFactory::getBean("Leads", $parentId);
            $granted_app_id = '';
            $contract_app_id = '';
            $approved_app_count = 0;
            if ($leadObj->load_relationship('contracts_leads_1')) {
                $relatedContracts = $leadObj->contracts_leads_1->getBeans();
                foreach ($relatedContracts as $contract) {
                    $contract_app_id = $contract->opportunity_id;
                }
            }
            
            if ($leadObj->load_relationship("leads_opportunities_1")) {
                $relatedApps = $leadObj->leads_opportunities_1->getBeans();
                foreach ($relatedApps as $relatedApp) {
                    if ($relatedApp->provider_status_id_c == 'granted') {
                        $granted_app_id = $relatedApp->id;
                        $approved_app_count++;
                    }
                    
                    if ($relatedApp->id != $contract_app_id){
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
                        $task->parent_id = $leadObj->id;
                        $task->load_relationship("leads");
                        $task->leads->add($leadObj->id);
                        $task->save();
                    }
                }
            }
            
            if (empty($contract_app_id)) {
                if($approved_app_count==1)
                $GLOBALS['db']->query("UPDATE opportunities_cstm SET provider_status_id_c='active' WHERE id_c='$granted_app_id'");
                insertRecordInAuditTable('Opportunities', 'provider_status_id_c', 'granted', 'active', $granted_app_id, 'enum');
            } else {
                $GLOBALS['db']->query("UPDATE opportunities_cstm SET provider_status_id_c='active' WHERE id_c='$contract_app_id'");
                insertRecordInAuditTable('Opportunities', 'provider_status_id_c', 'granted', 'active', $contract_app_id, 'enum');
            }
        }
        
        if (!self::$tasks_updated) {
            self::setTasksStatus($parentId, $lead_status);
            self::$tasks_updated = true;
        }
        
        if ($creat_task) {
            $bank=getTaskBank($parentId);
            $task = BeanFactory::getBean("Tasks");
            //$task->category_c = $subject;
            if (!empty($subject)) {
                $task->name = $app_list_strings['dotb_task_categories_list'][$subject];
            }
            if (self::$task_credit_amount) {
                $task->lead_amount_c = self::$task_credit_amount;
                $task->is_lead_amount_from_app = true;
            }
            $task->assigned_user_id = $assingedTo;
            $task->date_due = $dueDate;
            $task->parent_type = $parentType;
            $task->parent_id = $parentId;
            $task->bank_c = $bank;
            $task->status = 'open';
            $task->lead_status_c = $lead_status;
            
            /*
             * Inhereting teams from parent
             */
            require_once('modules/Teams/TeamSet.php');
            $teamSetBean = new TeamSet();
            $primaryTeam = '';
            $parent = BeanFactory::getBean($parentType, $parentId);
            
            
            $team_id = array();
            $teams = $teamSetBean->getTeams($parent->team_set_id);
            foreach ($teams as $key => $team) {
                $team_id[] = $key;
            }
            if (empty($team_id))
                $team_id[] = $parent->team_id;

            /*
             * Adding Global team in Task
             */
            $team_id[] = '1';

            if (empty($assingedToTeam)) {
                $task->team_id = $parent->team_id;
            }else{
            $team = new Team();
            if ($team->retrieve_by_string_fields(array('name' => $assingedToTeam))) {
                $task->team_id = $team->id;
                $team_id[] = $parent->team_id;
            }
            }
            $task->created_from_workflow = true;
            $task->save();
            $task->load_relationship("leads");
            $task->leads->add($parentId);
            
                        
            $task->load_relationship('teams');
            $task->teams->replace($team_id);
            self::$return_arr['task_bank']=$bank;
            self::$return_arr['task_name']=$task->name;
            //only send task id if there are multiple applications linked to a lead			
            if(self::checkApplicationsRelatedToLead($parentId, $task->id, $task->application_provider_c, $task->assigned_user_id))
                $return_task_id = $task->id;

            return $return_task_id;
        }
        
    }

    function sendEmail($emailTo, $emailSubject, $emailBody, SugarBean $relatedBean = null, $customParseObj = null) {
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        
        $mail->From = (isset($customParseObj->fromAddress) ? $customParseObj->fromAddress: $defaults['email']);
        $mail->FromName = (isset($customParseObj->fromName)? $customParseObj->fromName: $defaults['name']);
        
       // $mail->ClearAllRecipients();
       // $mail->ClearReplyTos();
        $mail->Subject = from_html($emailSubject);
        $mail->Body_html = $emailBody;
        $mail->Body = $emailBody;
        $mail->IsHTML(true); //Omit or comment out this line if plain text
        $mail->prepForOutbound();
        $mail->AddAddress($emailTo);

        
        if(isset($customParseObj->replyTo)){

            $replyToName = '';
            if(isset($this->replyToName)) {
                $replyToName = $this->replyToName;
            }
            $mail->addReplyTo($customParseObj->replyTo, $replyToName);
        }
        //now create email
        if (@$mail->Send()) {
            $emailObj->to_addrs = $emailTo;
            $emailObj->type = 'archived';
            $emailObj->deleted = '0';
            $emailObj->name = $mail->Subject;
            $emailObj->description = strip_tags($mail->Body);
            $emailObj->description_html = $mail->Body_html;
            $emailObj->from_addr = $defaults['email'];
            if ($relatedBean instanceOf SugarBean && !empty($relatedBean->id)) {
                $emailObj->parent_type = $relatedBean->module_dir;
                $emailObj->parent_id = $relatedBean->id;
            }
            $emailObj->date_sent = TimeDate::getInstance()->nowDb();
            $emailObj->modified_user_id = '1';
            $emailObj->created_by = '1';
            $emailObj->status = 'sent';
            $emailObj->save();
            $from=$defaults['name'].' <'.$defaults['email'].'>';
            $to=$relatedBean->name.' <'.$emailTo.'>';
            $GLOBALS['db']->query("UPDATE emails_text SET from_addr='$from',to_addrs='$to' WHERE email_id='$emailObj->id'");
        }
    }

    /*
     * Set all related tasks lead status same as Lead status
     */

    function setTasksStatus($lead_id, $lead_new_status) {
        if (!empty($lead_id)) {
            $lead = BeanFactory::getBean("Leads", $lead_id);
            if ($lead->load_relationship("tasks")) {
                $relatedTasks = $lead->tasks->getBeans();
                foreach ($relatedTasks as $task) {
                    $GLOBALS['db']->query("UPDATE tasks_cstm SET lead_status_c='$lead_new_status' WHERE id_c='$task->id'");
                    insertRecordInAuditTable('Tasks', 'lead_status_c', $task->lead_status_c, $lead_new_status, $task->id, 'enum');

                }
            }
        }
    }
    
    /*
     * Generate PDF for 07 - Vertragsversand
     */

    function generatePaymentPdf($lead_id) {
        global $current_user;

        $leadObj = BeanFactory::getBean("Leads", $lead_id);

        //applications
        $app = array();
        $leadObj->load_relationship("leads_opportunities_1");
        $relatedApplications = $leadObj->leads_opportunities_1->getBeans();
        $count = 0;
        $date_entered = '';
        $makePdf = false;
        foreach ($relatedApplications as $application) {
            if ($application->provider_id_c == "rci" && $application->contract_transfer_fee == 1) {
                $makePdf = true;
                $copy = false;
                if ($count == 0) {
                    $copy = true;
                    $date_entered = $application->date_entered;
                } else if (strtotime($application->date_entered) > strtotime($date_entered)) {
                    $copy = true;
                    $date_entered = $application->date_entered;
                }
                if ($copy) {
                    $app['provider_application_no_c'] = $application->provider_application_no_c;
                    $app['credit_amount_c'] = $application->contract_credit_amount;
                    $app['saldo'] = $application->contract_saldo_1;
                    $app['name_fremdbank'] = $application->contract_name_fremdbank_1;
                    $app['saldo_2'] = $application->contract_saldo_2;
                    $app['name_fremdbank_2'] = $application->contract_name_fremdbank_2;
                    $app['saldo_3'] = $application->contract_saldo_3;
                    $app['name_fremdbank_3'] = $application->contract_name_fremdbank_3;

                    if (isset($app['saldo']) && !empty($app['saldo'])) {
                        $app['saldo_check1'] = 1;
                    }

                    if (isset($app['saldo_2']) && !empty($app['saldo_2'])) {
                        $app['saldo_check2'] = 1;
                    }

                    if (isset($app['saldo_3']) && !empty($app['saldo_3'])) {
                        $app['saldo_check3'] = 1;
                    }
                }
                $count++;
            }
        }

        if ($makePdf == true) {
            $pdf_name = "Zahlungsauftrag_RCI";

            if (!is_dir("./dotb_pdf_generation/models") || !file_exists("./dotb_pdf_generation/models/zahlungsauftrag-rci.pdf") || !file_exists("./dotb_pdf_generation/models/zahlungsauftrag-rci.mapping.php")
            ) {
                throw new SugarApiExceptionNotFound('pdf model or mapping file not found');
            }
            $tempDir = "./cache/pdftk";
            if (!is_dir($tempDir)) {
                mkdir($tempDir);
            }
            $documentDir = "./dotb_pdf_generation/documents";
            if (!is_dir($documentDir)) {
                mkdir($documentDir);
            }

            $emptyFile = "./dotb_pdf_generation/models/zahlungsauftrag-rci.pdf";

            $dataFileName = "{$tempDir}/$pdf_name.xfdf";

            include "./dotb_pdf_generation/models/zahlungsauftrag-rci.mapping.php";
            if (!isset($fieldMap) || !is_array($fieldMap)) {
                throw new SugarApiException("Field map not correctly initialized");
            }

            // Step 2 : create a xfdf file containing all values to include in pdf file
            $dataFile = fopen($dataFileName, "w+");

            $xfdfHeader = "<?xml version='1.0' encoding='UTF-8'?>\n" .
                    "<xfdf xmlns='http://ns.adobe.com/xfdf/' xml:space='preserve'>\n" .
                    "  <fields>\n";
            fwrite($dataFile, $xfdfHeader);

            foreach ($fieldMap as $pdfFieldName => $fieldDescription) {
                $xfdfFieldText = "<field name='" . $fieldDescription['pdf_name'] . "'><value>";
                $model_name = $fieldDescription['model_name'];
                if ($fieldDescription['type'] == 'lead-text') {
                    if ($model_name == 'lead_name') {
                        $xfdfFieldText .= $leadObj->first_name . ' ' . $leadObj->last_name;
                    } else {
                        $xfdfFieldText .= $leadObj->$model_name;
                    }
                } else if ($fieldDescription['type'] == 'app-text') {
                    if (isset($app[$model_name])) {
                        if (($model_name == 'saldo_check1' || $model_name == 'saldo_check2' || $model_name == 'saldo_check3') && $fieldDescription['values_map'][$app[$fieldDescription['model_name']]]) {
                            $value = $fieldDescription['values_map'][$app[$fieldDescription['model_name']]];
                            $xfdfFieldText .= $value;
                        } else {
                            $xfdfFieldText .= $app[$model_name];
                        }
                    }
                } elseif ($fieldDescription['type'] == 'date') {
                    $format = $current_user->getPreference('datef');
                    $xfdfFieldText .= '( Vers. '.date($format.' H:i:s').' )';
                }

                $xfdfFieldText .= "</value></field>\n";
                fwrite($dataFile, $xfdfFieldText);
            }

            $xfdfFooter = "  </fields>\n</xfdf>";
            fwrite($dataFile, $xfdfFooter);

            fclose($dataFile);

            $document = BeanFactory::getBean('Documents');
            $document->id = create_guid();
            $document->new_with_id = true;
            $doc_name = str_replace("_", " ", $pdf_name);
            $document->name = "$doc_name.pdf";
            $document->rev_file_name = "$doc_name.pdf";

            $previousRevision = BeanFactory::getBean('DocumentRevisions', $document->document_revision_id);
            $revision = BeanFactory::getBean('DocumentRevisions');
            $revision->id = create_guid();
            $revision->new_with_id = true;
            $revision->document_id = $document->id;
            $revision->doc_type = 'Sugar';
            $revision->filename = $document->name;
            $revision->file_ext = 'pdf';
            $revision->file_mime_type = 'application/pdf';
            $revision->revision = ++$previousRevision->revision;
            $document->document_revision_id = $revision->id;
            $revision->save();
            $document->leads_documents_1leads_ida = $leadObj->id;
            $document->save();
            $leadObj->load_relationship("leads_documents_1");
            $leadObj->leads_documents_1->add($document->id);

            // Step 3 : use pdftk library to fill the pdf form
            exec("pdftk {$emptyFile} fill_form {$dataFileName} output upload/$revision->id");
            unlink($dataFileName);
            return true;
        }
    }

    /**
    ** check if there are multiple applications related to a lead record
    **/
    function checkApplicationsRelatedToLead($lead_id, $task_id, $task_app_provider, $task_assigned_user_id) {
        $leadBean = BeanFactory::getBean("Leads", $lead_id);
        $leadBean->load_relationship('leads_opportunities_1');
        $related_applications = $leadBean->leads_opportunities_1->getBeans();
        $numb_related_applications = count($related_applications);
        if($numb_related_applications > 1)
            return true;
        else {
            foreach ($related_applications as $app) {
                $app_user_approval = $app->user_id_c;
                if(self::$set_application_user_approval){
                    if(empty($app->user_id_c)){
                        require_once('custom/modules/Opportunities/applicationAssignment.php');
                        $appHandler = new applicationAssignment();
                        $app_user_approval = $appHandler->setApprovalUser($app,'','');
                        $GLOBALS['db']->query("UPDATE opportunities_cstm SET user_id_c='$app_user_approval' WHERE id_c='$app->id'");
                        $GLOBALS['log']->debug("Approval user assigned to application in workflow: $app_user_approval");
                    }
                    $GLOBALS['db']->query("UPDATE tasks SET assigned_user_id='$app_user_approval' WHERE id='$task_id'");
                    insertRecordInAuditTable('Tasks', 'assigned_user_id', $task_assigned_user_id, $app_user_approval, $task_id, 'id');
                }
                $GLOBALS['db']->query("UPDATE tasks_cstm SET application_provider_c='$app->provider_id_c', user_id_c = '$app_user_approval' WHERE id_c='$task_id'");
                insertRecordInAuditTable('Tasks', 'application_provider_c', $task_app_provider, $app->provider_id_c, $task_id, 'enum');
            }
            return false;
        }
    }

}

?>