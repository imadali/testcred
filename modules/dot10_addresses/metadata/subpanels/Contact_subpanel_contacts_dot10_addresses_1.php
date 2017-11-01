<?php
// created: 2016-05-21 16:06:22
$subpanel_layout['list_fields'] = array (
  'full_name' => 
  array (
    'type' => 'fullname',
    'link' => true,
    'studio' => 
    array (
      'listview' => false,
    ),
    'vname' => 'LBL_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'primary_address_street' => 
  array (
    'type' => 'text',
    'vname' => 'LBL_PRIMARY_ADDRESS_STREET',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
  'primary_address_postalcode' => 
  array (
    'type' => 'varchar',
    'vname' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
    'width' => '10%',
    'default' => true,
  ),
  'primary_address_city' => 
  array (
    'type' => 'varchar',
    'vname' => 'LBL_PRIMARY_ADDRESS_CITY',
    'width' => '10%',
    'default' => true,
  ),
  'dotb_resident_since_c' => 
  array (
    'type' => 'date',
    'default' => true,
    'vname' => 'LBL_DOTB_RESIDENT_SINCE',
    'width' => '10%',
  ),
  'dotb_resident_till_c' => 
  array (
    'type' => 'date',
    'default' => true,
    'vname' => 'LBL_DOTB_RESIDENT_TILL',
    'width' => '10%',
  ),
  'first_name' => 
  array (
    'name' => 'first_name',
    'usage' => 'query_only',
  ),
  'last_name' => 
  array (
    'name' => 'last_name',
    'usage' => 'query_only',
  ),
  'salutation' => 
  array (
    'name' => 'salutation',
    'usage' => 'query_only',
  ),
);