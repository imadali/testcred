<?php
require_once 'include/TimeDate.php';

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class leadPdfManagement extends SugarApi {

    /**
     *
     */
    public function registerApiRest() {
        return array(
            'GeneratePdf' => array(
                'reqType' => 'POST',
                'path' => array('Leads', 'generatePDF', '?'),
                'pathVars' => array('', '', 'pdfName'),
                'method' => 'generatePdf',
                'shortHelp' => 'This api will fill a pdf with informations given in model',
                'longHelp' => '',
            )
        );
    }

    /**
     * need
     *
     * @param SugarApi $api
     * @param array $args
     */
    public function generatePdf($api, $args) {
        global $timedate; 
        
        $today = $timedate->getInstance()->nowDbDate(); // Today

        //$logger = LoggerManager::getLogger();
        //$logger->debug(print_r($args, true));

        // Step 1 : initialize environnement and check all requirements
        //$this->requireArgs($args, array('pdfName', 'model'));
        $model = $args['model'];
               
        // Get the Latest granted application records linked with Leads
        if($args['pdfName'] == 'cembra') {
            $sql_application = 'SELECT opportunities.ppi_plus, opportunities_cstm.ppi_c, '
                    . ' opportunities_cstm.credit_duration_c, opportunities_cstm.interest_rate_c, '
					. ' opportunities_cstm.credit_amount_c '
                . ' FROM opportunities '
                . ' INNER JOIN opportunities_cstm ON opportunities_cstm.id_c = opportunities.id'
                . ' INNER JOIN leads_opportunities_1_c ON opportunities.id = leads_opportunities_1_c.leads_opportunities_1opportunities_idb  '
                . ' AND leads_opportunities_1_c.leads_opportunities_1leads_ida = "'.$args['model']['lead_id'].'" AND leads_opportunities_1_c.deleted = 0'
                . ' WHERE opportunities.deleted = 0 '
                . ' ORDER BY opportunities.date_modified DESC LIMIT 1';
            
            $model['ppi_id'] = '';
			$model['ppi_plus'] = '';
			$results_app = $GLOBALS['db']->query($sql_application);
            $row = $GLOBALS['db']->fetchByAssoc($results_app);
            if(isset($row) && !empty($row)){
                $model['interest_rate_c'] = $this->setContractInterestRate($row['interest_rate_c']);
                $model['credit_duration_c'] = $this->setContractCreditAmount($row['credit_duration_c']);
                $model['credit_amount'] = $this->setContractCreditAmount($row['credit_amount_c']);
                if($row['ppi_c'])
                    $model['ppi_id'] = 1;
                if($row['ppi_plus'])
                    $model['ppi_plus'] = 1;
            }
        }
		
        //  Offene Betreibungen Not mapped --> expected value: value NO checked by default 
        $model['open_operations'] = 1;
        
        $currentDate = explode(' ',$today);
        $model['application_creation_date'] = $currentDate[0];

        $language = $model['language'];
        
        if (!empty($language))
        // print German template if English is selected
            if ($language == 'en')
                $pdfName = 'de-' . $args['pdfName'];
            else
                $pdfName = $language . '-' . $args['pdfName'];
        else
            $pdfName = 'de-' . $args['pdfName'];

        if (!is_string($pdfName)) {
            throw new SugarApiException("Given pdf name is not a string");
        }
        if (!is_array($model)) {
            throw new SugarApiException("Given model is not correctly formatted");
        }

        if (!is_dir("./dotb_pdf_generation/models") || !file_exists("./dotb_pdf_generation/models/{$pdfName}.pdf") || !file_exists("./dotb_pdf_generation/models/{$pdfName}.mapping.php")
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

        $emptyFile = "./dotb_pdf_generation/models/{$pdfName}.pdf";

        $uniqueId = uniqid();
        $dataFileName = "{$tempDir}/{$pdfName}_{$uniqueId}.xfdf";
        while (file_exists($dataFileName)) {
            $uniqueId = uniqid();
            $dataFileName = "{$tempDir}/{$pdfName}_{$uniqueId}.xfdf";
        }

        include "./dotb_pdf_generation/models/{$pdfName}.mapping.php";

        if (!isset($fieldMap) || !is_array($fieldMap)) {
            throw new SugarApiException("Field map not correctly initialized");
        }
		
		//latest granted application date
		/*$leadObj = BeanFactory::getBean("Leads", $model['lead_id']);
		if ($leadObj->load_relationship('leads_opportunities_1')) {
                    $relatedApps = $leadObj->leads_opportunities_1->getBeans();
                    $count = 0;
                    $date_entered = '';
                    foreach ($relatedApps as $appObj) {
                        if ($appObj->provider_status_id_c == "granted") {
                            if ($count == 0) {
                                $app_user_approval_id = $appObj->assigned_user_id;
                                $date_entered = $appObj->date_entered;
                            } else if (strtotime($appObj->date_entered) > strtotime($date_entered)) {
                                $app_user_approval_id = $appObj->assigned_user_id;
                                $date_entered = $appObj->date_entered;
                            }
                            $count++;
                        }
                    }
                }*/
            
		/*if(!empty($date_entered)){
			$td = new TimeDate();
			$dateFormat = $current_user->getPreference('datef');
			
			$application_date = explode(" " , $date_entered);
			if(isset($application_date[0])){
				$application_datetime = new DateTime();
				$formatted_date = $application_datetime->createFromFormat($dateFormat, $application_date[0]);
				$application_date_entered = $td->asDbDate($formatted_date);
				$model['application_creation_date'] = $application_date_entered;
			} else {
				$model['application_creation_date'] = '';
			}
		}else {
			$model['application_creation_date'] = '';
		}*/
		
        // Step 2 : create a xfdf file containing all values to include in pdf file
        $dataFile = fopen($dataFileName, "w+");
        
        $GLOBALS['log']->debug('Model Value :: '.print_r($model,1));
        
        $xfdfHeader = "<?xml version='1.0' encoding='UTF-8'?>\n" .
                "<xfdf xmlns='http://ns.adobe.com/xfdf/' xml:space='preserve'>\n" .
                "  <fields>\n";
        fwrite($dataFile, $xfdfHeader);

        foreach ($fieldMap as $pdfFieldName => $fieldDescription) {
            $xfdfFieldText = "    <field name='" . $fieldDescription['pdf_name'] . "'><value>";

            switch ($fieldDescription['type']) {
                case 'radio_button':
                    $value = '';
                    if (isset($fieldDescription['values_map'][$model[$fieldDescription['model_name']]])) {
                        $value = $fieldDescription['values_map'][$model[$fieldDescription['model_name']]];
                    }
                    $xfdfFieldText .= $value;
                    break;
                case 'fixed_value':
                    $xfdfFieldText .= $fieldDescription['value'];
                    break;
                case 'enum':
                    $transletedValue = translate($fieldDescription['options'], '', $model[$fieldDescription['model_name']]);

                    if (!is_array($transletedValue)) {
                        $xfdfFieldText .= $transletedValue;
                    }
                    break;
                case 'date':
                    $timedate = TimeDate::getInstance();
                    if(isset($model[$fieldDescription['model_name']]))
                    if (!empty($model[$fieldDescription['model_name']])) {
                        $date = $timedate->swap_formats($model[$fieldDescription['model_name']], $timedate->get_db_date_format(), 'd/m/Y');
                        $xfdfFieldText .= $date;
                    }
                    break;
                case 'text':
                    if(isset($model[$fieldDescription['model_name']]))
                    $xfdfFieldText .= $model[$fieldDescription['model_name']];
                default :
                    $xfdfFieldText .= '';
                    break;
            }

            $xfdfFieldText .= "</value></field>\n";
            fwrite($dataFile, $xfdfFieldText);
        }

        $xfdfFooter = "  </fields>\n</xfdf>";
        fwrite($dataFile, $xfdfFooter);

        fclose($dataFile);

        $leadBean = BeanFactory::getBean('Leads', $model['lead_id']);

        $documents = $leadBean->get_linked_beans('leads_documents_1', 'Documents');
        $documents_count = count($documents);
        if (empty($language)) {
            $lang_flag = 'de';
        } else {
            $lang_flag = $language;
        }
		
		$doc_counter = 0;
        if (empty($documents)) {
            $document = BeanFactory::getBean('Documents');
            $document->id = create_guid();
            $document->new_with_id = true;
            $document->name = 'Antrag Cembra credaris-' . $leadBean->credit_request_number_c . '-' . $lang_flag . '.pdf';
            $document->rev_file_name = 'Antrag Cembra credaris-' . $leadBean->credit_request_number_c . '-' . $lang_flag . '.pdf';
        } else {
            foreach ($documents as $document) {
                if ($document->name == "{$pdfName}_{$leadBean->credit_request_number_c}.pdf") {
                    break;
                } else {
                    $doc_counter++;
                }
            }
        }

        //no document found with matching name so create a new document
        if ($documents_count == $doc_counter) {
            $document = BeanFactory::getBean('Documents');
            $document->id = create_guid();
            $document->new_with_id = true;
            $document->name = 'Antrag Cembra credaris-' . $leadBean->credit_request_number_c . '-' . $lang_flag . '.pdf';
            $document->rev_file_name = 'Antrag Cembra credaris-' . $leadBean->credit_request_number_c . '-' . $lang_flag . '.pdf';
        }

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
        $document->leads_documents_1leads_ida = $leadBean->id;
        $document->save();
        $leadBean->leads_documents_1->add(array($document));
        
        // Step 3 : use pdftk library to fill the pdf form
        exec("pdftk {$emptyFile} fill_form {$dataFileName} output upload/{$revision->id}");
        // exec("/usr/local/bin/pdftk {$emptyFile} fill_form {$dataFileName} output upload/{$revision->id}");
		
		
        unlink($dataFileName);
        return array(true);
    }
    
    private function checkValuesOfCheckBox($val) {
        if(isset($val) && $val){
            return 1;
        }else{
            return '';
        }
    }
    
    private function setContractInterestRate($val) {
        if(isset($val) && $val){
            return $val.'%';
        }
        return $val;
    }
    
    private function setContractCreditAmount($val) {
        if(isset($val) && $val){
            return $val;
        }
        return 0;
    }

}
