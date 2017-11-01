<?php
 // created: 2016-10-05 14:30:56
$dictionary['Lead']['fields']['dotb_housing_costs_c']['labelValue'] = 'Housing costs';
$dictionary['Lead']['fields']['dotb_housing_costs_c']['enforced'] = '';
$dictionary['Lead']['fields']['dotb_housing_costs_c']['dependency'] = 'equal($dotb_is_home_owner_c,"no")';
$dictionary['Lead']['fields']['dotb_housing_costs_c']['related_fields'][0] = 'currency_id';
$dictionary['Lead']['fields']['dotb_housing_costs_c']['related_fields'][1] = 'base_rate';
$dictionary['Lead']['fields']['dotb_housing_costs_c']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['dotb_housing_costs_c']['merge_filter'] = 'enabled';

