<?php
// created: 2015-07-10 15:09:18
$dictionary["dotb5_credit_history_contacts"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'dotb5_credit_history_contacts' => 
    array (
      'lhs_module' => 'Contacts',
      'lhs_table' => 'contacts',
      'lhs_key' => 'id',
      'rhs_module' => 'dotb5_credit_history',
      'rhs_table' => 'dotb5_credit_history',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'dotb5_credit_history_contacts_c',
      'join_key_lhs' => 'dotb5_credit_history_contactscontacts_ida',
      'join_key_rhs' => 'dotb5_credit_history_contactsdotb5_credit_history_idb',
    ),
  ),
  'table' => 'dotb5_credit_history_contacts_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'dotb5_credit_history_contactscontacts_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'dotb5_credit_history_contactsdotb5_credit_history_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'dotb5_credit_history_contactsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'dotb5_credit_history_contacts_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'dotb5_credit_history_contactscontacts_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'dotb5_credit_history_contacts_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'dotb5_credit_history_contactsdotb5_credit_history_idb',
      ),
    ),
  ),
);