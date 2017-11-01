<?php
$viewdefs['Contracts'] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'SHARE',
          2 => 'DUPLICATE',
          3 => 'DELETE',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_CONTRACT_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'lbl_contract_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'name',
            'label' => 'LBL_CONTRACT_NAME',
          ),
          1 => 
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value}&nbsp;{$APP.LBL_BY}&nbsp;{$fields.created_by_name.value}',
            'label' => 'LBL_DATE_ENTERED',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'date_modified',
            'customCode' => '{$fields.date_modified.value}&nbsp;{$APP.LBL_BY}&nbsp;{$fields.modified_by_name.value}',
            'label' => 'LBL_DATE_MODIFIED',
          ),
          1 => 
          array (
            'name' => 'provider_id_c',
            'label' => 'LBL_PROVIDER_ID',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'credit_amount_c',
            'label' => 'LBL_CREDIT_AMOUNT',
          ),
          1 => 
          array (
            'name' => 'interest_rate_c',
            'label' => 'LBL_INTEREST_RATE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'credit_duration_c',
            'label' => 'LBL_CREDIT_DURATION',
          ),
          1 => 
          array (
            'name' => 'contract_date_c',
            'label' => 'LBL_CONTRACT_DATE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'provision_c',
            'label' => 'LBL_PROVISION',
          ),
          1 => 
          array (
            'name' => 'bestand_bonus_c',
            'label' => 'LBL_BESTAND_BONUS',
          ),
        ),
        5 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'provider_contract_no',
            'label' => 'LBL_PROVIDER_CONTRACT_NUMBER',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'promo_bonus_c',
            'label' => 'LBL_PROMO_BONUS',
          ),
          1 => 
          array (
            'name' => 'ppi_c',
            'label' => 'LBL_PPI',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'ppi_provision_c',
            'label' => 'LBL_PPI_PROVISION',
          ),
          1 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'contract_complete_c',
            'label' => 'LBL_CONTRACT_COMPLETE',
          ),
          1 => 
          array (
            'name' => 'paying_date_c',
            'label' => 'LBL_PAYING_DATE',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'provision_confirmed_c',
            'label' => 'LBL_PROVISION_CONFIRMED',
          ),
          1 => 
          array (
            'name' => 'storno_c',
            'label' => 'LBL_STORNO',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'storno_date_c',
            'label' => 'LBL_STORNO_DATE',
          ),
          1 => 
          array (
            'name' => 'payment_option_id_c',
            'label' => 'LBL_PAYMENT_OPTION_ID',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'opportunity_name',
            'label' => 'LBL_OPPORTUNITY',
          ),
          1 => 
          array (
            'name' => 'contracts_leads_1_name',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'dotb_soko_c',
          ),
          1 => 
          array (
            'name' => 'contacts_contracts_1_name',
            'label' => 'LBL_CONTACTS_CONTRACTS_1_FROM_CONTACTS_TITLE',
          ),
        ),
        13 => 
        array (
          0 => 'team_name',
          1 => 
          array (
            'name' => 'lead_first_name',
            'label' => 'LBL_LEAD_NAME',
          ),
        ),
        14 => 
        array (
          0 => 
          array (
            'related_fields' => 
            array (
              0 => 'currency_id',
              1 => 'base_rate',
            ),
            'name' => 'basic_commission_agent',
            'label' => 'LBL_BASIC_COMMISSION_AGENT',
          ),
          1 => 
          array (
            'name' => 'basic_payout_date',
            'label' => 'LBL_BASIC_PAYOUT_DATE',
            'comment' => 'Date Field Comment',
          ),
        ),
        15 => 
        array (
          0 => 
          array (
            'related_fields' => 
            array (
              0 => 'currency_id',
              1 => 'base_rate',
            ),
            'name' => 'volume_commission_agent',
            'label' => 'LBL_VOLUME_COMMISSION_AGENT',
          ),
          1 => 
          array (
            'name' => 'volume_payout_date',
            'label' => 'LBL_VOLUME_PAYOUT_DATE',
            'comment' => 'Date Field Comment',
          ),
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'related_fields' => 
            array (
              0 => 'currency_id',
              1 => 'base_rate',
            ),
            'name' => 'customer_credit_amount_c',
            'label' => 'LBL_CUSTOMER_CREDIT_AMOUNT',
          ),
          1 => 
          array (
            'name' => 'customer_credit_duration_c',
            'label' => 'LBL_CREDIT_DURATION',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'customer_interest_rate_c',
            'label' => 'LBL_INTEREST_RATE',
          ),
          1 => 
          array (
            'name' => 'customer_ppi_c',
            'label' => 'LBL_PPI',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'contract_ppi_plus',
            'label' => 'LBL_CONTRACT_PPI_PLUS',
          ),
          1 => 
          array (
            'name' => 'credit_card_commission',
            'label' => 'LBL_CREDDIT_CARD_COMMISSION',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'contract_transfer_fee',
            'label' => 'LBL_CONTRACT_TRANSFER_FEE',
          ),
        ),
      ),
    ),
  ),
);
