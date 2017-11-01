<?php
// created: 2017-07-10 14:09:17
$dictionary['documents_dotb7_document_tracking_1'] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'documents_dotb7_document_tracking_1' => 
    array (
      'lhs_module' => 'Documents',
      'lhs_table' => 'documents',
      'lhs_key' => 'id',
      'rhs_module' => 'dotb7_document_tracking',
      'rhs_table' => 'dotb7_document_tracking',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'documents_dotb7_document_tracking_1_c',
      'join_key_lhs' => 'documents_dotb7_document_tracking_1documents_ida',
      'join_key_rhs' => 'documents_dotb7_document_tracking_1dotb7_document_tracking_idb',
    ),
  ),
  'table' => 'documents_dotb7_document_tracking_1_c',
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
    'documents_dotb7_document_tracking_1documents_ida' => 
    array (
      'name' => 'documents_dotb7_document_tracking_1documents_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    'documents_dotb7_document_tracking_1dotb7_document_tracking_idb' => 
    array (
      'name' => 'documents_dotb7_document_tracking_1dotb7_document_tracking_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
    'document_revision_id' => 
    array (
      'name' => 'document_revision_id',
      'type' => 'varchar',
      'len' => '36',
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'documents_dotb7_document_tracking_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'documents_dotb7_document_tracking_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'documents_dotb7_document_tracking_1documents_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'documents_dotb7_document_tracking_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'documents_dotb7_document_tracking_1dotb7_document_tracking_idb',
      ),
    ),
  ),
);