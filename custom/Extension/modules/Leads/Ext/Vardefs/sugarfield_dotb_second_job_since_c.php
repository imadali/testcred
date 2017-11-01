<?php
 // created: 2016-10-05 14:30:57
$dictionary['Lead']['fields']['dotb_second_job_since_c']['labelValue'] = 'Second job since';
$dictionary['Lead']['fields']['dotb_second_job_since_c']['enforced'] = 'false';
$dictionary['Lead']['fields']['dotb_second_job_since_c']['dependency'] = 'or(equal($dotb_has_second_job_c,"yes"),equal($dotb_has_second_income_c,"yes"))';
$dictionary['Lead']['fields']['dotb_second_job_since_c']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['dotb_second_job_since_c']['merge_filter'] = 'enabled';
$dictionary['Lead']['fields']['dotb_second_job_since_c']['full_text_search']['boost'] = 1;

