<?php
 // created: 2016-10-05 14:30:57
$dictionary['Lead']['fields']['dotb_rent_or_alimony_income_c']['labelValue'] = 'Rent or Alimony income';
$dictionary['Lead']['fields']['dotb_rent_or_alimony_income_c']['enforced'] = '';
$dictionary['Lead']['fields']['dotb_rent_or_alimony_income_c']['dependency'] = 'equal($dotb_rent_alimony_income_c,"yes")';
$dictionary['Lead']['fields']['dotb_rent_or_alimony_income_c']['type'] = 'currency';
$dictionary['Lead']['fields']['dotb_rent_or_alimony_income_c']['default'] = NULL;
$dictionary['Lead']['fields']['dotb_rent_or_alimony_income_c']['related_fields'][0] = 'currency_id';
$dictionary['Lead']['fields']['dotb_rent_or_alimony_income_c']['related_fields'][1] = 'base_rate';
$dictionary['Lead']['fields']['dotb_rent_or_alimony_income_c']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['dotb_rent_or_alimony_income_c']['merge_filter'] = 'enabled';

