<?php
// created: 2017-07-10 14:09:17
$dictionary['contacts_contracts_1'] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'contacts_contracts_1' => 
    array (
      'lhs_module' => 'Contacts',
      'lhs_table' => 'contacts',
      'lhs_key' => 'id',
      'rhs_module' => 'Contracts',
      'rhs_table' => 'contracts',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'contacts_contracts_1_c',
      'join_key_lhs' => 'contacts_contracts_1contacts_ida',
      'join_key_rhs' => 'contacts_contracts_1contracts_idb',
    ),
  ),
  'table' => 'contacts_contracts_1_c',
  'fields' => 
  array (
    'id' => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    'date_modified' => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    'deleted' => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    'contacts_contracts_1contacts_ida' => 
    array (
      'name' => 'contacts_contracts_1contacts_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    'contacts_contracts_1contracts_idb' => 
    array (
      'name' => 'contacts_contracts_1contracts_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'contacts_contracts_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'contacts_contracts_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'contacts_contracts_1contacts_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'contacts_contracts_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'contacts_contracts_1contracts_idb',
      ),
    ),
  ),
);