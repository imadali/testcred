<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');


class saveCurrentTime {
    
    function saveTime($bean, $event, $arguments) {
        global $timedate;
        if($bean->fetched_row['assigned_user_id'] != $bean->assigned_user_id){
       
           $bean->assigned_date_c = $timedate->nowDb();
        }
        if($bean->parent_type=='Leads' && empty($bean->fetched_row['id'])){
               $lead=new Lead();
               $lead->retrieve($bean->parent_id);
               $bean->lead_status_c=$lead->credit_request_status_id_c;
               $bean->amount_c=$lead->credit_amount_c;
               $bean->bank_c=$lead->dotb_bank_name_c;
               $bean->dotb_correspondence_language_c=$lead->dotb_correspondence_language_c;
               //$bean->assigned_user_id=$lead->assigned_user_id;
           }
    }
    
}
