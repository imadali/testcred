<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once ('include/TimeDate.php');

class CustomLeadConversionAPI extends SugarApi {

    public function registerApiRest() {
        return array(
            'createApplication' => array(
                'reqType' => 'POST',
                'path' => array('ConvLead', 'CreateApplication'),
                'pathVars' => array('', ''),
                'method' => 'createApplication',
                'shortHelp' => 'Convert Lead to Application',
                'longHelp' => '',
            ),
            'createContract' => array(
                'reqType' => 'POST',
                'path' => array('ConvLead', 'CreateContract'),
                'pathVars' => array('', ''),
                'method' => 'createContract',
                'shortHelp' => 'Convert Lead to Contract',
                'longHelp' => '',
            ),
            'createCrHistory' => array(
                'reqType' => 'POST',
                'path' => array('ConvLead', 'CreateCrHistory'),
                'pathVars' => array('', ''),
                'method' => 'createCrHistory',
                'shortHelp' => 'Create credit history record by copying information from contract',
                'longHelp' => '',
            ),
            'createContact' => array(
                'reqType' => 'POST',
                'path' => array('ConvLead', 'CreateContact'),
                'pathVars' => array('', ''),
                'method' => 'createContact',
                'shortHelp' => 'Convert Lead to Contact',
                'longHelp' => '',
            ),
            'createLead' => array(
                'reqType' => 'POST',
                'path' => array('ConvLead', 'CreateLead'),
                'pathVars' => array('', ''),
                'method' => 'createLead',
                'shortHelp' => 'Convert Lead to Contact',
                'longHelp' => '',
            ),
            'GetRelatedPartner' => array(
                'reqType' => 'POST',
                'path' => array('ConvLead', 'GetRelatedPartner'),
                'pathVars' => array('', ''),
                'method' => 'GetRelatedPartner',
                'shortHelp' => 'Get Related Partner',
                'longHelp' => '',
            ),
            'GetRelatedLeadPartner' => array(
                'reqType' => 'POST',
                'path' => array('ConvLead', 'GetRelatedLeadPartner'),
                'pathVars' => array('', ''),
                'method' => 'GetRelatedLeadPartner',
                'shortHelp' => 'Get Related Lead Partner',
                'longHelp' => '',
            ),
            'GetLatestAddress' => array(
                'reqType' => 'POST',
                'path' => array('ConvLead', 'GetLatestAddress'),
                'pathVars' => array('', ''),
                'method' => 'GetLatestAddress',
                'shortHelp' => 'Get Latest Address',
                'longHelp' => '',
            ),
            'GetDocumentTrackingRecord' => array(
                'reqType' => 'POST',
                'path' => array('ConvLead', 'GetDocumentTrackingRecord'),
                'pathVars' => array('', ''),
                'method' => 'GetDocumentTrackingRecord',
                'shortHelp' => 'Get all the Document Trackings assciated to Document and Document and linked to Contact',
                'longHelp' => '',
            ),
        );
    }

    /*
     *  First Getting all related contacts 
     *  and then getting related Categories 
     *  to that Contact
     */

    public function GetDocumentTrackingRecord($api, $args) {
        $sql_documents = 'SELECT documents.id AS doc_id FROM documents'
                . ' INNER JOIN documents_contacts ON documents.id = documents_contacts.document_id  AND documents_contacts.contact_id = "' . $args['id'] . '" AND documents_contacts.deleted = 0 '
                . ' WHERE documents.deleted = 0 ';

        $result = $GLOBALS['db']->query($sql_documents);

        while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
            $record_id[] = $row['doc_id'];
        }

        if (!empty($record_id)) {
            $rec_ids = implode(',', array_map('add_quotes', $record_id));
            $sql_doc_trac = ' SELECT tracking.category, tracking.id, tracking.status, tracking.month, tracking.description FROM documents_dotb7_document_tracking_1_c AS doc_tracking'
                    . ' INNER JOIN dotb7_document_tracking  AS tracking  ON doc_tracking.documents_dotb7_document_tracking_1dotb7_document_tracking_idb = tracking.id AND tracking.deleted = 0 '
                    . ' WHERE doc_tracking.deleted = 0 AND doc_tracking.documents_dotb7_document_tracking_1documents_ida IN (' . $rec_ids . ') ';

            $result1 = $GLOBALS['db']->query($sql_doc_trac);

            $avoidDuplicateCategory = array();
            $relatedTrackingData = array();
            while ($row1 = $GLOBALS['db']->fetchByAssoc($result1)) {
                $duplicate_key = '';
                $category_months = '';
                if (!in_array($row1['category'], $avoidDuplicateCategory)) {
                    $relatedTrackingData[] = array('id' => $row1['id'], 'status' => $row1['status'],
                        'category' => $row1['category'], 'notes' => $row1['description'], 'month' => $row1['month']);
                    $avoidDuplicateCategory[] = $row1['category'];
                } else {
                    $duplicate_key = $this->findObjectByCategory($row1['category'], $relatedTrackingData);
                    if ($duplicate_key != '-1') {
                        $relatedTrackingData[$duplicate_key]['notes'] = $relatedTrackingData[$duplicate_key]['notes'] . "\r\n" . $row1['description'];

                        if (!empty($relatedTrackingData[$duplicate_key]['month']))
                            $category_months = $relatedTrackingData[$duplicate_key]['month'];

                        if (!empty($row1['month'])) {
                            if (!empty($category_months))
                                $category_months .= ',' . $row1['month'];
                            else
                                $category_months .= $row1['month'];
                        }

                        $category_months = implode(',', array_unique(explode(',', $category_months)));
                        $relatedTrackingData[$duplicate_key]['month'] = $category_months;

                        // if status is other than OK and current row status is OK then update status
                        if ($row1['status'] == 'ok') {
                            $relatedTrackingData[$duplicate_key]['status'] = $row1['status'];
                        } else if ($relatedTrackingData[$duplicate_key]['status'] != 'ok' && $row1['status'] == 'nok') {
                            $relatedTrackingData[$duplicate_key]['status'] = $row1['status'];
                        } else if ($row1['status'] == 'fehlt' && $relatedTrackingData[$duplicate_key]['status'] != 'nok' && $relatedTrackingData[$duplicate_key]['status'] != 'ok') {
                            $relatedTrackingData[$duplicate_key]['status'] = $row1['status'];
                        }
                    }
                }
            }
            global $app_list_strings;
            if (!empty($relatedTrackingData)) {
                $docTrackCollection = array();
                $manualDocTracColleciton = array();
                foreach ($relatedTrackingData as $key => $recordData) {
                    if (isset($app_list_strings['dotb_document_category_list'][$recordData['category']])) {
                        $category = $app_list_strings['dotb_document_category_list'][$recordData['category']];
                    } else {
                        $category = '';
                    }

                    if (!empty($category)) {
                        $docTrackCollection[] = $recordData;
                    } else {
                        $manualDocTracColleciton[] = $recordData;
                    }
                }
                $GLOBALS['log']->debug('Data in Array :: ' . print_r($app_list_strings['dotb_document_category_list'], 1));
                $GLOBALS['log']->debug('docTracCollection :: ' . print_r($docTrackCollection, 1));
                $GLOBALS['log']->debug('Manual Do Track Collection :: ' . print_r($manualDocTracColleciton, 1));

                $GLOBALS['log']->debug('Returned Data :: ' . print_r($relatedTrackingData, 1));

                return array('docTrack' => $docTrackCollection, 'manualDocTrack' => $manualDocTracColleciton);
            }
        }

