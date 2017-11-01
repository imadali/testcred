<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['dotb_employed_until']['type'] = 'date';
$dictionary['Contact']['fields']['dotb_employed_until']['name'] = 'dotb_employed_until';
$dictionary['Contact']['fields']['dotb_employed_until']['vname'] = 'LBL_DOTB_EMPLOYED_UNTIL';
$dictionary['Contact']['fields']['dotb_employed_until']['merge_filter'] = 'enabled';
$dictionary['Contact']['fields']['dotb_employed_until']['importable'] = 'true';
$dictionary['Contact']['fields']['dotb_employed_until']['studio'] = 'visible';
$dictionary['Contact']['fields']['dotb_employed_until']['massupdate'] = true;
$dictionary['Contact']['fields']['dotb_employed_until']['calculated'] = false;
$dictionary['Contact']['fields']['dotb_employed_until']['enable_range_search'] = false;
$dictionary['Contact']['fields']['dotb_employed_until']['required'] = false;
$dictionary['Contact']['fields']['dotb_employed_until']['audited'] = false;
$dictionary['Contact']['fields']['dotb_employed_until']['duplicate_merge'] = 'enabled';
$dictionary['Contact']['fields']['dotb_employed_until']['duplicate_merge_dom_value'] = '1';
$dictionary['Contact']['fields']['dotb_employed_until']['dependency'] = 'or(equal($dotb_employment_type_id,"temporary_contract"),equal($dotb_employment_type_id,"fixed_term_contract"))';
$dictionary['Contact']['fields']['dotb_employed_until']['full_text_search']['boost'] = 1;

