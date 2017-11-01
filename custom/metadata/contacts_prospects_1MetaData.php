<?php
// created: 2017-05-08 16:59:22
$dictionary["contacts_prospects_1"] = array (
  'true_relationship_type' => 'many-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'contacts_prospects_1' => 
    array (
      'lhs_module' => 'Contacts',
      'lhs_table' => 'contacts',
      'lhs_key' => 'id',
      'rhs_module' => 'Prospects',
      'rhs_table' => 'prospects',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'contacts_prospects_1_c',
      'join_key_lhs' => 'contacts_prospects_1contacts_ida',
      'join_key_rhs' => 'contacts_prospects_1prospects_idb',
    ),
  ),
  'table' => 'contacts_prospects_1_c',
  'fields' => 
  array (
    'id' => 
    array (
      'name' => 'id',
      'type' => 'id',
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
      'default' => 0,
    ),
    'contacts_prospects_1contacts_ida' => 
    array (
      'name' => 'contacts_prospects_1contacts_ida',
      'type' => 'id',
    ),
    'contacts_prospects_1prospects_idb' => 
    array (
      'name' => 'contacts_prospects_1prospects_idb',
      'type' => 'id',
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'contacts_prospects_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'contacts_prospects_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'contacts_prospects_1contacts_ida',
        1 => 'contacts_prospects_1prospects_idb',
      ),
    ),
  ),
);