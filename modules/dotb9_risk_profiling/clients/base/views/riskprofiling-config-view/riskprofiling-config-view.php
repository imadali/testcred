<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

$moduleName = 'dotb9_risk_profiling';
$customMetaFields = array();
$customMetaPanels = array();
$status = array ('yes' => 'Ja','no' => 'Nein','not_relevant' => 'nicht Relevant');

// field to panel mapping
$panelFieldMapping = array(
	'more_than_80000_c' => 'credit experience',
	'postcode_if_liechtenstein_c' => 'Other credit inquiries',
	'zip_liechtenstein_and_swiss_c' => 'Other credit inquiries',
	'if_younger_than_18_c' => 'Birth Date / Age',
	'if_younger_than_21_c' => 'Birth Date / Age',
	'if_young_21_credit_amount_15_c' => 'Birth Date / Age',
	'if_younger_than_25_c' => 'Birth Date / Age',
	'if_young_25_credit_amount_25_c' => 'Birth Date / Age',
	'if_older_than_59_c' => 'Birth Date / Age',
	'if_older_59_credit_amount_50_c' => 'Birth Date / Age',
	'if_younger_than_64_c' => 'Birth Date / Age',
	'if_younger_64_credit_50000_c' => 'Birth Date / Age',
	'if_older_than_65_c' => 'Birth Date / Age',
	'if_older_than_70_c' => 'Birth Date / Age',
	'pay_bills_taxes_inv_real_est_c' => 'credit purpose',
	'currently_open_enforcements_c' => 'prosecutions',
	'if_enforcements_in_the_past_c' => 'prosecutions',
	'if_iran_red_syr_mianmar_sud_c' => 'nationality',
	'if_iraq_zim_con_leb_yem_usa_c' => 'nationality',
	'if_redt_ger_aus_den_sweden_c' => 'nationality',
	'if_b_permit_less_6_month_c' => 'residence permit',
	'if_b_permit_btwn_6_12_month_c' => 'residence permit',
	'if_b_btw_12_net_m_sal_l_4000_c' => 'residence permit',
	'if_b_6_12_sal_btw_4_6_amt_15_c' => 'residence permit',
	'if_b_6_12_sal_btw_6_8_amt_20_c' => 'residence permit',
	'if_b_btw_6_12_m_sal_8_amt_30_c' => 'residence permit',
	'if_b_btw_12_24__sal_4_amt_15_c' => 'residence permit',
	'if_b_btw_12_24_sal_4_6_am_25_c' => 'residence permit',
	'if_b_btw_12_24_sal_6_8_am_30_c' => 'residence permit',
	'if_b_btw_12_24_sal_8_amnt_40_c' => 'residence permit',
        'if_b_permit_btw_24_36_months_c' => 'residence permit',
	'if_b_btw_24_36_sal_4_amnt_25_c' => 'residence permit',
	'if_b_btw_24_36_sal_4_6_am_35_c' => 'residence permit',
	'if_b_btw_24_36_sal_6_8_am_40_c' => 'residence permit',
	'if_b_btw_24_36_sal_8_amnt_50_c' => 'residence permit',
	'if_b_36_sal_4_6_credit_am_45_c' => 'residence permit',
	'if_b_36_sal_6_8_credit_am_50_c' => 'residence permit',
	'if_b_36_sal_8_credit_amnt_60_c' => 'residence permit',
	'if_b_permit_btw_12_24_months_c' => 'residence permit',
	'if_b_12_alo_child_sin_parent_c' => 'residence permit',
	'if_b_36_sal_4_credit_amnt_35_c' => 'residence permit',
	'if_g_less_3_years_employer_c' => 'residence permit',
	'if_g_permit_more_3_years_c' => 'residence permit',
	'if_l_permit_less_1_year_c' => 'residence permit',
	'if_l_permit_more_than_1_year_c' => 'residence permit',
	'if_diplomat_less_3_years_c' => 'residence permit',
	'if_diplomat_more_3_years_c' => 'residence permit',
	'if_b_btw_24_36_sal_4_a_g_25_c' => 'residence permit',
	'if_self_emp_less_2_years_c' => 'employment relationship',
	'if_self_more_2_years_c' => 'employment relationship',
	'if_unemp_not_working_c' => 'employment relationship',
	'if_temp_cont_6_months_c' => 'employment relationship',
	'if_temp_cont_6_12_mon_c' => 'employment relationship',
	'if_temp_cont_12_month_c' => 'employment relationship',
	'if_disable_pension_ret_c' => 'employment relationship',
	'if_less_than_3_months_c' => 'Employed since',
	'if_less_than_12_month_c' => 'Employed since',
	'if_with_par_by_parent_c' => 'residential ratio',
	'if_urbanize_flat_share_c' => 'residential ratio',
	'if_less_than_12_months_c' => 'Resident since',
	'if_less_than_24_months_c' => 'Resident since',
	'if_no_c' => 'homeowners',
	'if_yes_and_less_than_2_years_c' => 'homeowners',
	'if_yes_long_2_current_adress_c' => 'homeowners',
	'if_yes_c' => 'Premium Reduction',
	'if_customer_receives_alimony_c' => 'alimony',
	'if_customer_has_pay_alimony_c' => 'alimony',
	'if_div_judicial_sep_div_sep_c' => 'civil status',
	'if_married_c' => 'civil status',
	'no_code_and_cannot_be_found_c' => 'Delta Vista code',
	'code_1_c' => 'Delta Vista code',
	'code_2_c' => 'Delta Vista code',
	'code_3_c' => 'Delta Vista code',
	'code_4_c' => 'Delta Vista code',
	'if_dv_score_440_c' => 'Delta Vista code',
	'total_income_less_than_2500_c' => 'income',
	'total_income_less_than_3000_c' => 'income',
	'total_income_less_than_4000_c' => 'income',
	'if_credit_amount_80000_c' => 'PPI',
	'if_credit_duration_60_months_c' => 'PPI',
);

