<?php
 // created: 2016-10-05 14:30:56
$dictionary['Lead']['fields']['dot_second_job_employer_town_c']['labelValue'] = 'Second job employer town';
$dictionary['Lead']['fields']['dot_second_job_employer_town_c']['full_text_search']['enabled'] = true;
$dictionary['Lead']['fields']['dot_second_job_employer_town_c']['full_text_search']['searchable'] = false;
$dictionary['Lead']['fields']['dot_second_job_employer_town_c']['full_text_search']['boost'] = 1;
$dictionary['Lead']['fields']['dot_second_job_employer_town_c']['enforced'] = 'false';
$dictionary['Lead']['fields']['dot_second_job_employer_town_c']['dependency'] = 'or(equal($dotb_has_second_job_c,"yes"),equal($dotb_has_second_income_c,"yes"))';
$dictionary['Lead']['fields']['dot_second_job_employer_town_c']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['dot_second_job_employer_town_c']['merge_filter'] = 'enabled';

