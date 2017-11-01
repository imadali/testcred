<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['dotb_second_job_employer_npa']['type'] = 'varchar';
$dictionary['Contact']['fields']['dotb_second_job_employer_npa']['name'] = 'dotb_second_job_employer_npa';
$dictionary['Contact']['fields']['dotb_second_job_employer_npa']['vname'] = 'LBL_DOTB_SECOND_JOB_EMPLOYER_NPA';
$dictionary['Contact']['fields']['dotb_second_job_employer_npa']['len'] = '255';
$dictionary['Contact']['fields']['dotb_second_job_employer_npa']['merge_filter'] = 'enabled';
$dictionary['Contact']['fields']['dotb_second_job_employer_npa']['importable'] = 'true';
$dictionary['Contact']['fields']['dotb_second_job_employer_npa']['studio'] = 'visible';
$dictionary['Contact']['fields']['dotb_second_job_employer_npa']['audited'] = false;
$dictionary['Contact']['fields']['dotb_second_job_employer_npa']['massupdate'] = false;
$dictionary['Contact']['fields']['dotb_second_job_employer_npa']['duplicate_merge'] = 'enabled';
$dictionary['Contact']['fields']['dotb_second_job_employer_npa']['duplicate_merge_dom_value'] = '1';
$dictionary['Contact']['fields']['dotb_second_job_employer_npa']['full_text_search']['enabled'] = true;
$dictionary['Contact']['fields']['dotb_second_job_employer_npa']['full_text_search']['searchable'] = false;
$dictionary['Contact']['fields']['dotb_second_job_employer_npa']['full_text_search']['boost'] = 1;
$dictionary['Contact']['fields']['dotb_second_job_employer_npa']['calculated'] = false;
$dictionary['Contact']['fields']['dotb_second_job_employer_npa']['dependency'] = 'or(equal($dotb_has_second_job,"yes"),equal($dotb_has_second_income,"yes"))';

