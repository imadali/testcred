<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['dotb_second_job_employer_name']['type'] = 'varchar';
$dictionary['Contact']['fields']['dotb_second_job_employer_name']['name'] = 'dotb_second_job_employer_name';
$dictionary['Contact']['fields']['dotb_second_job_employer_name']['vname'] = 'LBL_DOTB_SECOND_JOB_EMPLOYER_NAME';
$dictionary['Contact']['fields']['dotb_second_job_employer_name']['len'] = '255';
$dictionary['Contact']['fields']['dotb_second_job_employer_name']['merge_filter'] = 'enabled';
$dictionary['Contact']['fields']['dotb_second_job_employer_name']['importable'] = 'true';
$dictionary['Contact']['fields']['dotb_second_job_employer_name']['studio'] = 'visible';
$dictionary['Contact']['fields']['dotb_second_job_employer_name']['audited'] = false;
$dictionary['Contact']['fields']['dotb_second_job_employer_name']['massupdate'] = false;
$dictionary['Contact']['fields']['dotb_second_job_employer_name']['duplicate_merge'] = 'enabled';
$dictionary['Contact']['fields']['dotb_second_job_employer_name']['duplicate_merge_dom_value'] = '1';
$dictionary['Contact']['fields']['dotb_second_job_employer_name']['full_text_search']['enabled'] = true;
$dictionary['Contact']['fields']['dotb_second_job_employer_name']['full_text_search']['searchable'] = false;
$dictionary['Contact']['fields']['dotb_second_job_employer_name']['full_text_search']['boost'] = 1;
$dictionary['Contact']['fields']['dotb_second_job_employer_name']['calculated'] = false;
$dictionary['Contact']['fields']['dotb_second_job_employer_name']['dependency'] = 'or(equal($dotb_has_second_job,"yes"),equal($dotb_has_second_income,"yes"))';

