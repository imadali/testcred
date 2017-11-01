<?php
$popupMeta = array (
    'moduleMain' => 'Contract',
    'varName' => 'CONTRACT',
    'orderBy' => 'contracts.name',
    'whereClauses' => array (
  'name' => 'contracts.name',
  'reference_code' => 'contracts.reference_code',
  'status' => 'contracts.status',
  'account_id' => 'contracts.account_id',
),
    'searchInputs' => array (
  0 => 'account_id',
  1 => 'account_name',
  2 => 'name',
  3 => 'reference_code',
  4 => 'status',
),
    'searchdefs' => array (
  0 => 'name',
  1 => 'reference_code',
  2 => 'status',
  3 => 'start_date',
  4 => 'end_date',
  5 => 
  array (
    'name' => 'assigned_user_id',
    'type' => 'enum',
    'label' => 'LBL_ASSIGNED_TO',
    'function' => 
    array (
      'name' => 'get_user_array',
      'params' => 
      array (
        0 => false,
      ),
    ),
  ),
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_CONTRACT_NAME',
    'link' => true,
    'default' => true,
    'name' => 'name',
  ),
  'ACCOUNT_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_ACCOUNT_NAME',
    'id' => 'ACCOUNT_ID',
    'width' => '10%',
    'default' => true,
  ),
  'CREDIT_AMOUNT_C' => 
  array (
    'type' => 'decimal',
    'default' => true,
    'label' => 'LBL_CREDIT_AMOUNT',
    'width' => '10%',
  ),
  'CREDIT_DURATION_C' => 
  array (
    'type' => 'int',
    'default' => true,
    'label' => 'LBL_CREDIT_DURATION',
    'width' => '10%',
  ),
  'PAYING_DATE_C' => 
  array (
    'type' => 'date',
    'default' => true,
    'label' => 'LBL_PAYING_DATE',
    'width' => '10%',
  ),
  'PAYMENT_OPTION_ID_C' => 
  array (
    'type' => 'enum',
    'default' => true,
    'label' => 'LBL_PAYMENT_OPTION_ID',
    'width' => '10%',
  ),
  'DATE_MODIFIED' => 
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
),
);