//panel details i.e label
$panelDetails = array(
	0 => array(
		'name' => 'credit experience',
		'label' => 'LBL_CREDIT_EXPERIENCE',
		'counter' => '0',
	),
	1 => array(
		'name' => 'Other credit inquiries',
		'label' => 'LBL_OTHER_CREDIT_INQUIRIES',
		'counter' => '0',
	),
	2 => array(
		'name' => 'Birth Date / Age',
		'label' => 'LBL_BIRTH_DATE_AGE',
		'counter' => '0',
	),
	3 => array(
		'name' => 'credit purpose',
		'label' => 'LBL_CREDIT_PURPOSE',
		'counter' => '0',
	),
	4 => array(
		'name' => 'prosecutions',
		'label' => 'LBL_PROSECUTIONS',
		'counter' => '0',
	),
	5 => array(
		'name' => 'nationality',
		'label' => 'LBL_NATIONALITY',
		'counter' => '0',
	),
	6 => array(
		'name' => 'residence permit',
		'label' => 'LBL_RESIDENCE_PERMIT',
		'counter' => '0',
	),
	7 => array(
		'name' => 'employment relationship',
		'label' => 'LBL_EMPLOYMENT_RELATIONSHIP',
		'counter' => '0',
	),
	8 => array(
		'name' => 'Employed since',
		'label' => 'LBL_EMPLOYED_SINCE',
		'counter' => '0',
	),
	9 => array(
		'name' => 'residential ratio',
		'label' => 'LBL_RESIDENTIAL_RATIO',
		'counter' => '0',
	),
	10 => array(
		'name' => 'Resident since',
		'label' => 'LBL_RESIDENT_SINCE',
		'counter' => '0',
	),
	11 => array(
		'name' => 'homeowners',
		'label' => 'LBL_HOMEOWNERS',
		'counter' => '0',
	),
	12 => array(
		'name' => 'Premium Reduction',
		'label' => 'LBL_PREMIUM_REDUCTION',
		'counter' => '0',
	),
	13 => array(
		'name' => 'alimony',
		'label' => 'LBL_ALIMONY',
		'counter' => '0',
	),
	14 => array(
		'name' => 'civil status',
		'label' => 'LBL_CIVIL_STATUS',
		'counter' => '0',
	),
	15 => array(
		'name' => 'Delta Vista code',
		'label' => 'LBL_DELTA_VISTA_CODE',
		'counter' => '0',
	),
	16 => array(
		'name' => 'income',
		'label' => 'LBL_INCOME',
		'counter' => '0',
	),
	17 => array(
		'name' => 'PPI',
		'label' => 'LBL_PPI',
		'counter' => '0',
	),
);

