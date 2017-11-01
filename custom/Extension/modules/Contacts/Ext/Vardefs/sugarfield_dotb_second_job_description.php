<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['dotb_second_job_description']['type'] = 'varchar';
$dictionary['Contact']['fields']['dotb_second_job_description']['name'] = 'dotb_second_job_description';
$dictionary['Contact']['fields']['dotb_second_job_description']['vname'] = 'LBL_DOTB_SECOND_JOB_DESCRIPTION';
$dictionary['Contact']['fields']['dotb_second_job_description']['len'] = '255';
$dictionary['Contact']['fields']['dotb_second_job_description']['merge_filter'] = 'enabled';
$dictionary['Contact']['fields']['dotb_second_job_description']['importable'] = 'true';
$dictionary['Contact']['fields']['dotb_second_job_description']['studio'] = 'visible';
$dictionary['Contact']['fields']['dotb_second_job_description']['audited'] = false;
$dictionary['Contact']['fields']['dotb_second_job_description']['massupdate'] = false;
$dictionary['Contact']['fields']['dotb_second_job_description']['duplicate_merge'] = 'enabled';
$dictionary['Contact']['fields']['dotb_second_job_description']['duplicate_merge_dom_value'] = '1';
$dictionary['Contact']['fields']['dotb_second_job_description']['full_text_search']['enabled'] = true;
$dictionary['Contact']['fields']['dotb_second_job_description']['full_text_search']['searchable'] = false;
$dictionary['Contact']['fields']['dotb_second_job_description']['full_text_search']['boost'] = 1;
$dictionary['Contact']['fields']['dotb_second_job_description']['calculated'] = false;
$dictionary['Contact']['fields']['dotb_second_job_description']['dependency'] = 'or(equal($dotb_has_second_job,"yes"),equal($dotb_has_second_income,"yes"))';

