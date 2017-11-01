<?php
// created: 2016-05-09 07:11:35
$dictionary["contracts_activities_1_notes"] = array (
  'relationships' => 
  array (
    'contracts_activities_1_notes' => 
    array (
      'lhs_module' => 'Contracts',
      'lhs_table' => 'contracts',
      'lhs_key' => 'id',
      'rhs_module' => 'Notes',
      'rhs_table' => 'notes',
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