<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class generateBriefingPdfApi extends SugarApi {

    /**
     *
     */
    public function registerApiRest() {
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
    
    public function remindarTaskForCRIF($api, $args) {
        $this->requireArgs($args, array('id'));
        
        global $timedate, $current_user;
        
        $currentUser = $current_user->id;
        $beanTask = BeanFactory::getBean($args['module_create'], array('disable_row_level_security' => true));
        
        $beanTask->name  = 'Info DV an Bank';
        $beanTask->date_due =  $timedate->asUser($timedate->getNow(), $current_user);
        $beanTask->parent_id = $args['id'];
        $beanTask->parent_type = $args['module'];
        $beanTask->assigned_user_id = $currentUser;
        
        $beanTask->save();
        return true;
    }

    /**
     * need
     *
     * @param SugarApi $api
     * @param array $args
     */
    public function generateBriefingPdf($api, $args) {
        global $app_list_strings;
        $lead_id = $args['lead_id'];
        $bank_name = $args['pdfName'];
        if ($bank_name == 'bob') {
            $pdf_name = "Application_Form_Bob";
        } else if ($bank_name == 'eny_finance') {
            $pdf_name = "Application_Form_EnyFinance";
        } else if ($bank_name == 'bank_now_casa') {
            $pdf_name = "Application_Form_Bank_now_Casa";
        }
        //$GLOBALS['log']->fatal("Lead Id: $lead_id, pdf name: $pdf_name");

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
        //$GLOBALS['log']->fatal("Lead Naem: " . $leadObj->first_name);

        $lead_addresses = array();
        $counter = 2;
        $leadObj->load_relationship("leads_dot10_addresses_1");
        $relatedAddresses = $leadObj->leads_dot10_addresses_1->getBeans();
        foreach ($relatedAddresses as $address) {
            if(!$address->current_address_c){
            $lead_addresses["primary_address_street_$counter"] = $address->primary_address_street;
            $lead_addresses["address_c_o_$counter"] = $address->address_c_o;
            $lead_addresses["primary_address_postalcode_$counter"] = $address->primary_address_postalcode;  //PLZ
            $lead_addresses["primary_address_city_$counter"] = $address->primary_address_city; //Ort
            $lead_addresses["primary_address_country_$counter"] = $address->primary_address_country;
            $lead_addresses["dotb_resident_till_c_$counter"] = $address->dotb_resident_till_c; //Wohnhaft bis
            $lead_addresses["dotb_resident_since_c_$counter"] = $address->dotb_resident_since_c; //Wohnhaft seit
            $counter++;
            }
        }
        //applications
        $app = array();
        $leadObj->load_relationship("leads_opportunities_1");
        $relatedApplications = $leadObj->leads_opportunities_1->getBeans();
        $count = 0;
        $date_entered = '';
        foreach ($relatedApplications as $application) {
            //$GLOBALS['log']->fatal("Bank Rq: " . $bank_name);
            //$GLOBALS['log']->fatal("Bank Ap: " . $application->provider_id_c);
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
                    $app['ppi_c'] = $application->ppi_c;
                    //$app['provider_id_c']=  $application->provider_id_c;    
                    $app['provider_id_c'] = $app_list_strings['dotb_credit_provider_list'][$application->provider_id_c];
					if($application->transfer_fee)
						$app['transfer_fee'] = $app_list_strings['lq_yes_no_status_list']['yes'];
					else
						$app['transfer_fee'] = $app_list_strings['lq_yes_no_status_list']['no'];
                }
                $count++;
            }
        }
        $credit_histories = array();
        $counter = 0;
        $leadObj->load_relationship("leads_dotb5_credit_history_1");
        $relatedHistories = $leadObj->leads_dotb5_credit_history_1->getBeans();
        
        $credit_histories['credit_balance'] = '';
        $credit_histories['monthly_credit_rate'] = '';
        
        foreach ($relatedHistories as $relatedHistory) {
            if (!empty($relatedHistory->credit_balance))
                $credit_histories['credit_balance'] .= number_format($relatedHistory->credit_balance, 2, '.', '') . ',';
            if (!empty($relatedHistory->monthly_credit_rate))
                $credit_histories['monthly_credit_rate'] .= number_format($relatedHistory->monthly_credit_rate, 2, '.', '') . ',';
        }

        //linked partner
        $lead_partner = '';
        $leadObj->load_relationship("leads_contacts_1");
        $relatedPartner = $leadObj->leads_contacts_1->getBeans();
        if (!empty($relatedPartner))
        {
            reset($relatedPartner);        
            $lead_partner = current($relatedPartner);
        }
        /*foreach ($relatedPartner as $partner) {
            if ($partner->relative_type_c == "partner") {
                $lead_partner = $partner;
                break;
            }
        }*/

        foreach ($fieldMap as $pdfFieldName => $fieldDescription) {
            $xfdfFieldText = "<field name='" . $fieldDescription['pdf_name'] . "'><value>";
            $model_name = $fieldDescription['model_name'];
            if ($fieldDescription['type'] == 'text') {
                if (is_numeric($leadObj->$model_name) && $model_name != 'phone_mobile' && $model_name!='phone_other' && $model_name != 'phone_work' && $model_name != 'dotb_age_c' && $model_name!='primary_address_postalcode' && $model_name != 'credit_request_number_c' && $model_name != 'no_of_dependent_children_c' && $model_name != 'dotb_employer_npa_c' && $model_name != 'dotb_second_job_employer_npa_c' && $model_name != 'dotb_bank_zip_code_c' ) {
                    $xfdfFieldText .= number_format($leadObj->$model_name, 2, '.', '');
                } elseif ($model_name == 'no_of_dependent_children_c' || $model_name == 'dotb_employer_npa_c' || $model_name == 'dotb_second_job_employer_npa_c' || $model_name == 'dotb_bank_zip_code_c' ) {
                    $xfdfFieldText .= round($leadObj->$model_name);
                } else {
                    $xfdfFieldText .= $leadObj->$model_name;
                }
            } else if ($fieldDescription['type'] == 'address') {
                if(isset($lead_addresses[$model_name]))
                $xfdfFieldText .= $lead_addresses[$model_name];
            } else if ($fieldDescription['type'] == 'enum' && isset($app_list_strings[$fieldDescription['options']][$leadObj->$model_name])) {
                $xfdfFieldText .= $app_list_strings[$fieldDescription['options']][$leadObj->$model_name];
            } else if ($fieldDescription['type'] == 'partner') {
                if (!empty($lead_partner)) {
                     if (isset($fieldDescription['enum'])) {
                        $xfdfFieldText .= $app_list_strings[$fieldDescription['options']][$lead_partner->$model_name];
                    } else {
                        if (is_numeric($lead_partner->$model_name) && $model_name != 'dotb_employer_npa' && $model_name != 'dotb_second_job_employer_npa' ) {
                            $xfdfFieldText .= number_format($lead_partner->$model_name, 2, '.', '');
                        } elseif ($model_name == 'dotb_employer_npa' || $model_name == 'dotb_second_job_employer_npa') {
                            $xfdfFieldText .= round($lead_partner->$model_name);
                        } else {
                            $xfdfFieldText .= $lead_partner->$model_name;
                        }
                    }
                }
            } else if ($fieldDescription['type'] == 'app') {
                if(isset($app[$model_name]))
                $xfdfFieldText .= $app[$model_name];
            } else if ($fieldDescription['type'] == 'history') {
                if(isset($credit_histories[$model_name]))
                $xfdfFieldText .= rtrim($credit_histories[$model_name], ",");
            } else if ($fieldDescription['type'] == 'children') {
                $children_years = $leadObj->$model_name;
                $children_years = explode('-*#*-', $children_years);
                $year_str = '';
                foreach ($children_years as $key => $year) {
                    $year_str[] = substr($year, -2);
                }
                $year_str = implode(",", $year_str);
                $xfdfFieldText .= $year_str;
            }

            $xfdfFieldText .= "</value></field>\n";
            ////$GLOBALS['log']->fatal($xfdfFieldText);
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
         //$file_link=$sugar_config['site_url']."/index.php?entryPoint=customDownload&id=$revision->id&type=Documents";
        return true;
    }

}
