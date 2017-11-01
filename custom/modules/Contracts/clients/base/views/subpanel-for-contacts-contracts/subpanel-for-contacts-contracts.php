<?php
// created: 2016-06-09 11:24:03
$viewdefs['Contracts']['base']['view']['subpanel-for-contacts-contracts'] = array (
  'panels' => 
  array (
    0 => 
    array (
      'name' => 'panel_header',
      'label' => 'LBL_PANEL_1',
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'name',
          'label' => 'LBL_LIST_NAME',
          'enabled' => true,
          'default' => true,
          'link' => true,
        ),
        1 => 
        array (
          'name' => 'account_name',
          'target_record_key' => 'account_id',
          'target_module' => 'Accounts',
          'label' => 'LBL_LIST_ACCOUNT_NAME',
          'enabled' => true,
          'default' => true,
        ),
        2 => 
        array (
          'name' => 'start_date',
          'label' => 'LBL_LIST_START_DATE',
          'enabled' => true,
          'default' => true,
        ),
        3 => 
        array (
          'name' => 'end_date',
          'label' => 'LBL_LIST_END_DATE',
          'enabled' => true,
          'default' => true,
        ),
        4 => 
        array (
          'name' => 'total_contract_value',
          'label' => 'LBL_LIST_CONTRACT_VALUE',
          'related_fields' => 
          array (
            0 => 'total_contract_value',
            1 => 'currency_id',
            2 => 'base_rate',
          ),
          'currency_field' => 'currency_id',
          'base_rate_field' => 'base_rate',
          'enabled' => true,
          'default' => true,
        ),
      ),
    ),
  ),
  'rowactions' => 
  array (
    'actions' => 
    array (
      0 => 
      array (
        'type' => 'rowaction',
        'name' => 'edit_button',
        'icon' => 'fa-pencil',
        'label' => 'LBL_EDIT_BUTTON',
        'event' => 'list:editrow:fire',
        'acl_action' => 'edit',
        'allow_bwc' => true,
      ),
      1 => 
      array (
        'type' => 'unlink-action',
        'icon' => 'fa-chain-broken',
        'label' => 'LBL_UNLINK_BUTTON',
      ),
    ),
  ),
  'type' => 'subpanel-list',
);