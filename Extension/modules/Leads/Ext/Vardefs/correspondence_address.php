<?php

$dictionary['Lead']['fields']['correspondence_address_c_o'] = array(
    'required' => false,
    'name' => 'correspondence_address_c_o',
    'vname' => 'LBL_CORRESPONDENCE_ADDRESS_C_O',
    'type' => 'varchar',
    'len' => '255',
    'size' => '20',
    'merge_filter' => 'enabled',
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '1',
);
$dictionary['Lead']['fields']['correspondence_address_street'] = array(
    'name' => 'correspondence_address_street',
    'vname' => 'LBL_CORRESPONDENCE_ADDRESS_STREET',
    'type' => 'text',
    'dbType' => 'varchar',
    'len' => '150',
    'comment' => 'The street address used for correspondence address',
    'group' => 'correspondence_address',
    'merge_filter' => 'enabled',
    'duplicate_on_record_copy' => 'always',
    'audited' => false,
    'massupdate' => false,
    'comments' => 'The street address used for correspondence address',
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '1',
    'full_text_search' =>
    array(
        'boost' => '0',
        'enabled' => false,
    ),
    'calculated' => false,
    'rows' => '4',
    'cols' => '20',
);
$dictionary['Lead']['fields']['correspondence_address_city'] = array(
    'name' => 'correspondence_address_city',
    'vname' => 'LBL_CORRESPONDENCE_ADDRESS_CITY',
    'type' => 'varchar',
    'len' => '100',
    'group' => 'correspondence_address',
    'comment' => 'City for correspondence address',
    'merge_filter' => 'enabled',
    'duplicate_on_record_copy' => 'always',
    'audited' => true,
    'massupdate' => false,
    'comments' => 'City for correspondence address',
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '1',
    'full_text_search' =>
    array(
        'boost' => '0',
        'enabled' => false,
    ),
    'calculated' => false,
    'required' => false,
);
$dictionary['Lead']['fields']['correspondence_address_postalcode'] = array(
    'name' => 'correspondence_address_postalcode',
    'vname' => 'LBL_CORRESPONDENCE_ADDRESS_POSTALCODE',
    'type' => 'varchar',
    'len' => '20',
    'group' => 'correspondence_address',
    'comment' => 'Postal code for correspondence address',
    'merge_filter' => 'enabled',
    'duplicate_on_record_copy' => 'always',
    'audited' => true,
    'massupdate' => false,
    'comments' => 'Postal code for correspondence address',
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '1',
    'full_text_search' =>
    array(
        'boost' => '0',
        'enabled' => false,
    ),
    'calculated' => false,
    'required' => false,
);
$dictionary['Lead']['fields']['correspondence_address_country'] = array(
    'name' => 'correspondence_address_country',
    'vname' => 'LBL_CORRESPONDENCE_ADDRESS_COUNTRY',
    'type' => 'varchar',
    'len' => '20',
    'group' => 'correspondence_address',
    'comment' => 'Country for correspondence address',
    'merge_filter' => 'enabled',
    'duplicate_on_record_copy' => 'always',
    'audited' => true,
    'massupdate' => false,
    'comments' => 'Country for correspondence address',
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '1',
    'full_text_search' =>
    array(
        'boost' => '0',
        'enabled' => false,
    ),
    'calculated' => true,
    'formula' => 'getDropdownValue("dotb_postcode_list",$correspondence_address_postalcode)',
    'required' => false,
    'enforced' => false,
);
?>