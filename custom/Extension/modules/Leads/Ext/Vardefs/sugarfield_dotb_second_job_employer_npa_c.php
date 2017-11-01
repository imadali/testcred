<?php
 // created: 2016-10-05 14:30:57
$dictionary['Lead']['fields']['dotb_second_job_employer_npa_c']['labelValue'] = 'Second job employer NPA';
$dictionary['Lead']['fields']['dotb_second_job_employer_npa_c']['full_text_search']['enabled'] = true;
$dictionary['Lead']['fields']['dotb_second_job_employer_npa_c']['full_text_search']['searchable'] = false;
$dictionary['Lead']['fields']['dotb_second_job_employer_npa_c']['full_text_search']['boost'] = 1;
$dictionary['Lead']['fields']['dotb_second_job_employer_npa_c']['enforced'] = '';
$dictionary['Lead']['fields']['dotb_second_job_employer_npa_c']['dependency'] = 'or(equal($dotb_has_second_job_c,"yes"),equal($dotb_has_second_income_c,"yes"))';
$dictionary['Lead']['fields']['dotb_second_job_employer_npa_c']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['dotb_second_job_employer_npa_c']['merge_filter'] = 'enabled';

