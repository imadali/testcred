<?php
// created: 2016-05-09 07:11:10
$dictionary["contracts_activities_1_calls"] = array (
  'relationships' => 
  array (
    'contracts_activities_1_calls' => 
    array (
      'lhs_module' => 'Contracts',
      'lhs_table' => 'contracts',
      'lhs_key' => 'id',
      'rhs_module' => 'Calls',
      'rhs_table' => 'calls',
      'relationship_role_column_value' => 'Contracts',
      'rhs_key' => 'parent_id',
      'relationship_type' => 'one-to-many',
      'relationship_role_column' => 'parent_type',
    ),
  ),
  'fields' => '',
  'indices' => '',
  'table' => '',
);