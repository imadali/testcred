<?php
$viewdefs['Opportunities'] = 
array (
  'base' => 
  array (
    'view' => 
    array (
      'record' => 
      array (
        'buttons' => 
        array (
          0 => 
          array (
            'type' => 'button',
            'name' => 'cancel_button',
            'label' => 'LBL_CANCEL_BUTTON_LABEL',
            'css_class' => 'btn-invisible btn-link',
            'showOn' => 'edit',
          ),
          1 => 
          array (
            'type' => 'rowaction',
            'event' => 'button:save_button:click',
            'name' => 'save_button',
            'label' => 'LBL_SAVE_BUTTON_LABEL',
            'css_class' => 'btn btn-primary',
            'showOn' => 'edit',
            'acl_action' => 'edit',
          ),
          2 => 
          array (
            'type' => 'actiondropdown',
            'name' => 'main_dropdown',
            'primary' => true,
            'showOn' => 'view',
            'buttons' => 
            array (
              0 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:edit_button:click',
                'name' => 'edit_button',
                'label' => 'LBL_EDIT_BUTTON_LABEL',
                'acl_action' => 'edit',
              ),
              1 => 
              array (
                'type' => 'shareaction',
                'name' => 'share',
                'label' => 'LBL_RECORD_SHARE_BUTTON',
                'acl_action' => 'view',
              ),
              2 => 
              array (
                'type' => 'pdfaction',
                'name' => 'download-pdf',
                'label' => 'LBL_PDF_VIEW',
                'action' => 'download',
                'acl_action' => 'view',
              ),
              3 => 
              array (
                'type' => 'pdfaction',
                'name' => 'email-pdf',
                'label' => 'LBL_PDF_EMAIL',
                'action' => 'email',
                'acl_action' => 'view',
              ),
              4 => 
              array (
                'type' => 'divider',
              ),
              5 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:find_duplicates_button:click',
                'name' => 'find_duplicates_button',
                'label' => 'LBL_DUP_MERGE',
                'acl_action' => 'edit',
              ),
              6 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:duplicate_button:click',
                'name' => 'duplicate_button',
                'label' => 'LBL_DUPLICATE_BUTTON_LABEL',
                'acl_module' => 'Opportunities',
                'acl_action' => 'create',
              ),
              7 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:historical_summary_button:click',
                'name' => 'historical_summary_button',
                'label' => 'LBL_HISTORICAL_SUMMARY_CUSTOM',
                'acl_action' => 'view',
              ),
              8 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:audit_button:click',
                'name' => 'audit_button',
                'label' => 'LNK_VIEW_CHANGE_LOG',
                'acl_action' => 'view',
              ),
              9 => 
              array (
                'type' => 'divider',
              ),
              10 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:delete_button:click',
                'name' => 'delete_button',
                'label' => 'LBL_DELETE_BUTTON_LABEL',
                'acl_action' => 'delete',
              ),
            ),
          ),
          3 => 
          array (
            'name' => 'sidebar_toggle',
            'type' => 'sidebartoggle',
          ),
        ),
        'panels' => 
        array (
          0 => 
          array (
            'name' => 'panel_header',
            'header' => true,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'picture',
                'type' => 'avatar',
                'size' => 'large',
                'dismiss_label' => true,
                'readonly' => true,
              ),
              1 => 
              array (
                'name' => 'name',
                'related_fields' => 
                array (
                  0 => 'total_revenue_line_items',
                  1 => 'closed_revenue_line_items',
                ),
              ),
              2 => 
              array (
                'name' => 'favorite',
                'label' => 'LBL_FAVORITE',
                'type' => 'favorite',
                'dismiss_label' => true,
              ),
              3 => 
              array (
                'name' => 'follow',
                'label' => 'LBL_FOLLOW',
                'type' => 'follow',
                'readonly' => true,
                'dismiss_label' => true,
              ),
            ),
          ),
          1 => 
          array (
            'name' => 'panel_body',
            'label' => 'LBL_RECORD_BODY',
            'columns' => 2,
            'labels' => true,
            'labelsOnTop' => true,
            'placeholders' => true,
            'newTab' => false,
            'panelDefault' => 'expanded',
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'leads_opportunities_1_name',
                'label' => 'LBL_LEADS_OPPORTUNITIES_1_FROM_LEADS_TITLE',
              ),
              1 => 
              array (
                'name' => 'create_approval_task',
              ),
              2 => 
              array (
                'name' => 'provider_id_c',
                'label' => 'LBL_PROVIDER_ID',
              ),
              3 => 
              array (
                'name' => 'auto_assign_task',
                'label' => 'LBL_AUTO_ASSIGN_TASK',
              ),
              4 => 
              array (
                'name' => 'provider_status_id_c',
                'label' => 'LBL_PROVIDER_STATUS_ID',
              ),
              5 => 
              array (
                'name' => 'provider_application_no_c',
                'label' => 'LBL_PROVIDER_APPLICATION_NO',
              ),
              6 => 
              array (
              ),
              7 => 
              array (
                'name' => 'provider_contract_no',
                'label' => 'LBL_PROVIDER_CONTRACT_NUMBER',
              ),
              8 => 
              array (
                'name' => 'expiry_date_c',
                'label' => 'LBL_EXPIRY_DATE',
              ),
              9 => 
              array (
                'name' => 'assigned_user_name',
              ),
              10 => 
              array (
                'name' => 'dotb_user_approval_c',
                'studio' => 'visible',
                'label' => 'LBL_USER_APPROVAL',
              ),
              11 => 'team_name',
              12 => 
              array (
                'name' => 'dotb_soko_c',
                'label' => 'LBL_DOTB_SOKO',
              ),
              13 => 
              array (
                'name' => 'soko_flex',
                'label' => 'LBL_SOKO_FLEX',
              ),
            ),
          ),
          2 => 
          array (
            'newTab' => false,
            'panelDefault' => 'expanded',
            'name' => 'LBL_RECORDVIEW_PANEL2',
            'label' => 'LBL_RECORDVIEW_PANEL2',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'credit_amount_c',
                'label' => 'LBL_CREDIT_AMOUNT',
              ),
              1 => 
              array (
                'name' => 'credit_duration_c',
                'label' => 'LBL_CREDIT_DURATION',
              ),
              2 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'applied_credit_amount_flex',
                'label' => 'LBL_CREDIT_AMOUNT_FLEX',
              ),
              3 => 
              array (
                'name' => 'applied_credit_duration_flex',
                'label' => 'LBL_CREDIT_DURATION_FLEX',
              ),
              4 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'applied_first_payment_flex',
                'label' => 'LBL_FIRST_PAYMENT_FLEX',
              ),
              5 => 
              array (
              ),
              6 => 
              array (
                'name' => 'interest_rate_c',
                'label' => 'LBL_INTEREST_RATE',
              ),
              7 => 
              array (
                'name' => 'ppi_c',
                'label' => 'LBL_PPI',
              ),
              8 => 
              array (
                'name' => 'applied_interest_rate_flex',
                'label' => 'LBL_INTEREST_RATE_FLEX',
              ),
              9 => 
              array (
                'name' => 'applied_ppi_flex',
                'label' => 'LBL_PPI_FLEX',
              ),
              10 => 
              array (
              ),
              11 => 
              array (
              ),
              12 => 
              array (
                'name' => 'ppi_plus',
                'label' => 'LBL_PPI_PLUS',
              ),
              13 => 
              array (
                'name' => 'transfer_fee',
                'label' => 'LBL_TRANSFER_FEE',
              ),
              14 => 
              array (
                'name' => 'applied_saldo',
                'label' => 'LBL_MULTI_SALDO',
                'type' => 'multiple-balance',
              ),
              15 => 
              array (
                'name' => 'applied_name_fremdbank',
                'label' => 'LBL_MULTI_NAME_FREMDBANK',
                'type' => 'multiple-bank',
              ),
            ),
          ),
          3 => 
          array (
            'newTab' => false,
            'panelDefault' => 'expanded',
            'name' => 'LBL_RECORDVIEW_PANEL1',
            'label' => 'LBL_RECORDVIEW_PANEL1',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'approved_credit_amount_c',
                'label' => 'LBL_CREDIT_AMOUNT',
              ),
              1 => 
              array (
                'name' => 'approved_credit_duration_c',
                'label' => 'LBL_CREDIT_DURATION',
              ),
              2 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'approved_credit_amount_flex',
                'label' => 'LBL_CREDIT_AMOUNT_FLEX',
              ),
              3 => 
              array (
                'name' => 'approved_credit_duration_flex',
                'label' => 'LBL_CREDIT_DURATION_FLEX',
              ),
              4 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'approved_first_payment_flex',
                'label' => 'LBL_FIRST_PAYMENT_FLEX',
              ),
              5 => 
              array (
              ),
              6 => 
              array (
                'name' => 'approved_interest_rate_c',
                'label' => 'LBL_INTEREST_RATE',
              ),
              7 => 
              array (
                'name' => 'approved_ppi_c',
                'label' => 'LBL_PPI',
              ),
              8 => 
              array (
                'name' => 'approved_interest_rate_flex',
                'label' => 'LBL_INTEREST_RATE_FLEX',
              ),
              9 => 
              array (
                'name' => 'approved_ppi_flex',
                'label' => 'LBL_PPI_FLEX',
              ),
              10 => 
              array (
                'name' => 'actual_margin',
                'label' => 'LBL_KREMO_ACTUAL_MARGIN',
              ),
              11 => 
              array (
                'name' => 'approved_ppi_plus',
                'label' => 'LBL_APPROVED_PPI_PLUS',
              ),
              12 => 
              array (
                'name' => 'actual_margin_flex',
                'label' => 'LBL_KREMO_ACTUAL_MARGIN_FLEX',
              ),
              13 => 
              array (
              ),
              14 => 
              array (
              ),
              15 => 
              array (
                'name' => 'approved_transfer_fee',
                'label' => 'LBL_APPROVED_TRANSFER_FEE',
              ),
              16 => 
              array (
                'name' => 'status_bank_application_kk',
                'label' => 'LBL_STATUS_BANK_APP',
              ),
              17 => 
              array (
                'name' => 'credit_card_limit',
                'label' => 'LBL_CREDIT_CARD_LIMIT',
              ),
              18 => 
              array (
                'name' => 'approved_saldo',
                'label' => 'LBL_MULTI_APPROVED_SALDO',
                'type' => 'multiple-balance',
              ),
              19 => 
              array (
                'name' => 'approved_name_fremdbank',
                'label' => 'LBL_MULTI_APPROVED_NAME_FREMDBANK',
                'type' => 'multiple-bank',
              ),
            ),
          ),
          4 => 
          array (
            'newTab' => false,
            'name' => 'LBL_CONTRACT_CHOICES',
            'label' => 'LBL_CONTRACT_CHOICES',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'panelDefault' => 'expanded',
            'fields' => 
            array (
              0 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'contract_credit_amount',
                'label' => 'LBL_CONTRACT_CREDIT_AMOUNT',
              ),
              1 => 
              array (
                'name' => 'contract_credit_duration',
                'label' => 'LBL_CONTRACT_CREDIT_DURATION',
              ),
              2 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'contract_credit_amount_flex',
                'label' => 'LBL_CREDIT_AMOUNT_FLEX',
              ),
              3 => 
              array (
                'name' => 'contract_credit_duration_flex',
                'label' => 'LBL_CREDIT_DURATION_FLEX',
              ),
              4 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'contract_first_payment_flex',
                'label' => 'LBL_FIRST_PAYMENT_FLEX',
              ),
              5 => 
              array (
              ),
              6 => 
              array (
                'name' => 'contract_interest_rate',
                'label' => 'LBL_CONTRACT_INTEREST_RATE',
              ),
              7 => 
              array (
                'name' => 'contract_ppi',
                'label' => 'LBL_CONTRACT_PPI',
              ),
              8 => 
              array (
                'name' => 'contract_interest_rate_flex',
                'label' => 'LBL_INTEREST_RATE_FLEX',
              ),
              9 => 
              array (
                'name' => 'contract_ppi_flex',
                'label' => 'LBL_PPI_FLEX',
              ),
              10 => 
              array (
                'name' => 'contract_ppi_plus',
                'label' => 'LBL_CONTRACT_PPI_PLUS',
              ),
              11 => 
              array (
                'name' => 'contract_transfer_fee',
                'label' => 'LBL_CONTRACT_TRANSFER_FEE',
              ),
              12 => 
              array (
                'name' => 'customer_request_kk',
                'label' => 'LBL_CUSTOMER_REQUEST_KK',
              ),
              13 => 
              array (
              ),
              14 => 
              array (
                'name' => 'contract_saldo',
                'label' => 'LBL_MULTI_CONTRACT_SALDO',
                'type' => 'multiple-balance',
              ),
              15 => 
              array (
                'name' => 'contract_name_fremdbank',
                'label' => 'LBL_MULTI_CONTRACT_NAME_FREMDBANK',
                'type' => 'multiple-bank',
              ),
            ),
          ),
          5 => 
          array (
            'name' => 'panel_hidden',
            'label' => 'LBL_RECORD_SHOWMORE',
            'hide' => true,
            'labelsOnTop' => true,
            'placeholders' => true,
            'columns' => 2,
            'newTab' => false,
            'panelDefault' => 'expanded',
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'date_entered_by',
                'readonly' => true,
                'type' => 'fieldset',
                'label' => 'LBL_DATE_ENTERED',
                'fields' => 
                array (
                  0 => 
                  array (
                    'name' => 'date_entered',
                  ),
                  1 => 
                  array (
                    'type' => 'label',
                    'default_value' => 'LBL_BY',
                  ),
                  2 => 
                  array (
                    'name' => 'created_by_name',
                  ),
                ),
              ),
              1 => 
              array (
                'name' => 'date_modified_by',
                'readonly' => true,
                'type' => 'fieldset',
                'label' => 'LBL_DATE_MODIFIED',
                'fields' => 
                array (
                  0 => 
                  array (
                    'name' => 'date_modified',
                  ),
                  1 => 
                  array (
                    'type' => 'label',
                    'default_value' => 'LBL_BY',
                  ),
                  2 => 
                  array (
                    'name' => 'modified_by_name',
                  ),
                ),
              ),
            ),
          ),
        ),
        'templateMeta' => 
        array (
          'useTabs' => false,
        ),
      ),
    ),
  ),
);
