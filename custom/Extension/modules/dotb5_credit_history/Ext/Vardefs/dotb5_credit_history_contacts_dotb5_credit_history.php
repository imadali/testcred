<?php
// created: 2015-07-10 15:09:18
$dictionary["dotb5_credit_history"]["fields"]["dotb5_credit_history_contacts"] = array (
  'name' => 'dotb5_credit_history_contacts',
  'type' => 'link',
  'relationship' => 'dotb5_credit_history_contacts',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'side' => 'right',
  'vname' => 'LBL_DOTB5_CREDIT_HISTORY_CONTACTS_FROM_DOTB5_CREDIT_HISTORY_TITLE',
  'id_name' => 'dotb5_credit_history_contactscontacts_ida',
  'link-type' => 'one',
);
$dictionary["dotb5_credit_history"]["fields"]["dotb5_credit_history_contacts_name"] = array (
  'name' => 'dotb5_credit_history_contacts_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_DOTB5_CREDIT_HISTORY_CONTACTS_FROM_CONTACTS_TITLE',
  'save' => true,
  'id_name' => 'dotb5_credit_history_contactscontacts_ida',
  'link' => 'dotb5_credit_history_contacts',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'full_name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["dotb5_credit_history"]["fields"]["dotb5_credit_history_contactscontacts_ida"] = array (
  'name' => 'dotb5_credit_history_contactscontacts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_DOTB5_CREDIT_HISTORY_CONTACTS_FROM_DOTB5_CREDIT_HISTORY_TITLE_ID',
  'id_name' => 'dotb5_credit_history_contactscontacts_ida',
  'link' => 'dotb5_credit_history_contacts',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
