<?php
/*
 * Please Do not un commnet the following functionality.
 * 
$dependencies['Contacts']['dotb_gender_id'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_gender_id_dup'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_gender_id',
               'value' =>  '$dotb_gender_id_dup',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_employment_type_id'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_employment_type_id_dup'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_employment_type_id',
               'value' =>  '$dotb_employment_type_id_dup',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_employer_name'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_employer_name_dup'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_employer_name',
               'value' =>  '$dotb_employer_name_dup',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_employer_npa'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_employer_npa_dup'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_employer_npa',
               'value' =>  '$dotb_employer_npa_dup',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_employer_town'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_employer_town_dup'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_employer_town',
               'value' =>  '$dotb_employer_town_dup',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_employed_since'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_employed_since_dup'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_employed_since',
               'value' =>  '$dotb_employed_since_dup',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_monthly_gross_income'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_monthly_gross_income_dup'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_monthly_gross_income',
               'value' =>  '$dotb_monthly_gross_income_dup',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_gender_id_dup'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_gender_id'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_gender_id_dup',
               'value' =>  '$dotb_gender_id',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_employment_type_id_dup'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_employment_type_id'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_employment_type_id_dup',
               'value' =>  '$dotb_employment_type_id',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_employer_name_dup'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_employer_name'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_employer_name_dup',
               'value' =>  '$dotb_employer_name',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_employer_npa_dup'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_employer_npa'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_employer_npa_dup',
               'value' =>  '$dotb_employer_npa',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_employer_town_dup'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_employer_town'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_employer_town_dup',
               'value' =>  '$dotb_employer_town',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_employed_since_dup'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_employed_since'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_employed_since_dup',
               'value' =>  '$dotb_employed_since',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_monthly_gross_income_dup'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_monthly_gross_income'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_monthly_gross_income_dup',
               'value' =>  '$dotb_monthly_gross_income',
           ),
       ),
   ),
)
 
 
//$dependencies['Contacts']['visibility_partner_tab_1'] = array(
// 'hooks' => array("edit","view"),
// 'trigger' => 'true',
// 'triggerFields' => array('relative_type_c'),
// 'onload' => true,
// 'actions' => array(
//   array(
//     'name' => 'SetPanelVisibility',
//     'params' => array(
//       'target' => 'LBL_RECORDVIEW_PANEL31',
//       'value' => 'or(equal($relative_type_c, "married"),equal($relative_type_c, "partner"))',
//     ),
//   ),
// ),
//);

$dependencies['Contacts']['dotb_correspondence_language_dup_c'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_correspondence_language'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_correspondence_language_dup_c',
               'value' =>  '$dotb_correspondence_language',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_correspondence_language'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_correspondence_language_dup_c'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_correspondence_language',
               'value' =>  '$dotb_correspondence_language_dup_c',
           ),
       ),
   ),
);

$dependencies['Contacts']['dotb_nationality_code_dup_c'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_iso_nationality_code'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_nationality_code_dup_c',
               'value' =>  '$dotb_iso_nationality_code',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_iso_nationality_code'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_nationality_code_dup_c'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_iso_nationality_code',
               'value' =>  '$dotb_nationality_code_dup_c',
           ),
       ),
   ),
);

$dependencies['Contacts']['dotb_work_permit_until_dup_c'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_work_permit_until'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_work_permit_until_dup_c',
               'value' =>  '$dotb_work_permit_until',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_work_permit_until'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_work_permit_until_dup_c'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_work_permit_until',
               'value' =>  '$dotb_work_permit_until_dup_c',
           ),
       ),
   ),
);

$dependencies['Contacts']['dotb_work_permit_type_dup_c'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_work_permit_type_id'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_work_permit_type_dup_c',
               'value' =>  '$dotb_work_permit_type_id',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_work_permit_type_id'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_work_permit_type_dup_c'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_work_permit_type_id',
               'value' =>  '$dotb_work_permit_type_dup_c',
           ),
       ),
   ),
);

$dependencies['Contacts']['dotb_work_permit_since_dup_c'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_work_permit_since'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_work_permit_since_dup_c',
               'value' =>  '$dotb_work_permit_since',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_work_permit_since'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_work_permit_since_dup_c'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_work_permit_since',
               'value' =>  '$dotb_work_permit_since_dup_c',
           ),
       ),
   ),
);

$dependencies['Contacts']['dotb_has_thirteenth_dup_c'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_has_thirteenth_salary'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_has_thirteenth_dup_c',
               'value' =>  '$dotb_has_thirteenth_salary',
           ),
       ),
   ),
);
$dependencies['Contacts']['dotb_has_thirteenth_salary'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('dotb_has_thirteenth_dup_c'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'dotb_has_thirteenth_salary',
               'value' =>  '$dotb_has_thirteenth_dup_c',
           ),
       ),
   ),
);

$dependencies['Contacts']['relative_type_dup_c'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('relative_type_c'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'relative_type_dup_c',
               'value' =>  '$relative_type_c',
           ),
       ),
   ),
);
$dependencies['Contacts']['relative_type_c'] = array(
   'hooks' => array("all"),
   'trigger' => 'true',
   'triggerFields' => array('relative_type_dup_c'),
   'onload' => true,
   'actions' => array(
       array(
           'name' => 'SetValue',
           'params' => array(
               'target' => 'relative_type_c',
               'value' =>  '$relative_type_dup_c',
           ),
       ),
   ),
);

*/

?>
