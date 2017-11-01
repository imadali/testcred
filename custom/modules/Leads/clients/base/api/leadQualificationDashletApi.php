<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');



require_once ('include/TimeDate.php');

class leadQualificationDashletApi extends SugarApi {

   
    public function registerApiRest() {
        return array(
            'getleadQualification' => array(
                'reqType' => 'GET',
                'path' => array('Leads', 'LeadQualification', '?'),
                'pathVars' => array('', '', 'id'),
                'method' => 'getMissingFields',
                'shortHelp' => 'This api will return the missing fields for a Lead',
                'longHelp' => '',
            ),
        );
    }

    public function getMissingFields(ServiceBase $api, array $args) {
        global $app_strings,$timedate;
        $panelNames = array();

        //Required field List
        $requiredFields = array(
            'status' => 'status',
            'credit_amount' => 'credit_amount_c',
            'credit_usage' => 'credit_usage_type_id_c',
            'applied_for_other_credits' => 'has_applied_for_other_credit_c',
            'lead_type' => 'lead_type_id_c',
            'remarks' => 'description',
            'input_process' => 'input_process_type_id_c',
            'customer_contact' => 'assigned_user_name',
            'past_credit' => 'dotb_had_past_credit_c',
            'enforcements' => 'dotb_has_enforcements_c',
            'past_enforcements' => 'dotb_past_enforcements_c',
            'nationality' => 'dotb_iso_nationality_code_c',
            'work_permit_since' => 'dotb_work_permit_since_c',
            'date_of_birth' => 'birthdate',
            'civil_status' => 'dotb_civil_status_id_c',
            'primary_address' => 'primary_address',
            'residence_since' => 'dotb_resident_since_c',
            'second_income' => 'dotb_has_second_income_c',
            'second_job' => 'dotb_has_second_job_c',
            'second_job_employer_name' => 'dot_second_job_employer_name_c',
            'employment_type' => 'dotb_employment_type_id_c',
            'employer_name' => 'dotb_employer_name_c',
            'employed_since' => 'dotb_employed_since_c',
            'employed_till' => 'dotb_employed_until_c',
            'monthly_gross_income' => 'dotb_monthly_gross_income_c',
            'housing_situation' => 'dotb_housing_situation_id_c',
            'housing_costs' => 'dotb_housing_costs_c',
            'premium_reduction' => 'dotb_has_premium_reduction_c',
            'has_children' => 'has_children_c',
            'correspondence_language' => 'dotb_correspondence_language_c',
            'gender' => 'dotb_gender_id_c',
            'work_permit_type' => 'dotb_work_permit_type_id_c',
            'payment_behaviour' => 'dotb_payment_behaviour_type_c',
            'intrum_score' => 'intrum_score_c',
            'mortgage_amount' => 'dotb_mortgage_amount_c',
            'enforcement_description' => 'dot_enforcements_description_c',
            'current_enforcement_no' => 'dotb_current_enforcement_num_c',
            'current_enforcement_amount' => 'dotb_current_enforcement_amo_c',
            'past_enforcement_number' => 'dotb_past_enforcement_number_c',
            'past_enforcement_amount' => 'dotb_past_enforcement_amount_c',
            'residence_since' => 'dotb_resident_since_c',
            'rent_alimony_income' => 'dotb_rent_alimony_income_c',
            'additional_income' => 'dotb_additional_income_desc_c',
            'rent_or_alimony_income' => 'dotb_rent_or_alimony_income_c',
            'home_owner' => 'dotb_is_home_owner_c',
            'housing_rent_cost' => 'dotb_housing_costs_rent_c',
            'health_insurance' => 'dot_health_insurance_premium_c',
            'alimoiny_payment' => 'dotb_has_alimony_payments_c',
            'other_expenses' => 'dotb_other_expenses_c',
            'dependent_children' => 'no_of_dependent_children_c',
            'second_monythly_income' => 'dotb_monthly_net_income_nb_c',
            'second_gross_income' => 'dotb_second_job_gross_income_c',
            'housing_cost_rent' => 'dotb_housing_costs_rent_c',
            'credit_duration' => 'credit_duration_c',
            'employer_name' => 'dotb_employer_name_c',
            'employer_npa' => 'dotb_employer_npa_c',
            'employer_town' => 'dotb_employer_town_c',
            'probation_period' => 'dotb_is_in_probation_period_c',
            'monthly_net_income' => 'dotb_monthly_net_income_c',
            'gross_income' => 'dotb_monthly_gross_income_c',
            'thirteen_salary' => 'dotb_has_thirteenth_salary_c',
            'second_npa' => 'dotb_second_job_employer_npa_c',
            'second_thirteen' => 'dotb_second_job_has_13th_c',
            'second_town' => 'dot_second_job_employer_town_c',
            'deltavista_score' => 'deltavista_score_c',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'email' => 'email',
            'children_birth_years' => 'children_birth_years_c',
            'alimente' => 'dotb_aliments_c',
            'additional_expenses' => 'dotb_additional_expenses_c',
			'dotb_partner_agreement' => 'dotb_partner_agreement_c',
        );

		//partner required fields for dotb_partner_agreement_c field
		$partnerAggrementsRequiredFields = array(
            'relative_type' => 'relative_type_c',
            'dotb_correspondence_language' => 'dotb_correspondence_language',
			'dotb_gender_id' => 'dotb_gender_id',
			'dotb_birthdate' => 'birthdate',/////
			'dotb_nationality_code' => 'dotb_iso_nationality_code',////
			'dotb_work_permit_since' => 'dotb_work_permit_since',///
			'dotb_work_permit_until' => 'dotb_work_permit_until',////
			'dotb_employer_name' => 'dotb_employer_name',
			'dotb_employer_npa' => 'dotb_employer_npa',
			'dotb_employer_town' => 'dotb_employer_town',
			'dotb_employed_since' => 'dotb_employed_since',
			'dotb_monthly_income' => 'dotb_monthly_net_income',///
			'dotb_monthly_gross_income' => 'dotb_monthly_gross_income',
			'dotb_has_thirteenth_dup' => 'dotb_has_thirteenth_salary',//./
			'dotb_employment_type_id' => 'dotb_employment_type_id',
		);
		//partner required fields for dotb_civil_status_id_c field
		$partnerCivilStatusRequiredFields = array(
            'first_name' => 'first_name',
			'last_name' => 'last_name',
			'birthdate' => 'birthdate',
		);

        $leadId = $args['id'];

        if (file_exists("custom/modules/Leads/clients/base/views/record/record.php")) {
            require_once ('custom/modules/Leads/clients/base/views/record/record.php');
        } else {
            require_once ('modules/Leads/clients/base/views/record/record.php');
        }
        require_once('include/utils.php');
        
        $panels = $viewdefs['Leads']['base']['view']['record']['panels'];
        $tempPanel = $panels;
        $leadBean = BeanFactory::getBean("Leads", $leadId);
        
        
        //Checking for Residence Since field in Address Module
        $totalMonths = 0;
        if(!empty($leadBean->dotb_resident_since_c)){
            $date_obj2 = new TimeDate();
            $date_to = date("Y-m-d");
            $from_res = $date_obj2->to_db_date($leadBean->dotb_resident_since_c);

            $days2 = floor((abs(strtotime($date_to) - strtotime($from_res))) / 86400);
            $months2 = floor($days2 / 30);
            $totalMonths += $months2;
        }
        
        unset($panels[0]);
        unset($panels[1]);
        unset($panels[2]);

        //Calculation for Months and Years for comparison
        $date_obj = new TimeDate();
        $dateto = date("Y-m-d");
        $datefrom = $leadBean->dotb_work_permit_since_c;
        $from = $date_obj->to_db_date($leadBean->dotb_employed_since_c);

        $days = floor((abs(strtotime($dateto) - strtotime($from))) / 86400);
        $months = floor($days / 30);
        $years = floor($months / 12);

        $temp_array = array();
        $fieldDefs = $leadBean->getFieldDefinitions();
		$partnerEmptyFields = array();
        foreach ($panels as $key => $value) {

            $emptyFields = array();

            //Unsetting unrequired fields on the basis on dependent field values
            if ($leadBean->dotb_iso_nationality_code_c == 'ch') {
                unset($requiredFields['work_permit_since']);
                unset($requiredFields['work_permit_type']);
            }

            if ($leadBean->has_applied_for_other_credit_c == "no") {
                unset($requiredFields['remarks']);
            }

            if ($leadBean->dotb_employment_type_id_c != "fixed_term_contract" && $leadBean->dotb_employment_type_id_c != "temporary_contract" && $leadBean->dotb_employment_type_id_c != "permanent_contract" && $leadBean->dotb_employment_type_id_c != "self_employed") {
                unset($requiredFields['employed_since']);
                unset($requiredFields['employed_till']);
            } else if ($leadBean->dotb_employment_type_id_c == "permanent_contract" || $leadBean->dotb_employment_type_id_c == "self_employed") {
                unset($requiredFields['employed_till']);
            }

            if ($leadBean->dotb_is_home_owner_c == "yes") {
                unset($requiredFields['housing_rent_cost']);
            } else if ($leadBean->dotb_is_home_owner_c == "no") {
                unset($requiredFields['mortgage_amount']);
            } else {
                unset($requiredFields['housing_rent_cost']);
                unset($requiredFields['mortgage_amount']);
            }

            if ($leadBean->dotb_employment_type_id_c != "permanent_contract") {
                unset($requiredFields['monthly_gross_income']);
            }

            if ($leadBean->has_applied_for_other_credit_c == "no" || empty($leadBean->has_applied_for_other_credit_c)) {
                unset($requiredFields['remarks']);
            }

            if ($leadBean->dotb_had_past_credit_c == "no" || empty($leadBean->dotb_had_past_credit_c)) {
                unset($requiredFields['payment_behaviour']);
            }

            if ($leadBean->dotb_has_enforcements_c == "no" || empty($leadBean->dotb_has_enforcements_c)) {
                unset($requiredFields['enforcement_description']);
                unset($requiredFields['current_enforcement_no']);
                unset($requiredFields['current_enforcement_amount']);
            }

            if ($leadBean->dotb_past_enforcements_c == "no" || empty($leadBean->dotb_past_enforcements_c)) {
                unset($requiredFields['past_enforcement_number']);
                unset($requiredFields['past_enforcement_amount']);
            }

            if ($leadBean->dotb_has_second_job_c == "no" || empty($leadBean->dotb_has_second_job_c)) {
                unset($requiredFields['second_job_employer_name']);
                unset($requiredFields['second_monythly_income']);
                unset($requiredFields['second_gross_income']);
                unset($requiredFields['second_npa']);
                unset($requiredFields['second_thirteen']);
                unset($requiredFields['second_town']);
            }

            if ($leadBean->dotb_rent_alimony_income_c == "no" || empty($leadBean->dotb_rent_alimony_income_c)) {
                unset($requiredFields['additional_income']);
                unset($requiredFields['rent_or_alimony_income']);
            }
            
            //Checking no.of Dependent Children
            if($leadBean->no_of_dependent_children_c == 0 || $leadBean->no_of_dependent_children_c == NULL){
                unset($requiredFields['children_birth_years']);
            }
            

            //Checking for alimente
            if($leadBean->dotb_has_alimony_payments_c == "no" || empty($leadBean->dotb_has_alimony_payments_c)){
                unset($requiredFields['alimente']);
            }
            
            
            if ($value['newTab'] == false) {

                //Adding the Lead Name Panel and Fields
                if (empty($leadBean->$tempPanel[0]['fields'][1]['fields'][1])) {
                    $emptyFields[] = $fieldDefs[$tempPanel[0]['fields'][1]['fields'][1]]['vname'];
                    $panelNames[$tempPanel[0]['fields'][1]['label']] = $emptyFields;
                } else {
                    $panelNames[$tempPanel[0]['fields'][1]['label']] = "";
                }

                $emptyFields = array();
                if ($value['label'] != "LBL_RECORDVIEW_PANEL20") {
                    $panelNames[$value['label']] = "";
                }

                foreach ($value['fields'] as $k => $v) {

                    foreach ($requiredFields as $rk => $rv) {

                        //Checking the current field in meta exists in the pre-defined field array
                        if (isset($v['name']) && ($v['name'] == $rv || $v == $rv)) {

                            //Checking primary address fields
                            if ($v['name'] == "primary_address") {

                                foreach ($v['fields'] as $k2 => $v2) {
                                    if (!empty($leadBean->$v2['name'])) {
                                        $temp_array[] = 1;
                                    } else {
                                        $vname = $fieldDefs[$v2['name']]['vname'];
                                        $emptyFields[] = $vname;
                                        $temp_array[] = 0;
                                    }
                                }
                                
                                
                                if (in_array(0, $temp_array)) {
                                    $vname = $fieldDefs[$v2['name']]['vname'];
                                    $emptyFields[] = $vname;
                                    $size = sizeof($emptyFields);
                                    unset($emptyFields[0]);
                                    unset($emptyFields[$size - 1]);
                                    unset($emptyFields[$size - 2]);
                                }
                             //Checking fields with no meta
                            } else if (is_array($v) && array_values($v) == null) {
                                if (empty($leadBean->$v)) {
                                    $vname = $fieldDefs[$v]['vname'];
                                    $emptyFields[] = $vname;
                                }
                            //Checking  remaining fields
                            } else if($rk == "children_birth_years"){
                                    $birthYears = explode("-*#*-",$leadBean->children_birth_years_c);
                                    $totalYears = count($birthYears);
                                    foreach ($birthYears as $year) {
                                        if(trim($year)=='n/a'){
                                          $totalYears=0;  
                                        }
                                    }
                                    if($totalYears < $leadBean->no_of_dependent_children_c || $leadBean->children_birth_years_c == NULL){
                                            $vname = $fieldDefs[$v['name']]['vname'];
                                            $emptyFields[] = $vname;
                                    }
                                    if($leadBean->no_of_dependent_children_c != NULL && $leadBean->no_of_dependent_children_c > 0){
                                        //$vname = $fieldDefs[$v['name']]['vname'];
                                        //$emptyFields[] = $vname;
                                    }
                                    
                            }else if($rv == "no_of_dependent_children_c"){
                                    if($leadBean->no_of_dependent_children_c == NULL){//|| $leadBean->no_of_dependent_children_c == 0
                                            $vname = $fieldDefs[$v['name']]['vname'];
                                            $emptyFields[] = $vname;
                                    }
                                    
                                    
                            }
							else if ($v['name'] == "dotb_partner_agreement_c") {
								if (empty($leadBean->dotb_partner_agreement_c)) {
									$vname = $fieldDefs[$v['name']]['vname'];
                                    $emptyFields[] = $vname;
								} else if($leadBean->dotb_partner_agreement_c == 'yes'){
									// check related contact (partner) data
									$latest_partner = "SELECT leads_contacts_1contacts_idb FROM leads_contacts_1_c WHERE leads_contacts_1leads_ida='$leadBean->id' AND deleted=0 ORDER BY date_modified DESC LIMIT 1";
									$latest_partner_result = $GLOBALS['db']->query($latest_partner);
									$contact_id = '';
									while ($partner_row = $GLOBALS['db']->fetchByAssoc($latest_partner_result)) {
										$contact_id = $partner_row['leads_contacts_1contacts_idb'];
									}
									$partnerBean = BeanFactory::getBean("Contacts", $contact_id);
									$partnerFieldDefs = $partnerBean->getFieldDefinitions();
									foreach ($partnerAggrementsRequiredFields as $partnerKey => $partnerValue) {
										if (empty($partnerBean->$partnerValue)) {
											if($partnerValue == 'dotb_correspondence_language')
												$partnerEmptyFields[] = 'LBL_DOTB_CORRESPONDENCE_LANGUAGE';
											else
												$partnerEmptyFields[] = $partnerFieldDefs[$partnerValue]['vname'];
										}
									}
									
								} 
							}
							else if ($v['name'] == "dotb_civil_status_id_c") {
								if (empty($leadBean->dotb_civil_status_id_c)) {
									$vname = $fieldDefs[$v['name']]['vname'];
                                    $emptyFields[] = $vname;
								} else if($leadBean->dotb_civil_status_id_c == 'married' || $leadBean->dotb_civil_status_id_c == 'registered_partnership'){
									// check related contact (partner) data
									$latest_partner = "SELECT leads_contacts_1contacts_idb FROM leads_contacts_1_c WHERE leads_contacts_1leads_ida='$leadBean->id' AND deleted=0 ORDER BY date_modified DESC LIMIT 1";
									$latest_partner_result = $GLOBALS['db']->query($latest_partner);
									$contact_id = '';
									while ($partner_row = $GLOBALS['db']->fetchByAssoc($latest_partner_result)) {
										$contact_id = $partner_row['leads_contacts_1contacts_idb'];
									}
									$partnerBean = BeanFactory::getBean("Contacts", $contact_id);
									$partnerFieldDefs = $partnerBean->getFieldDefinitions();
									foreach ($partnerCivilStatusRequiredFields as $partnerKey => $partnerValue) {
										if (empty($partnerBean->$partnerValue)) {
											$partnerEmptyFields[] = $partnerFieldDefs[$partnerValue]['vname'];
										}
									}
									
								} 
							}
                            else{
                                    if (empty($leadBean->$v['name'])) {
                                        if (!isset($v['label'])) {
                                            $vname = $fieldDefs[$v['name']]['vname'];
                                            $emptyFields[] = $vname;
                                        } else {
                                            $emptyFields[] = $v['label'];
                                        }

                                    }
                                    
                                }
      
                        }
                    }
                }
                //Pushing each panel array in parent array
                if ($emptyFields != null) {
                    $panelNames[$value['label']] = $emptyFields;
                }
            }
        }
        //LBL_DOTB_RESIDENT_SINCE
        $totalMonths += $leadBean->address_months_c;
        $resident_since=date("d.m.Y",strtotime($leadBean->dotb_resident_since_c));
         if ($resident_since == '01.01.1901' || empty($leadBean->dotb_resident_since_c)) {
            if (is_array($panelNames['LBL_RECORDVIEW_PANEL10'])) {
                if (!in_array('LBL_DOTB_RESIDENT_SINCE', $panelNames['LBL_RECORDVIEW_PANEL10'])) {
                    $panelNames['LBL_RECORDVIEW_PANEL10'][] = 'LBL_DOTB_RESIDENT_SINCE';
                }    
            }    
        }else if ($totalMonths < 36) {
            $panelNames['LBL_RECORDVIEW_PANEL10'][] = 'LBL_RESIDENCE_SINCE_LESS_THAN_3_YEARS';
        }
		
		//partner field
		if(!empty($partnerEmptyFields)){
			$uniquePartnerEmptyFields = array_unique($partnerEmptyFields);
			$panelNames['LBL_PARTNER_PANEL'] = $uniquePartnerEmptyFields;
		}
        
        return $panelNames;
    }

}
