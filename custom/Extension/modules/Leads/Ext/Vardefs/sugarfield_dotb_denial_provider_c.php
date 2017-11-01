<?php
 // created: 2016-10-05 14:30:56
$dictionary['Lead']['fields']['dotb_denial_provider_c']['labelValue'] = 'Denial provider';
$dictionary['Lead']['fields']['dotb_denial_provider_c']['full_text_search']['enabled'] = true;
$dictionary['Lead']['fields']['dotb_denial_provider_c']['full_text_search']['searchable'] = false;
$dictionary['Lead']['fields']['dotb_denial_provider_c']['full_text_search']['boost'] = 1;
$dictionary['Lead']['fields']['dotb_denial_provider_c']['enforced'] = '';
$dictionary['Lead']['fields']['dotb_denial_provider_c']['dependency'] = 'equal($dotb_credit_denial_in_last_6_c,"yes")';
$dictionary['Lead']['fields']['dotb_denial_provider_c']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['dotb_denial_provider_c']['merge_filter'] = 'enabled';

