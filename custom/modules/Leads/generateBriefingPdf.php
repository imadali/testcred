<?php
	require_once('vendor/tcpdf/tcpdf.php');

	/* Smarty Object */
	$ss = new Sugar_Smarty();

	/* Making Object of TCPDF to generate Outbound Fax */
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('RT');
	$pdf->SetTitle('Briefing Pdf');
	$pdf->SetSubject('TCPDF Tutorial');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	// $pdf->setFooterData($tc=array(0,64,0), $lc=array(0,64,128));

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$pdf->setPrintHeader(false);
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	//set margins
	$pdf->SetMargins('10', '10', '10');
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	// $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	//set some language-dependent strings
	$pdf->setLanguageArray($l);
	$pdf->SetFont('helvetica', '', 10, '', true);
	
	global $app_list_strings;
	$lead_basic_info = array();
	$lead_bean = BeanFactory::getBean('Leads');
	$lead_bean->retrieve($_REQUEST['lead_id']);
	
	$fieldDefs = $lead_bean->getFieldDefinitions();
	foreach($fieldDefs as $field){
		if($field['type'] != 'link'){
			if($field['type'] == 'enum'){
			$value = $app_list_strings[$field['options']][$lead_bean->$field['name']];
			$lead_basic_info[$field['name']] = $value;
			} else {
				$lead_basic_info[$field['name']] = $lead_bean->$field['name'];
			}
		}
	}
	$children_years=$lead_basic_info['children_birth_years_c'];
        $children_years=  explode('-*#*-', $children_years);
        $year_str='';
        foreach ($children_years as $key => $year) {
            $year_str[]= substr($year, -2);
        }
        $year_str=  implode(",", $year_str);
        $lead_basic_info['children_birth_years_c']=$year_str;
	// Addresses
	$lead_addresses = array();
	$counter = 0;
	$lead_bean->load_relationship("leads_dot10_addresses_1");
	$relatedAddresses = $lead_bean->leads_dot10_addresses_1->getBeans();
	foreach ($relatedAddresses as $address) {
		$lead_addresses[$counter]['primary_address_street'] = $address->primary_address_street;
		$lead_addresses[$counter]['address_c_o'] = $address->address_c_o;
		$lead_addresses[$counter]['postal_code'] = $address->primary_address_postalcode;  //PLZ
		$lead_addresses[$counter]['city'] = $address->primary_address_city; //Ort
		$lead_addresses[$counter]['land'] = '';
		$lead_addresses[$counter]['residence_to'] = $address->dotb_resident_till_c; //Wohnhaft bis
		$lead_addresses[$counter]['residence_since'] = $address->dotb_resident_since_c; //Wohnhaft seit
		$counter++;
	}

	//applications
	$app = array();
	$lead_bean->load_relationship("leads_opportunities_1");
	$relatedApplications = $lead_bean->leads_opportunities_1->getBeans();
        $count=0;
        $date_entered='';
            foreach ($relatedApplications as $application) {
                //$GLOBALS['log']->fatal("Bank Rq: ".$_REQUEST['bank']);
                //$GLOBALS['log']->fatal("Bank Ap: ".$application->provider_id_c);
                if($_REQUEST['bank']==$application->provider_id_c){
                $copy=false;
                if($count==0){
                $copy=true;
                $date_entered=$application->date_entered;
                }else if(strtotime($application->date_entered)>strtotime($date_entered)){
                $copy=true;
                $date_entered=$application->date_entered;   
                }
                if($copy){
                $app['credit_amount_c']=  $application->credit_amount_c;
                $app['credit_duration_c']=  $application->credit_duration_c;
                $app['ppi_c']=  $application->ppi_c;
                //$app['provider_id_c']=  $application->provider_id_c;    
                $app['provider_id_c']=  $app_list_strings['dotb_credit_provider_list'][$application->provider_id_c];    
                }
                $count++;
                }
            }
            
            // Addresses
	$credit_histories = array();
	$counter = 0;
	$lead_bean->load_relationship("leads_dotb5_credit_history_1");
	$relatedHistories = $lead_bean->leads_dotb5_credit_history_1->getBeans();
	foreach ($relatedHistories as $relatedHistory) {
		$credit_histories[$counter]['credit_balance'] = $relatedHistory->credit_balance;
		$credit_histories[$counter]['monthly_credit_rate'] = $relatedHistory->monthly_credit_rate;
		$counter++;
	}
            
          //$GLOBALS['log']->fatal(print_r($credit_histories,true));    
            
	//linked partner
	$lead_partner = array();
	$lead_bean->load_relationship("leads_contacts_1");
	$relatedPartner = $lead_bean->leads_contacts_1->getBeans();
	foreach ($relatedPartner as $partner) {
		if($partner->relative_type_c == "partner"){
			$partnerFieldDefs = $partner->getFieldDefinitions();
			foreach($partnerFieldDefs as $field){
				if($field['type'] != 'link'){
					if($field['type'] == 'enum'){
					$value = $app_list_strings[$field['options']][$partner->$field['name']];
					$lead_partner[$field['name']] = $value;
					} else {
						$lead_partner[$field['name']] = $partner->$field['name'];
					}
				}
			}
			break;
		}
	}
	
	$ss->assign("leadBasicInfo", $lead_basic_info);
	$ss->assign("leadAddresses", $lead_addresses);
        $ss->assign("app", $app);
        $ss->assign("creditHistories", $credit_histories);
	$ss->assign("partnerInfo", $lead_partner);
	$html = $ss->fetch("custom/modules/Leads/briefing_pdf.tpl");
	
	if (get_magic_quotes_gpc()) {
		$html = stripslashes($html);
	}
	
	// Add a page
	$pdf->AddPage();
	$pdf->writeHTML($html, true, false, true, false, '');
	
	ob_clean();
	$pdf->Output("BriefingPDF.pdf", "D");
?>
