<?php

$dependencies['Leads']['required_substatus'] = array(
    'hooks' => array("all"),
    'trigger' => 'true',
    'triggerFields' => array('credit_request_status_id_c'),
    'onload' => true,
    'actions' => array(
        array(
            'name' => 'SetRequired',
            'params' => array(
                'target' => 'credit_request_substatus_id_c',
                'label' => 'LBL_CREDIT_REQUEST_SUBSTATUS_ID',
                'value' => 'or(equal($credit_request_status_id_c,"00_pendent_geschlossen"), equal($credit_request_status_id_c,"11_closed"), equal($credit_request_status_id_c,"11_closed"))',           
            ),
        ),
    ),
);
/*$dependencies['Leads']['dotb_had_past_credit_c'] = array(
    'hooks' => array("edit"), // this is where you want it to fire
    'trigger' => 'true', // to fire when fields change
    'triggerFields' => array('dotb_had_past_credit_c'), // field that will trigger this when changed
    'onload' => true, // fire when page is loaded
    'actions' => array( // actions we want to run, you can set multiple dependency action here
        array(
        'name' => 'SetRequired', // function to trigger
        'params' => array( // the params for the set required action
            'target' => 'dotb_had_past_credit_c', // the field id
            'label' => 'LBL_DOTB_HAD_PAST_CREDIT', // the field label id
            'value' => 'equal($dotb_had_past_credit_c, "")', // the SugarLogic for it to trigger if the field is required or not
            ),
        ),
    ),
);

$dependencies['Leads']['has_applied_for_other_credits_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('has_applied_for_other_credits_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'has_applied_for_other_credits_c',
            'label' => 'LBL_HAS_APPLIED_FOR_OTHER_CREDIT',
            'value' => 'equal($has_applied_for_other_credits_c, "")',
            ),
        ),
    ),
);*/

/*$dependencies['Leads']['birthdate'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('birthdate'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'birthdate',
            'label' => 'LBL_BIRTHDATE',
            'value' => 'equal($birthdate, "")',
            ),
        ),
    ),
);*/

/*$dependencies['Leads']['dotb_has_enforcements_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_has_enforcements_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_has_enforcements_c',
            'label' => 'LBL_DOTB_HAS_ENFORCEMENTS',
            'value' => 'equal($dotb_has_enforcements_c, "")',
            ),
        ),
    ),
);*/

/*$dependencies['Leads']['dotb_current_enforcement_num_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_has_enforcements_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_current_enforcement_num_c',
            'label' => 'LBL_DOTB_CURRENT_ENFORCEMENT_NUM',
            'value' => 'and(equal($dotb_has_enforcements_c,"offen"),equal($dotb_current_enforcement_num_c,""))',
            ),
        ),
    ),
);

$dependencies['Leads']['dotb_past_enforcements_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_past_enforcements_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_past_enforcements_c',
            'label' => 'LBL_DOTB_PAST_ENFORCEMENTS',
            'value' => 'equal($dotb_past_enforcements_c, "")',
            ),
        ),
    ),
);

$dependencies['Leads']['dotb_past_enforcement_number_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_past_enforcements_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_past_enforcement_number_c',
            'label' => 'LBL_DOTB_PAST_ENFORCEMENT_NUMBER',
            'value' => 'and(equal($dotb_past_enforcements_c,"yes"),equal($dotb_past_enforcement_number_c,""))',
            ),
        ),
    ),
);

$dependencies['Leads']['dotb_iso_nationality_code_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_iso_nationality_code_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_iso_nationality_code_c',
            'label' => 'LBL_DOTB_ISO_NATIONALITY_CODE',
            'value' => 'equal($dotb_iso_nationality_code_c, "")',
            ),
        ),
    ),
);*/

//$dependencies['Leads']['dotb_work_permit_type_id_c'] = array(
//    'hooks' => array("edit"), 
//    'trigger' => 'true', 
//    'triggerFields' => array('dotb_iso_nationality_code_c,dotb_work_permit_type_id_c'),
//    'onload' => true,
//    'actions' => array(
//        array(
//        'name' => 'SetRequired', 
//        'params' => array( 
//            'target' => 'dotb_work_permit_type_id_c',
//            'label' => 'LBL_DOTB_WORK_PERMIT_TYPE_ID',
//            'value' => 'and(not(equal($dotb_iso_nationality_code_c, "CH")),equal($dotb_work_permit_type_id_c, ""))',
//            ),
//        ),
//    ),
//);

//$dependencies['Leads']['dotb_work_permit_since_c'] = array(
//    'hooks' => array("edit"), 
//    'trigger' => 'true', 
//    'triggerFields' => array('dotb_work_permit_since_c,dotb_work_permit_type_id_c'),
//    'onload' => true,
//    'actions' => array(
//        array(
//        'name' => 'SetRequired', 
//        'params' => array( 
//            'target' => 'dotb_work_permit_since_c',
//            'label' => 'LBL_DOTB_WORK_PERMIT_SINCE',
//            'value' => 'and(not(equal($dotb_work_permit_type_id_c, "")),equal($dotb_work_permit_since_c, ""))',
//            ),
//        ),
//    ),
//);

