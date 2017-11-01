<?php
// created: 2016-02-22 16:22:32
$subpanel_layout['list_fields'] = array (
  'name' => 
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '45%',
    'default' => true,
  ),
  'monthly_credit_rate' => 
  array (
    'type' => 'int',
    'default' => true,
    'vname' => 'LBL_MONTHLY_CREDIT_RATE',
    'width' => '10%',
  ),
  'credit_provider' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'vname' => 'LBL_CREDIT_PROVIDER',
    'width' => '10%',
  ),
  'credit_end_date' => 
  array (
    'type' => 'date',
    'vname' => 'LBL_CREDIT_END_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'edit_button' => 
  array (
    'vname' => 'LBL_EDIT_BUTTON',
    'widget_class' => 'SubPanelEditButton',
    'module' => 'dotb5_credit_history',
    'width' => '4%',
    'default' => true,
  ),
  'remove_button' => 
  array (
    'vname' => 'LBL_REMOVE',
    'widget_class' => 'SubPanelRemoveButton',
    'module' => 'dotb5_credit_history',
    'width' => '5%',
    'default' => true,
  ),
);