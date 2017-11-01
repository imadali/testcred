<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once ('include/TimeDate.php');

class addContact {

    protected static $fetchedRow = array();

    /**
     * Called as before_save logic hook to grab the fetched_row values
     */
    public function add($bean, $event, $arguments) {
        if ($bean->create_contact) {
            global $timedate;
            global $current_user;
            $contactFieldsMap = array(
                'description' => 'description',
                'salutation' => 'salutation',
                'first_name' => 'first_name',
                'last_name' => 'last_name',
                'title' => 'title',
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
                'dotb_leasing_expenses_c' => 'dotb_leasing_expenses',
                'dotb_education_costs_child_c' => 'dotb_education_costs_for_children',
                'dotb_had_past_credit_c' => 'dotb_had_credit_denial_in_last_6_months',
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
                'dotb_second_job_employer_npa_c' => 'dotb_second_job_employer_name',
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
                'dotb_resident_since_c' => 'dotb_resident_since',
                'dotb_work_expenses_c' => 'dotb_work_expenses',
                'dot_second_job_emp_number_c' => 'dot_second_job_emp_number_c',
                'dotb_is_pensioner_c' => 'dotb_is_pensioner',
                'dotb_unable_to_work_reason_c' => 'dotb_unable_to_work_reason',
                'dotb_additional_income' => 'dotb_additional_income',
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
                'dotb_date_of_loss_c' => 'dotb_date_of_loss_c',
                'dotb_partner_agreement_c' => 'dotb_partner_agreement_c',
                'dotb_monthly_net_income_nb_c' => 'dotb_monthly_net_income_nb_c',
                'dotb_rent_alimony_income_c' => 'dotb_rent_alimony_income_c',
                'assigned_user_id'=>'assigned_user_id'
            );
            $skipFields = array("");
            $leadObj = BeanFactory::getBean("Leads", $bean->contracts_leads_1leads_idb);
            $contactObj = BeanFactory::getBean("Contacts");

            /**
             *  Check for duplicate contacts
             */
            $contactDup = new Contact();
            $birthdate = new DateTime($leadObj->birthdate);
            $birthdate = $timedate->asDbDate($birthdate);
            if ($contactDup->retrieve_by_string_fields(array('last_name' => $leadObj->last_name, 'birthdate' => $birthdate))) {
                if (!empty($bean->id)) {
                    $contactDup->load_relationship('contacts_contracts_1');
                    $contactDup->contacts_contracts_1->add($bean->id);

                    $contactDup->load_relationship('contracts');
                    $contactDup->contracts->add($bean->id);
                }
                return true;
            }

            /**
             *  copy all fields from lead to contact, provided in 
             *  Field mapping 
             */
            foreach ($contactFieldsMap as $key => $value) {
                $contactObj->$value = $leadObj->$key;
            }
            $contactObj->assigned_user_id = $current_user->id;
            $contactObj->save();
            $contactObj->load_relationship('contracts');
            $contactObj->contracts->add($bean->id);
            $contactObj->load_relationship('contacts_contracts_1');
            $contactObj->contacts_contracts_1->add($bean->id);
            /**
             *  Copy Email address to Contact
             */
            $sea = new SugarEmailAddress;
            $primaryEmail = $sea->getPrimaryAddress($leadObj);
            $sea->addAddress($primaryEmail, true);
            $sea->save($contactObj->id, "Contacts");
            /**
             *
             */
            $leadObj->load_relationship('contacts');
            $leadObj->contacts->add($contactObj->id);
            $leadObj->save();
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
            if (!empty($bean->id)) {
                $contactObj->load_relationship('contacts_contracts_1');
                $contactObj->contacts_contracts_1->add($bean->id);
            }
            /**
             *  Copy Addresses to converted Contact
             */
            if ($leadObj->load_relationship('leads_dot10_addresses_1')) {
                $contactObj->load_relationship("contacts_dot10_addresses_1");
                $relatedAddresses = $leadObj->leads_dot10_addresses_1->getBeans();
                foreach ($relatedAddresses as $address) {
                    $contactObj->contacts_dot10_addresses_1->add($address->id);
                }
            }
            $bean->create_contact = false;
            $bean->save();
            $sql = "UPDATE leads_cstm SET contract_created_c=1 WHERE id_c='$leadObj->id'";
            $GLOBALS['db']->query($sql);
        }
    }

}
