<?php
// created: 2016-09-22 18:42:23
$dictionary["dot11_document_log_documents"] = array (
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
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'dot11_document_log_documentsdocuments_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'dot11_document_log_documentsdot11_document_log_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
    5 => 
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