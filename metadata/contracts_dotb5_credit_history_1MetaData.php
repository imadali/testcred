<?php
// created: 2017-07-10 14:09:17
$dictionary['contracts_dotb5_credit_history_1'] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'contracts_dotb5_credit_history_1' => 
    array (
      'lhs_module' => 'Contracts',
      'lhs_table' => 'contracts',
      'lhs_key' => 'id',
      'rhs_module' => 'dotb5_credit_history',
      'rhs_table' => 'dotb5_credit_history',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'contracts_dotb5_credit_history_1_c',
      'join_key_lhs' => 'contracts_dotb5_credit_history_1contracts_ida',
      'join_key_rhs' => 'contracts_dotb5_credit_history_1dotb5_credit_history_idb',
    ),
  ),
  'table' => 'contracts_dotb5_credit_history_1_c',
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
    'contracts_dotb5_credit_history_1contracts_ida' => 
    array (
      'name' => 'contracts_dotb5_credit_history_1contracts_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    'contracts_dotb5_credit_history_1dotb5_credit_history_idb' => 
    array (
      'name' => 'contracts_dotb5_credit_history_1dotb5_credit_history_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'contracts_dotb5_credit_history_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'contracts_dotb5_credit_history_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'contracts_dotb5_credit_history_1contracts_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'contracts_dotb5_credit_history_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'contracts_dotb5_credit_history_1dotb5_credit_history_idb',
      ),
    ),
  ),
);