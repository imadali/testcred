<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class updateDeltaVistaAndIntrum {

    function updateRequestIds($bean, $event, $arguments) {
		if(empty($bean->fetched_row['id'])){
			global $sugar_config;
			$td = new TimeDate();
			
			//birth date
			if (!empty($bean->birthdate)) {
				$date = new DateTime($bean->birthdate);
				$birthdate = $td->asDbDate($date);
			} else {
				$birthdate = '';
			}
			
			//date of residence
			if (!empty($bean->dotb_resident_since_c)) {
				$date = new DateTime($bean->dotb_resident_since_c);
				$residedate = $td->asDbDate($date);
			} else {
				$residedate = '';
			}
			
			(int) $amount = floor($bean->credit_amount_c);
			
			if ( preg_match('/(.+) (\d+.*)$/i', $bean->primary_address_street, $street) )
			{
				// $street[1] will have the steet name
				$streetName = $street[1];
				// and $street[2] is the number part. 
				$streetNumber = $street[2];
			} else {
				$streetName = $bean->primary_address_street;
				$streetNumber = '';
			}
			
			//data for delta vista
			$deltaVista_data = array(
				"ClientSource" => "Crm",
				"RequestMode"  => "Premium",
				"Provider"     => "Deltavista",
				"Contact"      => array (
										  "Firstname"           => $bean->first_name,
										  "Lastname"            => $bean->last_name,
										  "DateOfBirth"         => $birthdate,
										  "Gender"              => $bean->dotb_gender_id_c,
										  "EmailAdress"        => $bean->email1,                             
										  "PhoneNumberMobile"   => $bean->phone_mobile,
										  "IsoNationalityCode"  => $bean->dotb_iso_nationality_code_c,
										  "Street"              => $streetName,
										  "Streetnumber"        => $streetNumber,
										  "PostalCode"          => $bean->primary_address_postalcode,
										  "Townname"            => $bean->primary_address_city,
										  "Country"             => $bean->primary_address_country,
										  "ResidingSince"       => $residedate,
										  "CreditAmount"        => $amount
										)
            );                                                                    
			$data_string = json_encode($deltaVista_data);                                                                                                                              
			$ch = curl_init($sugar_config['neolution_url']);                                                                      
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
				'Content-Type: application/json',                                                                                
				'Content-Length: ' . strlen($data_string))                                                                       
			); 
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		                                                                                                                    
			$result = curl_exec($ch);
			
			//Extraction of JSON in a table
			$result_data = json_decode($result, true);
			
			//we update the delta vista fields
			$bean->deltavista_request_id_c = $result_data['Id'];
			$bean->has_deltavista_response_c = 'no';
		}
    }

}
