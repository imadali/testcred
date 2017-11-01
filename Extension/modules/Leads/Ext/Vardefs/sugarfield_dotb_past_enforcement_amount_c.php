<?php
 // created: 2016-10-05 14:30:57
$dictionary['Lead']['fields']['dotb_past_enforcement_amount_c']['labelValue'] = 'Past enforcement amount';
$dictionary['Lead']['fields']['dotb_past_enforcement_amount_c']['default'] = NULL;
$dictionary['Lead']['fields']['dotb_past_enforcement_amount_c']['enforced'] = '';
$dictionary['Lead']['fields']['dotb_past_enforcement_amount_c']['required'] = false;
$dictionary['Lead']['fields']['dotb_past_enforcement_amount_c']['dependency'] = 'equal($dotb_past_enforcements_c,"yes")';
$dictionary['Lead']['fields']['dotb_past_enforcement_amount_c']['type'] = 'currency';
$dictionary['Lead']['fields']['dotb_past_enforcement_amount_c']['related_fields'][0] = 'currency_id';
$dictionary['Lead']['fields']['dotb_past_enforcement_amount_c']['related_fields'][1] = 'base_rate';
$dictionary['Lead']['fields']['dotb_past_enforcement_amount_c']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['dotb_past_enforcement_amount_c']['merge_filter'] = 'enabled';

