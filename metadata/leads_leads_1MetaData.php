<?php
// created: 2017-07-10 14:09:17
$dictionary['leads_leads_1'] = array (
  'true_relationship_type' => 'many-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'leads_leads_1' => 
    array (
      'lhs_module' => 'Leads',
      'lhs_table' => 'leads',
      'lhs_key' => 'id',
      'rhs_module' => 'Leads',
      'rhs_table' => 'leads',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'leads_leads_1_c',
      'join_key_lhs' => 'leads_leads_1leads_ida',
      'join_key_rhs' => 'leads_leads_1leads_idb',
    ),
  ),
  'table' => 'leads_leads_1_c',
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
    'leads_leads_1leads_ida' => 
    array (
      'name' => 'leads_leads_1leads_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    'leads_leads_1leads_idb' => 
    array (
      'name' => 'leads_leads_1leads_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'leads_leads_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'leads_leads_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'leads_leads_1leads_ida',
        1 => 'leads_leads_1leads_idb',
      ),
    ),
  ),
);