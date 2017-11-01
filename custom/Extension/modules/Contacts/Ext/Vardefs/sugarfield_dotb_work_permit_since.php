<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['dotb_work_permit_since']['type'] = 'date';
$dictionary['Contact']['fields']['dotb_work_permit_since']['name'] = 'dotb_work_permit_since';
$dictionary['Contact']['fields']['dotb_work_permit_since']['vname'] = 'LBL_DOTB_WORK_PERMIT_SINCE';
$dictionary['Contact']['fields']['dotb_work_permit_since']['merge_filter'] = 'enabled';
$dictionary['Contact']['fields']['dotb_work_permit_since']['importable'] = 'true';
$dictionary['Contact']['fields']['dotb_work_permit_since']['studio'] = 'visible';
$dictionary['Contact']['fields']['dotb_work_permit_since']['massupdate'] = true;
$dictionary['Contact']['fields']['dotb_work_permit_since']['calculated'] = false;
$dictionary['Contact']['fields']['dotb_work_permit_since']['enable_range_search'] = false;
$dictionary['Contact']['fields']['dotb_work_permit_since']['audited'] = false;
$dictionary['Contact']['fields']['dotb_work_permit_since']['duplicate_merge'] = 'enabled';
$dictionary['Contact']['fields']['dotb_work_permit_since']['duplicate_merge_dom_value'] = '1';
$dictionary['Contact']['fields']['dotb_work_permit_since']['dependency'] = 'not(equal($dotb_iso_nationality_code,"ch"))';
$dictionary['Contact']['fields']['dotb_work_permit_since']['required'] = false;
$dictionary['Contact']['fields']['dotb_work_permit_since']['full_text_search']['boost'] = 1;

