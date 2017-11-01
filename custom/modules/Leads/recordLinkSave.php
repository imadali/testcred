<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class recordLinkSave{
    
    function saveLink($bean, $event, $arguments)
    {
            global $sugar_config;
            $url = $sugar_config['site_url'];
            $bean->record_link_c = $url.'/#Leads/'.$bean->id;
                    
    }
    
}
