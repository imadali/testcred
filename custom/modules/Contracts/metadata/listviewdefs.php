<?php
// created: 2016-10-05 14:28:36
$listViewDefs['Contracts'] = array (
  'name' => 
  array (
    'width' => '40%',
    'label' => 'LBL_LIST_CONTRACT_NAME',
    'link' => true,
    'default' => true,
  ),
  'account_name' => 
  array (
    'width' => '20%',
    'label' => 'LBL_LIST_ACCOUNT_NAME',
    'module' => 'Accounts',
    'id' => 'ACCOUNT_ID',
    'link' => true,
    'default' => true,
    'ACLTag' => 'ACCOUNT',
    'related_fields' => 
    array (
      0 => 'account_id',
    ),
  ),
  'credit_amount_c' => 
  array (
    'type' => 'decimal',
    'default' => true,
    'label' => 'LBL_CREDIT_AMOUNT',
    'width' => '10%',
  ),
  'credit_duration_c' => 
  array (
    'type' => 'int',
    'default' => true,
    'label' => 'LBL_CREDIT_DURATION',
    'width' => '10%',
  ),
  'paying_date_c' => 
  array (
    'type' => 'date',
    'default' => true,
    'label' => 'LBL_PAYING_DATE',
    'width' => '10%',
  ),
  'payment_option_id_c' => 
  array (
    'type' => 'enum',
    'default' => true,
    'label' => 'LBL_PAYMENT_OPTION_ID',
    'width' => '10%',
  ),
  'date_modified' => 
  array (
    'type' => 'datetime',
    'studio' => 
    array (
      'portaleditview' => false,
    ),
    'readonly' => true,
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => true,
  ),
  'ppi_c' => 
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_PPI',
    'width' => '10%',
  ),
  'interest_rate_c' => 
  array (
    'type' => 'decimal',
    'default' => false,
    'label' => 'LBL_INTEREST_RATE',
    'width' => '10%',
  ),
  'storno_date_c' => 
  array (
    'type' => 'date',
    'default' => false,
    'label' => 'LBL_STORNO_DATE',
    'width' => '10%',
  ),
  'assigned_user_name' => 
  array (
    'width' => '2%',
    'label' => 'LBL_LIST_ASSIGNED_TO_USER',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => false,
  ),
  'end_date' => 
  array (
    'width' => '15%',
    'label' => 'LBL_LIST_END_DATE',
    'link' => false,
    'default' => false,
  ),
  'status' => 
  array (
    'width' => '10%',
    'label' => 'LBL_STATUS',
    'link' => false,
    'default' => false,
  ),
  'start_date' => 
  array (
    'width' => '15%',
    'label' => 'LBL_LIST_START_DATE',
    'link' => false,
    'default' => false,
  ),
  'storno_c' => 
  array (
    'type' => 'decimal',
    'default' => false,
    'label' => 'LBL_STORNO',
    'width' => '10%',
  ),
  'ppi_provision_c' => 
  array (
    'type' => 'decimal',
    'default' => false,
    'label' => 'LBL_PPI_PROVISION',
    'width' => '10%',
  ),
  'promo_bonus_c' => 
  array (
    'type' => 'decimal',
    'default' => false,
    'label' => 'LBL_PROMO_BONUS',
    'width' => '10%',
  ),
  'provider_id_c' => 
  array (
    'type' => 'enum',
    'default' => false,
    'label' => 'LBL_PROVIDER_ID',
    'width' => '10%',
  ),
  'team_name' => 
  array (
    'width' => '2%',
    'label' => 'LBL_LIST_TEAM',
    'default' => false,
    'related_fields' => 
    array (
      0 => 'team_id',
    ),
  ),
);