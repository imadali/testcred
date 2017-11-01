<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');


class setUser {
    
    function setCustomerContact($bean, $event, $arguments) {
        if($bean->fetched_row['customer_contact_id'] != $bean->customer_contact_id && ($bean->parent_type=='Leads' || $bean->parent_type=='Contacts')){
           // $GLOBALS['db']->query("UPDATE leads SET assigned_user_id='$bean->customer_contact_id' WHERE id='$bean->parent_id'");
           // $GLOBALS['db']->query("UPDATE leads_cstm SET user_id_c='$bean->customer_contact_id' WHERE id_c='$bean->parent_id'");
        }
    }
    
}
