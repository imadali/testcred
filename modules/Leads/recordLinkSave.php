<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class RecordLinkSave
{
    
    function saveLink($bean, $event, $arguments)
    {
        // CRED-780 hook should execute if field is empty
        if (empty($bean->record_link_c)) {
            global $sugar_config;
            $url = $sugar_config['site_url'];
            $bean->record_link_c = $url.'/#Leads/'.$bean->id;
        }
    }
    
}