if(BeanFactory::getBean($moduleName)){
	$riskProfilingQuery = new SugarQuery();
	$riskProfilingQuery->select(array('id'));
	$riskProfilingQuery->from(BeanFactory::getBean($moduleName));
	$riskProfilingQuery->where()->equals('deleted','0');
	$riskProfilingQuery->where()->equals('status_c','Active');
	$riskProfilingQuery->join('accounts_dotb9_risk_profiling_1', array('alias' => 'accounts_dotb9_risk_profiling_1'));
	$riskProfilingQuery->orderBy('accounts_dotb9_risk_profiling_1.bank_order_c', 'ASC');
	$riskProfilingResults = $riskProfilingQuery->execute();

	//get dummy record id
	$dummyRecordQuery = new SugarQuery();
	$dummyRecordQuery->select(array('id'));
	$dummyRecordQuery->from(BeanFactory::getBean($moduleName));
	$dummyRecordQuery->where()->equals('deleted','0');
	$dummyRecordQuery->where()->equals('name', 'RiskFactor');
	$dummyRecordQuery->where()->equals('status_c', 'Inactive');
	$dummyRecordResults = $dummyRecordQuery->execute();
	
	// get field definition
	$riskBean = BeanFactory::getBean($moduleName);
	$fieldDefs = $riskBean->getFieldDefinitions();
	$i = 0;
	$j = 0;
	foreach($fieldDefs as $field){
		if($field['type'] == 'enum' && $field['name'] != 'status_c'){
			$panelName = $panelFieldMapping[$field['name']];
			if($panelName){
				//to get panel index
				$panelIndex = array_search($panelName, valuelist($panelDetails, 'name'));
				$panelCounter = $panelDetails[$panelIndex]['counter'];
				
				//label field
				$rpCustomField = array('name'=>'','label' => $field['vname'],'type' => $field['type'],'fieldView' => 'list');
				$customMetaFields[$panelIndex][$panelCounter][] =  $rpCustomField;
				// risk factor column
				$rfCustomField = array('name'=> $field['name'].'/'.$dummyRecordResults[0]['id'],'label' => 'risk_factor','type' => 'enum-with-image','riskFactor' => '','options' => array('Red'=>'Red','Yellow'=>'Yellow','Green'=>'Green'),'modelId' => $dummyRecordResults[0]['id'],'fieldView' => 'list');
				$customMetaFields[$panelIndex][$panelCounter][] =  $rfCustomField;
				
				foreach ($riskProfilingResults as $key => $value){
					// field/record-id
					$customField = array('name' => $field['name'].'/'.$value['id'],'label' => $field['vname'],'type' => $field['type'],'options' => array('yes'=>$status['yes'],'no'=>$status['no'],'not_relevant'=>$status['not_relevant']),'modelId' => $value['id'],'fieldView' => 'list');
					$customMetaFields[$panelIndex][$panelCounter][] =  $customField;	
				}
				$i++;
				$panelCounter++;
				$panelDetails[$panelIndex]['counter'] = $panelCounter;
			}
			
		}
	}

	foreach($panelDetails as $panel){
		$customPanels = array('name' => $panel['name'],'label' => $panel['label'],'fields' => $customMetaFields[$j]);
		$customMetaPanels[$j] = $customPanels;
		$j++;
	}
}
// $GLOBALS['log']->fatal("customMetaPanels");
// $GLOBALS['log']->fatal($customMetaPanels);
 
$viewdefs[$moduleName]['base']['view']['riskprofiling-config-view'] = array(
	'panels' => $customMetaPanels,
);

?>