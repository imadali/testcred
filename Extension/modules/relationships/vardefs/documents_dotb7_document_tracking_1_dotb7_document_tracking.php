<?php
// created: 2016-02-17 12:58:54
$dictionary["dotb7_document_tracking"]["fields"]["documents_dotb7_document_tracking_1"] = array (
  'name' => 'documents_dotb7_document_tracking_1',
  'type' => 'link',
  'relationship' => 'documents_dotb7_document_tracking_1',
  'source' => 'non-db',
  'module' => 'Documents',
  'bean_name' => 'Document',
  'side' => 'right',
  'vname' => 'LBL_DOCUMENTS_DOTB7_DOCUMENT_TRACKING_1_FROM_DOTB7_DOCUMENT_TRACKING_TITLE',
  'id_name' => 'documents_dotb7_document_tracking_1documents_ida',
  'link-type' => 'one',
);
$dictionary["dotb7_document_tracking"]["fields"]["documents_dotb7_document_tracking_1_name"] = array (
  'name' => 'documents_dotb7_document_tracking_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_DOCUMENTS_DOTB7_DOCUMENT_TRACKING_1_FROM_DOCUMENTS_TITLE',
  'save' => true,
  'id_name' => 'documents_dotb7_document_tracking_1documents_ida',
  'link' => 'documents_dotb7_document_tracking_1',
  'table' => 'documents',
  'module' => 'Documents',
  'rname' => 'document_name',
);
$dictionary["dotb7_document_tracking"]["fields"]["documents_dotb7_document_tracking_1documents_ida"] = array (
  'name' => 'documents_dotb7_document_tracking_1documents_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_DOCUMENTS_DOTB7_DOCUMENT_TRACKING_1_FROM_DOTB7_DOCUMENT_TRACKING_TITLE_ID',
  'id_name' => 'documents_dotb7_document_tracking_1documents_ida',
  'link' => 'documents_dotb7_document_tracking_1',
  'table' => 'documents',
  'module' => 'Documents',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
