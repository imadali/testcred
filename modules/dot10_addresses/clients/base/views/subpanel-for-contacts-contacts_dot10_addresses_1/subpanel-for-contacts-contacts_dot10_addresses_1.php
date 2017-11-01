<?php
// created: 2016-05-21 16:06:32
$viewdefs['dot10_addresses']['base']['view']['subpanel-for-contacts-contacts_dot10_addresses_1'] = array (
  'panels' => 
  array (
    0 => 
    array (
      'name' => 'panel_header',
      'label' => 'LBL_PANEL_1',
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'full_name',
          'type' => 'fullname',
          'fields' => 
          array (
            0 => 'salutation',
            1 => 'first_name',
            2 => 'last_name',
          ),
          'link' => true,
          'css_class' => 'full-name',
          'label' => 'LBL_LIST_NAME',
          'enabled' => true,
          'default' => true,
        ),
        1 => 
        array (
          'name' => 'primary_address_street',
          'label' => 'LBL_PRIMARY_ADDRESS_STREET',
          'enabled' => true,
          'sortable' => false,
          'default' => true,
        ),
        2 => 
        array (
          'name' => 'primary_address_postalcode',
          'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
          'enabled' => true,
          'default' => true,
        ),
        3 => 
        array (
          'name' => 'primary_address_city',
          'label' => 'LBL_PRIMARY_ADDRESS_CITY',
          'enabled' => true,
          'default' => true,
        ),
        4 => 
        array (
          'name' => 'dotb_resident_since_c',
          'label' => 'LBL_DOTB_RESIDENT_SINCE',
          'enabled' => true,
          'default' => true,
        ),
        5 => 
        array (
          'name' => 'dotb_resident_till_c',
          'label' => 'LBL_DOTB_RESIDENT_TILL',
          'enabled' => true,
          'default' => true,
        ),
      ),
    ),
  ),
  'type' => 'subpanel-list',
);