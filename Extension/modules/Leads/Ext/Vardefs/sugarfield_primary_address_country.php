<?php
 // created: 2016-10-05 14:30:57
$dictionary['Lead']['fields']['primary_address_country']['audited'] = false;
$dictionary['Lead']['fields']['primary_address_country']['massupdate'] = false;
$dictionary['Lead']['fields']['primary_address_country']['comments'] = 'Country for primary address';
$dictionary['Lead']['fields']['primary_address_country']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['primary_address_country']['duplicate_merge_dom_value'] = 0;
$dictionary['Lead']['fields']['primary_address_country']['merge_filter'] = 'enabled';
$dictionary['Lead']['fields']['primary_address_country']['calculated'] = 'true';
$dictionary['Lead']['fields']['primary_address_country']['importable'] = 'false';
$dictionary['Lead']['fields']['primary_address_country']['full_text_search']['enabled'] = true;
$dictionary['Lead']['fields']['primary_address_country']['full_text_search']['searchable'] = false;
$dictionary['Lead']['fields']['primary_address_country']['full_text_search']['boost'] = 1;
$dictionary['Lead']['fields']['primary_address_country']['formula'] = 'getDropdownValue("dotb_postcode_list",$primary_address_postalcode)';
$dictionary['Lead']['fields']['primary_address_country']['enforced'] = false;

