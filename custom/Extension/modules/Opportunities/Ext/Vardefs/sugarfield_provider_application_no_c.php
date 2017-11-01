<?php
 // created: 2016-10-05 14:30:58
$dictionary['Opportunity']['fields']['provider_application_no_c']['labelValue'] = 'Provider application no';
$dictionary['Opportunity']['fields']['provider_application_no_c']['full_text_search']['enabled'] = true;
$dictionary['Opportunity']['fields']['provider_application_no_c']['full_text_search']['searchable'] = false;
$dictionary['Opportunity']['fields']['provider_application_no_c']['full_text_search']['boost'] = 1;
$dictionary['Opportunity']['fields']['provider_application_no_c']['calculated'] = '1';
$dictionary['Opportunity']['fields']['provider_application_no_c']['formula'] = 'ifElse(or(equal($provider_id_c,"bob"),equal($provider_id_c,"eny_finance"),equal($provider_id_c,"cembra")),related($leads_opportunities_1,"credit_request_number_c"),"")';
$dictionary['Opportunity']['fields']['provider_application_no_c']['enforced'] = false;
$dictionary['Opportunity']['fields']['provider_application_no_c']['dependency'] = '';
$dictionary['Opportunity']['fields']['provider_application_no_c']['required'] = false;

