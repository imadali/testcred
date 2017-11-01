<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class GenerateBriefingPdfApi extends SugarApi
{
    /**
     * API Enpoint registry
     * 
     * @return array
     */
    public function registerApiRest()
    {
        return array(
            'GenerateBriefingPdf' => array(
                'reqType' => 'POST',
                'path' => array('Leads', 'generateBriefingPdf', '?'),
                'pathVars' => array('', '', 'pdfName'),
                'method' => 'generateBriefingPdf',
                'shortHelp' => 'This api will fill a pdf with informations given in model',
                'longHelp' => '',
            ),
            'createRemindartaskForCRIFEmail' => array(
                'reqType' => 'POST',
                'path' => array('Leads', 'remindarTaskForCRIF', '?'),
                'pathVars' => array('', '', 'id'),
                'method' => 'remindarTaskForCRIF',
                'shortHelp' => 'This api will create Task for Leads',
                'longHelp' => '',
            )
        );
    }
    
     /**
     * Creates a reminder Task for CRIF
     * 
     * @param object $api  The API Object
     * @param array  $args The Arguments Array
     * 
     * @return true
     */
    public function remindarTaskForCRIF($api, $args)
    {
        $this->requireArgs($args, array('id'));

        global $timedate, $current_user;

        $currentUser = $current_user->id;
        $beanTask = BeanFactory::getBean($args['module_create'], array('disable_row_level_security' => true));

        /**
         * CRED-1047 : Reception CRIF-Check (PDF)
         * CRED-765 : Add Approval team
         */
        $dueDate = new DateTime($timedate->getNow());
        $dueDate->add(new DateInterval('P1D'));

        /**
         * CRED-953 : Global and Lead's Team not being set for CRIF task
         */
        $parent = BeanFactory::getBean($args['module'], $args['id']);

        $beanTask->name = 'Info DV an Bank';
        $beanTask->date_due = $timedate->asUser($dueDate, $current_user);
        $beanTask->parent_id = $args['id'];
        $beanTask->parent_type = $args['module'];
        $beanTask->parent_module = $args['module'];
        $beanTask->assigned_user_id = $currentUser;

        $beanTask->save();

        /**
         * CRED-765 : Add Approval team
         */
        include_once 'modules/Teams/TeamSet.php';
        $teamSetBean = new TeamSet();
        $team_id = array();
        $teams = $teamSetBean->getTeams($parent->team_set_id);
        foreach ($teams as $key => $team) {
            $team_id[] = $key;
        }

        $team = new Team();
        if ($team->retrieve_by_string_fields(array('name' => 'Approval'))) {
            $team_id[] = $team->id;
        }

        /*
         * CRED-953 : Global and Lead's Team not being set for CRIF task
         * 
         * Adding Global team in All activities
         */
        $team_id[] = '1';

        $beanTask->load_relationship('teams');
        $beanTask->teams->replace($team_id);
        return true;
    }

    /**
     * CRED-698 : Redesign Application Forms for Banks
     * 
     * @param object $api  The API Object
     * @param array  $args The Arguments Array
     * 
     * @return true
     */
    public function generateBriefingPdf($api, $args)
    {
        global $app_list_strings;
        $saldo = array();
        $fremdbank = array();
        $children_years = array();
        $lead_id = $args['lead_id'];
        $bank_name = $args['pdfName'];
        if ($bank_name == 'bob') {
            $pdf_name = "Application_Form_Bob";
        } else if ($bank_name == 'eny_finance') {
            $pdf_name = "Application_Form_EnyFinance";
        } else if ($bank_name == 'bank_now_casa') {
            $pdf_name = "Application_Form_Bank_now_Casa";
        } else if ($bank_name == 'bank_now_car') {
           /**
            * CRED-689 : Redesign Application Forms for Banks
            * New PDF with category Bank Now Car
            */
            $pdf_name = "Application_Form_Bank_now_Car";
        }

        if (!is_dir("./dotb_pdf_generation/models") || !file_exists("./dotb_pdf_generation/models/application-form.pdf") || !file_exists("./dotb_pdf_generation/models/application-form.mapping.php")
        ) {
            throw new SugarApiExceptionNotFound('pdf model or mapping file not found');
        }
        $tempDir = "./cache/pdftk";
        if (!is_dir($tempDir)) {
            mkdir($tempDir);
        }
        $documentDir = "./dotb_pdf_generation/documents";
        if (!is_dir($documentDir)) {
            mkdir($documentDir);
        }

        $emptyFile = "./dotb_pdf_generation/models/application-form.pdf";

        $dataFileName = "{$tempDir}/$pdf_name.xfdf";

        include "./dotb_pdf_generation/models/application-form.mapping.php";
        if (!isset($fieldMap) || !is_array($fieldMap)) {
            throw new SugarApiException("Field map not correctly initialized");
        }

        // Step 2 : create a xfdf file containing all values to include in pdf file
        $dataFile = fopen($dataFileName, "w+");

        $xfdfHeader = "<?xml version='1.0' encoding='UTF-8'?>\n" .
                "<xfdf xmlns='http://ns.adobe.com/xfdf/' xml:space='preserve'>\n" .
                "  <fields>\n";
        fwrite($dataFile, $xfdfHeader);
        $leadObj = BeanFactory::getBean("Leads", $lead_id);

        /**
         * Fetching Related Latest Address
         */
        $lead_addresses = array();
        $leadObj->load_relationship("leads_dot10_addresses_1");
        $relatedAddresses = $leadObj->leads_dot10_addresses_1->getBeans();
        $resident_since = '';
        $count = 0;
        foreach ($relatedAddresses as $id => $address) {
            if ($count == 0) {
                $lead_addresses['primary_address_street'] = $address->primary_address_street;
                $lead_addresses['primary_address_postalcode'] = $address->primary_address_postalcode;
                $lead_addresses['primary_address_city'] = $address->primary_address_city;
                $lead_addresses['dotb_resident_till_c'] = $address->dotb_resident_till_c;
                $resident_since = $address->dotb_resident_since_c;
            } else if (strtotime(str_replace('/', '-', $address->dotb_resident_since_c)) > strtotime(str_replace('/', '-', $resident_since))) {
                $lead_addresses['primary_address_street'] = $address->primary_address_street;
                $lead_addresses['primary_address_postalcode'] = $address->primary_address_postalcode;
                $lead_addresses['primary_address_city'] = $address->primary_address_city;
                $lead_addresses['dotb_resident_till_c'] = $address->dotb_resident_till_c;
                $resident_since = $address->dotb_resident_since_c;
            }
            $count++;
        }
        
        /**
         * Fetching Related Application
         */
        $app = array();
        $leadObj->load_relationship("leads_opportunities_1");
        $relatedApplications = $leadObj->leads_opportunities_1->getBeans();
        $count = 0;
        $date_entered = '';
        foreach ($relatedApplications as $application) {
            if ($bank_name == $application->provider_id_c) {
                $copy = false;
                if ($count == 0) {
                    $copy = true;
                    $date_entered = $application->date_entered;
                } else if (strtotime($application->date_entered) > strtotime($date_entered)) {
                    $copy = true;
                    $date_entered = $application->date_entered;
                }
                if ($copy) {
                    $app['credit_amount_c'] = $application->credit_amount_c;
                    $app['credit_duration_c'] = $application->credit_duration_c;
                    $app['interest_rate_c'] = $application->interest_rate_c;
                    if ($application->ppi_c) {
                        $app['ppi_c'] = 'Yes';
                    }
                    if ($application->transfer_fee) {
                        $app['transfer_fee_yes'] = 'Yes';
                        $app['applied_saldo'] = $application->applied_saldo;
                        $app['applied_name_fremdbank'] = $application->applied_name_fremdbank;
                    } else {
                        $app['transfer_fee_no'] = 'Yes';
                    }
                }
                $count++;
            }
        }
       
        /**
         * Fetching Linked Partner
         */
        $lead_partner = '';
        $leadObj->load_relationship("leads_contacts_1");
        $relatedPartner = $leadObj->leads_contacts_1->getBeans();
        if (!empty($relatedPartner)) {
            reset($relatedPartner);        
            $lead_partner = current($relatedPartner);
        }

        /**
         * CRED-948: Empty fields for PDF
         */
        $this->emptyDependentFields($leadObj);

        /**
         * Formating Data to Populate PDF
         */
        foreach ($fieldMap as $pdfFieldName => $fieldDescription) {
            $xfdfFieldText = "<field name='" . $fieldDescription['pdf_name'] . "'><value>";
            $model_name = $fieldDescription['model_name'];
            if ($fieldDescription['type'] == 'text') {
                if ((is_numeric($leadObj->$model_name) && $model_name != 'phone_mobile' && $model_name!='phone_other' 
                    && $model_name != 'phone_work' && $model_name!='primary_address_postalcode' && $model_name != 'no_of_dependent_children_c'
                    && $model_name != 'dotb_employer_npa_c' && $model_name != 'dotb_second_job_employer_npa_c' && $model_name != 'correspondence_address_postalcode' )
                    || ($model_name == 'dotb_mortgage_amount_c' && $model_name == 'dotb_current_enforcement_amo_c')
                ) {
                    $xfdfFieldText .= number_format($leadObj->$model_name, 2, '.', '');
                } elseif ($model_name == 'no_of_dependent_children_c' || $model_name == 'dotb_employer_npa_c' || $model_name == 'dotb_second_job_employer_npa_c') {
                    if ($leadObj->$model_name != 0) {
                        $xfdfFieldText .= round($leadObj->$model_name);
                    }
                } else {
                    $xfdfFieldText .= $leadObj->$model_name;
                }
            } else if ($fieldDescription['type'] == 'old-address') {
                if (!empty($lead_addresses)) {
                    $xfdfFieldText .= $lead_addresses[$model_name];
                }
            } else if ($fieldDescription['type'] == 'enum' && isset($app_list_strings[$fieldDescription['options']][$leadObj->$model_name])) {
                $xfdfFieldText .= $app_list_strings[$fieldDescription['options']][$leadObj->$model_name];
            } else if ($fieldDescription['type'] == 'partner') {
                if (!empty($lead_partner)) {
                    if (isset($fieldDescription['enum'])) {
                        $xfdfFieldText .= $app_list_strings[$fieldDescription['options']][$lead_partner->$model_name];
                    } elseif (isset($fieldDescription['radio'])) {
                        $value = '';
                        if (isset($fieldDescription['values_map'][$lead_partner->{$fieldDescription['model_name']}])) {
                            $value = $fieldDescription['values_map'][$lead_partner->{$fieldDescription['model_name']}];
                        }
                        $xfdfFieldText .= $value;
                    } else {
                        if (is_numeric($lead_partner->$model_name) && $model_name != 'dotb_employer_npa' && $model_name != 'dotb_second_job_employer_npa' ) {
                            $xfdfFieldText .= number_format($lead_partner->$model_name, 2, '.', '');
                        } else if( $model_name == 'dotb_employer_npa' || $model_name == 'dotb_second_job_employer_npa' ) {
                            if ($lead_partner->$model_name != 0) {
                                $xfdfFieldText .= round($lead_partner->$model_name);
                            }
                        } else {
                             $xfdfFieldText .= $lead_partner->$model_name;
                        }
                    }
                }
            } else if ($fieldDescription['type'] == 'app' || $fieldDescription['type'] == 'app-enum') {
                if (isset($app[$model_name])) {
                    $xfdfFieldText .= $app[$model_name];
                }
            } else if ($fieldDescription['type'] == 'text-children') {
                $children_years = $leadObj->children_birth_years_c;
                $children_years = explode('-*#*-', $children_years);
                if ($model_name == 'children_birth_years_1' && isset($children_years[0])) {
                    $xfdfFieldText .= $children_years[0];
                } elseif ($model_name == 'children_birth_years_2' && isset($children_years[1])) {
                    $xfdfFieldText .= $children_years[1];
                } elseif ($model_name == 'children_birth_years_3' && isset($children_years[2])) {
                    $xfdfFieldText .= $children_years[2];
                } elseif ($model_name == 'children_birth_years_4' && isset($children_years[3])) {
                    $xfdfFieldText .= $children_years[3];
                }
                
            } else if ($fieldDescription['type'] == 'text-saldo' && !empty($app['applied_saldo'])) {
                $saldo = explode(' , ', $app['applied_saldo']);
                if ($model_name == 'applied_saldo1' && isset($saldo[0])) {
                    $xfdfFieldText .= $saldo[0];
                } elseif ($model_name == 'applied_saldo2' && isset($saldo[1])) {
                    $xfdfFieldText .= $saldo[1];
                } elseif ($model_name == 'applied_saldo3' && isset($saldo[2])) {
                    $xfdfFieldText .= $saldo[2];
                } elseif ($model_name == 'applied_saldo4' && isset($saldo[3])) {
                    $xfdfFieldText .= $saldo[3];
                }
            } else if ($fieldDescription['type'] == 'text-fremdbank' && !empty($app['applied_name_fremdbank'])) {
                $fremdbank = explode(' , ', $app['applied_name_fremdbank']);
                if ($model_name == 'applied_name_fremdbank1' && isset($fremdbank[0])) {
                    $xfdfFieldText .= $fremdbank[0];
                } elseif ($model_name == 'applied_name_fremdbank2' && isset($fremdbank[1])) {
                    $xfdfFieldText .= $fremdbank[1];
                } elseif ($model_name == 'applied_name_fremdbank3' && isset($fremdbank[2])) {
                    $xfdfFieldText .= $fremdbank[2];
                } elseif ($model_name == 'applied_name_fremdbank4' && isset($fremdbank[3])) {
                    $xfdfFieldText .= $fremdbank[3];
                }
            } else if ($fieldDescription['type'] == 'radio_button') {
                $value = '';
                if (isset($fieldDescription['values_map'][$leadObj->{$fieldDescription['model_name']}])) {
                    $value = $fieldDescription['values_map'][$leadObj->{$fieldDescription['model_name']}];
                }
                $xfdfFieldText .= $value;
            } else if ($fieldDescription['type'] == 'fixed_value') {
                /**
                * CRED-926 : Fixed value for field
                */
                $xfdfFieldText .= $fieldDescription['value'];
            }

            $xfdfFieldText .= "</value></field>\n";
            fwrite($dataFile, $xfdfFieldText);
        }
        
        $xfdfFooter = "  </fields>\n</xfdf>";
        fwrite($dataFile, $xfdfFooter);

        fclose($dataFile);
        
        $document = BeanFactory::getBean('Documents');
        $document->id = create_guid();
        $document->new_with_id = true;
        $doc_name=  str_replace("_", " ", $pdf_name);
        $document->name = "$doc_name.pdf";
        $document->rev_file_name = "$doc_name.pdf";
        
        $previousRevision = BeanFactory::getBean('DocumentRevisions', $document->document_revision_id);
        $revision = BeanFactory::getBean('DocumentRevisions');
        $revision->id = create_guid();
        $revision->new_with_id = true;
        $revision->document_id = $document->id;
        $revision->doc_type = 'Sugar';
        $revision->filename = $document->name;
        $revision->file_ext = 'pdf';
        $revision->file_mime_type = 'application/pdf';
        $revision->revision = ++$previousRevision->revision;
        $document->document_revision_id = $revision->id;
        $revision->save();
        $document->leads_documents_1leads_ida = $leadObj->id;
        $document->save();
        $leadObj->load_relationship("leads_documents_1");
        $leadObj->leads_documents_1->add($document->id);
        
        // Step 3 : use pdftk library to fill the pdf form
        exec("pdftk {$emptyFile} fill_form {$dataFileName} output upload/$revision->id");
        unlink($dataFileName);
        
        return true;
    }

    /**
     * CRED-948: empty fields
     */
    private function emptyDependentFields(&$leadBean) {
        if ($leadBean->dotb_is_home_owner_c == 'yes') {
            $leadBean->dotb_housing_costs_rent_c = "";
        } else {
            $leadBean->dotb_mortgage_amount_c =  "";
        }

        if ($leadBean->dotb_has_enforcements_c != "yes") {
            $leadBean->dotb_current_enforcement_num_c =  "";
           $leadBean->dotb_current_enforcement_amo_c =  "";
        }
        if ($leadBean->dotb_past_enforcements_c != "yes") {
            $leadBean->dotb_past_enforcement_number_c =  "";
            $leadBean->dotb_past_enforcement_amount_c =  "";
        }
        if ($leadBean->dotb_iso_nationality_code_c == "ch") {
            $leadBean->dotb_work_permit_type_id_c =  "";
            $leadBean->dotb_work_permit_since_c =  "";
            $leadBean->dotb_work_permit_until_c = "";
        }

        if ($leadBean->dotb_employment_type_id_c  == "permanent_contract") {
           $leadBean->dotb_employed_until_c =  "";
        }

        if ($leadBean->dotb_employment_type_id_c == "fixed_term" || $leadBean->dotb_employment_type_id_c == "temporary_contract") {
            $leadBean->dotb_has_thirteenth_salary_c =  "";
            $leadBean->dotb_monthly_gross_income_c =  "";
        }

        if ($leadBean->dotb_employment_type_id_c == "self_employed") {
           $leadBean->dotb_has_thirteenth_salary_c =  "";
           $leadBean->dotb_monthly_gross_income_c =  "";
           $leadBean->dotb_employed_until_c =  "";
        }

        if ($leadBean->dotb_employment_type_id_c == "disabled_gets_pension" || $leadBean->dotb_employment_type_id_c == "retirement") {
            $leadBean->dotb_has_thirteenth_salary_c =  "";
            $leadBean->dotb_monthly_gross_income_c =  "";
            $leadBean->dotb_employed_since_c =  "";
            $leadBean->dotb_employed_until_c = "";
        }

        if ($leadBean->dotb_has_second_job_c != "yes") {
            $leadBean->dotb_second_job_description_c = "";
            $leadBean->dotb_second_job_employer_npa_c = "";
            $leadBean->dotb_second_job_gross_income_c =  "";
            $leadBean->dotb_second_job_since_c =  "";
            $leadBean->dot_second_job_employer_name_c =  "";
            $leadBean->dot_second_job_employer_town_c =  "";
            $leadBean->dotb_second_job_has_13th_c = "";
            $leadBean->dotb_monthly_net_income_nb_c = "";
            $leadBean->dotb_sideline_bonus_gratuity_c =  "";
            $leadBean->sideline_hired_since_c = "";
        }
        return true;
    }

}
