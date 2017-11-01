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
            'related_fields' => 
            array (
              0 => 'currency_id',
              1 => 'base_rate',
            ),
            'name' => 'credit_amount_flex',
            'label' => 'LBL_CREDIT_AMOUNT_FLEX',
          ),
          1 => 
          array (
            'name' => 'interest_rate_flex',
            'label' => 'LBL_INTEREST_RATE_FLEX',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'related_fields' => 
            array (
              0 => 'currency_id',
              1 => 'base_rate',
            ),
            'name' => 'first_payment_flex',
            'label' => 'LBL_FIRST_PAYMENT_FLEX',
          ),
          1 => '',
        ),
        5 => 
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
        6 => 
        array (
          0 => 
          array (
            'name' => 'credit_duration_flex',
            'label' => 'LBL_CREDIT_DURATION_FLEX',
          ),
          1 => '',
        ),
        7 => 
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
        8 => 
        array (
          0 => 
          array (
            'name' => 'provision_flex',
            'label' => 'LBL_PROVISION_FLEX',
          ),
          1 => 
          array (
            'name' => 'bestand_bonus_flex',
            'label' => 'LBL_BESTAND_BONUS_FLEX',
          ),
        ),
        9 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'provider_contract_no',
            'label' => 'LBL_PROVIDER_CONTRACT_NUMBER',
          ),
        ),
        10 => 
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
        11 => 
        array (
          0 => 
          array (
            'name' => 'promo_bonus_flex',
            'label' => 'LBL_PROMO_BONUS_FLEX',
          ),
          1 => 
          array (
            'name' => 'ppi_flex',
            'label' => 'LBL_PPI_FLEX',
          ),
        ),
        12 => 
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
        13 => 
        array (
          0 => 
          array (
            'name' => 'ppi_provision_flex',
            'label' => 'LBL_PPI_PROVISION_FLEX',
          ),
          1 => '',
        ),
        14 => 
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
        15 => 
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
        16 => 
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
        17 => 
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
        18 => 
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
        19 => 
        array (
          0 => 
          array (
            'name' => 'soko_flex',
            'label' => 'LBL_SOKO_FLEX',
          ),
          1 => '',
        ),
        20 => 
        array (
          0 => 'team_name',
          1 => 
          array (
            'name' => 'lead_first_name',
            'label' => 'LBL_LEAD_NAME',
          ),
        ),
        21 => 
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
        22 => 
        array (
          0 => 
          array (
            'related_fields' => 
            array (
              0 => 'currency_id',
              1 => 'base_rate',
            ),
            'name' => 'basic_commission_agent_flex',
            'label' => 'LBL_BASIC_COMMISSION_AGENT_FLEX',
          ),
          1 => 
          array (
            'name' => 'basic_payout_date_flex',
            'label' => 'LBL_BASIC_PAYOUT_DATE_FLEX',
            'comment' => '',
          ),
        ),
        23 => 
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
        24 => 
        array (
          0 => 
          array (
            'related_fields' => 
            array (
              0 => 'currency_id',
              1 => 'base_rate',
            ),
            'name' => 'volume_commission_agent_flex',
            'label' => 'LBL_VOLUME_COMMISSION_AGENT_FLEX',
          ),
          1 => 
          array (
            'name' => 'volume_payout_date_flex',
            'label' => 'LBL_VOLUME_PAYOUT_DATE_FLEX',
            'comment' => '',
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
            'label' => 'LBL_CUSTOMER_CREDIT_AMOUNT_FLEX',
            'related_fields' => 
            array (
              0 => 'currency_id',
              1 => 'base_rate',
            ),
            'name' => 'customer_credit_amount_flex',
          ),
          1 => 
          array (
            'label' => 'LBL_CREDIT_DURATION_FLEX',
            'name' => 'customer_credit_duration_flex',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'label' => 'LBL_FIRST_PAYMENT_FLEX',
            'related_fields' => 
            array (
              0 => 'currency_id',
              1 => 'base_rate',
            ),
            'name' => 'customer_first_payment_flex',
          ),
          1 => '',
        ),
        3 => 
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
        4 => 
        array (
          0 => 
          array (
            'label' => 'LBL_INTEREST_RATE_FLEX',
            'name' => 'customer_interest_rate_flex',
          ),
          1 => 
          array (
            'label' => 'LBL_CONTRACT_PPI_PLUS_FLEX',
            'name' => 'contract_ppi_plus_flex',
          ),
        ),
        5 => 
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
        6 => 
        array (
          0 => 
          array (
            'name' => 'customer_ppi_flex',
            'label' => 'LBL_PPI_FLEX',
          ),
          1 => 
          array (
            'name' => 'credit_card_commission_flex',
            'label' => 'LBL_CREDIT_CARD_COMMISSION_FLEX',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'contract_transfer_fee',
            'label' => 'LBL_CONTRACT_TRANSFER_FEE',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'label' => 'LBL_CONTRACT_TRANSFER_FEE_FLEX',
            'name' => 'contract_transfer_fee_flex',
          ),
          1 => '',
        ),
      ),
    ),
  ),
);
