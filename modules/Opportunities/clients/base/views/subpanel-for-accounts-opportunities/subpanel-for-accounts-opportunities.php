<?php
// created: 2016-05-21 19:06:14
$viewdefs['Opportunities']['base']['view']['subpanel-for-accounts-opportunities'] = array (
  'type' => 'subpanel-list',
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
          'label' => 'LBL_LIST_OPPORTUNITY_NAME',
          'enabled' => true,
          'default' => true,
          'link' => true,
        ),
        1 => 
        array (
          'name' => 'date_entered',
          'label' => 'LBL_DATE_ENTERED',
          'enabled' => true,
          'readonly' => true,
          'default' => true,
        ),
        2 => 
        array (
          'name' => 'provider_id_c',
          'label' => 'LBL_PROVIDER_ID',
          'enabled' => true,
          'default' => true,
        ),
        3 => 
        array (
          'name' => 'assigned_user_name',
          'target_record_key' => 'assigned_user_id',
          'target_module' => 'Employees',
          'label' => 'LBL_LIST_ASSIGNED_TO_NAME',
          'enabled' => true,
          'default' => true,
        ),
        4 => 
        array (
          'name' => 'dotb_user_approval_c',
          'label' => 'LBL_USER_APPROVAL',
          'enabled' => true,
          'id' => 'USER_ID_C',
          'link' => true,
          'sortable' => false,
          'default' => true,
        ),
      ),
    ),
  ),
);