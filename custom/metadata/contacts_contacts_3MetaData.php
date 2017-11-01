<?php
// created: 2016-03-15 15:13:54
$dictionary["contacts_contacts_3"] = array (
  'true_relationship_type' => 'many-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'contacts_contacts_3' => 
    array (
      'lhs_module' => 'Contacts',
      'lhs_table' => 'contacts',
      'lhs_key' => 'id',
      'rhs_module' => 'Contacts',
      'rhs_table' => 'contacts',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'contacts_contacts_3_c',
      'join_key_lhs' => 'contacts_contacts_3contacts_ida',
      'join_key_rhs' => 'contacts_contacts_3contacts_idb',
    ),
  ),
  'table' => 'contacts_contacts_3_c',
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
      'name' => 'contacts_contacts_3contacts_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'contacts_contacts_3contacts_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'contacts_contacts_3spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'contacts_contacts_3_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'contacts_contacts_3contacts_ida',
        1 => 'contacts_contacts_3contacts_idb',
      ),
    ),
  ),
);