<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');


class assignedTeam {
    
    function changeAssignedTeam($bean, $event, $arguments) {
        global $sugar_config;
        if (empty($bean->fetched_row['id'])){
            if(empty($bean->leads_opportunities_1leads_ida)){
                $bean->assigned_user_id = null;
            }else{
                $lead = BeanFactory::getBean('Leads', $bean->leads_opportunities_1leads_ida);
                $bean->assigned_user_id=$lead->assigned_user_id;
                $bean->team_id=$lead->team_id;
            }
        }
        
    }
    
}