/*$dependencies['Leads']['dotb_employer_name_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_employer_name_c,dotb_employment_type_id_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_employer_name_c',
            'label' => 'LBL_DOTB_EMPLOYER_NAME',
            'value' => 'equal($dotb_employment_type_id_c,"permanent_contract")',
            ),
        ),
    ),
);

$dependencies['Leads']['dotb_employed_since_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_employed_since_c,dotb_employment_type_id_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_employed_since_c',
            'label' => 'LBL_DOTB_EMPLOYED_SINCE',
            'value' => 'equal($dotb_employment_type_id_c,"permanent_contract")',
            ),
        ),
    ),
);

$dependencies['Leads']['dotb_monthly_gross_income_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_monthly_gross_income_c,dotb_employment_type_id_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_monthly_gross_income_c',
            'label' => 'LBL_DOTB_MONTHLY_GROSS_INCOME',
            'value' => 'equal($dotb_employment_type_id_c,"permanent_contract")',
            ),
        ),
    ),
);

$dependencies['Leads']['dotb_has_thirteenth_salary_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_has_thirteenth_salary_c,dotb_employment_type_id_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_has_thirteenth_salary_c',
            'label' => 'LBL_DOTB_HAS_THIRTEENTH_SALARY',
            'value' => 'equal($dotb_employment_type_id_c,"permanent_contract")',
            ),
        ),
    ),
);

$dependencies['Leads']['dotb_housing_costs_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_is_home_owner_c,dotb_housing_costs_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_housing_costs_c',
            'label' => 'LBL_DOTB_HOUSING_COSTS',
            'value' => 'equal($dotb_is_home_owner_c,"false")',
            ),
        ),
    ),
);

$dependencies['Leads']['dotb_housing_situation_id_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_housing_situation_id_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_housing_situation_id_c',
            'label' => 'LBL_DOTB_HOUSING_SITUATION_ID',
            'value' => 'equal($dotb_housing_situation_id_c, "")',
            ),
        ),
    ),
);

$dependencies['Leads']['dotb_is_rent_split_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_housing_situation_id_c','dotb_is_rent_split_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_is_rent_split_c',
            'label' => 'LBL_DOTB_IS_RENT_SPLIT',
            'value' => 'equal($dotb_housing_situation_id_c,"flat_share")',
            ),
        ),
    ),
);

$dependencies['Leads']['dotb_mortgage_amount_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_mortgage_amount_c','dotb_is_home_owner_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_mortgage_amount_c',
            'label' => 'LBL_DOTB_MORTGAGE_AMOUNT',
            'value' => 'equal($dotb_is_home_owner_c,"true")',
            ),
        ),
    ),
);

$dependencies['Leads']['dotb_additional_expenses_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_additional_expenses_c','dotb_is_home_owner_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_additional_expenses_c',
            'label' => 'LBL_DOTB_ADDITIONAL_EXPENSES',
            'value' => 'equal($dotb_is_home_owner_c,"true")',
            ),
        ),
    ),
);

$dependencies['Leads']['dotb_aliments_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_aliments_c','dotb_civil_status_id','dotb_housing_situation_id_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_aliments_c',
            'label' => 'LBL_DOTB_ALIMENTS',
            'value' => 'or(equal($dotb_civil_status_id_c,"divorced"),equal($dotb_civil_status_id_c,"separated"),and(equal($dotb_civil_status_id_c,"married"),equal($dotb_housing_situation_id_c,"alone")))',
            ),
        ),
    ),
);

$dependencies['Leads']['dot_second_job_employer_name_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_has_second_job_c','dot_second_job_employer_name_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dot_second_job_employer_name_c',
            'label' => 'LBL_DOT_SECOND_JOB_EMPLOYER_NAME',
            'value' => 'equal($dotb_has_second_job_c,"yes")',
            ),
        ),
    ),
);

$dependencies['Leads']['dotb_second_job_since_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_has_second_job_c','dotb_second_job_since_c','dotb_has_second_income_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_second_job_since_c',
            'label' => 'LBL_DOTB_SECOND_JOB_SINCE',
            'value' => 'or(equal($dotb_has_second_job_c,"yes"),equal($dotb_has_second_income_c,"yes"))',
            ),
        ),
    ),
);


$dependencies['Leads']['dotb_second_job_gross_income_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_has_second_job_c','dotb_second_job_gross_income_c','dotb_has_second_income_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_second_job_gross_income_c',
            'label' => 'LBL_DOTB_SECOND_JOB_GROSS_INCOME',
            'value' => 'or(equal($dotb_has_second_job_c,"yes"),equal($dotb_has_second_income_c,"yes"))',
            ),
        ),
    ),
);

$dependencies['Leads']['dotb_second_job_has_13th_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_has_second_job_c','dotb_second_job_has_13th_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_second_job_has_13th_c',
            'label' => 'LBL_DOTB_SECOND_JOB_HAS_13TH',
            'value' => 'equal($dotb_has_second_job_c,"yes")',
            ),
        ),
    ),
);

$dependencies['Leads']['dotb_has_second_income_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_has_second_income_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetRequired', 
        'params' => array( 
            'target' => 'dotb_has_second_income_c',
            'label' => 'LBL_DOTB_HAS_SECOND_INCOME',
            'value' => 'equal($dotb_has_second_income_c,"")',
            ),
        ),
    ),
);*/

