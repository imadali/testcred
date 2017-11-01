<?php
 // created: 2016-10-05 14:30:57
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['labelValue'] = 'Credit Amount';
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['enforced'] = '';
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['dependency'] = 'not(equal($provider_id_c,"bank_now_flex"))';
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['related_fields'][0] = 'currency_id';
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['related_fields'][1] = 'base_rate';
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['required'] = false;
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['source'] = 'custom_fields';
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['name'] = 'approved_credit_amount_c';
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['vname'] = 'LBL_CREDIT_AMOUNT';
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['type'] = 'currency';
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['massupdate'] = false;
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['default'] = NULL;
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['no_default'] = false;
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['comments'] = '';
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['help'] = '';
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['importable'] = 'true';
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['duplicate_merge'] = 'enabled';
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['duplicate_merge_dom_value'] = '1';
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['audited'] = true;
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['reportable'] = true;
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['unified_search'] = false;
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['merge_filter'] = 'disabled';
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['calculated'] = false;
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['len'] = '26';
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['size'] = '20';
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['enable_range_search'] = false;
$dictionary['Opportunity']['fields']['approved_credit_amount_c']['precision'] = 6;

