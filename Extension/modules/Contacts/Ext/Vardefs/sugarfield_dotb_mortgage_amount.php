<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['dotb_mortgage_amount']['type'] = 'currency';
$dictionary['Contact']['fields']['dotb_mortgage_amount']['related_fields'][0] = 'currency_id';
$dictionary['Contact']['fields']['dotb_mortgage_amount']['related_fields'][1] = 'base_rate';
$dictionary['Contact']['fields']['dotb_mortgage_amount']['name'] = 'dotb_mortgage_amount';
$dictionary['Contact']['fields']['dotb_mortgage_amount']['vname'] = 'LBL_DOTB_MORTGAGE_AMOUNT';
$dictionary['Contact']['fields']['dotb_mortgage_amount']['unified_search'] = false;
$dictionary['Contact']['fields']['dotb_mortgage_amount']['studio'] = 'visible';
$dictionary['Contact']['fields']['dotb_mortgage_amount']['massupdate'] = false;
$dictionary['Contact']['fields']['dotb_mortgage_amount']['merge_filter'] = 'enabled';
$dictionary['Contact']['fields']['dotb_mortgage_amount']['calculated'] = false;
$dictionary['Contact']['fields']['dotb_mortgage_amount']['reportable'] = true;
$dictionary['Contact']['fields']['dotb_mortgage_amount']['importable'] = 'true';
$dictionary['Contact']['fields']['dotb_mortgage_amount']['audited'] = true;
$dictionary['Contact']['fields']['dotb_mortgage_amount']['required'] = false;
$dictionary['Contact']['fields']['dotb_mortgage_amount']['len'] = 26;
$dictionary['Contact']['fields']['dotb_mortgage_amount']['precision'] = 2;
$dictionary['Contact']['fields']['dotb_mortgage_amount']['duplicate_merge'] = 'enabled';
$dictionary['Contact']['fields']['dotb_mortgage_amount']['duplicate_merge_dom_value'] = '1';
$dictionary['Contact']['fields']['dotb_mortgage_amount']['dependency'] = 'equal($dotb_is_home_owner,"yes")';
$dictionary['Contact']['fields']['dotb_mortgage_amount']['enable_range_search'] = false;