//$dependencies['Leads']['dotb_work_permit_type_id_c'] = array(
//    'hooks' => array("edit"), 
//    'trigger' => 'true', 
//    'triggerFields' => array('dotb_iso_nationality_code_c'),
//    'onload' => true,
//    'actions' => array(
//        array(
//        'name' => 'SetRequired', 
//        'params' => array( 
//            'target' => 'dotb_work_permit_type_id_c',
//            'label' => 'LBL_DOTB_WORK_PERMIT_TYPE_ID',
//            'value' => 'not(equal($dotb_iso_nationality_code_c, "CH"))',
//            ),
//        ),
//    ),
//);

//$dependencies['Leads']['dotb_work_permit_type_id_c'] = array(
//    'hooks' => array("edit"), 
//    'trigger' => 'true', 
//    'triggerFields' => array('dotb_iso_nationality_code_c'),
//    'onload' => true,
//    'actions' => array(
//        array(
//        'name' => 'SetVisibility', 
//        'params' => array(
//            'target' => 'dotb_work_permit_type_id_c',
//            'label' => 'LBL_DOTB_WORK_PERMIT_TYPE_ID',
//            'value' => 'or(not(equal($dotb_iso_nationality_code_c, "CH")),not(equal($dotb_iso_nationality_code_c, "SZ")))',
//            ),
//        ),
//    ),
//);
//$dependencies['Leads']['dotb_work_permit_since_c'] = array(
//    'hooks' => array("edit"), 
//    'trigger' => 'true', 
//    'triggerFields' => array('dotb_iso_nationality_code_c'),
//    'onload' => true,
//    'actions' => array(
//        array(
//        'name' => 'SetVisibility', 
//        'params' => array(
//            'target' => 'dotb_work_permit_since_c',
//            'label' => 'LBL_DOTB_WORK_PERMIT_TYPE_ID',
//            'value' => 'or(not(equal($dotb_iso_nationality_code_c, "CH")),not(equal($dotb_iso_nationality_code_c, "SZ")))',
//            ),
//        ),
//    ),
//);
//$dependencies['Leads']['dotb_work_permit_until_c'] = array(
//    'hooks' => array("edit"), 
//    'trigger' => 'true', 
//    'triggerFields' => array('dotb_iso_nationality_code_c'),
//    'onload' => true,
//    'actions' => array(
//        array(
//        'name' => 'SetVisibility', 
//        'params' => array(
//            'target' => 'dotb_work_permit_until_c',  
//            'label' => 'LBL_DOTB_WORK_PERMIT_TYPE_ID',
//            'value' => 'or(not(equal($dotb_iso_nationality_code_c, "CH")),not(equal($dotb_iso_nationality_code_c, "SZ")))',
//            ),
//        ),
//    ),
//);

$dependencies['Leads']['dotb_work_permit_type_id_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_iso_nationality_code_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetVisibility', 
        'params' => array(
            'target' => 'dotb_work_permit_type_id_c',
            'label' => 'LBL_DOTB_WORK_PERMIT_TYPE_ID',
            'value' => 'and(not(equal($dotb_iso_nationality_code_c, "ch")),not(equal($dotb_iso_nationality_code_c, "sz")))',
            ),
        ),
    ),
);
$dependencies['Leads']['dotb_work_permit_since_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_iso_nationality_code_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetVisibility', 
        'params' => array(
            'target' => 'dotb_work_permit_since_c',
            'label' => 'LBL_DOTB_WORK_PERMIT_TYPE_ID',
            'value' => 'and(not(equal($dotb_iso_nationality_code_c, "ch")),not(equal($dotb_iso_nationality_code_c, "sz")))',
            ),
        ),
    ),
);
$dependencies['Leads']['dotb_work_permit_until_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_iso_nationality_code_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetVisibility', 
        'params' => array(
            'target' => 'dotb_work_permit_until_c',  
            'label' => 'LBL_DOTB_WORK_PERMIT_TYPE_ID',
            'value' => 'and(not(equal($dotb_iso_nationality_code_c, "ch")),not(equal($dotb_iso_nationality_code_c, "sz")))',
            ),
        ),
    ),
);
//***
$dependencies['Leads']['dotb_direct_withholding_tax_c'] = array(
    'hooks' => array("edit"), 
    'trigger' => 'true', 
    'triggerFields' => array('dotb_iso_nationality_code_c'),
    'onload' => true,
    'actions' => array(
        array(
        'name' => 'SetVisibility', 
        'params' => array(
            'target' => 'dotb_direct_withholding_tax_c',  
            'label' => 'LBL_DOTB_DIRECT_WITHHOLDING_TAX',
            'value' => 'and(not(equal($dotb_iso_nationality_code_c, "ch")),not(equal($dotb_iso_nationality_code_c, "sz")))',
            ),
        ),
    ),
);



?>