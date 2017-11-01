<?php
$searchdefs['Contracts'] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'opportunity_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_OPPORTUNITY_NAME',
        'width' => '10%',
        'default' => true,
        'id' => 'OPPORTUNITY_ID',
        'name' => 'opportunity_name',
      ),
      'payment_option_id_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'label' => 'LBL_PAYMENT_OPTION_ID',
        'width' => '10%',
        'name' => 'payment_option_id_c',
      ),
      'provider_id_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'label' => 'LBL_PROVIDER_ID',
        'width' => '10%',
        'name' => 'provider_id_c',
      ),
      'date_entered' => 
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
        'name' => 'date_modified',
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'favorites_only' => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'opportunity_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_OPPORTUNITY_NAME',
        'id' => 'OPPORTUNITY_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'opportunity_name',
      ),
      'contacts_contracts_1_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CONTACTS_CONTRACTS_1_FROM_CONTACTS_TITLE',
        'id' => 'CONTACTS_CONTRACTS_1CONTACTS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'contacts_contracts_1_name',
      ),
      'contracts_leads_1_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CONTRACTS_LEADS_1_FROM_LEADS_TITLE',
        'id' => 'CONTRACTS_LEADS_1LEADS_IDB',
        'width' => '10%',
        'default' => true,
        'name' => 'contracts_leads_1_name',
      ),
      'credit_amount_c' => 
      array (
        'type' => 'decimal',
        'default' => true,
        'label' => 'LBL_CREDIT_AMOUNT',
        'width' => '10%',
        'name' => 'credit_amount_c',
      ),
      'credit_duration_c' => 
      array (
        'type' => 'int',
        'default' => true,
        'label' => 'LBL_CREDIT_DURATION',
        'width' => '10%',
        'name' => 'credit_duration_c',
      ),
      'contract_date_c' => 
      array (
        'type' => 'date',
        'default' => true,
        'label' => 'LBL_CONTRACT_DATE',
        'width' => '10%',
        'name' => 'contract_date_c',
      ),
      'contract_complete_c' => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_CONTRACT_COMPLETE',
        'width' => '10%',
        'name' => 'contract_complete_c',
      ),
      'promo_bonus_c' => 
      array (
        'type' => 'decimal',
        'default' => true,
        'label' => 'LBL_PROMO_BONUS',
        'width' => '10%',
        'name' => 'promo_bonus_c',
      ),
      'storno_c' => 
      array (
        'type' => 'decimal',
        'default' => true,
        'label' => 'LBL_STORNO',
        'width' => '10%',
        'name' => 'storno_c',
      ),
      'storno_date_c' => 
      array (
        'type' => 'date',
        'default' => true,
        'label' => 'LBL_STORNO_DATE',
        'width' => '10%',
        'name' => 'storno_date_c',
      ),
      'interest_rate_c' => 
      array (
        'type' => 'decimal',
        'default' => true,
        'label' => 'LBL_INTEREST_RATE',
        'width' => '10%',
        'name' => 'interest_rate_c',
      ),
      'provision_confirmed_c' => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_PROVISION_CONFIRMED',
        'width' => '10%',
        'name' => 'provision_confirmed_c',
      ),
      'ppi_provision_c' => 
      array (
        'type' => 'decimal',
        'default' => true,
        'label' => 'LBL_PPI_PROVISION',
        'width' => '10%',
        'name' => 'ppi_provision_c',
      ),
      'ppi_c' => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_PPI',
        'width' => '10%',
        'name' => 'ppi_c',
      ),
      'payment_option_id_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'label' => 'LBL_PAYMENT_OPTION_ID',
        'width' => '10%',
        'name' => 'payment_option_id_c',
      ),
      'start_date' => 
      array (
        'name' => 'start_date',
        'default' => true,
        'width' => '10%',
      ),
      'end_date' => 
      array (
        'name' => 'end_date',
        'default' => true,
        'width' => '10%',
      ),
      'status' => 
      array (
        'name' => 'status',
        'default' => true,
        'width' => '10%',
      ),
      'provider_id_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'label' => 'LBL_PROVIDER_ID',
        'width' => '10%',
        'name' => 'provider_id_c',
      ),
      'paying_date_c' => 
      array (
        'type' => 'date',
        'default' => true,
        'label' => 'LBL_PAYING_DATE',
        'width' => '10%',
        'name' => 'paying_date_c',
      ),
      'assigned_user_id' => 
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
      'date_entered' => 
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
        'name' => 'date_modified',
      ),
      'favorites_only' => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
    ),
  ),
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
);
