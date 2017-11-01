<?php
 // created: 2016-10-05 14:30:55
$dictionary['Contact']['fields']['primary_address_country']['default'] = '';
$dictionary['Contact']['fields']['primary_address_country']['audited'] = false;
$dictionary['Contact']['fields']['primary_address_country']['massupdate'] = false;
$dictionary['Contact']['fields']['primary_address_country']['comments'] = 'Country for primary address';
$dictionary['Contact']['fields']['primary_address_country']['duplicate_merge'] = 'enabled';
$dictionary['Contact']['fields']['primary_address_country']['duplicate_merge_dom_value'] = '1';
$dictionary['Contact']['fields']['primary_address_country']['merge_filter'] = 'enabled';
$dictionary['Contact']['fields']['primary_address_country']['calculated'] = '1';
$dictionary['Contact']['fields']['primary_address_country']['formula'] = 'ifElse(equal(getDropdownValue("dotb_postcode_list",$primary_address_postalcode),""),$primary_address_country,getDropdownValue("dotb_postcode_list",$primary_address_postalcode))';
$dictionary['Contact']['fields']['primary_address_country']['enforced'] = false;
$dictionary['Contact']['fields']['primary_address_country']['full_text_search']['enabled'] = true;
$dictionary['Contact']['fields']['primary_address_country']['full_text_search']['searchable'] = false;
$dictionary['Contact']['fields']['primary_address_country']['full_text_search']['boost'] = 1;

