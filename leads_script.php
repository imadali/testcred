<?php
/**
    * CRED-1005 class to send Suagr API calls to get leads data
*/
class RecordAdd
{
    protected $instanceUrl;    
    /**
     * Function constructor
     * Description class constructor
     *
     * @param type $Url Sugar url
     */
    public function __construct($Url)
    {
        $this->instanceUrl = $Url;    
    }
    /**
     * Function call
     * Description To send API call
     *
     * @param  type $requestUrl Sugar url
     * @param  type $params     data to send
     * @param  type $oauthToken oauth token
     *
     * @return data
     */
    function call($requestUrl, $params, $oauthToken='')
    {
	    $urls = $this->instanceUrl.$requestUrl;
        $curlRequest = curl_init($urls);
        curl_setopt($curlRequest, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curlRequest, CURLOPT_HEADER, false);
        curl_setopt($curlRequest, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlRequest, CURLOPT_FOLLOWLOCATION, 0);
        if ($requestUrl == '/oauth2/token') {
            curl_setopt($curlRequest, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json"
            ));
        } else {
            curl_setopt($curlRequest, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "oauth-token: {$oauthToken}"
            ));                
        }
        //convert arguments to json
        $jsonArguments = json_encode($params);
        curl_setopt($curlRequest, CURLOPT_POSTFIELDS, $jsonArguments);

        //execute request
        $oauth2TokenResponse = curl_exec($curlRequest);
        //decode oauth2 response to get token
        $oauth2TokenResponseObj = json_decode($oauth2TokenResponse);
        // close connection	
        curl_close($curlRequest);
        return $oauth2TokenResponseObj;
    }
    /**
     * Function createFieldInsert
     * Description To set fields
     *
     * @param  type $fieldArr   fields array
     *
     * @return data
     */
    public function createFieldInsert($fieldArr)
    {
        $fldStr = "";
        for ($k = 0; $k < count($fieldArr); $k++) {
            if ($fldStr == "") {
                $fldStr = $fieldArr[$k];
            } else {
                $fldStr .= ",".$fieldArr[$k];
            }
        }
        return $fldStr;
    }
    /**
     * Function createFieldInsertVal
     * Description To set fields
     *
     * @param  type $fieldArr   fields array
     * @param  type $valArr   values array
     * @param  type $numericFields   number fields array
     * @param  type $dateFields   date fields array
     *
     * @return data
     */
    public function createFieldInsertVal($fieldArr, $valArr, $numericFields, $dateFields)
    {
        $fldStr = "";
        for ($k = 0; $k < count($fieldArr); $k++) {
            if ($fldStr == "") {
                $fldStr = "'".$valArr[$fieldArr[$k]]."'";
            } else {
                if (empty($valArr[$fieldArr[$k]])) {
                    $valArr[$fieldArr[$k]] = "NULL";
                }
                if (in_array($fieldArr[$k], $dateFields) && $valArr[$fieldArr[$k]]!= "NULL") {
                    $valArr[$fieldArr[$k]] = "Cast('".$valArr[$fieldArr[$k]]."' as date)";
                }
                if (in_array($fieldArr[$k], $numericFields) || in_array($fieldArr[$k], $dateFields)) {
                    $fldStr .= ",".$valArr[$fieldArr[$k]];
                } else {
                    if ($valArr[$fieldArr[$k]] != "NULL") {
                        $valArr[$fieldArr[$k]] = str_replace("'", "''", htmlspecialchars_decode($valArr[$fieldArr[$k]], ENT_QUOTES));
                    }
                    $fldStr .= ",N'".$valArr[$fieldArr[$k]]."'";
                }
            }
        }
        return $fldStr;
    }
    /**
     * Function createFieldInsertBlank
     * Description To set fields
     *
     * @param  type $fieldArr   fields array
     *
     * @return data
     */
    public function createFieldInsertBlank($fieldArr)
    {
        $fldStr = "";
        for ($k = 0; $k < count($fieldArr); $k++) {
            if ($fldStr == "") {
                $fldStr = "NULL";
            } else {
                $fldStr .= ",NULL";
            }
        }
        return $fldStr;
    }
    /**
     * Function createFieldInsertApplication
     * Description To set fields
     *
     * @param  type $fieldArr   fields array
     * @param  type $valArray   fields array
     * @param  type $insertIndx   fields array
     * @param  type $numericFields   fields array
     * @param  type $dateFields   fields array
     * @param  type $dateTimeFields   fields array
     *
     * @return data
     */
    public function createFieldInsertApplication($fieldArr, $valArray, $insertIndx, $numericFields, $dateFields, $dateTimeFields)
    {
        $fldStr = "";
        for ($k = 0; $k < count($valArray); $k++) {
            if (isset($valArray[$k]['id'])) {
                for ($i=0; $i < count($fieldArr); $i++) {
                    if ($fldStr == "") {
                        $fldStr = "'".$valArray[$k][$fieldArr[$i]]."'";
                    } else {
                        if (empty($valArray[$k][$fieldArr[$i]])) {
                            $valArray[$k][$fieldArr[$i]] = "NULL";
                        }
                        if (in_array($fieldArr[$i], $dateFields) && $valArray[$k][$fieldArr[$i]]!= "NULL") {
                            $valArray[$k][$fieldArr[$i]] = "Cast('".$valArray[$k][$fieldArr[$i]]."' as date)";
                        }
                        if (in_array($fieldArr[$i], $dateTimeFields) && $valArray[$k][$fieldArr[$i]]!= "NULL") {
                            $valArray[$k][$fieldArr[$i]] = "'".strtotime($valArray[$k][$fieldArr[$i]])."'";
                        }
                        if (in_array($fieldArr[$i], $numericFields) || in_array($fieldArr[$i], $dateFields) || in_array($fieldArr[$i], $dateTimeFields)) {
                            $fldStr .= ",".$valArray[$k][$fieldArr[$i]];
                        } else {
                            if ($valArray[$k][$fieldArr[$i]] != "NULL") {
                                $valArray[$k][$fieldArr[$i]] = str_replace("'", "''", htmlspecialchars_decode($valArray[$k][$fieldArr[$i]], ENT_QUOTES));
                            }
                            $fldStr .= ",N'".$valArray[$k][$fieldArr[$i]]."'";
                        }
                    }
                }
            }
        }
        $total = count($valArray) * count($fieldArr);
        if (!isset($valArray[0]['id'])) {
            $total = 0 * count($fieldArr);
        }
        if ($total < $insertIndx) {
            $counter = $insertIndx - $total;
            for ($cntr = 0; $cntr < $counter; $cntr++) {
                if ($fldStr == "") {
                    $fldStr = "NULL";
                } else {
                    $fldStr .= ",NULL";
                }
            }
        }
        return $fldStr;
    }
    /**
     * Function createFieldUpdateVal
     * Description To set fields
     *
     * @param type $fieldArr      fields array
     * @param type $viewFld       fields array
     * @param type $valArr        fields array
     * @param type $numericFields fields array
     * @param type $dateFields    fields array
     *
     * @return data
     */
    public function createFieldUpdateVal($fieldArr, $viewFld, $valArr, $numericFields, $dateFields)
    {
        $fldStr = "";
        for ($k = 0; $k < count($fieldArr); $k++) {
            if ($fldStr == "") {
                $fldStr = $viewFld[$k]." = '".$valArr[$fieldArr[$k]]."'";
            } else {
                if (empty($valArr[$fieldArr[$k]])) {
                    $valArr[$fieldArr[$k]] = "NULL";
                }
                if (in_array($fieldArr[$k], $dateFields) && $valArr[$fieldArr[$k]]!= "NULL") {
                    $valArr[$fieldArr[$k]] = $viewFld[$k]." = Cast('".$valArr[$fieldArr[$k]]."' as date)";
                } else if (in_array($fieldArr[$k], $dateFields) && $valArr[$fieldArr[$k]] == "NULL") {
                     $valArr[$fieldArr[$k]] = $viewFld[$k]." = ".$valArr[$fieldArr[$k]];
                }
                if (in_array($fieldArr[$k], $numericFields)) {
                    $valArr[$fieldArr[$k]] = $viewFld[$k]."=".$valArr[$fieldArr[$k]];
                }
                if (in_array($fieldArr[$k], $numericFields) || in_array($fieldArr[$k], $dateFields)) {
                    $fldStr .= ",".$valArr[$fieldArr[$k]];
                } else {
                    if ($valArr[$fieldArr[$k]] != "NULL") {
                        $valArr[$fieldArr[$k]] = str_replace("'", "''", htmlspecialchars_decode($valArr[$fieldArr[$k]], ENT_QUOTES));
                    }
                    $fldStr .= ",".$viewFld[$k]."= N'".$valArr[$fieldArr[$k]]."'";
                }
            }
        }
        return $fldStr;
    }
    /**
     * Function createFieldUpdateApplication
     * Description To set fields
     *
     * @param type $fieldArr       fields array
     * @param type $valArr         fields array
     * @param type $numericFields  fields array
     * @param type $dateFields     fields array
     * @param type $dateTimeFields fields array
     * @param type $prefix         fields array
     *
     * @return data
     */
    public function createFieldUpdateApplication($fieldArr, $valArr, $numericFields, $dateFields, $dateTimeFields, $prefix)
    {
        $fldStr = "";     
        for ($k = 0; $k < count($valArr); $k++) {
            for ($i = 0; $i < count($fieldArr); $i++ ) {
                if ($fldStr == "") {
                    $fldStr = $prefix[$k].$fieldArr[$i]." = '".$valArr[$k][$fieldArr[$i]]."'";
                } else {
                    if (empty($valArr[$k][$fieldArr[$i]])) {
                        $valArr[$k][$fieldArr[$i]] = "NULL";
                    }
                    if (in_array($fieldArr[$i], $dateFields) && $valArr[$k][$fieldArr[$i]]!= "NULL") {
                        $valArr[$k][$fieldArr[$i]] = $prefix[$k].$fieldArr[$i]." = Cast('".$valArr[$k][$fieldArr[$i]]."' as date)";
                    } else if (in_array($fieldArr[$i], $dateFields) && $valArr[$k][$fieldArr[$i]] == "NULL") {
                        $valArr[$k][$fieldArr[$i]] = $prefix[$k].$fieldArr[$i]." = ".$valArr[$k][$fieldArr[$i]];
                    }
                    if (in_array($fieldArr[$i], $numericFields)) {
                        $valArr[$k][$fieldArr[$i]] = $prefix[$k].$fieldArr[$i]."=".$valArr[$k][$fieldArr[$i]];
                    }
                    if (in_array($fieldArr[$i], $dateTimeFields)) {

                        $valArr[$k][$fieldArr[$i]] = $prefix[$k].$fieldArr[$i]."="."'".strtotime($valArr[$k][$fieldArr[$i]])."'";
                    }
                    if (in_array($fieldArr[$i], $numericFields) || in_array($fieldArr[$i], $dateFields) || in_array($fieldArr[$i], $dateTimeFields)) {
                        $fldStr .= ",".$valArr[$k][$fieldArr[$i]];
                    } else {
                        if ($valArr[$k][$fieldArr[$i]] != "NULL") {
                            $valArr[$k][$fieldArr[$i]] = str_replace("'", "''", htmlspecialchars_decode($valArr[$k][$fieldArr[$i]], ENT_QUOTES));
                        }
                        $fldStr .= ",".$prefix[$k].$fieldArr[$i]."= N'".$valArr[$k][$fieldArr[$i]]."'";
                    }
                }
            }
        }
        return $fldStr;
    }



}
$leadsFld = array(
    'id',
    'credit_request_status_id_c',
    'lq_next_best_steps_c',
    'first_name',
    'last_name',
    'birthdate',
    'dotb_correspondence_language_c',
    'dotb_gender_id_c',
    'phone_other',
    'phone_mobile',
    'phone_work',
    'assigned_user_name',
    'credit_amount_c',
    'credit_duration_c',
    'contact_name',
    'email1',
    'primary_address_street',
    'primary_address_postalcode',
    'primary_address_city',
    'primary_address_country',
    'correspondence_address_street',
    'correspondence_address_postalcode',
    'correspondence_address_city',
    'correspondence_address_country',
    'dotb_resident_since_c',
    'dotb_bank_name_c',
    'dotb_bank_zip_code_c',
    'dotb_bank_city_name_c',
    'dotb_iban_c',
    'dotb_employer_name_c',
    'dotb_employer_npa_c',
    'dotb_employer_town_c',
    'dotb_is_in_probation_period_c',
    'dotb_monthly_net_income_c',
    'dotb_monthly_gross_income_c',
    'dot_second_job_employer_name_c',
    'dotb_second_job_employer_npa_c',
    'dot_second_job_employer_town_c',
    'dotb_monthly_net_income_nb_c',
    'dotb_second_job_gross_income_c',
    'sideline_hired_since_c',
    'dotb_rent_or_alimony_income_c',
    'dotb_mortgage_amount_c',
    'dotb_housing_costs_rent_c',
    'cstm_last_name_c',
    'cc_id'
);
$partnerFldCrm = array(
    'id',
    'first_name',
    'last_name',
    'birthdate',
    'email1',
    'phone_mobile',
    'phone_other',
    'phone_work',
    'assigned_user_name',
    'address_c_o',
    'primary_address_street',
    'primary_address_postalcode',
    'primary_address_city',
    'primary_address_country',
    'correspondence_address_street',
    'correspondence_address_postalcode',
    'correspondence_address_city',
    'correspondence_address_country',
    'dotb_bank_name',
    'dotb_bank_zip_code',
    'dotb_bank_city_name',
    'dotb_iban',
    'dotb_employment_type_id',
    'dotb_is_pensioner',
    'dotb_pension_type_id',
    'dotb_is_unable_to_work',
    'dotb_unable_to_work_in_last_5_years',
    'dotb_partner_agreement_c',
    'dotb_employer_name',
    'dotb_employer_npa',
    'dotb_employer_town',
    'dotb_is_in_probation_period',
    'dotb_monthly_net_income',
    'dotb_monthly_gross_income',
    'dotb_has_thirteenth_salary',
    'sideline_hired_since_c',
    'dotb_rent_or_alimony_income',
    'dotb_mortgage_amount',
    'dotb_housing_costs_rent_c'
);
$partnerFldView = array(
    'ptr_id',
    'ptr_first_name',
    'ptr_last_name',
    'ptr_birthdate',
    'ptr_email1',
    'ptr_phone_mobile',
    'ptr_phone_other',
    'ptr_phone_work',
    'ptr_assigned_user_name',
    'ptr_address_c_o',
    'ptr_primary_address_street',
    'ptr_primary_address_postalcode',
    'ptr_primary_address_city',
    'ptr_primary_address_country',
    'ptr_correspondence_address_street',
    'ptr_correspondence_address_postalcode',
    'ptr_correspondence_address_city',
    'ptr_correspondence_address_country',
    'ptr_dotb_bank_name',
    'ptr_dotb_bank_zip_code',
    'ptr_dotb_bank_city_name',
    'ptr_dotb_iban',
    'ptr_dotb_employment_type_iddotb_employment_type_id',
    'ptr_dotb_is_pensioner',
    'ptr_dotb_pension_type_id',
    'ptr_dotb_is_unable_to_work',
    'ptr_dotb_unable_to_work_in_last_5_years',
    'ptr_dotb_partner_agreement_c',
    'ptr_dotb_employer_name',
    'ptr_dotb_employer_npa',
    'ptr_dotb_employer_town',
    'ptr_dotb_is_in_probation_period',
    'ptr_dotb_monthly_net_income',
    'ptr_dotb_monthly_gross_income',
    'ptr_dotb_has_thirteenth_salary',
    'ptr_sideline_hired_since_c',
    'ptr_dotb_rent_or_alimony_income',
    'ptr_dotb_mortgage_amount',
    'ptr_dotb_housing_costs_rent_c'
);
$contractFldCrm = array(
    'id',
    'provider_id_c',
    'credit_amount_c',
    'interest_rate_c',
    'credit_duration_c',
    'contract_date_c',
    'paying_date_c',
    'customer_credit_amount_c',
    'customer_credit_duration_c',
    'customer_interest_rate_c',
    'contacts_contracts_1_name'
);
$contractFldView = array(
    'cntr_id',
    'cntr_provider_id_c',
    'cntr_credit_amount_c',
    'cntr_interest_rate_c',
    'cntr_credit_duration_c',
    'cntr_contract_date_c',
    'cntr_paying_date_c',
    'cntr_customer_credit_amount_c',
    'cntr_customer_credit_duration_c',
    'cntr_customer_interest_rate_c',
    'cntr_contacts_contracts_1_name'
);
$applicationFldCrm = array(
    'id',
    'provider_application_no_c',
    'provider_contract_no',
    'credit_amount_c',
    'credit_duration_c',
    'interest_rate_c',
    'approved_saldo',
    'contract_credit_amount',
    'contract_credit_duration',
    'contract_interest_rate',
    'date_modified'
);
$addressesFldCrm = array(
    'id',
    'first_name',
    'last_name',
    'primary_address_street',
    'primary_address_postalcode',
    'primary_address_city',
    'date_modified',
    'contacts_dot10_addresses_1_name',
    'leads_dot10_addresses_1_name'
);
$numericFields = array(
    'credit_amount_c',
    'credit_duration_c',
    'interest_rate_c',
    'contract_credit_amount',
    'contract_credit_duration',
    'contract_interest_rate',
    'customer_credit_amount_c',
    'customer_credit_duration_c',
    'customer_interest_rate_c',
    'dotb_monthly_net_income',
    'dotb_monthly_gross_income',
    'dotb_rent_or_alimony_income',
    'dotb_mortgage_amount',
    'dotb_housing_costs_rent_c',
    'dotb_monthly_net_income_c',
    'dotb_monthly_gross_income_c',
    'dot_second_job_employer_name_c',
    'dotb_monthly_net_income_nb_c',
    'dotb_second_job_gross_income_c',
    'dotb_rent_or_alimony_income_c',
    'dotb_mortgage_amount_c'
);
$applicationPrefix = array(
    'app1_',
    'app2_',
    'app3_'
);
$addressesPrefix = array(
    'address1_',
    'address2_'
);
$dateFields = array(
    'contract_date_c',
    'paying_date_c',
    'birthdate',
    'sideline_hired_since_c',
    'dotb_resident_since_c'
);
$dateTimeFields = array(
    'date_modified'
);
$upRelArray = array(
    'app1_id',
    'app1_provider_application_no_c',
    'app1_provider_contract_no',
    'app1_credit_amount_c',
    'app1_credit_duration_c',
    'app1_interest_rate_c',
    'app1_approved_saldo',
    'app1_contract_credit_amount',
    'app1_contract_credit_duration',
    'app1_contract_interest_rate',
    'app1_date_modified',
    'app2_id',
    'app2_provider_application_no_c',
    'app2_provider_contract_no',
    'app2_credit_amount_c',
    'app2_credit_duration_c',
    'app2_interest_rate_c',
    'app2_approved_saldo',
    'app2_contract_credit_amount',
    'app2_contract_credit_duration',
    'app2_contract_interest_rate',
    'app2_date_modified',
    'app3_id',
    'app3_provider_application_no_c',
    'app3_provider_contract_no',
    'app3_credit_amount_c',
    'app3_credit_duration_c',
    'app3_interest_rate_c',
    'app3_approved_saldo',
    'app3_contract_credit_amount',
    'app3_contract_credit_duration',
    'app3_contract_interest_rate',
    'app3_date_modified',
    'address1_id',
    'address1_first_name',
    'address1_last_name',
    'address1_primary_address_street',
    'address1_primary_address_postalcode',
    'address1_primary_address_city',
    'address1_date_modified',
    'address1_contacts_dot10_addresses_1_name',
    'address1_leads_dot10_addresses_1_name',
    'address2_id',
    'address2_first_name',
    'address2_last_name',
    'address2_primary_address_street',
    'address2_primary_address_postalcode',
    'address2_primary_address_city',
    'address2_date_modified',
    'address2_contacts_dot10_addresses_1_name',
    'address2_leads_dot10_addresses_1_name',
    'ptr_id',
    'ptr_first_name',
    'ptr_last_name',
    'ptr_birthdate',
    'ptr_email1',
    'ptr_phone_mobile',
    'ptr_phone_other',
    'ptr_phone_work',
    'ptr_assigned_user_name',
    'ptr_address_c_o',
    'ptr_primary_address_street',
    'ptr_primary_address_postalcode',
    'ptr_primary_address_city',
    'ptr_primary_address_country',
    'ptr_correspondence_address_street',
    'ptr_correspondence_address_postalcode',
    'ptr_correspondence_address_city',
    'ptr_correspondence_address_country',
    'ptr_dotb_bank_name',
    'ptr_dotb_bank_zip_code',
    'ptr_dotb_bank_city_name',
    'ptr_dotb_iban',
    'ptr_dotb_employment_type_iddotb_employment_type_id',
    'ptr_dotb_is_pensioner',
    'ptr_dotb_pension_type_id',
    'ptr_dotb_is_unable_to_work',
    'ptr_dotb_unable_to_work_in_last_5_years',
    'ptr_dotb_partner_agreement_c',
    'ptr_dotb_employer_name',
    'ptr_dotb_employer_npa',
    'ptr_dotb_employer_town',
    'ptr_dotb_is_in_probation_period',
    'ptr_dotb_monthly_net_income',
    'ptr_dotb_monthly_gross_income',
    'ptr_dotb_has_thirteenth_salary',
    'ptr_sideline_hired_since_c',
    'ptr_dotb_rent_or_alimony_income',
    'ptr_dotb_mortgage_amount',
    'ptr_dotb_housing_costs_rent_c',
    'cntr_id',
    'cntr_provider_id_c',
    'cntr_credit_amount_c',
    'cntr_interest_rate_c',
    'cntr_credit_duration_c',
    'cntr_contract_date_c',
    'cntr_paying_date_c',
    'cntr_customer_credit_amount_c',
    'cntr_customer_credit_duration_c',
    'cntr_customer_interest_rate_c',
    'cntr_contacts_contracts_1_name'
);
$sugarUrl = "https://credarisstaging.rolustech.com/rest/v10";
//$sugarUrl = "http://sugardev.credaris.ch/rest/v10";
$obj = new RecordAdd($sugarUrl);
$insrtFlds = $obj->createFieldInsert($leadsFld);
$insrtFld = str_replace("assigned_user_name", "leads_assigned_user_name", $insrtFlds);
$insrtFld = str_replace("email1", "lead_email1", $insrtFld);
$partnerInsrt = $obj->createFieldInsert($partnerFldView);
$contractInsrt = $obj->createFieldInsert($contractFldView);
$insrtFld .= ",".$partnerInsrt.",".$contractInsrt;
$insrtFld .= ",app1_id,app1_provider_application_no_c,app1_provider_contract_no,app1_credit_amount_c,app1_credit_duration_c,app1_interest_rate_c,app1_approved_saldo,app1_contract_credit_amount,app1_contract_credit_duration,app1_contract_interest_rate,app1_date_modified,app2_id,app2_provider_application_no_c,app2_provider_contract_no,app2_credit_amount_c,app2_credit_duration_c,app2_interest_rate_c,app2_approved_saldo,app2_contract_credit_amount,app2_contract_credit_duration,app2_contract_interest_rate,app2_date_modified,app3_id,app3_provider_application_no_c,app3_provider_contract_no,app3_credit_amount_c,app3_credit_duration_c,app3_interest_rate_c,app3_approved_saldo,app3_contract_credit_amount,app3_contract_credit_duration,app3_contract_interest_rate,app3_date_modified,address1_id,address1_first_name,address1_last_name,address1_primary_address_street,address1_primary_address_postalcode,address1_primary_address_city,address1_date_modified,address1_contacts_dot10_addresses_1_name,address1_leads_dot10_addresses_1_name,address2_id,address2_first_name,address2_last_name,address2_primary_address_street,address2_primary_address_postalcode,address2_primary_address_city,address2_date_modified,address2_contacts_dot10_addresses_1_name,address2_leads_dot10_addresses_1_name";
    $userName = "dev6";
    $password = "Sugar4credaris";
    $authUrl = "/oauth2/token";
    $oauth2TokenArguments = array(
        "grant_type" => "password",
        "client_id" => "sugar", 
        "client_secret" => "",
        "username" => $userName,
        "password" => $password,
        "platform" => "api" 
    );
    // send login call
    $result = $obj->call($authUrl, $oauth2TokenArguments);
    // get access token
    $oauthToken = $result->access_token;

    $connectionInfo = array("Database"=>"credaris_leads", "UID"=>"sa", "PWD"=>"abc@123", 'CharacterSet'=>'UTF-8');
    $conn = sqlsrv_connect("localhost", $connectionInfo);
    $executionTime = "select cast(lastexecution as datetime) as executetime from sync_time where id=1";
    $extTime = sqlsrv_query($conn, $executionTime);
    $tims = sqlsrv_fetch_array($extTime, SQLSRV_FETCH_ASSOC);
    $exuctTim = date("Y-m-d H:i:s", $tims["executetime"]->getTimestamp());
    //$timezone  = 0;
    $record = array(
        'lastExecution' => $exuctTim
    );
    $sGrl = "/DMRDelta/getLatestDelta";
    $results = $obj->call($sGrl, $record, $oauthToken);
    $data = json_decode($results->updated, true);
    $delLeads =  $results->deleted;
    $leadDelStr = "";
    if (!empty($delLeads)) {
        for ($d =0; $d < count($delLeads); $d++) {
            if ($leadDelStr == "") {
                $leadDelStr = "'".$delLeads[$d]."'";       
            } else {
                $leadDelStr = ",'".$delLeads[$d]."'"; 
            }
        }
        if ($leadDelStr != "") {
            $delQuery = "delete from leads_view_new where id IN (".$leadDelStr.")";
            sqlsrv_query($conn, $delQuery);
        }
    }
    if (!empty($data)) {
        $connectionInfo = array( "Database"=>"credaris_leads", "UID"=>"sa", "PWD"=>"abc@123", 'CharacterSet'=>'UTF-8');
        $conn = sqlsrv_connect("localhost", $connectionInfo);
        if (!$conn) {
            echo "Connection could not be established.<br />";
            die(print_r(sqlsrv_errors(), true));
        }
        foreach ($data as $key => $value) {
            $tableName =  "leads_view_new";  
            $query = "select id from $tableName where id='".$key."'";
            $res = sqlsrv_query($conn, $query);
            if ($res !== false) {
                $row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC);
                if (!empty($row['id'])) {
                    $leadsUpdateField = "";
                    $partnerUpdateField = "";
                    $contractUpdateField = "";
                    $applicationUpdateField = "";
                    $addressUpdateField = "";
                    $leadsUpdateField = $obj->createFieldUpdateVal($leadsFld, $leadsFld, $value, $numericFields, $dateFields);
                    $leadsUpdateField = str_replace("assigned_user_name", "leads_assigned_user_name", $leadsUpdateField);
                    $leadsUpdateField = str_replace("email1", "lead_email1", $leadsUpdateField);
                    $upStr = "";
                    for ($n = 0; $n < count($upRelArray); $n++) {
                        if ($upStr == "") {
                            $upStr = $upRelArray[$n]."= NULL";
                        } else {
                            $upStr .= ",".$upRelArray[$n]."= NULL";
                        }
                    }
                    if ($upStr != "") {
                        $nlQry = "UPDATE [dbo].[$tableName] set $upStr where id='".$key."'";
                        sqlsrv_query($conn, $nlQry);
                    }
                    if (isset($value['partner']['id'])) {
                        $partnerUpdateField = $obj->createFieldUpdateVal($partnerFldCrm, $partnerFldView, $value['partner'], $numericFields, $dateFields);
                        if (!empty($partnerUpdateField)) {
                            $leadsUpdateField .= ",".$partnerUpdateField;
                        }
                    }
                    if (isset($value['contract']['id'])) {
                        $contractUpdateField = $obj->createFieldUpdateVal($contractFldCrm, $contractFldView, $value['contract'], $numericFields, $dateFields);
                        if (!empty($contractUpdateField)) {
                            $leadsUpdateField .= ",".$contractUpdateField;
                        }
                    }
                    if (is_array($value['application']) && !empty($value['application'][0]['id'])) {
                        $applicationUpdateField = $obj->createFieldUpdateApplication($applicationFldCrm, $value['application'], $numericFields, $dateFields, $dateTimeFields, $applicationPrefix);
                        if (!empty($applicationUpdateField)) {
                            $leadsUpdateField .= ",".$applicationUpdateField;
                        }
                    }
                    if (is_array($value['address']) && !empty($value['address'][0]['id'])) {  
                        $addressUpdateField = $obj->createFieldUpdateApplication($addressesFldCrm, $value['address'], $numericFields, $dateFields, $dateTimeFields, $addressesPrefix);
                        if (!empty($addressUpdateField)) {
                            $leadsUpdateField .= ",".$addressUpdateField;
                        }
                    }
                    if ($leadsUpdateField != "") {
                        $upDateQry = "UPDATE [dbo].[$tableName] set $leadsUpdateField where id='".$key."'";
                        sqlsrv_query($conn, $upDateQry);
                    }
                } else {
                    $insrtVal = $obj->createFieldInsertVal($leadsFld, $value, $numericFields, $dateFields);
                    $partnerVal = "";
                    if (isset($value['partner']['id'])) {
                        $partnerVal = $obj->createFieldInsertVal($partnerFldCrm, $value['partner'], $numericFields, $dateFields);
                    } else {
                        $partnerVal = $obj->createFieldInsertBlank($partnerFldCrm); 
                    }
                    $insrtVal .= ",".$partnerVal;
                    $contrctVal = "";
                    if (isset($value['contract']['id'])) {
                        $contrctVal = $obj->createFieldInsertVal($contractFldCrm, $value['contract'], $numericFields, $dateFields);
                    } else {
                        $contrctVal = $obj->createFieldInsertBlank($contractFldCrm); 
                    }   
                    $insrtVal .= ",".$contrctVal;
                    $applicationVal = "";  
                    $addressVal = "";
                    if (is_array($value['application']) && !empty($value['application'][0]['id'])) {
                        $applicationVal = $obj->createFieldInsertApplication($applicationFldCrm, $value['application'], 33, $numericFields, $dateFields, $dateTimeFields);
                    } else {
                        $applicationVal = $obj->createFieldInsertApplication($applicationFldCrm, $value['application'], 33, $numericFields, $dateFields, $dateTimeFields);
                    }
                    if (is_array($value['address']) && !empty($value['address'][0]['id'])) {
                        $addressVal = $obj->createFieldInsertApplication($addressesFldCrm, $value['address'], 18, $numericFields, $dateFields, $dateTimeFields);
                    } else {
                        $addressVal = $obj->createFieldInsertApplication($addressesFldCrm, $value['address'], 18, $numericFields, $dateFields);
                    }
                    $insrtVal .= ",".$applicationVal.",".$addressVal;
                    $insrtQry = "INSERT INTO [dbo].[$tableName] (".$insrtFld.")VALUES (".$insrtVal.")";
                    $rest = sqlsrv_query($conn, $insrtQry);

                }
            } else {
                echo 'Wrong SQL: ' . $query . ' Error: ' . $conn->error, E_USER_ERROR;
                die();
            }
        }
    }
    $updateTim = gmdate("Y-m-d H:i:s", time());
    $upTime = "update sync_time set lastexecution = '$updateTim' where id=1";
    sqlsrv_query($conn, $upTime);
