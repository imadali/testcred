<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class CreateContact {

    function create($bean, $event, $arguments) {
        global $timedate;
        global $app_list_strings;
        global $current_user;
        $contactObj = null;
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
            'primary_resident_since_c' => 'dotb_resident_since',
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
            'credit_request_substatus_id_c' => 'credit_request_substatus_id_c',
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
            'primary_resident_till_c' => 'dotb_resident_till_c',
            'dotb_other_credits_c' => 'dotb_other_credits_c',
            'dotb_date_of_loss_c' => 'dotb_date_of_loss_c',
            'dotb_partner_agreement_c' => 'dotb_partner_agreement_c',
            'dotb_monthly_net_income_nb_c' => 'dotb_monthly_net_income_nb_c',
            'dotb_rent_alimony_income_c' => 'dotb_rent_alimony_income_c',
            'assigned_user_id' => 'assigned_user_id'
        );
        $skipFields = array("");
        /**
         *  If lead is moved to status 11_closed or 10_active then
         *  Copy related data to existing related contact 
         *  Or create new contact and copy all related data to new Contact
         * */
         if (($bean->credit_request_status_id_c == '10_active' && $bean->fetched_row['credit_request_status_id_c'] != '10_active') || ($bean->credit_request_status_id_c == '11_closed' && $bean->fetched_row['credit_request_status_id_c'] != '11_closed')) {
        //if ($bean->credit_request_status_id_c == '10_active' || $bean->credit_request_status_id_c == '11_closed') {
            if ($bean->load_relationship('contacts')) {
                $relatedBeans = $bean->contacts->getBeans();
                $parentContact = false;
                if (!empty($relatedBeans)) {
                    reset($relatedBeans);
                    $parentContact = current($relatedBeans);
                    if ($parentContact) {
                        $contactObj = $parentContact;
                    }
                }
                /**
                 * Contact Does not exist so create new contact
                 */ else {
                    $contactObj = BeanFactory::getBean("Contacts");
                }

                /**
                 *
                 * Now Copy all related modules data to existing contact or new contact
                 *
                 */
                if ($contactObj) {
                    /**
                     *  copy all fields from lead to contact, provided in 
                     *  Field mapping 
                     */
                    foreach ($contactFieldsMap as $key => $value) {
                        $contactObj->$value = $bean->$key;
                    }
                    if (empty($bean->assigned_user_id))
                        $contactObj->assigned_user_id = $current_user->id;
                    $contactObj->reference_number = $bean->reference_number_c;
                    $contactObj->processed = true;
                    $contactObj->save();
                    /**
                     *  Copy Email address to Contact
                     */
                    $sea = new SugarEmailAddress;
                    $primaryEmail = $sea->getPrimaryAddress($bean);
                    $sea->addAddress($primaryEmail, true);
                    $sea->save($contactObj->id, "Contacts");

                    /**
                     * Relate new contact with current Lead
                     */
                    $bean->load_relationship('contacts');
                    $bean->contacts->add($contactObj->id);

                    /**
                    * CRED-848 : Do not move the task in the following WFs: 
                    * 04 -> 11 - Antrag verzichten
                    * 05 -> 11 - Antrag verzichten
                    * 06 -> 11 - Antrag verzichten
                    * 07 -> 11 - Antrag verzichten
                    * 08 -> 11 - Antrag verzichten
                    * 09 -> 11 - Antrag verzichten
                    * In these cases we'll check status and substatus. If status is 11 and substatus is waiver and latest task name is Antrag verzichten. Then do not move that task               
                    */
                    $lead_latest_task_id = '';
                    if ($bean->credit_request_status_id_c == '11_closed' && $bean->credit_request_substatus_id_c == 'waiver') {
                        //get latest task
                        $latest_task_query = 'SELECT tasks.id, tasks.name FROM tasks WHERE tasks.parent_type = "Leads" AND tasks.parent_id="'.$bean->id.'" AND tasks.deleted=0 ORDER BY date_entered DESC LIMIT 0,1';
                        $latest_task_result = $GLOBALS['db']->query($latest_task_query);
                        $lead_latest_task = $GLOBALS['db']->fetchByAssoc($latest_task_result);
                        if ($lead_latest_task['name'] == 'Antrag verzichten'){
                            $lead_latest_task_id = $lead_latest_task['id'];
                        }	
                    }

                    //Copy All open tasks to contact
                    if ($bean->load_relationship("tasks")) {
                        $contactObj->load_relationship('tasks');
                        $relatedTasks = $bean->tasks->getBeans();
                        foreach ($relatedTasks as $task) {
                            if ($task->status != 'closed' && $task->id != $lead_latest_task_id) {
                                /**
                                 *  As Tasks can have only one parent so
                                 *  make duplicate of record
                                 */
                                //unset($task->id); 
                                $task->parent_type = 'Contacts';
                                $task->parent_module = 'Contacts';
                                $task->parent_id = $contactObj->id;
                                $task->contact_id = $contactObj->id;
                                $task->processed = true;
                                $task->save();
                                $contactObj->tasks->add($task->id);
                            }
                        }
                    }

                    /**
                     * Copy Notes to Contact
                     *
                      if ($bean->load_relationship("notes")) {
                      $contactObj->load_relationship('notes');
                      $relatedNotes = $bean->notes->getBeans();
                      foreach ($relatedNotes as $note) {
                      /**
                     *  As Notes can have only one parent so
                     *  make duplicate of record
                     *
                      //unset($note->id);
                      $note->parent_type = 'Contacts';
                      $note->parent_id = $contactObj->id;
                      $note->contact_id = $contactObj->id;
                      $note->processed = true;
                      $note->save();
                      $contactObj->notes->add($note->id);
                      }
                      }

                      /**
                     * Copy Archived Emails to Contact
                     *
                      if ($bean->load_relationship("emails")) {
                      $contactObj->load_relationship('emails');
                      $relatedEmails = $bean->emails->getBeans();
                      foreach ($relatedEmails as $email) {
                     * *
                     *  As Emails can have only one parent so
                     *  make duplicate of record
                     * *
                      unset($email->id);
                      $email->parent_type = 'Contacts';
                      $email->parent_id = $contactObj->id;
                      $email->save();
                      }
                      } */
                    if ($bean->load_relationship("leads_documents_1")) {
                        $relatedDocs = $bean->leads_documents_1->getBeans();
                        $contactObj->load_relationship('documents');
                        foreach ($relatedDocs as $doc) {
                            $contactObj->documents->add($doc->id);
                        }
                        /*
                          // copy of document is being created
                          $contactObj->load_relationship('documents');
                          $relatedDocs = $bean->leads_documents_1->getBeans();

                          require_once('include/upload_file.php');
                          //unlink existing documents
                          $contactDocs = $contactObj->documents->getBeans();
                          foreach ($contactDocs as $contactDoc) {
                          $contactObj->documents->delete($contactDoc->id);
                          }


                          $relatedDocs = $bean->leads_documents_1->getBeans();
                          require_once('include/upload_file.php');
                          foreach ($relatedDocs as $doc) {
                          //load doc tracking records linked to this document
                          $doc->load_relationship('documents_dotb7_document_tracking_1');
                          $document_tracking = $doc->documents_dotb7_document_tracking_1->getBeans();

                          unset($doc->id);
                          $doc->save();
                          $old_revision_id = $doc->document_revision_id;
                          $old_revision = new DocumentRevision();
                          $old_revision->retrieve($old_revision_id);

                          //$contents = file_get_contents($doc->filename);
                          $revision = new DocumentRevision;
                          $revision->document_id = $doc->id;
                          //$revision->file = base64_encode($contents);
                          $revision->filename = $old_revision->filename;
                          $revision->revision = 1;
                          $revision->doc_type = $old_revision->doc_type;
                          $revision->file_mime_type = $old_revision->file_mime_type;

                          $revision->save();
                          if (is_file("upload/$old_revision_id")) {
                          copy("upload/$old_revision_id", "upload/$revision->id");
                          }
                          $doc->document_revision_id = $revision->id;
                          // $doc->category_id = $document_tracking_category;
                          $doc->save();
                          // if(!empty($document_tracking_id)){
                          // $doc->load_relationship('documents_dotb7_document_tracking_1');
                          // $doc->documents_dotb7_document_tracking_1->add($document_tracking_id);
                          // }

                          //create doc tracking records and link to new document record
                          foreach ($document_tracking as $id => $document_tracking_bean) {
                          //create doc tracking record
                          $doc_tracking_bean = BeanFactory::getBean("dotb7_document_tracking");
                          $doc_tracking_bean->name = $document_tracking_bean->name;
                          $doc_tracking_bean->status = $document_tracking_bean->status;
                          $doc_tracking_bean->description = $document_tracking_bean->description;
                          $doc_tracking_bean->category = $document_tracking_bean->category;
                          $doc_tracking_bean->documents_checked = $document_tracking_bean->documents_checked;
                          $doc_tracking_bean->documents_recieved = $document_tracking_bean->documents_recieved;
                          $doc_tracking_bean->save();

                          $doc->load_relationship('documents_dotb7_document_tracking_1');
                          $doc->documents_dotb7_document_tracking_1->add($doc_tracking_bean->id);
                          }

                          $contactObj->documents->add($doc->id);
                          } */
                    }
                    if ($bean->load_relationship("leads_dotb5_credit_history_1")) {
                        $contactObj->load_relationship('dotb5_credit_history_contacts');
                        $relatedCrHistory = $bean->leads_dotb5_credit_history_1->getBeans();
                        foreach ($relatedCrHistory as $CrHistory) {
                            $contactObj->dotb5_credit_history_contacts->add($CrHistory->id);
                        }
                    }
                    if ($bean->load_relationship("leads_contacts_1")) {
                        $contactObj->load_relationship('contacts_contacts_1');
                        $relatedRelatives = $bean->leads_contacts_1->getBeans();
                        foreach ($relatedRelatives as $relative) {
                            $contactObj->contacts_contacts_1->add($relative->id);
                        }
                    }

                    if ($bean->load_relationship('contracts_leads_1')) {
                        $contactObj->load_relationship('contracts');
                        $relatedContracts = $bean->contracts_leads_1->getBeans();
                        foreach ($relatedContracts as $contract) {
                            $contactObj->contracts->add($contract->id);
                        }
                    }

                    //copy applications to contact
                    if ($bean->load_relationship('leads_opportunities_1')) {
                        $contactObj->load_relationship('opportunities');
                        $relatedApplications = $bean->leads_opportunities_1->getBeans();
                        foreach ($relatedApplications as $application) {
                            $contactObj->opportunities->add($application->id);
                        }
                    }

                    /**
                     *  Copy Addresses to converted Contact
                     */
                    if ($bean->load_relationship('leads_dot10_addresses_1')) {
                        $contactObj->load_relationship("contacts_dot10_addresses_1");
                        $relatedAddresses = $bean->leads_dot10_addresses_1->getBeans();
                        foreach ($relatedAddresses as $address) {
                            $contactObj->contacts_dot10_addresses_1->add($address->id);
                        }
                    }

                    /*
                     * Seting Reasoning in the all related leads,
                     */
                    $leads = $contactObj->get_linked_beans('leads', 'Lead');
                    foreach ($leads as $lead) {
                        if ($lead->credit_request_status_id_c != '10_active' && $lead->credit_request_status_id_c != '11_closed' && $lead->credit_request_status_id_c != '00_pendent_geschlossen' && $lead->credit_request_status_id_c != '00_pendent_geschlossen') {
                            $GLOBALS['db']->query("UPDATE leads_cstm SET credit_request_substatus_id_c='$bean->credit_request_substatus_id_c' WHERE id_c='$lead->id'");
                            insertRecordInAuditTable('Leads', 'credit_request_substatus_id_c', $lead->credit_request_status_id_c, $bean->credit_request_substatus_id_c, $lead->id, 'enum');
                        }
                    }
                }
            }
            
            /**
             * CRED-978 : Updating field in credit status on promotion
             * of lead to Status 10
             * CRED-1013 : Sync SugarCRM - Evalanche - DropDown-Handling (Populate provider_evalanche field)
             */
            if ($bean->credit_request_status_id_c == '10_active') {
                $contractBean = BeanFactory::getBean('Contracts', $bean->contracts_leads_1contracts_ida);
                $contact_id = $bean->contact_id;
                $contactBean = BeanFactory::getBean('Contacts', $contact_id);
                $contactBean->provider_evalanche = $app_list_strings['dotb_credit_provider_list'][$contractBean->provider_id_c];
                $contactBean->credit_amount = $contractBean->credit_amount_c;
                $contactBean->duration = $contractBean->credit_duration_c;
                $contactBean->provider_contract_number = $contractBean->provider_contract_no;
                $contactBean->profile_id = $contact_id;
                $contactBean->processed = true;
                $contactBean->save();
            }
        }
    }

}
