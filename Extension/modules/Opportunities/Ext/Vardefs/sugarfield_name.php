<?php
 // created: 2016-10-05 14:30:58
$dictionary['Opportunity']['fields']['name']['audited'] = true;
$dictionary['Opportunity']['fields']['name']['massupdate'] = false;
$dictionary['Opportunity']['fields']['name']['comments'] = 'Name of the opportunity';
$dictionary['Opportunity']['fields']['name']['duplicate_merge'] = 'disabled';
$dictionary['Opportunity']['fields']['name']['duplicate_merge_dom_value'] = 0;
$dictionary['Opportunity']['fields']['name']['merge_filter'] = 'disabled';
$dictionary['Opportunity']['fields']['name']['full_text_search']['enabled'] = true;
$dictionary['Opportunity']['fields']['name']['full_text_search']['searchable'] = true;
$dictionary['Opportunity']['fields']['name']['full_text_search']['boost'] = 1.6499999999999999;
$dictionary['Opportunity']['fields']['name']['calculated'] = '1';
$dictionary['Opportunity']['fields']['name']['len'] = '255';
$dictionary['Opportunity']['fields']['name']['required'] = false;
$dictionary['Opportunity']['fields']['name']['importable'] = 'false';
$dictionary['Opportunity']['fields']['name']['formula'] = 'concat($lead_first_name," ",getDropdownValue("dotb_credit_provider_list",$provider_id_c)," ",toString($credit_amount_c)," ",toString($interest_rate_c)," ",toString($credit_duration_c)," ",ifElse(equal($ppi_c,0),"NOPPI","PPI")," ",ifElse(equal($ppi_plus,0),"","PPIPlus"),toString(today())," Antrag")';
$dictionary['Opportunity']['fields']['name']['enforced'] = false;

