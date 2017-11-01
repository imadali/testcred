<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once ('include/TimeDate.php');
class updateLeadsAddressMonths {
	/**
	** Update address months(address_months_c) in Leads module to show alert.
	**/
    function updateMonthsInLeads($bean, $event, $arguments) {
		global $current_user; 
		global $timedate;
		
		$related_lead_id = $bean->leads_dot10_addresses_1leads_ida;
		
		$lead_bean = BeanFactory::getBean("Leads");
		$lead_bean->retrieve($related_lead_id);
		$lead_bean->load_relationship("leads_dot10_addresses_1");
        $relatedAddresses = $lead_bean->leads_dot10_addresses_1->getBeans();
		
		$totalMonths = 0;
		foreach ($relatedAddresses as $address) {
			if(!($address->current_address_c) && (!empty($address->dotb_resident_since_c) && !empty($address->dotb_resident_till_c))){
				$since = $address->dotb_resident_since_c;
				$till = $address->dotb_resident_till_c;
				
				$days = floor((abs(strtotime($till) - strtotime($since))) / 86400);
				$months = floor($days / 30);
				$totalMonths += $months;
			}
		}
		
		$lead_update_query = "UPDATE leads_cstm SET address_months_c='".$totalMonths."' WHERE id_c='".$related_lead_id."'";
		// $GLOBALS['log']->fatal("Leads query: " . $lead_update_query);
		$GLOBALS['db']->query($lead_update_query);
    }
	
	/**
	** Update address months(address_months_c) in Leads module when an address is unlinked.
	**/
    function updateMonthsInLeadsWhenAddressDeleted($bean, $event, $arguments) {
		global $current_user; 
		global $timedate;
		if($arguments['related_module'] == 'Leads' && $arguments['link'] == 'leads_dot10_addresses_1' && $arguments['relationship'] == 'leads_dot10_addresses_1'){
			$related_lead_id = $arguments['related_id'];
			
			$lead_bean = BeanFactory::getBean("Leads");
			$lead_bean->retrieve($related_lead_id);
			$lead_bean->load_relationship("leads_dot10_addresses_1");
			$relatedAddresses = $lead_bean->leads_dot10_addresses_1->getBeans();
			
			$totalMonths = 0;
			foreach ($relatedAddresses as $address) {
				if(!($address->current_address_c) && (!empty($address->dotb_resident_since_c) && !empty($address->dotb_resident_till_c))){
					$since = $address->dotb_resident_since_c;
					$till = $address->dotb_resident_till_c;
					
					$days = floor((abs(strtotime($till) - strtotime($since))) / 86400);
					$months = floor($days / 30);
					$totalMonths += $months;
				}
			}
			
			$lead_update_query = "UPDATE leads_cstm SET address_months_c='".$totalMonths."' WHERE id_c='".$related_lead_id."'";
			// $GLOBALS['log']->fatal("Leads query: " . $lead_update_query);
			$GLOBALS['db']->query($lead_update_query);
		}
    }

}
