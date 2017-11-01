<?php
// created: 2016-02-27 16:09:02
$dictionary["Contact"]["fields"]["contacts_contacts_2"] = array (
  'name' => 'contacts_contacts_2',
  'type' => 'link',
  'relationship' => 'contacts_contacts_2',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'vname' => 'LBL_CONTACTS_CONTACTS_2_FROM_CONTACTS_L_TITLE',
  'id_name' => 'contacts_contacts_2contacts_idb',
  'link-type' => 'many',
  'side' => 'left',
);
$dictionary["Contact"]["fields"]["contacts_contacts_2_right"] = array (
  'name' => 'contacts_contacts_2_right',
  'type' => 'link',
  'relationship' => 'contacts_contacts_2',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'side' => 'right',
  'vname' => 'LBL_CONTACTS_CONTACTS_2_FROM_CONTACTS_R_TITLE',
  'id_name' => 'contacts_contacts_2contacts_ida',
  'link-type' => 'one',
);
$dictionary["Contact"]["fields"]["contacts_contacts_2_name"] = array (
  'name' => 'contacts_contacts_2_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CONTACTS_CONTACTS_2_FROM_CONTACTS_L_TITLE',
  'save' => true,
  'id_name' => 'contacts_contacts_2contacts_ida',
  'link' => 'contacts_contacts_2_right',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'full_name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["Contact"]["fields"]["contacts_contacts_2contacts_ida"] = array (
  'name' => 'contacts_contacts_2contacts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_CONTACTS_CONTACTS_2_FROM_CONTACTS_R_TITLE_ID',
  'id_name' => 'contacts_contacts_2contacts_ida',
  'link' => 'contacts_contacts_2_right',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
