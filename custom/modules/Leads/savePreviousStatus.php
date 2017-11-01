<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class savePreviousStatus{
    
    function saveStatus($bean, $event, $arguments)
    {
            //Saving Previous Status Value
            if($bean->fetched_row['credit_request_status_id_c'] != $bean->credit_request_status_id_c){
       
                $bean->dotb_status_dup_c = $bean->fetched_row['credit_request_status_id_c'];

            }
                    
    }
    
}