        return false;
    }

    private function searchCategoryInArray() {
        
    }

    public function findObjectByCategory($category, $tracking_array) {
        foreach ($tracking_array as $key => $element) {
            if ($category == $element['category']) {
                return $key;
            }
        }

        return '-1';
    }

    public function GetLatestAddress($api, $args) {
        global $timedate;
        $Lead = BeanFactory::getBean("Leads", $args['id']);
        $return_address = '';
        if ($Lead->load_relationship('leads_dot10_addresses_1')) {
            $addresses = $Lead->leads_dot10_addresses_1->getBeans();
            $resident_since = '';
            foreach ($addresses as $id => $address) {
                if ($count == 0) {
                    $return_address = $address->primary_address_street . ', ' . $address->primary_address_postalcode . ', ' . $address->primary_address_city . ', ' . $address->dotb_resident_since_c;
                    $resident_since = $address->dotb_resident_since_c;
                } else if (strtotime(str_replace('/', '-', $address->dotb_resident_since_c)) > strtotime(str_replace('/', '-', $resident_since))) {
                    $return_address = $address->primary_address_street . ', ' . $address->primary_address_postalcode . ' ' . $address->primary_address_city . ', ' . $address->dotb_resident_since_c;
                    $resident_since = $address->dotb_resident_since_c;
                }
                $count++;
            }
            $return_address = str_replace(", ,", ",", $return_address);
            $return_address = str_replace(" , ", ", ", $return_address);
            $return_address = rtrim($return_address, ", ");
            $return_address = ltrim($return_address, ", ");
            return $return_address;
        }
    }

    public function GetRelatedLeadPartner($api, $args) {
        global $current_user, $timedate;
        $Lead = BeanFactory::getBean("Leads", $args['id']);
        /*
         * If lead is a new record and is not created from contact
         */
        if (empty($Lead->contact_id)) {
            return false;
        }
        if ($Lead->load_relationship('leads_contacts_1')) {
            $contacts = $Lead->leads_contacts_1->getBeans();
            foreach ($contacts as $id => $contact) {
                return false;
            }

            if ($Lead->load_relationship('leads_leads_1')) {
                $leads = $Lead->leads_leads_1->getBeans();
                foreach ($leads as $id => $lead) {
                    return true;
                }
                return false;
            }
            return false;
        }
    }

    public function GetRelatedPartner($api, $args) {
        global $current_user, $timedate;
        $Contact = BeanFactory::getBean("Contacts", $args['id']);
        if ($Contact->load_relationship('leads_contacts_1')) {
            $leads = $Contact->leads_contacts_1->getBeans();

            foreach ($leads as $id => $lead) {
                //  if (empty($lead->contact_id) || empty($lead->contracts_leads_1contracts_ida)) {
                if (empty($lead->contact_id)) {
                    return false;
                } else {
                    return true;
                }
            }
            return true;
        }
    }

    public function createApplication($api, $args) {
        global $current_user, $timedate;
        $applicationFieldsMap = array(
            'description' => 'description',
            'lead_source' => 'lead_source',
            'mkto_id' => 'mkto_id',
            'mkto_sync' => 'mkto_sync',
            'credit_amount_c' => 'amount',
                /**
                 *  below fields not found in Leads module
                 */
                /* '' => 'sales_stage',
                  '' => 'probability',
                  '' => 'best_case',
                  '' => 'worst_case',
                  '' => 'expiry_date_c',
                  '' => 'provider_id_c',
                  '' => 'provider_status_id_c',
                  '' => 'provider_application_no_c',
                  '' => 'commit_stage',
                  '' => 'next_step',
                  '' => 'amount_usdollar',
                  '' => 'opportunity_type', */
        );
        $skipFields = array("");
        $leadObj = BeanFactory::getBean("Leads", $args['id']);
        $appObj = BeanFactory::getBean("Opportunities");

        /**
         *  copy all fields from lead to application, provided in 
         *  Field mapping 
         */
        foreach ($applicationFieldsMap as $key => $value) {
            $appObj->$value = $leadObj->$key;
        }

        /**
         *  concate first name and last name
         *  and copy to application, as application 
         *  contains only name fields
         */
        $appObj->name = $leadObj->first_name . " " . $leadObj->last_name;
        $appObj->assigned_user_id = $current_user->id;
        $appObj->date_closed = $timedate->nowDbDate();
        $appObj->save();
        $leadObj->load_relationship('leads_opportunities_1');
        $leadObj->leads_opportunities_1->add($appObj->id);
        $leadObj->application_created_c = 1;
        $leadObj->save();
    }

    public function createCrHistory($api, $args) {
        //$leadObj = BeanFactory::getBean("Leads", $args['id']);
        global $app_list_strings, $timedate, $current_user;
        $contractObj = BeanFactory::getBean("Contracts", $args['contractId']);
        $crObj = BeanFactory::getBean("dotb5_credit_history");
        $fieldMap = array(
            'credit_balance' => 'credit_amount_c',
            'interest_rate_c' => 'interest_rate_c',
            'contract_name' => 'name',
        );
        foreach ($fieldMap as $key => $value) {
            $crObj->$key = $contractObj->$value;
        }
        $crObj->assigned_user_id = $current_user->id;
        $crObj->credit_type_id = 'privat_credit';
        if (!empty($contractObj->provider_id_c)) {
            $crObj->credit_provider = $app_list_strings['dotb_credit_provider_list'][$contractObj->provider_id_c];
        }
        $crObj->name = $app_list_strings['dotb_credit_history_type_list'][$crObj->credit_type_id];
        /**
         * Calulate credit end date
         */
        if (!empty($contractObj->credit_duration_c) && !empty($contractObj->paying_date_c)) {
            $date = $timedate->fromUserDate($contractObj->paying_date_c);
            $date->add(new DateInterval("P{$contractObj->credit_duration_c}M"));
            $crObj->credit_end_date = $date->format('Y-m-d');
        }
        $crObj->save();
        if (!empty($args['id']) && $crObj->load_relationship('leads_dotb5_credit_history_1')) {
            $crObj->load_relationship('leads_dotb5_credit_history_1');
            $crObj->leads_dotb5_credit_history_1->add($args['id']);
        }
    }

    public function createContract($api, $args) {
        global $timedate;
        global $current_user;
        $approvedCount = 0;
        $approvedAppObj = null;
        $arr = array();
        $granted_apps = array();
        if (isset($args['app_id'])) {
            $approvedAppObj = BeanFactory::getBean("Opportunities", $args['app_id']);
            $approvedCount = 1;
        } else {
        $leadObj = BeanFactory::getBean("Leads", $args['id']);
        if ($leadObj->load_relationship('leads_opportunities_1')) {
            $relatedApps = $leadObj->leads_opportunities_1->getBeans();
            foreach ($relatedApps as $appObj) {
                if ($appObj->provider_status_id_c == "granted") {
                    $approvedCount++;
                    $approvedAppObj = $appObj;
                        $granted_apps[$appObj->id] = $appObj->name;
                }
            }
        }
        $return = '';
        if ($approvedCount == 0) {
                $arr['response'] = "no_approved";
        }
        if ($approvedCount > 1) {
                $arr['response'] = "multi_approved";
                $arr['granted_apps'] = $granted_apps;
        }
        }
        /*

          //Here to do

         */

        if ($approvedCount == 1 && $approvedAppObj != null) {
            $arr['opportunity_id'] = $approvedAppObj->id;
            $arr['account_id'] = $approvedAppObj->account_id;
            $arr['account_name'] = $approvedAppObj->account_name;
            $arr['interest_rate'] = $approvedAppObj->approved_interest_rate_c;
            $arr['credit_amount'] = $approvedAppObj->approved_credit_amount_c;

            if ($approvedAppObj->approved_ppi_c) {
                $arr['ppi'] = 'PPI';
            } else {
                $arr['ppi'] = 'NOPPI';
            }

            $arr['duration'] = $approvedAppObj->approved_credit_duration_c;
            $arr['current_date'] = $timedate->nowDbDate();
            $arr['current_date_time'] = date('Y-m-d H:i:s', strtotime($timedate->now()));

            $arr['customer_credit_amount_c'] = $approvedAppObj->contract_credit_amount;
            $arr['customer_credit_duration_c'] = $approvedAppObj->contract_credit_duration;
            $arr['customer_interest_rate_c'] = $approvedAppObj->contract_interest_rate;
            $arr['customer_ppi_c'] = $approvedAppObj->contract_ppi;
            $arr['contract_ppi_plus'] = $approvedAppObj->contract_ppi_plus;
            $arr['contract_transfer_fee'] = $approvedAppObj->contract_transfer_fee;
            $arr['provider_id_c'] = $approvedAppObj->provider_id_c;
            $arr['soko'] = $approvedAppObj->dotb_soko_c;
            $arr['response'] = 'create_contract';
            $arr['provider_contract_no'] = $approvedAppObj->provider_contract_no;
        }
        return $arr;
    }

    public function createContact($api, $args) {
        global $timedate;
        global $current_user;
        $contactFieldsMap = array(
            'description' => 'description',
            'salutation' => 'salutation',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'title' => 'title',
            'custom_notes' => 'custom_notes',
            'facebook' => 'facebook',
            'twitter' => 'twitter',
            'googleplus' => 'googleplus',
            'department' => 'department',
            'do_not_call' => 'do_not_call',
            'phone_home' => 'phone_home',
            'phone_mobile' => 'phone_mobile',
            'phone_work' => 'phone_work',
            'phone_other' => 'phone_other',
            'phone_fax' => 'phone_fax',
            'primary_address_street' => 'primary_address_street',
            'primary_address_city' => 'primary_address_city',
            'address_c_o' => 'address_c_o',
            'primary_address_postalcode' => 'primary_address_postalcode',
            'primary_address_country' => 'primary_address_country',
            'alt_address_street' => 'alt_address_street',
            'alt_address_city' => 'alt_address_city',
            'alt_address_state' => 'alt_address_state',
            'alt_address_postalcode' => 'alt_address_postalcode',
            'alt_address_country' => 'alt_address_country',
            'correspondence_address_c_o' => 'correspondence_address_c_o',
            'correspondence_address_street' => 'correspondence_address_street',
            'correspondence_address_city' => 'correspondence_address_city',
            'correspondence_address_postalcode' => 'correspondence_address_postalcode',
            'assistant' => 'assistant',
            'assistant_phone' => 'assistant_phone',
            'lead_source' => 'lead_source',
            'dnb_principal_id' => 'dnb_principal_id',
            'birthdate' => 'birthdate',
            'preferred_language' => 'preferred_language',
            'mkto_sync' => 'mkto_sync',
            'mkto_id' => 'mkto_id',
            'mkto_lead_score' => 'mkto_lead_score',
            'dotb_bank_name_c' => 'dotb_bank_name',
            'dotb_monthly_net_income_c' => 'dotb_monthly_net_income',
            'dotb_car_count_c' => 'dotb_car_count',
            'dotb_direct_withholding_tax_c' => 'dotb_direct_withholding_tax',
            'dotb_has_enforcements_c' => 'dotb_has_enforcements',
            'dotb_employment_type_id_c' => 'dotb_employment_type_id',
            'dotb_leasing_expenses_c' => 'dotb_leasing_expenses',
            'dotb_education_costs_child_c' => 'dotb_education_costs_for_children',
            'dotb_had_past_credit_c' => 'dotb_had_past_credit', //'dotb_had_credit_denial_in_last_6_months',
            'dotb_past_enforcements_c' => 'dotb_past_enforcements',
            'dotb_second_job_employer_npa_c' => 'dotb_second_job_employer_npa',
            'do_receives_salary_over_bank_c' => 'dotb_receives_salary_over_bank_transfer',
            'dotb_gender_id_c' => 'dotb_gender_id',
            'dotb_age_c' => 'dotb_age_c',
            'dotb_payment_behaviour_type_c' => 'dotb_payment_behaviour_type_id',
            'reference_number_c' => 'reference_number',
            'dot_second_job_emp_street_c' => 'dotb_second_job_emp_street_c',
            'dot_second_job_emp_zip_c' => 'dotb_second_job_emp_zip_c',
            'dotb_payout_option_id_c' => 'dotb_payout_option_id',
            'dotb_employer_town_c' => 'dotb_employer_town',
            'dotb_credit_denial_in_last_2_c' => 'dotb_had_credit_denial_in_last_2_years',
            'do_patronized_or_has_advisor_c' => 'dotb_is_patronized_or_has_adviser',
            'dotb_bank_city_name_c' => 'dotb_bank_city_name',
            'dotb_is_reset_c' => 'dotb_is_reset',
            'dotb_health_costs_c' => 'dotb_health_costs',
            'dotb_civil_status_id_c' => 'dotb_civil_status_id',
            'dotb_housing_situation_id_c' => 'dotb_housing_situation_id',
            'dotb_mobility_costs_c' => 'dotb_mobility_costs',
            'dotb_iso_nationality_code_c' => 'dotb_iso_nationality_code',
            'dotb_employer_npa_c' => 'dotb_employer_npa',
            'dotb_has_second_job_c' => 'dotb_has_second_job',
            'dot_second_job_employer_name_c' => 'dotb_second_job_employer_name',
            'dotb_has_alimony_payments_c' => 'dotb_has_alimony_payments',
            'dotb_iban_c' => 'dotb_iban',
            'dot_enforcements_description_c' => 'dotb_enforcements_description',
            'dotb_credit_anomaly_provider_c' => 'dotb_past_credit_anomaly_provider',
            'dotb_has_open_attachment_c' => 'dotb_has_open_attachment_of_earnings',
            'dotb_work_permit_since_c' => 'dotb_work_permit_since',
            'dotb_rent_or_alimony_income_c' => 'dotb_rent_or_alimony_income',
            'dotb_life_expenses_c' => 'dotb_life_expenses',
            'dotb_had_past_credit_c' => 'dotb_had_past_credit',
            'dotb_additional_income_desc_c' => 'dotb_additional_income_desc',
            'dotb_other_expenses_c' => 'dotb_other_expenses',
            'dotb_is_home_owner_c' => 'dotb_is_home_owner',
            'dotb_has_dependent_children_c' => 'dotb_has_dependent_children',
            'dotb_had_warnings_in_last_3_c' => 'dotb_had_warnings_in_last_3_years',
            'dotb_pension_type_id_c' => 'dotb_pension_type_id',
            'dotb_work_permit_until_c' => 'dotb_work_permit_until',
            'dotb_taxes_c' => 'dotb_taxes',
            'dotb_second_job_description_c' => 'dotb_second_job_description',
            'dotb_denial_provider_c' => 'dotb_denial_provider',
            'dotb_is_unable_to_work_c' => 'dotb_is_unable_to_work',
            'dotb_work_permit_type_id_c' => 'dotb_work_permit_type_id',
            'dot_health_insurance_premium_c' => 'dotb_health_insurance_premium',
            'dotb_has_premium_reduction_c' => 'dotb_has_premium_reduction',
            'dotb_income_include_alimony_c' => 'dotb_income_include_alimony',
            'dotb_second_job_has_13th_c' => 'dotb_second_job_has_13th',
            'dotb_correspondence_language_c' => 'dotb_correspondence_language',
            'dotb_employed_until_c' => 'dotb_employed_until',
            'dot_second_job_employer_town_c' => 'dotb_second_job_employer_town',
            'dotb_unable_to_work_in_last_c' => 'dotb_unable_to_work_in_last_5_years',
            'dotb_bank_zip_code_c' => 'dotb_bank_zip_code',
            //'dotb_resident_since_c' => 'dotb_resident_since',
            'dotb_work_expenses_c' => 'dotb_work_expenses',
            'dot_second_job_emp_number_c' => 'dot_second_job_emp_number_c',
            'dotb_is_pensioner_c' => 'dotb_is_pensioner',
            'dotb_unable_to_work_reason_c' => 'dotb_unable_to_work_reason',
            'dotb_additional_income_c' => 'dotb_additional_income',
            'dotb_employer_name_c' => 'dotb_employer_name',
            'dotb_aliments_c' => 'dotb_aliments',
            'dotb_is_rent_split_c' => 'dotb_is_rent_split',
            'dotb_past_enforcement_number_c' => 'dotb_past_enforcement_number',
            'dotb_additional_expenses_c' => 'dotb_additional_expenses',
            'dotb_past_enforcement_amount_c' => 'dotb_past_enforcement_amount',
            'dotb_current_enforcement_num_c' => 'dotb_current_enforcement_number',
            'dotb_current_enforcement_amo_c' => 'dotb_current_enforcement_amount',
            'dotb_mortgage_amount_c' => 'dotb_mortgage_amount',
            'dotb_has_thirteenth_salary_c' => 'dotb_has_thirteenth_salary',
            'dotb_employed_since_c' => 'dotb_employed_since',
            'dotb_second_job_since_c' => 'dotb_second_job_since',
            'dotb_has_second_income_c' => 'dotb_has_second_income',
            'dotb_second_job_gross_income_c' => 'dotb_second_job_gross_income',
            'dotb_monthly_gross_income_c' => 'dotb_monthly_gross_income',
            'dotb_is_in_probation_period_c' => 'dotb_is_in_probation_period',
            'dotb_housing_costs_c' => 'dotb_housing_costs',
            'user_id1_c' => 'user_id1_c',
            'bank_contact_user_id_c' => 'bank_contact_user_id_c',
            'children_birth_years_c' => 'children_birth_years_c',
            'comparis_ref_gid_c' => 'comparis_ref_gid_c',
            'contact_type_option_id_c' => 'contact_type_option_id_c',
            'credit_amount_c' => 'credit_amount_c',
            'credit_duration_c' => 'credit_duration_c',
            'credit_request_status_id_c' => 'credit_request_status_id_c',
            //'credit_request_substatus_id_c' => 'credit_request_substatus_id_c',
            'credit_usage_type_id_c' => 'credit_usage_type_id_c',
            'current_occupation_c' => 'current_occupation_c',
            //'user_id_c' => 'user_id_c',
            //'customer_contact_user_id_c' => 'customer_contact_user_id_c',
            'deltavista_request_id_c' => 'deltavista_request_id_c',
            'deltavista_score_c' => 'deltavista_score_c',
            'dotb_employer_number_c' => 'dotb_employer_number_c',
            'dotb_employer_street_c' => 'dotb_employer_street_c',
            'dotb_employer_zip_c' => 'dotb_employer_zip_c',
            'dotb_soko_c' => 'dotb_soko_c',
            'has_applied_for_other_credit_c' => 'has_applied_for_other_credit_c',
            'has_children_c' => 'has_children_c',
            'has_deltavista_response_c' => 'has_deltavista_response_c',
            'has_intrum_response_c' => 'has_intrum_response_c',
            'input_process_type_id_c' => 'input_process_type_id_c',
            'intrum_request_id_c' => 'intrum_request_id_c',
            'intrum_score_c' => 'intrum_score_c',
            'lead_type_assignment_date_c' => 'lead_type_assignment_date_c',
            'lead_type_id_c' => 'lead_type_id_c',
            'legal_terms_accepted_date_c' => 'legal_terms_accepted_date_c',
            'maiden_name_c' => 'maiden_name_c',
            'no_of_dependent_children_c' => 'no_of_dependent_children_c',
            'other_credit_reason_c' => 'other_credit_reason_c',
            'ppi_id_c' => 'ppi_id_c',
            'previous_employed_since_c' => 'previous_employed_since_c',
            'previous_employer_c' => 'previous_employer_c',
            'provision_c' => 'provision_c',
            'dotb_credit_denial_in_last_6_c' => 'dotb_had_credit_denial_in_last_6_months',
            //'primary_resident_till_c' => 'dotb_resident_till_c',
            'dotb_other_credits_c' => 'dotb_other_credits_c',
            'dotb_date_of_loss_c' => 'dotb_date_of_loss_c',
            'dotb_partner_agreement_c' => 'dotb_partner_agreement_c',
            'dotb_monthly_net_income_nb_c' => 'dotb_monthly_net_income_nb_c',
            'dotb_rent_alimony_income_c' => 'dotb_rent_alimony_income_c',
            'settlement_type' => 'settlement_type',
            'assigned_user_id' => 'assigned_user_id',
            'team_id' => 'team_id'
                //'dotb_other_expenses_c' => 'dotb_additional_expenses',

                /**
                 *  below fields not found in Leads module
                 */
                /* '' => 'relative_type_c',
                  '' => 'portal_name',
                  '' => 'portal_active',
                  '' => 'portal_password',
                  '' => 'portal_app', */
        );
        $skipFields = array("");
        $leadObj = BeanFactory::getBean("Leads", $args['id']);

        $contactObj = BeanFactory::getBean("Contacts");

        /**
         *  Check for duplicate contacts
         */
        $contactDup = new Contact();
        if (empty($leadObj->contact_id)) {
            if (empty($leadObj->birthdate)) {
                $contactDup->retrieve_by_string_fields(array('last_name' => $leadObj->last_name));
            } else {
                try {
                    $birthdate = new DateTime($leadObj->birthdate);
                    $birthdate = $timedate->asDbDate($birthdate);
                    $contactDup->retrieve_by_string_fields(array('last_name' => $leadObj->last_name, 'birthdate' => $birthdate));
                } catch (Exception $e) {
                    $contactDup->retrieve_by_string_fields(array('last_name' => $leadObj->last_name));
                }
            }
        } else {
            $contactDup->retrieve($leadObj->contact_id);
        }
        if (!empty($contactDup->id)) {
            $contactObj = $contactDup;
        } else {
            /**
             *  copy all fields from lead to contact, provided in 
             *  Field mapping 
             */
            foreach ($contactFieldsMap as $key => $value) {
                $contactObj->$value = $leadObj->$key;
            }
            if (empty($leadObj->assigned_user_id))
                $contactObj->assigned_user_id = $current_user->id;
            $contactObj->reference_number = $leadObj->reference_number_c - 1;
            $contactObj->save();
            /**
             * Relate new contact with current Lead
             */
        }
        $leadObj->load_relationship('contacts');
        $leadObj->contacts->add($contactObj->id);
        /**
         *
         * Now Copy all related modules data to existing contact or new contact
         *
         */
        if ($contactObj) {
            /**
             *  Copy Email address to Contact
             */
            $emailAddresses = new SugarEmailAddress;
            $sea = new SugarEmailAddress;
            $lead_email_addresses = $sea->getAddressesByGUID($leadObj->id, $leadObj->module_name);
            $opt_emails = '';
            foreach ($lead_email_addresses as $lead_email_address) {
                if ($lead_email_address['email_address'] == $leadObj->email1) {
                    $emailAddresses->addAddress($leadObj->email1, true);
                } else {
                    $emailAddresses->addAddress($lead_email_address['email_address'], false, null, false, true);
                    $opt_emails.=$lead_email_address['email_address'] . ',';
                }
            }

            $opt_emails = trim($opt_emails);
            $opt_emails = rtrim($opt_emails, ',');
            $contactObj->opt_emails = $opt_emails;
            $contactObj->processed = false;
            require_once 'custom/modules/Leads/syncLead.php';
            syncLead::$triggeredFromLead = true;
            $contactObj->save();
            $emailAddresses->save($contactObj->id, $contactObj->module_name);
            //Copy All open tasks to contact
            /*if ($leadObj->load_relationship("tasks")) {
                $relatedTasks = $leadObj->tasks->getBeans();
                foreach ($relatedTasks as $task) {
                    if ($task->status != 'closed') {
                        /**
                         *  As Tasks can have only one parent so
                         *  make duplicate of record
                         */
                        /*unset($task->id);
                        $task->parent_type = 'Contacts';
                        $task->parent_id = $contactObj->id;
                        $task->contact_id = $contactObj->id;

                        $task->save();

                    }
                }
            }*/

            /**
             * Copy Notes to Contact
             *
              if ($leadObj->load_relationship("notes")) {
              $relatedNotes = $leadObj->notes->getBeans();
              foreach ($relatedNotes as $note) {
              /**
             *  As Notes can have only one parent so
             *  make duplicate of record
             *
              unset($note->id);
              $note->parent_type = 'Contacts';
              $note->parent_id = $contactObj->id;
              $note->contact_id = $contactObj->id;
              $note->save();
              }
              } */
            /**
             * Copy Archived Emails to Contact
             *
            if ($leadObj->load_relationship("emails")) {
                $contactObj->load_relationship('emails');
                $relatedEmails = $leadObj->emails->getBeans();
                foreach ($relatedEmails as $email) {
                    /**
                     *  As Emails can have only one parent so
                     *  make duplicate of record
                     *
                    unset($email->id);
                    $email->parent_type = 'Contacts';
                    $email->parent_id = $contactObj->id;
                    $email->save();
                }
            }
            if ($leadObj->load_relationship("leads_documents_1")) {
                $contactObj->load_relationship('documents');
                $relatedDocs = $leadObj->leads_documents_1->getBeans();
                foreach ($relatedDocs as $doc) {
                    $contactObj->documents->add($doc->id);
                }
            }
            if ($leadObj->load_relationship("leads_dotb5_credit_history_1")) {
                $contactObj->load_relationship('dotb5_credit_history_contacts');
                $relatedCrHistory = $leadObj->leads_dotb5_credit_history_1->getBeans();
                foreach ($relatedCrHistory as $CrHistory) {
                    $contactObj->dotb5_credit_history_contacts->add($CrHistory->id);
                }
            }
            if ($leadObj->load_relationship("leads_contacts_1")) {
                $contactObj->load_relationship('contacts_contacts_1');
                $relatedRelatives = $leadObj->leads_contacts_1->getBeans();
                foreach ($relatedRelatives as $relative) {
                    $contactObj->contacts_contacts_1->add($relative->id);
                }
            }
            if ($leadObj->load_relationship('leads_contacts_1')) {
                $lead_relatives = $leadObj->leads_contacts_1->getBeans();
                foreach ($lead_relatives as $id => $lead_relative) {
                    $lead_relative->load_relationship('contacts_contacts_1');
                    $lead_relative->contacts_contacts_1->add($contactObj->id);
                }
            }
            if (!empty($args['contractId'])) {
                $contactObj->load_relationship('contacts_contracts_1');
                $contactObj->contacts_contracts_1->add($args['contractId']);
            }

            /**
             *  Copy Addresses to converted Contact
            
            if ($leadObj->load_relationship('leads_dot10_addresses_1')) {
                $contactObj->load_relationship("contacts_dot10_addresses_1");
                $relatedAddresses = $leadObj->leads_dot10_addresses_1->getBeans();
                foreach ($relatedAddresses as $address) {
                    $contactObj->contacts_dot10_addresses_1->add($address->id);
                }
            }
            */ 
        }
    }

    public function createLead($api, $args) {
        global $timedate;
        global $current_user;
        $contactFieldsMap = array(
            'salutation' => 'salutation',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'title' => 'title',
            'custom_notes' => 'custom_notes',
            'facebook' => 'facebook',
            'twitter' => 'twitter',
            'googleplus' => 'googleplus',
            'department' => 'department',
            'do_not_call' => 'do_not_call',
            'phone_home' => 'phone_home',
            'phone_mobile' => 'phone_mobile',
            'phone_work' => 'phone_work',
            'phone_other' => 'phone_other',
            'phone_fax' => 'phone_fax',
            'primary_address_street' => 'primary_address_street',
            'primary_address_city' => 'primary_address_city',
            'address_c_o' => 'address_c_o',
            'primary_address_postalcode' => 'primary_address_postalcode',
            'primary_address_country' => 'primary_address_country',
            'alt_address_street' => 'alt_address_street',
            'alt_address_city' => 'alt_address_city',
            'alt_address_state' => 'alt_address_state',
            'alt_address_postalcode' => 'alt_address_postalcode',
            'alt_address_country' => 'alt_address_country',
            'correspondence_address_c_o' => 'correspondence_address_c_o',
            'correspondence_address_street' => 'correspondence_address_street',
            'correspondence_address_city' => 'correspondence_address_city',
            'correspondence_address_postalcode' => 'correspondence_address_postalcode',
            'assistant' => 'assistant',
            'assistant_phone' => 'assistant_phone',
            'lead_source' => 'lead_source',
            'dnb_principal_id' => 'dnb_principal_id',
            'birthdate' => 'birthdate',
            'preferred_language' => 'preferred_language',
            'mkto_sync' => 'mkto_sync',
            'mkto_id' => 'mkto_id',
            'mkto_lead_score' => 'mkto_lead_score',
            'dotb_bank_name_c' => 'dotb_bank_name',
            'dotb_monthly_net_income_c' => 'dotb_monthly_net_income',
            'dotb_car_count_c' => 'dotb_car_count',
            'dotb_employment_type_id_c' => 'dotb_employment_type_id',
            'dotb_leasing_expenses_c' => 'dotb_leasing_expenses',
            'dotb_education_costs_child_c' => 'dotb_education_costs_for_children',
            'dotb_second_job_employer_npa_c' => 'dotb_second_job_employer_npa',
            'do_receives_salary_over_bank_c' => 'dotb_receives_salary_over_bank_transfer',
            'dotb_gender_id_c' => 'dotb_gender_id',
            'dotb_age_c' => 'dotb_age_c',
            'dot_second_job_emp_street_c' => 'dotb_second_job_emp_street_c',
            'dot_second_job_emp_zip_c' => 'dotb_second_job_emp_zip_c',
            'dotb_payout_option_id_c' => 'dotb_payout_option_id',
            'dotb_employer_town_c' => 'dotb_employer_town',
            'do_patronized_or_has_advisor_c' => 'dotb_is_patronized_or_has_adviser',
            'dotb_bank_city_name_c' => 'dotb_bank_city_name',
            'dotb_health_costs_c' => 'dotb_health_costs',
            'dotb_civil_status_id_c' => 'dotb_civil_status_id',
            'dotb_housing_situation_id_c' => 'dotb_housing_situation_id',
            'dotb_mobility_costs_c' => 'dotb_mobility_costs',
            'dotb_iso_nationality_code_c' => 'dotb_iso_nationality_code',
            'dotb_employer_npa_c' => 'dotb_employer_npa',
            'dotb_has_second_job_c' => 'dotb_has_second_job',
            'dot_second_job_employer_name_c' => 'dotb_second_job_employer_name',
            'dotb_has_alimony_payments_c' => 'dotb_has_alimony_payments',
            'dotb_iban_c' => 'dotb_iban',
            'dotb_rent_or_alimony_income_c' => 'dotb_rent_or_alimony_income',
            'dotb_life_expenses_c' => 'dotb_life_expenses',
            'dotb_additional_income_desc_c' => 'dotb_additional_income_desc',
            'dotb_other_expenses_c' => 'dotb_other_expenses',
            'dotb_is_home_owner_c' => 'dotb_is_home_owner',
            'dotb_has_dependent_children_c' => 'dotb_has_dependent_children',
            'dotb_pension_type_id_c' => 'dotb_pension_type_id',
            'dotb_taxes_c' => 'dotb_taxes',
            'dotb_second_job_description_c' => 'dotb_second_job_description',
            'dotb_is_unable_to_work_c' => 'dotb_is_unable_to_work',
            'dot_health_insurance_premium_c' => 'dotb_health_insurance_premium',
            'dotb_has_premium_reduction_c' => 'dotb_has_premium_reduction',
            'dotb_income_include_alimony_c' => 'dotb_income_include_alimony',
            'dotb_second_job_has_13th_c' => 'dotb_second_job_has_13th',
            'dotb_correspondence_language_c' => 'dotb_correspondence_language',
            'dotb_employed_until_c' => 'dotb_employed_until',
            'dot_second_job_employer_town_c' => 'dotb_second_job_employer_town',
            'dotb_unable_to_work_in_last_c' => 'dotb_unable_to_work_in_last_5_years',
            'dotb_bank_zip_code_c' => 'dotb_bank_zip_code',
            'dotb_resident_since_c' => 'dotb_resident_since',
            'dotb_work_expenses_c' => 'dotb_work_expenses',
            'dot_second_job_emp_number_c' => 'dot_second_job_emp_number_c',
            'dotb_is_pensioner_c' => 'dotb_is_pensioner',
            'dotb_unable_to_work_reason_c' => 'dotb_unable_to_work_reason',
            'dotb_additional_income_c' => 'dotb_additional_income',
            'dotb_employer_name_c' => 'dotb_employer_name',
            'dotb_aliments_c' => 'dotb_aliments',
            'dotb_is_rent_split_c' => 'dotb_is_rent_split',
            'dotb_additional_expenses_c' => 'dotb_additional_expenses',
            'dotb_mortgage_amount_c' => 'dotb_mortgage_amount',
            'dotb_has_thirteenth_salary_c' => 'dotb_has_thirteenth_salary',
            'dotb_employed_since_c' => 'dotb_employed_since',
            'dotb_second_job_since_c' => 'dotb_second_job_since',
            'dotb_has_second_income_c' => 'dotb_has_second_income',
            'dotb_second_job_gross_income_c' => 'dotb_second_job_gross_income',
            'dotb_monthly_gross_income_c' => 'dotb_monthly_gross_income',
            'dotb_is_in_probation_period_c' => 'dotb_is_in_probation_period',
            'dotb_housing_costs_c' => 'dotb_housing_costs',
            'user_id1_c' => 'user_id1_c',
            'children_birth_years_c' => 'children_birth_years_c',
            'current_occupation_c' => 'current_occupation_c',
            'dotb_employer_number_c' => 'dotb_employer_number_c',
            'dotb_employer_street_c' => 'dotb_employer_street_c',
            'dotb_employer_zip_c' => 'dotb_employer_zip_c',
            'dotb_soko_c' => 'dotb_soko_c',
            'has_children_c' => 'has_children_c',
            'maiden_name_c' => 'maiden_name_c',
            'no_of_dependent_children_c' => 'no_of_dependent_children_c',
            'previous_employed_since_c' => 'previous_employed_since_c',
            'previous_employer_c' => 'previous_employer_c',
            'provision_c' => 'provision_c',
            'dotb_other_credits_c' => 'dotb_other_credits_c',
            'dotb_partner_agreement_c' => 'dotb_partner_agreement_c',
            'dotb_monthly_net_income_nb_c' => 'dotb_monthly_net_income_nb_c',
            'dotb_rent_alimony_income_c' => 'dotb_rent_alimony_income_c',
            'dotb_housing_costs_rent_c' => 'dotb_housing_costs_rent_c',
            'dotb_andere_c' => 'dotb_andere_c',
            'dotb_sideline_bonus_gratuity_c' => 'dotb_sideline_bonus_gratuity_c',
            'dotb_bonus_gratuity_c' => 'dotb_bonus_gratuity_c',
            'sideline_hired_since_c' => 'sideline_hired_since_c',
            'settlement_type' => 'settlement_type',
            'assigned_user_id' => 'assigned_user_id',
            //'credit_request_substatus_id_c' => 'credit_request_substatus_id_c',
            'team_id' => 'team_id',
            'dotb_direct_withholding_tax_c' => 'dotb_direct_withholding_tax',
            'dotb_work_permit_since_c' => 'dotb_work_permit_since',
            'dotb_work_permit_until_c' => 'dotb_work_permit_until',
            'dotb_work_permit_type_id_c' => 'dotb_work_permit_type_id',
                // The following fields have been removed from Sync.
                //'reference_number_c' => 'reference_number',/./.
                //'dotb_is_reset_c' => 'dotb_is_reset',/./.
                //'bank_contact_user_id_c' => 'bank_contact_user_id_c',/./.
                //'comparis_ref_gid_c' => 'comparis_ref_gid_c',/./.
                //'deltavista_request_id_c' => 'deltavista_request_id_c',/./.
                //'deltavista_score_c' => 'deltavista_score_c',/./.        //'has_deltavista_response_c' => 'has_deltavista_response_c',/./.
                //'has_intrum_response_c' => 'has_intrum_response_c',/./.
                //'intrum_request_id_c' => 'intrum_request_id_c',/./.
                //'intrum_score_c' => 'intrum_score_c',/./.
                //'lead_type_assignment_date_c' => 'lead_type_assignment_date_c',/./.
                //'lead_type_id_c' => 'lead_type_id_c',/./.
                //'legal_terms_accepted_date_c' => 'legal_terms_accepted_date_c',/./.
                // 'description' => 'description',	//removed according to CRED-38
                // 'dotb_has_enforcements_c' => 'dotb_has_enforcements',	//removed according to CRED-38
                // 'dotb_had_past_credit_c' => 'dotb_had_past_credit', //'dotb_had_credit_denial_in_last_6_months',
                // 'dotb_past_enforcements_c' => 'dotb_past_enforcements',	//removed according to CRED-38
                // 'dotb_payment_behaviour_type_c' => 'dotb_payment_behaviour_type_id',	//removed according to CRED-38
                // 'dotb_credit_denial_in_last_2_c' => 'dotb_had_credit_denial_in_last_2_years',	//removed according to CRED-38
                // 'dot_enforcements_description_c' => 'dotb_enforcements_description',	//removed according to CRED-38
                // 'dotb_credit_anomaly_provider_c' => 'dotb_past_credit_anomaly_provider',	//removed according to CRED-38
                // 'dotb_has_open_attachment_c' => 'dotb_has_open_attachment_of_earnings',	//removed according to CRED-38
                // 'dotb_had_past_credit_c' => 'dotb_had_past_credit',	//removed according to CRED-38
                // 'dotb_had_warnings_in_last_3_c' => 'dotb_had_warnings_in_last_3_years',	//removed according to CRED-38
                // 'dotb_denial_provider_c' => 'dotb_denial_provider',	//removed according to CRED-38
                // 'primary_resident_since_c' => 'dotb_resident_since',
                // 'dotb_past_enforcement_number_c' => 'dotb_past_enforcement_number',	//removed according to CRED-38
                // 'dotb_past_enforcement_amount_c' => 'dotb_past_enforcement_amount',	//removed according to CRED-38
                // 'dotb_current_enforcement_num_c' => 'dotb_current_enforcement_number',	//removed according to CRED-38
                // 'dotb_current_enforcement_amo_c' => 'dotb_current_enforcement_amount',	//removed according to CRED-38
                // 'contact_type_option_id_c' => 'contact_type_option_id_c',	//removed according to CRED-38
                // 'credit_amount_c' => 'credit_amount_c',	//removed according to CRED-38
                // 'credit_duration_c' => 'credit_duration_c',	//removed according to CRED-38
                // 'credit_request_status_id_c' => 'credit_request_status_id_c',//removed according to CRED-437
                // 'credit_usage_type_id_c' => 'credit_usage_type_id_c',	//removed according to CRED-38
                // 'user_id_c' => 'user_id_c',
                // 'customer_contact_user_id_c' => 'customer_contact_user_id_c',            //'dotb_other_expenses_c' => 'dotb_additional_expenses',
                // 'dotb_date_of_loss_c' => 'dotb_date_of_loss_c',	//removed according to CRED-38
                // 'dotb_credit_denial_in_last_6_c' => 'dotb_had_credit_denial_in_last_6_months',	//removed according to CRED-38
                // 'primary_resident_till_c' => 'dotb_resident_till_c',            
                // 'other_credit_reason_c' => 'other_credit_reason_c',	//removed according to CRED-38
                // 'ppi_id_c' => 'ppi_id_c',	//removed according to CRED-38            
                // 'input_process_type_id_c' => 'input_process_type_id_c',	//removed according to CRED-38
                // 'has_applied_for_other_credit_c' => 'has_applied_for_other_credit_c',	//removed according to CRED-38
        );

        $contactObj = BeanFactory::getBean("Contacts", $args['id']);
        $contactObj->processed = 1;
        $leadObj = BeanFactory::getBean("Leads");


        /**
         * Check for duplicates by comparing email address
         */
        if ($args['ignore_duplicate'] != 'ignore') {
            $sea = new SugarEmailAddress;
            // Grab the primary address for the given record represented by the $contactObj object
            $primaryEmail = $sea->getPrimaryAddress($contactObj);
            $email_escaped = strtoupper(addslashes($primaryEmail));
            if (empty($email_escaped)) {
                $dupSql = "SELECT leads.id, first_name, last_name 
            FROM leads 
            INNER JOIN leads_cstm 
            ON leads.id=leads_cstm.id_c 
            WHERE  leads.deleted = 0
            AND ((leads_cstm.credit_request_status_id_c != '11_closed' AND  leads_cstm.credit_request_status_id_c != '10_active') 
            OR leads_cstm.credit_request_status_id_c IS NULL)
            AND leads.last_name='$contactObj->last_name' AND leads.contact_id='$contactObj->id'";
            } else {
                $dupSql = "SELECT leads.id, first_name, last_name 
            FROM leads 
            INNER JOIN leads_cstm 
            ON leads.id=leads_cstm.id_c 
            WHERE  leads.deleted = 0
            AND ((leads_cstm.credit_request_status_id_c != '11_closed' AND  leads_cstm.credit_request_status_id_c != '10_active')
            OR leads_cstm.credit_request_status_id_c IS NULL)
            AND leads.last_name='$contactObj->last_name' AND leads.contact_id='$contactObj->id'
            AND
        leads.id IN (
            SELECT eabr_scauth.bean_id
            FROM email_addr_bean_rel AS eabr_scauth

            INNER JOIN email_addresses AS ea_scauth
            ON ea_scauth.deleted = 0
            AND eabr_scauth.email_address_id = ea_scauth.id
            AND ea_scauth.email_address = '$email_escaped'

            WHERE eabr_scauth.deleted = 0
            AND eabr_scauth.bean_module = 'Leads'
            AND eabr_scauth.primary_address = 1
            )";
            }


            $res = $GLOBALS['db']->query($dupSql);
            $id = '';
            $name = '';
            while ($leadInfo = $GLOBALS['db']->fetchByAssoc($res)) {
                if (!empty($leadInfo['id']) && $leadInfo['last_name'] == $contactObj->last_name) {
                    $id = $leadInfo['id'];
                    $name = $leadInfo['first_name'] . ' ' . $leadInfo['last_name'];
                    break;
                    /* return array(
                      'id' => $leadInfo['id'],
                      'name' => $leadInfo['first_name'] . ' ' . $leadInfo['last_name'],
                      ); */
                }
            }

            return array(
                'id' => $id,
                'name' => $name,
            );
        }
        /**
         *  copy all fields from contact to lead, provided in 
         *  Field mapping 
         */
        foreach ($contactFieldsMap as $key => $value) {
            if ($contactObj->$value == '0.00' || $contactObj->$value == '0.000000' || $contactObj->$value == null || $contactObj->$value == '') {
                unset($contactObj->$value);
            } else {
                $leadObj->$key = $contactObj->$value;
            }
            //$leadObj->$value = $contactObj->$key;
        }
        if (empty($leadObj->credit_amount_c)) {
            unset($leadObj->credit_amount_c);
        }
        if (empty($contactObj->assigned_user_id))
            $leadObj->assigned_user_id = $current_user->id;
        //$leadObj->lead_type_id_c = 'a';
        $leadObj->credit_request_status_id_c = $args['lead_status'];
        $leadObj->campaign_id = $args['lead_campaign'];
        $leadObj->contact_id = $contactObj->id;
        $today = new DateTime($timedate->nowDb());
        $today = $today->format('Y-m-d');
        $leadObj->cstm_last_name_c = $contactObj->first_name . ' ' . $contactObj->last_name . ' ' . $today . ' Lead';
        //$leadObj->processed = 1;
        $leadObj->input_process_type_id_c = 'manually_added';
        /*
         * Getting Email Addresses
         */
        $emailAddresses = new SugarEmailAddress;

        $sea = new SugarEmailAddress;

        $contact_email_addresses = $sea->getAddressesByGUID($contactObj->id, $contactObj->module_name);
        $add = false;
        $opt_emails = '';
        foreach ($contact_email_addresses as $contact_email_address) {
            if ($contact_email_address['email_address'] == $contactObj->email1) {
                $emailAddresses->addAddress($contactObj->email1, true);
            } else {
                $emailAddresses->addAddress($contact_email_address['email_address'], false, null, false, true);
                $opt_emails.=$contact_email_address['email_address'] . ', ';
            }
            $add = true;
        }
        $opt_emails = trim($opt_emails);
        $opt_emails = rtrim($opt_emails, ',');
        $leadObj->opt_emails = $opt_emails;
        require_once 'custom/modules/Contacts/syncContact.php';
        syncContact::$triggeredFromContact = true;
        $leadObj->created_from_contact = true;
        $leadObj->save();
        if ($add) {
            $emailAddresses->save($leadObj->id, $leadObj->module_name);
        }

        /**
         *  Copy email address
         */
        /* if (!empty($primaryEmail) && $primaryEmail != null) {
          $lsea = new SugarEmailAddress;
          $lsea->addAddress($primaryEmail, true);
          $lsea->save($leadObj->id, "Leads");
          } */
        $l_sea = new SugarEmailAddress;
        $contact_addresses = $l_sea->getAddressesByGUID($contactObj->id, 'Contacts');
        $lead_sea = new SugarEmailAddress;
        foreach ($contact_addresses as $cont_address) {
            //primary email address
            if ($cont_address['primary_address']) {
                // Add a primary email address
                $lead_sea->addAddress($cont_address['email_address'], true);
            } else if ($cont_address['invalid_email'] && $cont_address['opt_out']) {
                $lead_sea->addAddress($cont_address['email_address'], false, null, true, true);
            } else if ($cont_address['invalid_email']) {
                // Add an invalid email address
                $lead_sea->addAddress($cont_address['email_address'], false, null, true);
            } else if ($cont_address['opt_out']) {
                // Add an email address that should be marked opt-out
                $lead_sea->addAddress($cont_address['email_address'], false, null, false, true);
            } else
                $lead_sea->addAddress($cont_address['email_address'], false, null, false, false);

            $lead_sea->save($leadObj->id, "Leads");
        }

        /* if ($contactObj->load_relationship('documents')) {
          $leadObj->load_relationship("leads_documents_1");
          $relatedDocs = $contactObj->documents->getBeans();
          foreach ($relatedDocs as $doc) {
          $leadObj->leads_documents_1->add($doc->id);
          }
          } */

        $docTrackCollection = json_decode($args['docTrackCollection'], true);
        $manualDocTrackCollection = json_decode($args['manualDocTrackollection'], true);
        //$docTrackCheck = array();
        $pdfPath = json_decode($args['pdfPath'], true);
        $documentId = '';
        $message = translate('LBL_LEAD_CREATED_SUCCESS', 'Contacts');
        $level = 'success';

        $document_name = 'Kundenunterlagen.pdf';
        if (empty($pdfPath)) {
            $document_name = 'Fehlende Dokumente.pdf';
        }
        if (!empty($pdfPath) || !empty($docTrackCollection) || !empty($manualDocTrackCollection)) {
            // Creating Document
            $documentBean = BeanFactory::getBean('Documents', array('disable_row_level_security' => true));
            $documentBean->document_name = rtrim($document_name, '.pdf');

            if (!empty($pdfPath)) {
                $documentBean->converted = 1; // merged newly created Merged PDF as converted
            }

            $documentBean->save();

            // Saving multiple categories aganist documents.
            $documentId = $documentBean->id;
            if (!empty($pdfPath)) {
                $bean_DocumentRevision = BeanFactory::getBean('DocumentRevisions');
                $bean_DocumentRevision->document_id = $documentId;
                $bean_DocumentRevision->doc_type = 'Sugar';
                $bean_DocumentRevision->filename = $document_name;
                $bean_DocumentRevision->file_ext = 'pdf';
                $bean_DocumentRevision->file_mime_type = 'application/pdf';
                $bean_DocumentRevision->revision = '1';
                $bean_DocumentRevision->save();

                $documentBean->document_revision_id = $bean_DocumentRevision->id;
                $documentBean->rev_file_name = $document_name;
                $documentBean->save();
                $GLOBALS['log']->debug('Document Revision ID :: ' . $bean_DocumentRevision->id);
            }
            $GLOBALS['log']->debug('Document ID :: ' . $documentBean->id);
        }

        $upload_folder = $GLOBALS['sugar_config']['upload_dir'];
        if (!empty($pdfPath)) {
            $mergePDF = new PDFConverter();
            $dataReturn = $mergePDF->createLeadConversionPDFMerged($pdfPath, $upload_folder . '/' . $bean_DocumentRevision->id);
            if ($dataReturn['level'] == 'success') {
                $GLOBALS['log']->debug('PDF Created With Success For Lead Conversion :: ');
            } else {
                if (!empty($documentId)) {
                    $sql_doc_update = 'UPDATE documents SET deleted = 1 WHERE id ="' . $documentId . '" ';
                    $GLOBALS['db']->query($sql_doc_update);

                    $documentId = '';
                    $message = translate('LBL_LEAD_CONVERSION_ERROR', 'Contacts');
                    $level = 'error';
                }
                //return false;
            }
        }
        if ($leadObj->load_relationship("leads_documents_1") && !empty($documentId)) {
            $leadObj->leads_documents_1->add($documentId);

            $mergedDocTrackCollection = array();
            foreach ($docTrackCollection as $docTrack) {
                $mergedDocTrackCollection[] = $docTrack;
            }
            foreach ($manualDocTrackCollection as $manualTrack) {
                $mergedDocTrackCollection[] = $manualTrack;
            }

            $updatedMergedTrackCollection = array();

            // converting array to string in month field
            foreach ($mergedDocTrackCollection as $k1 => $catgyData) {
                if (is_array($catgyData['month'])) {
                    $catgyData['month'] = implode(',', $catgyData['month']);
                }
                $catgyData['month'] = str_replace('^', '', $catgyData['month']);
                $updatedMergedTrackCollection[] = $catgyData;
            }


            $mergedDocCategoires = array();
            foreach ($updatedMergedTrackCollection as $k => $arrData) {
                if (!isset($mergedDocCategoires[$arrData['category']])) {

                    $mergedDocCategoires[$arrData['category']] = array(
                        'status' => $arrData['status'],
                        'description' => (empty($arrData['description']) ? "" : $arrData['description']),
                        'month' => $arrData['month'],
                        'category' => $arrData['category'],
                    );
                } else {

                    if ($arrData['status'] == 'ok') {
                        $mergedDocCategoires[$arrData['category']]['status'] = $arrData['status'];
                    } else if ($arrData['status'] == 'nok' && $mergedDocCategoires[$arrData['category']]['status'] != 'ok') {
                        $mergedDocCategoires[$arrData['category']]['status'] = $arrData['status'];
                    } else if ($mergedDocCategoires[$arrData['category']]['status'] != 'ok' && $mergedDocCategoires[$arrData['category']]['status'] != 'nok') {
                        $mergedDocCategoires[$arrData['category']]['status'] = $arrData['status'];
                    }

                    $month1 = explode(',', $mergedDocCategoires[$arrData['category']]['month']);
                    $month2 = explode(',', $arrData['month']);

                    $merged_months = array_unique(array_merge($month1, $month2));
                    $mergedDocCategoires[$arrData['category']]['month'] = implode(",", $merged_months);

                    $description = $mergedDocCategoires[$arrData['category']]['description'] . "\r\n" . $arrData['description'];
                    $mergedDocCategoires[$arrData['category']]['description'] = $description;
                }
            }

            $GLOBALS['log']->debug('Mered Data :: ' . print_r($mergedDocCategoires, 1));

            foreach ($mergedDocCategoires as $docTrack) {

                $docTrackItemBean = BeanFactory::newBean('dotb7_document_tracking');
                $docTrackItemBean->new_with_id = true;
                $docTrackItemBean->name = rtrim($document_name, '.pdf');

                foreach ($docTrack as $key => $value) {
                    if ($key == "category") {
                        if (array_search($value, $GLOBALS['app_list_strings']['dotb_document_category_list'])) {
                            $category_key = array_search($value, $GLOBALS['app_list_strings']['dotb_document_category_list']);
                        } else {
                            $category_key = $value;
                        }
                        $docTrackItemBean->$key = $category_key;
                    } else if ($key == 'month') {
                        $docTrackItemBean->$key = $this->convertStringToMultiEnum($value);
                    } else if ($key != 'id') {
                        $docTrackItemBean->$key = $value;
                    }
                }

                $docTrackItemBean->save();

                if ($docTrackItemBean->load_relationship('documents_dotb7_document_tracking_1') && !empty($documentId)) {
                    $docTrackItemBean->documents_dotb7_document_tracking_1->add($documentId);
                }
            } // end of foreach
        }

        if ($contactObj->load_relationship('dotb5_credit_history_contacts')) {
            $leadObj->load_relationship("leads_dotb5_credit_history_1");
            $relatedCrHistory = $contactObj->dotb5_credit_history_contacts->getBeans();
            foreach ($relatedCrHistory as $CrHistory) {
                $leadObj->leads_dotb5_credit_history_1->add($CrHistory->id);
            }
        }

        /**
         *  Check if Contact is family relative of some Lead
         *  If true then relate converted Lead to relative lead
         *
          if ($contactObj->load_relationship("leads_contacts_1")) {
          $relatedRelatives = $contactObj->leads_contacts_1->getBeans();
          $relativeLead = false;
          if (!empty($relatedRelatives)) {
          reset($relatedRelatives);
          $relativeLead = current($relatedRelatives);
          $leadObj->load_relationship("leads_leads_1");
          $leadObj->leads_leads_1->add($relativeLead->id);
          }
          } */
        /**
         *  Copy Relatives to converted Lead
         */
        if ($contactObj->load_relationship('contacts_contacts_1')) {
            $leadObj->load_relationship("leads_contacts_1");
            $relatedRelatives = $contactObj->contacts_contacts_1->getBeans();
            foreach ($relatedRelatives as $relative) {
                $leadObj->leads_contacts_1->add($relative->id);
            }
        }

        /**
         *  Copy Addresses to converted Lead
         */
        if ($contactObj->load_relationship('contacts_dot10_addresses_1')) {
            $leadObj->load_relationship("leads_dot10_addresses_1");
            $relatedAddresses = $contactObj->contacts_dot10_addresses_1->getBeans();
            foreach ($relatedAddresses as $address) {
                $leadObj->leads_dot10_addresses_1->add($address->id);
            }
        }
        /**
         *  Copy Open Tasks to converted Lead
         */
        if ($contactObj->load_relationship('tasks')) {
            $relatedTasks = $contactObj->tasks->getBeans();
            foreach ($relatedTasks as $task) {
                if ($task->status != 'closed') {
                    $task->parent_type = 'Leads';
                    $task->parent_id = $leadObj->id;
                    $task->contact_id = '';
                    $task->save();
                    $leadObj->tasks->add($task->id);
                }
            }
        }
        $contactObj->save();
        //$GLOBALS['db']->query("update leads set ignore_update=0 where id='$leadObj->id'");
        //$GLOBALS['db']->query("update contacts set ignore_update=0 where id='$contactObj->id'");
        return array('level' => $level, 'message' => $message);
    }

    private function convertStringToMultiEnum($monthStr) {
        $months = explode(',', $monthStr);
        $updateMonths = array();
        foreach ($months as $key => $month) {
            if (!empty($month)) {
                $updateMonths[] = '^' . $month . '^';
            }
        }
        if (!empty($updateMonths)) {
            return implode(',', $updateMonths);
        } else {
            return "";
        }
    }

}
