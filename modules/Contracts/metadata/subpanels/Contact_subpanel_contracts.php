<?php
// created: 2016-06-09 11:23:54
$subpanel_layout['list_fields'] = array (
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'module' => 'Contacts',
    'width' => '10%',
    'default' => true,
  ),
  'account_name' => 
  array (
    'name' => 'account_name',
    'module' => 'Accounts',
    'target_record_key' => 'account_id',
    'target_module' => 'Accounts',
    'widget_class' => 'SubPanelDetailViewLink',
    'vname' => 'LBL_LIST_ACCOUNT_NAME',
    'width' => '10%',
    'sortable' => false,
    'default' => true,
  ),
  'start_date' => 
  array (
    'name' => 'start_date',
    'vname' => 'LBL_LIST_START_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'end_date' => 
  array (
    'name' => 'end_date',
    'vname' => 'LBL_LIST_END_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'total_contract_value' => 
  array (
    'type' => 'currency',
    'related_fields' => 
    array (
      0 => 'currency_id',
      1 => 'base_rate',
    ),
    'vname' => 'LBL_TOTAL_CONTRACT_VALUE',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
  ),
  'currency_id' => 
  array (
    'name' => 'currency_id',
    'usage' => 'query_only',
  ),
);