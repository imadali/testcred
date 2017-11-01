<?php
// created: 2016-04-18 16:42:44
$subpanel_layout['list_fields'] = array (
  'name' => 
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '10%',
    'default' => true,
  ),
  'credit_provider' => 
  array (
    'type' => 'varchar',
    'default' => true,
    'vname' => 'LBL_CREDIT_PROVIDER',
    'width' => '10%',
  ),
  'credit_balance' => 
  array (
    'type' => 'currency',
    'default' => true,
    'related_fields' => 
    array (
      0 => 'currency_id',
      1 => 'base_rate',
    ),
    'vname' => 'LBL_CREDIT_BALANCE',
    'currency_format' => true,
    'width' => '10%',
  ),
  'monthly_credit_rate' => 
  array (
    'type' => 'currency',
    'default' => true,
    'related_fields' => 
    array (
      0 => 'currency_id',
      1 => 'base_rate',
    ),
    'vname' => 'LBL_MONTHLY_CREDIT_RATE',
    'currency_format' => true,
    'width' => '10%',
  ),
  'credit_end_date' => 
  array (
    'type' => 'date',
    'vname' => 'LBL_CREDIT_END_DATE',
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
);