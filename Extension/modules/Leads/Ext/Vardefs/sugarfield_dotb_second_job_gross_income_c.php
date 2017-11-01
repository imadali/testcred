<?php
 // created: 2016-10-05 14:30:57
$dictionary['Lead']['fields']['dotb_second_job_gross_income_c']['labelValue'] = 'Second job gross income';
$dictionary['Lead']['fields']['dotb_second_job_gross_income_c']['enforced'] = '';
$dictionary['Lead']['fields']['dotb_second_job_gross_income_c']['required'] = false;
$dictionary['Lead']['fields']['dotb_second_job_gross_income_c']['default'] = NULL;
$dictionary['Lead']['fields']['dotb_second_job_gross_income_c']['dependency'] = 'or(equal($dotb_has_second_job_c,"yes"),equal($dotb_has_second_income_c,"yes"))';
$dictionary['Lead']['fields']['dotb_second_job_gross_income_c']['type'] = 'currency';
$dictionary['Lead']['fields']['dotb_second_job_gross_income_c']['related_fields'][0] = 'currency_id';
$dictionary['Lead']['fields']['dotb_second_job_gross_income_c']['related_fields'][1] = 'base_rate';
$dictionary['Lead']['fields']['dotb_second_job_gross_income_c']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['dotb_second_job_gross_income_c']['merge_filter'] = 'enabled';

