<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['dotb_employed_since']['type'] = 'date';
$dictionary['Contact']['fields']['dotb_employed_since']['name'] = 'dotb_employed_since';
$dictionary['Contact']['fields']['dotb_employed_since']['vname'] = 'LBL_DOTB_EMPLOYED_SINCE';
$dictionary['Contact']['fields']['dotb_employed_since']['merge_filter'] = 'disabled';
$dictionary['Contact']['fields']['dotb_employed_since']['importable'] = 'true';
$dictionary['Contact']['fields']['dotb_employed_since']['studio'] = 'visible';
$dictionary['Contact']['fields']['dotb_employed_since']['massupdate'] = true;
$dictionary['Contact']['fields']['dotb_employed_since']['calculated'] = false;
$dictionary['Contact']['fields']['dotb_employed_since']['enable_range_search'] = false;
$dictionary['Contact']['fields']['dotb_employed_since']['audited'] = true;
$dictionary['Contact']['fields']['dotb_employed_since']['duplicate_merge'] = 'disabled';
$dictionary['Contact']['fields']['dotb_employed_since']['duplicate_merge_dom_value'] = '1';
$dictionary['Contact']['fields']['dotb_employed_since']['dependency'] = 'or(equal($dotb_employment_type_id,"permanent_contract"),equal($dotb_employment_type_id,"self_employed"),equal($dotb_employment_type_id,"temporary_contract"),equal($dotb_employment_type_id,"fixed_term_contract"))';
$dictionary['Contact']['fields']['dotb_employed_since']['required'] = false;
$dictionary['Contact']['fields']['dotb_employed_since']['full_text_search']['boost'] = 1;

