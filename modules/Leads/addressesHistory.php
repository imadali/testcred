<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once ('include/TimeDate.php');

class addressesHistory {

    protected static $fetchedRow = array();

    /**
     * Called as before_save logic hook to grab the fetched_row values
     */
    public function addAddress($bean, $event, $arguments) {
		global $timedate;
		if($bean->add_address_to_history_c){
			//$GLOBALS['log']->fatal("add address to address history");
			// set current address bit to 0 for earlier address
			$bean->load_relationship("leads_dot10_addresses_1");
			$relatedAddresses = $bean->leads_dot10_addresses_1->getBeans();
			
			foreach ($relatedAddresses as $address) {
				if($address->current_address_c){
					$address->current_address_c = 0;
					$address->save();
				}
			}
			
			//add this address in address history
			$address_bean = new dot10_addresses();
			$address_bean->first_name = $bean->first_name;
			$address_bean->last_name = $bean->last_name;
			$address_bean->address_c_o = $bean->address_c_o;
			$address_bean->primary_address_street = $bean->primary_address_street;
			$address_bean->primary_address_postalcode = $bean->primary_address_postalcode;
			$address_bean->primary_address_city = $bean->primary_address_city;
			if(!empty($bean->dotb_resident_since_c)){
				$date = new DateTime($bean->dotb_resident_since_c);
				$address_bean->dotb_resident_since_c = $timedate->asDbDate($date);
			}
			// set current address bit to 1 for thos address
			$address_bean->current_address_c = 1;
			$address_bean->save();
			$bean->load_relationship('leads_dot10_addresses_1');
			$bean->leads_dot10_addresses_1->add($address_bean->id);
			
			$bean->add_address_to_history_c = 0;
		} else if(($bean->add_address_to_history_c == 0) && 
					($bean->fetched_row['address_c_o'] != $bean->address_c_o ||
					$bean->fetched_row['primary_address_street'] != $bean->primary_address_street ||
					$bean->fetched_row['primary_address_postalcode'] != $bean->primary_address_postalcode ||
					$bean->fetched_row['primary_address_city'] != $bean->primary_address_city || 
					$bean->fetched_row['dotb_resident_since_c'] != $bean->dotb_resident_since_c) && 
					(!empty($bean->address_c_o) || !empty($bean->primary_address_street) ||
					!empty($bean->primary_address_postalcode) || !empty($bean->primary_address_city) || !empty($bean->dotb_resident_since_c))){
			
			// retrieve the address with current_address_c bit 1 and update it
			$bean->load_relationship("leads_dot10_addresses_1");
			$relatedAddresses = $bean->leads_dot10_addresses_1->getBeans();
			//$GLOBALS['log']->fatal("Update address");
			foreach ($relatedAddresses as $address) {
				if($address->current_address_c){
					$address->address_c_o = $bean->address_c_o;
					$address->primary_address_street = $bean->primary_address_street;
					$address->primary_address_postalcode = $bean->primary_address_postalcode;
					$address->primary_address_city = $bean->primary_address_city;
					
					// update residence since for current address
					if(!empty($bean->dotb_resident_since_c)){
						$date = new DateTime($bean->dotb_resident_since_c);
						$address->dotb_resident_since_c = $timedate->asDbDate($date);
					}
					$address->processed = true;
					$address->save();
				}
			}			
		}
    }

}
