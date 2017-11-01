<?php
 // created: 2016-10-05 14:30:56
$dictionary['Lead']['fields']['correspondence_address_country']['calculated'] = 'true';
$dictionary['Lead']['fields']['correspondence_address_country']['formula'] = 'getDropdownValue("dotb_postcode_list",$correspondence_address_postalcode)';
$dictionary['Lead']['fields']['correspondence_address_country']['enforced'] = false;
$dictionary['Lead']['fields']['correspondence_address_country']['duplicate_merge'] = 'enabled';
$dictionary['Lead']['fields']['correspondence_address_country']['merge_filter'] = 'enabled';
$dictionary['Lead']['fields']['correspondence_address_country']['full_text_search']['enabled'] = true;
$dictionary['Lead']['fields']['correspondence_address_country']['full_text_search']['searchable'] = false;
$dictionary['Lead']['fields']['correspondence_address_country']['full_text_search']['boost'] = 1;

