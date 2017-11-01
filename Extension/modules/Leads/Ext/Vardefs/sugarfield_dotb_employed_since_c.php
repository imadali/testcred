<?php
 // created: 2016-10-05 14:30:56
$dictionary['Lead']['fields']['dotb_employed_since_c']['labelValue'] = 'Angestellt seit';
$dictionary['Lead']['fields']['dotb_employed_since_c']['enforced'] = '';
$dictionary['Lead']['fields']['dotb_employed_since_c']['required'] = false;
$dictionary['Lead']['fields']['dotb_employed_since_c']['dependency'] = 'or(equal($dotb_employment_type_id_c,"permanent_contract"),equal($dotb_employment_type_id_c,"self_employed"),equal($dotb_employment_type_id_c,"temporary_contract"),equal($dotb_employment_type_id_c,"fixed_term_contract"),equal($dotb_employment_type_id_c,"hourly_wage"))';
$dictionary['Lead']['fields']['dotb_employed_since_c']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['dotb_employed_since_c']['merge_filter'] = 'enabled';
$dictionary['Lead']['fields']['dotb_employed_since_c']['full_text_search']['boost'] = 1;

