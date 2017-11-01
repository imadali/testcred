<?php
 // created: 2016-10-05 14:30:56
$dictionary['Lead']['fields']['dotb_aliments_c']['labelValue'] = 'Aliments';
$dictionary['Lead']['fields']['dotb_aliments_c']['enforced'] = '';
$dictionary['Lead']['fields']['dotb_aliments_c']['dependency'] = 'equal($dotb_has_alimony_payments_c,"yes")';
$dictionary['Lead']['fields']['dotb_aliments_c']['type'] = 'currency';
$dictionary['Lead']['fields']['dotb_aliments_c']['default'] = NULL;
$dictionary['Lead']['fields']['dotb_aliments_c']['related_fields'][0] = 'currency_id';
$dictionary['Lead']['fields']['dotb_aliments_c']['related_fields'][1] = 'base_rate';
$dictionary['Lead']['fields']['dotb_aliments_c']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['dotb_aliments_c']['merge_filter'] = 'enabled';
$dictionary['Lead']['fields']['dotb_aliments_c']['audited'] = true;

