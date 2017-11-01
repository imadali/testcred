<?php

require_once 'data/BeanFactory.php';
require_once 'include/api/SugarApi.php';
require_once 'include/TimeDate.php';


class leadNeolutionApi extends SugarApi
{
    public function registerApiRest()
    {
        return array(
            'createCreditCheckRequestForLead' => array(
                'reqType'   => 'GET',
                'path'      => array('Leads', '?', 'createCreditCheckRequestForLead', '?'),
                'pathVars'  => array('module', 'record', '', 'source'),
                'method'    => 'createCreditCheckRequestForLead',
                'shortHelp' => 'This method sends a credit check request to the selected source (Intrum or DeltaVista)',
                'longHelp'  => '',
            ),
        );
    }

    /**
     * Sends the requests and returns the returned id in the appropriate field
     * 
     * @returns string : identifiant de la Credit Check Request
    */
    public function createCreditCheckRequestForLead($api, $args)
    {
        global $sugar_config;
		global $current_user;    
		
        //get the lead bean
        $bean = BeanFactory::getBean('Leads', $args['record']);
        
        //We check if there is not already a pending request
        switch ($args['source']) {
            case 'Intrum':
                $field_id = 'intrum_request_id_c';
                $field_bl = 'has_intrum_response_c';
                break;
            
            case 'Deltavista':
                $field_id = 'deltavista_request_id_c';
                $field_bl = 'has_deltavista_response_c';
                break;
        }
        
        //Is an application already waiting ? (If id exists and the response is received , it has the right to launch a second request )
        if (!empty($bean->$field_id) && $bean->$field_bl == 'yes') {
            return array('success' => "ko", 'msg' => "pending_request");
        }

        
        //The list of mandatory fields depends on the source you want to question
        switch ($args['source']) {
            case 'Intrum':
                $mandatory_fields = array( 'bean' => array (
                                                              'first_name',
                                                              'last_name',
                                                              'birthdate',
                                                              //'dotb_gender_id', //#11901 - remove field from mandatory list
                                                              // 'email1',
                                                              // 'phone_mobile',
                                                              //'primary_address_street', //#11901 - remove field from mandatory list
                                                              'primary_address_postalcode',
                                                              //'primary_address_city', //#11901 - remove field from mandatory list
                                                              // 'dotb_resident_since_c',
															  // 'credit_amount_c'
                                                              ),
                                            /* 'bean' => array (
                                                              'credit_amount_c'
                                                            )  */
                                          );
                //We support the (s) file (s) to use appropriate language understandable labels
                $traductions1 = return_module_language($sugar_config['default_language'], 'Leads');                //On charge la langue par défaut
                $traductions = $traductions1;
				break;
            
            case 'Deltavista':
                $mandatory_fields = array(  'bean' => array ( 
                                                                'first_name',
                                                                'last_name',
                                                                'birthdate',
                                                                //'dotb_gender_id', //#11901 - remove field from mandatory list
                                                                //'primary_address_street', //#11901 - remove field from mandatory list
                                                                'primary_address_postalcode',
                                                                //'primary_address_city',  //#11901 - remove field from mandatory list
                                                                // 'dotb_resident_since_c'
                                                                ) 
                                          );
                //We support the (s) file (s) to use appropriate language understandable labels
                $traductions = return_module_language($sugar_config['default_language'], 'Leads');    //On charge la langue par défaut
                break;
        }
        
        $errors = array();
        //We check for each field required if well informed
        foreach($mandatory_fields as $mod => $fields) {
            foreach ($fields as $field) {
                if (empty($$mod->$field)) {
					//for removing _c from custom field for the labels
					$custom_field_name = substr($field, -2); 
					if($custom_field_name == '_c')
						$field_label = substr($field, 0,-2);
					else
						$field_label = $field;

                    $errors[$field] = $traductions['LBL_'.strtoupper($field_label)].' ('.$field.')';
                }
            }
        }
        
        //If some fields are missing, it returns an error without sending the WS call
        if (count($errors)>0) {
            return array('success' => "ko", 'msg' => "missing", 'details' => "Request was not sent because the following field".(count($errors)>1?'s are':' is')." missing: ".implode(', ', $errors));    
        }
        
        //Formatting data
        $td = new TimeDate();
        $dateFormat = $current_user->getPreference('datef');
		
        //birth date
        if (!empty($bean->birthdate)) {
            // $date = new DateTime($bean->birthdate);
			$birth_datetime = new DateTime();
			$date = $birth_datetime->createFromFormat($dateFormat, $bean->birthdate);
            $birthdate = $td->asDbDate($date);
        } else {
            $birthdate = '';
        }
        
        //date of residence
        if (!empty($bean->dotb_resident_since_c)) {
            // $date = new DateTime($bean->dotb_resident_since_c);
			$residence_datetime = new DateTime();
			$date = $residence_datetime->createFromFormat($dateFormat, $bean->dotb_resident_since_c);
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
        
        $data = array(
                        "ClientSource" => "Crm",
                        "RequestMode"  => "Premium",
                        "Provider"     => $args['source'],
                        "Contact"      => array (
                                                  "Firstname"           => $bean->first_name,
                                                  "Lastname"            => $bean->last_name,
                                                  "DateOfBirth"         => $birthdate,
                                                  "Gender"              => $bean->dotb_gender_id_c,
                                                  //"EmailAddress"        => $contact->emailAddress->getPrimaryAddress($contact),
                                                  "EmailAdress"        => $bean->email1,                                           //Un seul D à Address !!!
                                                  "PhoneNumberMobile"   => $bean->phone_mobile,
                                                  "IsoNationalityCode"  => $bean->dotb_iso_nationality_code_c,
                                                  "Street"              => $streetName,
                                                  "Streetnumber"        => $streetNumber,
                                                  "PostalCode"          => $bean->primary_address_postalcode,
                                                  "Townname"            => $bean->primary_address_city,
                                                  "Country"             => $bean->primary_address_country,
                                                  "ResidingSince"       => $residedate,
                                                  //This does not come from the contact, but it is expected in the Contact array, so...
                                                  "CreditAmount"        => $amount
                                                )
                      );                                                                    
        $data_string = json_encode($data);                                                                                   
                                                                                                                         
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
               
        return array('success' => "ok", 'msg' => "request_sent", 'request_id' => $result_data['Id'], 'has_response' => 'no');
    }
}
