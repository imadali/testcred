<?php
// created: 2016-05-09 07:11:54
$dictionary["contracts_activities_1_emails"] = array (
  'relationships' => 
  array (
    'contracts_activities_1_emails' => 
    array (
      'lhs_module' => 'Contracts',
      'lhs_table' => 'contracts',
      'lhs_key' => 'id',
      'rhs_module' => 'Emails',
      'rhs_table' => 'emails',
      'relationship_role_column_value' => 'Contracts',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'emails_beans',
      'join_key_rhs' => 'email_id',
      'join_key_lhs' => 'bean_id',
      'relationship_role_column' => 'bean_module',
    ),
  ),
  'fields' => '',
  'indices' => '',
  'table' => '',
);