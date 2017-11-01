<?php
// created: 2017-07-10 14:09:17
$dictionary['contracts_leads_1'] = array (
  'true_relationship_type' => 'one-to-one',
  'from_studio' => true,
  'relationships' => 
  array (
    'contracts_leads_1' => 
    array (
      'lhs_module' => 'Contracts',
      'lhs_table' => 'contracts',
      'lhs_key' => 'id',
      'rhs_module' => 'Leads',
      'rhs_table' => 'leads',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'contracts_leads_1_c',
      'join_key_lhs' => 'contracts_leads_1contracts_ida',
      'join_key_rhs' => 'contracts_leads_1leads_idb',
    ),
  ),
  'table' => 'contracts_leads_1_c',
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
    'contracts_leads_1contracts_ida' => 
    array (
      'name' => 'contracts_leads_1contracts_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    'contracts_leads_1leads_idb' => 
    array (
      'name' => 'contracts_leads_1leads_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'contracts_leads_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'contracts_leads_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'contracts_leads_1contracts_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'contracts_leads_1_idb2',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'contracts_leads_1leads_idb',
      ),
    ),
  ),
);