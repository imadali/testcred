<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['dotb_employed_since_dup']['type'] = 'date';
$dictionary['Contact']['fields']['dotb_employed_since_dup']['name'] = 'dotb_employed_since_dup';
$dictionary['Contact']['fields']['dotb_employed_since_dup']['vname'] = 'LBL_DOTB_EMPLOYED_SINCE_DUP';
$dictionary['Contact']['fields']['dotb_employed_since_dup']['merge_filter'] = 'disabled';
$dictionary['Contact']['fields']['dotb_employed_since_dup']['importable'] = 'true';
$dictionary['Contact']['fields']['dotb_employed_since_dup']['studio'] = 'visible';
$dictionary['Contact']['fields']['dotb_employed_since_dup']['massupdate'] = true;
$dictionary['Contact']['fields']['dotb_employed_since_dup']['calculated'] = false;
$dictionary['Contact']['fields']['dotb_employed_since_dup']['enable_range_search'] = false;
$dictionary['Contact']['fields']['dotb_employed_since_dup']['audited'] = true;
$dictionary['Contact']['fields']['dotb_employed_since_dup']['duplicate_merge'] = 'disabled';
$dictionary['Contact']['fields']['dotb_employed_since_dup']['duplicate_merge_dom_value'] = '1';
$dictionary['Contact']['fields']['dotb_employed_since_dup']['dependency'] = 'or(equal($dotb_employment_type_id,"permanent_contract"),equal($dotb_employment_type_id,"self_employed"),equal($dotb_employment_type_id,"temporary_contract"),equal($dotb_employment_type_id,"fixed_term_contract"))';
$dictionary['Contact']['fields']['dotb_employed_since_dup']['required'] = false;
$dictionary['Contact']['fields']['dotb_employed_since_dup']['full_text_search']['boost'] = 1;

