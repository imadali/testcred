<?php
// created: 2016-02-19 18:03:55
$dictionary["Contract"]["fields"]["contacts_contracts_1"] = array (
  'name' => 'contacts_contracts_1',
  'type' => 'link',
  'relationship' => 'contacts_contracts_1',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'side' => 'right',
  'vname' => 'LBL_CONTACTS_CONTRACTS_1_FROM_CONTRACTS_TITLE',
  'id_name' => 'contacts_contracts_1contacts_ida',
  'link-type' => 'one',
);
$dictionary["Contract"]["fields"]["contacts_contracts_1_name"] = array (
  'name' => 'contacts_contracts_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CONTACTS_CONTRACTS_1_FROM_CONTACTS_TITLE',
  'save' => true,
  'id_name' => 'contacts_contracts_1contacts_ida',
  'link' => 'contacts_contracts_1',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'full_name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["Contract"]["fields"]["contacts_contracts_1contacts_ida"] = array (
  'name' => 'contacts_contracts_1contacts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_CONTACTS_CONTRACTS_1_FROM_CONTRACTS_TITLE_ID',
  'id_name' => 'contacts_contracts_1contacts_ida',
  'link' => 'contacts_contracts_1',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
