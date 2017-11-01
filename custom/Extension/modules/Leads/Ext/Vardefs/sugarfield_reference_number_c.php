<?php
 // created: 2016-10-05 14:30:57
$dictionary['Lead']['fields']['reference_number_c']['labelValue'] = 'Reference no';
$dictionary['Lead']['fields']['reference_number_c']['full_text_search']['enabled'] = true;
$dictionary['Lead']['fields']['reference_number_c']['full_text_search']['searchable'] = false;
$dictionary['Lead']['fields']['reference_number_c']['full_text_search']['boost'] = 1;
$dictionary['Lead']['fields']['reference_number_c']['formula'] = 'add(max($reference_number_c),1)';
$dictionary['Lead']['fields']['reference_number_c']['enforced'] = false;
$dictionary['Lead']['fields']['reference_number_c']['dependency'] = '';
$dictionary['Lead']['fields']['reference_number_c']['required'] = false;
$dictionary['Lead']['fields']['reference_number_c']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['reference_number_c']['merge_filter'] = 'enabled';

