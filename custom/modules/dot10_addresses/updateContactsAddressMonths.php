<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once ('include/TimeDate.php');
class updateContactsAddressMonths {
	/**
	** Update address months(address_months_c) in Contacts module to show alert.
	**/
    function updateMonthsInContacts($bean, $event, $arguments) {
		global $current_user; 
		global $timedate;
		
		$related_contact_id = $bean->contacts_dot10_addresses_1contacts_ida;
		
		$contact_bean = BeanFactory::getBean("Contacts");
		$contact_bean->retrieve($related_contact_id);
		$contact_bean->load_relationship("contacts_dot10_addresses_1");
        $relatedAddresses = $contact_bean->contacts_dot10_addresses_1->getBeans();
		
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
		
		$contacts_update_query = "UPDATE contacts_cstm SET address_months_c='".$totalMonths."' WHERE id_c='".$related_contact_id."'";
		// $GLOBALS['log']->fatal("Contact query: " . $contacts_update_query);
		$GLOBALS['db']->query($contacts_update_query);
    }
	
	/**
	** Update address months(address_months_c) in Contacts module when an address is unlinked.
	**/
    function updateMonthsInContactsWhenAddressDeleted($bean, $event, $arguments) {
		global $current_user; 
		global $timedate;
		// $GLOBALS['log']->fatal(print_r($arguments,true));
		if($arguments['related_module'] == 'Contacts' && $arguments['link'] == 'contacts_dot10_addresses_1' && $arguments['relationship'] == 'contacts_dot10_addresses_1'){
			$related_cont_id = $arguments['related_id'];
			
			$contact_bean = BeanFactory::getBean("Contacts");
			$contact_bean->retrieve($related_cont_id);
			$contact_bean->load_relationship("contacts_dot10_addresses_1");
			$relatedAddresses = $contact_bean->contacts_dot10_addresses_1->getBeans();
			
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
			
			$contacts_update_query = "UPDATE contacts_cstm SET address_months_c='".$totalMonths."' WHERE id_c='".$related_cont_id."'";
			// $GLOBALS['log']->fatal("Contacts query: " . $contacts_update_query);
			$GLOBALS['db']->query($contacts_update_query);
		}
    }

}
