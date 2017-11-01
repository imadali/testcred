<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contract']['fields']['name']['audited'] = false;
$dictionary['Contract']['fields']['name']['massupdate'] = false;
$dictionary['Contract']['fields']['name']['comments'] = 'The name of the contract';
$dictionary['Contract']['fields']['name']['duplicate_merge'] = 'disabled';
$dictionary['Contract']['fields']['name']['duplicate_merge_dom_value'] = '0';
$dictionary['Contract']['fields']['name']['merge_filter'] = 'disabled';
$dictionary['Contract']['fields']['name']['unified_search'] = false;
$dictionary['Contract']['fields']['name']['full_text_search']['enabled'] = true;
$dictionary['Contract']['fields']['name']['full_text_search']['searchable'] = true;
$dictionary['Contract']['fields']['name']['full_text_search']['boost'] = 1.5900000000000001;
$dictionary['Contract']['fields']['name']['calculated'] = '1';
$dictionary['Contract']['fields']['name']['importable'] = 'false';
$dictionary['Contract']['fields']['name']['formula'] = 'concat($lead_first_name," ",getDropdownValue("dotb_credit_provider_list",$provider_id_c)," ",toString($credit_amount_c)," ",toString($interest_rate_c)," ",toString($credit_duration_c)," ",ifElse(equal($ppi_c,0),"NOPPI","PPI")," ",toString(today())," Vertrag")';
$dictionary['Contract']['fields']['name']['enforced'] = false;
$dictionary['Contract']['fields']['name']['required'] = false;

