<?php
 // created: 2016-10-05 14:30:56
$dictionary['Lead']['fields']['dotb_employed_until_c']['labelValue'] = 'Angestellt bis';
$dictionary['Lead']['fields']['dotb_employed_until_c']['enforced'] = false;
$dictionary['Lead']['fields']['dotb_employed_until_c']['required'] = false;
$dictionary['Lead']['fields']['dotb_employed_until_c']['dependency'] = 'or(equal($dotb_employment_type_id_c,"temporary_contract"),equal($dotb_employment_type_id_c,"fixed_term_contract"))';
$dictionary['Lead']['fields']['dotb_employed_until_c']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['dotb_employed_until_c']['merge_filter'] = 'enabled';
$dictionary['Lead']['fields']['dotb_employed_until_c']['full_text_search']['boost'] = 1;

