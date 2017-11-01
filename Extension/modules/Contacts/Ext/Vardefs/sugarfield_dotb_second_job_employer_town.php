<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['dotb_second_job_employer_town']['type'] = 'varchar';
$dictionary['Contact']['fields']['dotb_second_job_employer_town']['name'] = 'dotb_second_job_employer_town';
$dictionary['Contact']['fields']['dotb_second_job_employer_town']['vname'] = 'LBL_DOTB_SECOND_JOB_EMPLOYER_TOWN';
$dictionary['Contact']['fields']['dotb_second_job_employer_town']['len'] = '255';
$dictionary['Contact']['fields']['dotb_second_job_employer_town']['merge_filter'] = 'enabled';
$dictionary['Contact']['fields']['dotb_second_job_employer_town']['importable'] = 'true';
$dictionary['Contact']['fields']['dotb_second_job_employer_town']['studio'] = 'visible';
$dictionary['Contact']['fields']['dotb_second_job_employer_town']['audited'] = false;
$dictionary['Contact']['fields']['dotb_second_job_employer_town']['massupdate'] = false;
$dictionary['Contact']['fields']['dotb_second_job_employer_town']['duplicate_merge'] = 'enabled';
$dictionary['Contact']['fields']['dotb_second_job_employer_town']['duplicate_merge_dom_value'] = '1';
$dictionary['Contact']['fields']['dotb_second_job_employer_town']['full_text_search']['enabled'] = true;
$dictionary['Contact']['fields']['dotb_second_job_employer_town']['full_text_search']['searchable'] = false;
$dictionary['Contact']['fields']['dotb_second_job_employer_town']['full_text_search']['boost'] = 1;
$dictionary['Contact']['fields']['dotb_second_job_employer_town']['calculated'] = false;
$dictionary['Contact']['fields']['dotb_second_job_employer_town']['dependency'] = 'or(equal($dotb_has_second_job,"yes"),equal($dotb_has_second_income,"yes"))';

