<?php
 // created: 2016-10-05 14:30:57
$dictionary['Lead']['fields']['dotb_work_permit_since_c']['labelValue'] = 'Work permit since';
$dictionary['Lead']['fields']['dotb_work_permit_since_c']['enforced'] = false;
$dictionary['Lead']['fields']['dotb_work_permit_since_c']['dependency'] = 'not(equal($dotb_iso_nationality_code_c,"ch"))';
$dictionary['Lead']['fields']['dotb_work_permit_since_c']['required'] = false;
$dictionary['Lead']['fields']['dotb_work_permit_since_c']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['dotb_work_permit_since_c']['merge_filter'] = 'enabled';
$dictionary['Lead']['fields']['dotb_work_permit_since_c']['full_text_search']['boost'] = 1;

