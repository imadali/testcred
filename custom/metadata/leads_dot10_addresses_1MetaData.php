<?php
// created: 2016-03-10 12:55:42
$dictionary["leads_dot10_addresses_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'leads_dot10_addresses_1' => 
    array (
      'lhs_module' => 'Leads',
      'lhs_table' => 'leads',
      'lhs_key' => 'id',
      'rhs_module' => 'dot10_addresses',
      'rhs_table' => 'dot10_addresses',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'leads_dot10_addresses_1_c',
      'join_key_lhs' => 'leads_dot10_addresses_1leads_ida',
      'join_key_rhs' => 'leads_dot10_addresses_1dot10_addresses_idb',
    ),
  ),
  'table' => 'leads_dot10_addresses_1_c',
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
      'name' => 'leads_dot10_addresses_1leads_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'leads_dot10_addresses_1dot10_addresses_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'leads_dot10_addresses_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'leads_dot10_addresses_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'leads_dot10_addresses_1leads_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'leads_dot10_addresses_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'leads_dot10_addresses_1dot10_addresses_idb',
      ),
    ),
  ),
);