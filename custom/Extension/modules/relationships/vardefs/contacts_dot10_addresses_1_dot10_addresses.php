<?php
// created: 2016-02-19 12:27:57
$dictionary["dot10_addresses"]["fields"]["contacts_dot10_addresses_1"] = array (
  'name' => 'contacts_dot10_addresses_1',
  'type' => 'link',
  'relationship' => 'contacts_dot10_addresses_1',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'side' => 'right',
  'vname' => 'LBL_CONTACTS_DOT10_ADDRESSES_1_FROM_DOT10_ADDRESSES_TITLE',
  'id_name' => 'contacts_dot10_addresses_1contacts_ida',
  'link-type' => 'one',
);
$dictionary["dot10_addresses"]["fields"]["contacts_dot10_addresses_1_name"] = array (
  'name' => 'contacts_dot10_addresses_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CONTACTS_DOT10_ADDRESSES_1_FROM_CONTACTS_TITLE',
  'save' => true,
  'id_name' => 'contacts_dot10_addresses_1contacts_ida',
  'link' => 'contacts_dot10_addresses_1',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'full_name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["dot10_addresses"]["fields"]["contacts_dot10_addresses_1contacts_ida"] = array (
  'name' => 'contacts_dot10_addresses_1contacts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_CONTACTS_DOT10_ADDRESSES_1_FROM_DOT10_ADDRESSES_TITLE_ID',
  'id_name' => 'contacts_dot10_addresses_1contacts_ida',
  'link' => 'contacts_dot10_addresses_1',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
