<?php

array_push($job_strings, 'updateDeltaVistaLead');

/**
** Update delta vista deltavista_request_id_c if its empty
**/
function updateDeltaVistaLead()
{
    global $sugar_config;
    global $db;
    global $current_user;
    $td = new TimeDate();

    $dateFormat = $current_user->getPreference('datef');
    $leads_query = "SELECT leads.id FROM leads LEFT JOIN leads_cstm ON leads.id=leads_cstm.id_c
                    WHERE leads.deleted=0
                    AND (leads_cstm.deltavista_request_id_c IS NULL
                    OR leads_cstm.deltavista_request_id_c ='')";
 
    $leads_result = $db->query($leads_query);

    while ($lead_row = $db->fetchByAssoc($leads_result)) {
        $leadBean = BeanFactory::getBean('Leads', $lead_row['id']);
        if (!empty($leadBean->id)) {

            //birth date
            if (!empty($leadBean->birthdate)) {
                $birth_datetime = new DateTime();
                $date = $birth_datetime->createFromFormat($dateFormat, $leadBean->birthdate);
                $birthdate = $td->asDbDate($date);
            } else {
                $birthdate = '';
            }

            //date of residence
            if (!empty($leadBean->dotb_resident_since_c)) {
                $residence_datetime = new DateTime();
                $date = $residence_datetime->createFromFormat($dateFormat, $leadBean->dotb_resident_since_c);
                $residedate = $td->asDbDate($date);
            } else {
                $residedate = '';
            }

            (int) $amount = floor($leadBean->credit_amount_c);

            if ( preg_match('/(.+) (\d+.*)$/i', $leadBean->primary_address_street, $street) ) {
                // $street[1] will have the steet name
                $streetName = $street[1];
                // and $street[2] is the number part. 
                $streetNumber = $street[2];
            } else {
                $streetName = $leadBean->primary_address_street;
                $streetNumber = '';
            }

            //data for delta vista
            $deltaVista_data = array(
                "ClientSource" => "Crm",
                "RequestMode"  => "Premium",
                "Provider"     => "Deltavista",
                "Contact"      => array (
                                    "SugarGid"            => $leadBean->id,   // CRED-860 : Added Lead Id
                                    "Firstname"           => $leadBean->first_name,
                                    "Lastname"            => $leadBean->last_name,
                                    "DateOfBirth"         => $birthdate,
                                    "Gender"              => $leadBean->dotb_gender_id_c,
                                    "EmailAdress"        => $leadBean->email1,                         
                                    "PhoneNumberMobile"   => $leadBean->phone_mobile,
                                    "IsoNationalityCode"  => $leadBean->dotb_iso_nationality_code_c,
                                    "Street"              => $streetName,
                                    "Streetnumber"        => $streetNumber,
                                    "PostalCode"          => $leadBean->primary_address_postalcode,
                                    "Townname"            => $leadBean->primary_address_city,
                                    "Country"             => $leadBean->primary_address_country,
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
            $leadBean->deltavista_request_id_c = $result_data['Id'];
            $leadBean->has_deltavista_response_c = 'no';
            $leadBean->save();
        }
    }
    return true;
}

?>