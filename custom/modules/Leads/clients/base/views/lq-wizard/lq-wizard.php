<?php

/*
 * Meta file for Xero Configuration wizard view
 */
$viewdefs['Leads']['base']['view']['lq-wizard'] = array(
    'panels' => array(
        /*
         * Panel for Connection tab in Xero Wizard. Conatins non-db fields as we just want to use them in view to get input from user.
         * consumer_key
         * shared_secret
         * rsa_private_key
         * rsa_public_key
         * 
         * We have separately created Vardefs for these four fields; as file type fields was causing issues on Views (validation etc.).
         */
        array(
            'name' => 'panel_initial',
            'label' => 'LBL_PANEL_CONNECTION',
            'fields' => array(
                array(
                    'name' => 'credit_request_status_id_c',
                    'vname' => 'LBL_CREDIT_REQUEST_STATUS_ID',
                    'type' => 'enum',
                    'default' => '01_new',
                    'no_default' => false,
                    'comments' => '',
                    'help' => '',
                    'importable' => 'true',
                    'duplicate_merge' => 'enabled',
                    'duplicate_merge_dom_value' => '1',
                    'audited' => true,
                    'reportable' => true,
                    'unified_search' => false,
                    'merge_filter' => 'disabled',
                    'calculated' => false,
                    'len' => 100,
                    'size' => '20',
                    'options' => 'dotb_credit_request_status_list',
                ),
                array(
                    'name' => 'credit_request_substatus',
                    'type' => 'enum',
                    'label' => 'LBL_SUB_STATUS',
                    'css_class' => 'form-control',
                    'options' => 'dotb_credit_request_sub_status_list',
                    'comment' => 'Contains Sub Status Related to Crdit Request',
                    'merge_filter' => 'enabled',
                ),
                array(
                    'labelValue' => 'Next Best Steps',
                    'label' => 'LBL_LQ_NEXT_BEST_STEPS',
                    'required' => false,
                    'source' => 'custom_fields',
                    'name' => 'lq_next_best_steps_c',
                    'vname' => 'LBL_LQ_NEXT_BEST_STEPS',
                    'type' => 'enum',
                    'massupdate' => true,
                    'no_default' => false,
                    'comments' => '',
                    'help' => '',
                    'importable' => 'true',
                    'duplicate_merge' => 'enabled',
                    'duplicate_merge_dom_value' => '1',
                    'audited' => false,
                    'reportable' => true,
                    'unified_search' => false,
                    'merge_filter' => 'disabled',
                    'calculated' => false,
                    'len' => 100,
                    'size' => '20',
                ),
            ),
        ),
        /*
         * Panel for Integration tab in Xero Wizard. Conatins non-db fields as we just want to use them in view to get input from user.
         * suppliers
         * purchase_orders
         * customers
         * sales_invoices
         * inventory
         * tax_rates
         * charts_of_accounts
         * currencies
         * 
         * These fields aren't in Vardefs.
         */
        array(
            'name' => 'panel_integration',
            'label' => 'LBL_PANEL_INTEGRATION',
            'fields' => array(
                array(
                    'name' => 'suppliers',
                    'type' => 'enum',
                    'label' => 'LBL_INTEGRATION_SUPPLIERS',
                    'options' => 'xero_integration_list_full',
                    'len' => 50,
                ),
                array(
                    'name' => 'purchase_orders',
                    'type' => 'enum',
                    'label' => 'LBL_INTEGRATION_PURCHASE_ORDERS',
                    'options' => 'xero_integration_list_full',
                    'len' => 50,
                ),
                array(
                    'name' => 'customers',
                    'type' => 'enum',
                    'label' => 'LBL_INTEGRATION_CUSTOMERS',
                    'options' => 'xero_integration_list_full',
                    'len' => 50,
                ),
                array(
                    'name' => 'sales_invoices',
                    'type' => 'enum',
                    'label' => 'LBL_INTEGRATION_SALES_INVOICES',
                    'options' => 'xero_integration_list_full',
                    'len' => 50,
                ),
                array(
                    'name' => 'inventory',
                    'type' => 'enum',
                    'label' => 'LBL_INTEGRATION_INVENTORY',
                    'options' => 'xero_integration_list_half',
                    'len' => 50,
                ),
                array(
                    'name' => 'tax_rates',
                    'type' => 'enum',
                    'label' => 'LBL_INTEGRATION_TAX_RATES',
                    'options' => 'xero_integration_list_half',
                    'default' => 'Pull',
                    'len' => 50,
                ),
                array(
                    'name' => 'charts_of_accounts',
                    'type' => 'enum',
                    'label' => 'LBL_INTEGRATION_CHART_OF_ACCOUNTS',
                    'options' => 'xero_integration_list_half',
                    'default' => 'Pull',
                    'len' => 50,
                ),
                array(
                    'name' => 'currencies',
                    'type' => 'enum',
                    'label' => 'LBL_INTEGRATION_CURRENCIES',
                    'options' => 'xero_integration_list_half',
                    'default' => 'Pull',
                    'len' => 50,
                ),
            ),
        ),
    )
);
