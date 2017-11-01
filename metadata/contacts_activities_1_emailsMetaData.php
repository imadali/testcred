<?php
// created: 2015-09-18 14:22:00
$dictionary["contacts_activities_1_emails"] = array (
  'relationships' => 
  array (
    'contacts_activities_1_emails' => 
    array (
      'lhs_module' => 'Contacts',
      'lhs_table' => 'contacts',
      'lhs_key' => 'id',
      'rhs_module' => 'Emails',
      'rhs_table' => 'emails',
      'relationship_role_column_value' => 'Contacts',
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