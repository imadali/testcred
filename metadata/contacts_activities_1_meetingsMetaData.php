<?php
// created: 2015-09-18 14:21:56
$dictionary["contacts_activities_1_meetings"] = array (
  'relationships' => 
  array (
    'contacts_activities_1_meetings' => 
    array (
      'lhs_module' => 'Contacts',
      'lhs_table' => 'contacts',
      'lhs_key' => 'id',
      'rhs_module' => 'Meetings',
      'rhs_table' => 'meetings',
      'relationship_role_column_value' => 'Contacts',
      'rhs_key' => 'parent_id',
      'relationship_type' => 'one-to-many',
      'relationship_role_column' => 'parent_type',
    ),
  ),
  'fields' => '',
  'indices' => '',
  'table' => '',
);