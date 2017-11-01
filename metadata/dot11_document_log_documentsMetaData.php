<?php
// created: 2017-07-10 14:09:17
$dictionary['dot11_document_log_documents'] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'dot11_document_log_documents' => 
    array (
      'lhs_module' => 'Documents',
      'lhs_table' => 'documents',
      'lhs_key' => 'id',
      'rhs_module' => 'dot11_document_log',
      'rhs_table' => 'dot11_document_log',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'dot11_document_log_documents_c',
      'join_key_lhs' => 'dot11_document_log_documentsdocuments_ida',
      'join_key_rhs' => 'dot11_document_log_documentsdot11_document_log_idb',
    ),
  ),
  'table' => 'dot11_document_log_documents_c',
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
    'dot11_document_log_documentsdocuments_ida' => 
    array (
      'name' => 'dot11_document_log_documentsdocuments_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    'dot11_document_log_documentsdot11_document_log_idb' => 
    array (
      'name' => 'dot11_document_log_documentsdot11_document_log_idb',
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
      'name' => 'dot11_document_log_documentsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'dot11_document_log_documents_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'dot11_document_log_documentsdocuments_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'dot11_document_log_documents_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'dot11_document_log_documentsdot11_document_log_idb',
      ),
    ),
  ),
);