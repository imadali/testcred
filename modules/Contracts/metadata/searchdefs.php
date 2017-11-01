<?php
// created: 2017-07-10 11:50:42
$searchdefs['Contracts'] = array (
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
  'layout' => 
  array (
    'basic_search' => 
    array (
      0 => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      1 => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_OPPORTUNITY_NAME',
        'width' => '10%',
        'default' => true,
        'id' => 'OPPORTUNITY_ID',
        'name' => 'opportunity_name',
      ),
      2 => 
      array (
        'type' => 'enum',
        'default' => true,
        'label' => 'LBL_PAYMENT_OPTION_ID',
        'width' => '10%',
        'name' => 'payment_option_id_c',
      ),
      3 => 
      array (
        'type' => 'enum',
        'default' => true,
        'label' => 'LBL_PROVIDER_ID',
        'width' => '10%',
        'name' => 'provider_id_c',
      ),
      4 => 
      array (
        'type' => 'datetime',
        'studio' => 
        array (
          'portaleditview' => false,
        ),
        'readonly' => true,
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      5 => 
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
        'name' => 'date_modified',
      ),
      6 => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      7 => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      8 => 
      array (
        'type' => 'relate',
        'link' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'team_name',
      ),
    ),
    'advanced_search' => 
    array (
      0 => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      1 => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_OPPORTUNITY_NAME',
        'id' => 'OPPORTUNITY_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'opportunity_name',
      ),
      2 => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CONTACTS_CONTRACTS_1_FROM_CONTACTS_TITLE',
        'id' => 'CONTACTS_CONTRACTS_1CONTACTS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'contacts_contracts_1_name',
      ),
      3 => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CONTRACTS_LEADS_1_FROM_LEADS_TITLE',
        'id' => 'CONTRACTS_LEADS_1LEADS_IDB',
        'width' => '10%',
        'default' => true,
        'name' => 'contracts_leads_1_name',
      ),
      4 => 
      array (
        'type' => 'decimal',
        'default' => true,
        'label' => 'LBL_CREDIT_AMOUNT',
        'width' => '10%',
        'name' => 'credit_amount_c',
      ),
      5 => 
      array (
        'type' => 'int',
        'default' => true,
        'label' => 'LBL_CREDIT_DURATION',
        'width' => '10%',
        'name' => 'credit_duration_c',
      ),
      6 => 
      array (
        'type' => 'date',
        'default' => true,
        'label' => 'LBL_CONTRACT_DATE',
        'width' => '10%',
        'name' => 'contract_date_c',
      ),
      7 => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_CONTRACT_COMPLETE',
        'width' => '10%',
        'name' => 'contract_complete_c',
      ),
      8 => 
      array (
        'type' => 'decimal',
        'default' => true,
        'label' => 'LBL_PROMO_BONUS',
        'width' => '10%',
        'name' => 'promo_bonus_c',
      ),
      9 => 
      array (
        'type' => 'decimal',
        'default' => true,
        'label' => 'LBL_STORNO',
        'width' => '10%',
        'name' => 'storno_c',
      ),
      10 => 
      array (
        'type' => 'date',
        'default' => true,
        'label' => 'LBL_STORNO_DATE',
        'width' => '10%',
        'name' => 'storno_date_c',
      ),
      11 => 
      array (
        'type' => 'decimal',
        'default' => true,
        'label' => 'LBL_INTEREST_RATE',
        'width' => '10%',
        'name' => 'interest_rate_c',
      ),
      12 => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_PROVISION_CONFIRMED',
        'width' => '10%',
        'name' => 'provision_confirmed_c',
      ),
      13 => 
      array (
        'type' => 'decimal',
        'default' => true,
        'label' => 'LBL_PPI_PROVISION',
        'width' => '10%',
        'name' => 'ppi_provision_c',
      ),
      14 => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_PPI',
        'width' => '10%',
        'name' => 'ppi_c',
      ),
      15 => 
      array (
        'type' => 'enum',
        'default' => true,
        'label' => 'LBL_PAYMENT_OPTION_ID',
        'width' => '10%',
        'name' => 'payment_option_id_c',
      ),
      16 => 
      array (
        'name' => 'start_date',
        'default' => true,
        'width' => '10%',
      ),
      17 => 
      array (
        'name' => 'end_date',
        'default' => true,
        'width' => '10%',
      ),
      18 => 
      array (
        'name' => 'status',
        'default' => true,
        'width' => '10%',
      ),
      19 => 
      array (
        'type' => 'enum',
        'default' => true,
        'label' => 'LBL_PROVIDER_ID',
        'width' => '10%',
        'name' => 'provider_id_c',
      ),
      20 => 
      array (
        'type' => 'date',
        'default' => true,
        'label' => 'LBL_PAYING_DATE',
        'width' => '10%',
        'name' => 'paying_date_c',
      ),
      21 => 
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
        'default' => true,
        'width' => '10%',
      ),
      22 => 
      array (
        'type' => 'datetime',
        'studio' => 
        array (
          'portaleditview' => false,
        ),
        'readonly' => true,
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      23 => 
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
        'name' => 'date_modified',
      ),
      24 => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      25 => 
      array (
        'type' => 'relate',
        'link' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'team_name',
      ),
    ),
  ),
);