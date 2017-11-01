<?php
require_once('include/SugarQuery/SugarQuery.php');

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class CustomEmailAPI extends SugarApi
{
    public function registerApiRest()
    {
        return array(
            'GetEmail' => array(
                'reqType' => 'POST',
                'noLoginRequired' => false,
                'path' => array('CEmail', 'GetEmail'),
                'pathVars' => array('', ''),
                'method' => 'getEmail',
                'shortHelp' => 'Get Email ',
                'longHelp' => '',
                ),
            );
    }

    /**
     * Save Document and related Document Tracking Collection
     */
    public function getEmail($api, $args){
        $emailFields = array(
            'id' => '',
            'name' => '',
            'date_entered' => '',
            'date_modifieds' => '',
            'assigned_user_id' => '',
            'assigned_user_name' => '',
            'modified_user_id' => '',
            'created_by' => '',
            'team_id' => '',
            'from_addr' => '',
            'reply_to_addr' => '',
            'to_addrs' => '',
            'cc_addrs' => '',
            'bcc_addrs' => '',
            'reply_to_email' => '',
            'reply_to_name' => '',
            'reply_to_status' => '',
            'from_name' => '',
            'mailbox_id' => '',
            'intent' => '',
            'status' => '',
            'type' => '',
            'date_sent' => '',
            'message_id' => '',
            'description_html' => '',
            'raw_source' => '',
            'parent_id' => '',
            'parent_type' => '',
            'parent_name' => '',
            'date_start' => '',
            'time_start' => '',
            'from_addr_name' => '',
            'to_addrs_arr' => '',
            'cc_addrs_arr' => '',
            'bcc_addrs_arr' => '',
            'to_addrs_ids' => '',
            'to_addrs_names' => '',
            'to_addrs_emails' => '',
            'cc_addrs_ids' => '',
            'cc_addrs_names' => '',
            'cc_addrs_emails' => '',
            'bcc_addrs_ids' => '',
            'bcc_addrs_names' => '',
            'bcc_addrs_emails' => '',
            'contact_id' => '',
            'contact_name' => '',
            'duration_hours' => '',
             );
        $emailObj = new Email();
        $emailObj->retrieve($args['id']);
        foreach ($emailFields as $key => $value) {
            $emailFields[$key] = $emailObj->$key;
        }
        return $emailFields;
    }
}

?>