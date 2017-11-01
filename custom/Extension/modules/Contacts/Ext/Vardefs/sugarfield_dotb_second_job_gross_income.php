<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['type'] = 'currency';
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['name'] = 'dotb_second_job_gross_income';
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['vname'] = 'LBL_DOTB_SECOND_JOB_GROSS_INCOME';
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['unified_search'] = false;
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['studio'] = 'visible';
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['massupdate'] = false;
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['merge_filter'] = 'enabled';
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['calculated'] = false;
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['reportable'] = true;
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['importable'] = 'false';
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['audited'] = true;
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['required'] = false;
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['len'] = 26;
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['precision'] = 2;
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['default'] = NULL;
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['duplicate_merge'] = 'enabled';
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['duplicate_merge_dom_value'] = '0';
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['dependency'] = 'or(equal($dotb_has_second_job,"yes"),equal($dotb_has_second_income,"yes"))';
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['enable_range_search'] = false;
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['related_fields'][0] = 'currency_id';
$dictionary['Contact']['fields']['dotb_second_job_gross_income']['related_fields'][1] = 'base_rate';

