<?php
 // created: 2016-10-05 14:30:56
$dictionary['Lead']['fields']['dotb_is_rent_split_c']['labelValue'] = 'Rent split';
$dictionary['Lead']['fields']['dotb_is_rent_split_c']['enforced'] = '';
$dictionary['Lead']['fields']['dotb_is_rent_split_c']['dependency'] = 'equal($dotb_housing_situation_id_c,"flat_share")';
$dictionary['Lead']['fields']['dotb_is_rent_split_c']['len'] = 100;
$dictionary['Lead']['fields']['dotb_is_rent_split_c']['size'] = 255;
$dictionary['Lead']['fields']['dotb_is_rent_split_c']['type'] = 'enum';
$dictionary['Lead']['fields']['dotb_is_rent_split_c']['options'] = 'lq_yes_no_status_list';
$dictionary['Lead']['fields']['dotb_is_rent_split_c']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['dotb_is_rent_split_c']['merge_filter'] = 'enabled';

