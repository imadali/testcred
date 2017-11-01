<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['dotb_second_job_since']['type'] = 'date';
$dictionary['Contact']['fields']['dotb_second_job_since']['name'] = 'dotb_second_job_since';
$dictionary['Contact']['fields']['dotb_second_job_since']['vname'] = 'LBL_DOTB_SECOND_JOB_SINCE';
$dictionary['Contact']['fields']['dotb_second_job_since']['merge_filter'] = 'enabled';
$dictionary['Contact']['fields']['dotb_second_job_since']['importable'] = 'true';
$dictionary['Contact']['fields']['dotb_second_job_since']['studio'] = 'visible';
$dictionary['Contact']['fields']['dotb_second_job_since']['massupdate'] = true;
$dictionary['Contact']['fields']['dotb_second_job_since']['calculated'] = false;
$dictionary['Contact']['fields']['dotb_second_job_since']['enable_range_search'] = false;
$dictionary['Contact']['fields']['dotb_second_job_since']['audited'] = false;
$dictionary['Contact']['fields']['dotb_second_job_since']['duplicate_merge'] = 'enabled';
$dictionary['Contact']['fields']['dotb_second_job_since']['duplicate_merge_dom_value'] = '1';
$dictionary['Contact']['fields']['dotb_second_job_since']['dependency'] = 'or(equal($dotb_has_second_job,"yes"),equal($dotb_has_second_income,"yes"))';
$dictionary['Contact']['fields']['dotb_second_job_since']['full_text_search']['boost'] = 1;

