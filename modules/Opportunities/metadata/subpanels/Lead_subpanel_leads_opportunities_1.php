<?php
// created: 2016-03-31 11:09:32
$subpanel_layout['list_fields'] = array (
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_OPPORTUNITY_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '10%',
    'default' => true,
  ),
  'date_entered' => 
  array (
    'type' => 'datetime',
    'studio' => 
    array (
      'portaleditview' => false,
    ),
    'readonly' => true,
    'vname' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
  ),
  'description' => 
  array (
    'type' => 'text',
    'vname' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
  'provider_status_id_c' => 
  array (
    'type' => 'enum',
    'default' => true,
    'vname' => 'LBL_PROVIDER_STATUS_ID',
    'width' => '10%',
  ),
  'assigned_user_name' => 
  array (
    'name' => 'assigned_user_name',
    'vname' => 'LBL_LIST_ASSIGNED_TO_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'target_record_key' => 'assigned_user_id',
    'target_module' => 'Employees',
    'width' => '10%',
    'default' => true,
  ),
  'dotb_assigned_user_approval_c' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'vname' => 'LBL_DOTB_ASSIGNED_USER_APPROVAL',
    'width' => '10%',
  ),
  'currency_id' => 
  array (
    'usage' => 'query_only',
  ),
);