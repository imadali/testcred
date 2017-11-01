<?php
// created: 2016-09-22 18:42:23
$dictionary["dot11_document_log"]["fields"]["dot11_document_log_documents"] = array (
  'name' => 'dot11_document_log_documents',
  'type' => 'link',
  'relationship' => 'dot11_document_log_documents',
  'source' => 'non-db',
  'module' => 'Documents',
  'bean_name' => 'Document',
  'side' => 'right',
  'vname' => 'LBL_DOT11_DOCUMENT_LOG_DOCUMENTS_FROM_DOT11_DOCUMENT_LOG_TITLE',
  'id_name' => 'dot11_document_log_documentsdocuments_ida',
  'link-type' => 'one',
);
$dictionary["dot11_document_log"]["fields"]["dot11_document_log_documents_name"] = array (
  'name' => 'dot11_document_log_documents_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_DOT11_DOCUMENT_LOG_DOCUMENTS_FROM_DOCUMENTS_TITLE',
  'save' => true,
  'id_name' => 'dot11_document_log_documentsdocuments_ida',
  'link' => 'dot11_document_log_documents',
  'table' => 'documents',
  'module' => 'Documents',
  'rname' => 'document_name',
);
$dictionary["dot11_document_log"]["fields"]["dot11_document_log_documentsdocuments_ida"] = array (
  'name' => 'dot11_document_log_documentsdocuments_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_DOT11_DOCUMENT_LOG_DOCUMENTS_FROM_DOT11_DOCUMENT_LOG_TITLE_ID',
  'id_name' => 'dot11_document_log_documentsdocuments_ida',
  'link' => 'dot11_document_log_documents',
  'table' => 'documents',
  'module' => 'Documents',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
