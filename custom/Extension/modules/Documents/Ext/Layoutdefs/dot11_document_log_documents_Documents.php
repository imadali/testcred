<?php
 // created: 2016-09-22 18:42:23
$layout_defs["Documents"]["subpanel_setup"]['dot11_document_log_documents'] = array (
  'order' => 100,
  'module' => 'dot11_document_log',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_DOT11_DOCUMENT_LOG_DOCUMENTS_FROM_DOT11_DOCUMENT_LOG_TITLE',
  'get_subpanel_data' => 'dot11_document_log_documents',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
  ),
);
