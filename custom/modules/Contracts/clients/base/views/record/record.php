<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

$viewdefs['Leads']['base']['view']['record'] = array(
    'buttons' => array(
        array(
            'type' => 'button',
            'name' => 'cancel_button',
            'label' => 'LBL_CANCEL_BUTTON_LABEL',
            'css_class' => 'btn-invisible btn-link',
            'showOn' => 'edit',
        ),
        array(
            'type' => 'rowaction',
            'event' => 'button:save_button:click',
            'name' => 'save_button',
            'label' => 'LBL_SAVE_BUTTON_LABEL',
            'css_class' => 'btn btn-primary',
            'showOn' => 'edit',
            'acl_action' => 'edit',
        ),
        array(
            'type' => 'actiondropdown',
            'name' => 'main_dropdown',
            'primary' => true,
            'showOn' => 'view',
            'buttons' => array(
                array(
                    'type' => 'rowaction',
                    'event' => 'button:edit_button:click',
                    'name' => 'edit_button',
                    'label' => 'LBL_EDIT_BUTTON_LABEL',
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'shareaction',
                    'name' => 'share',
                    'label' => 'LBL_RECORD_SHARE_BUTTON',
                    'acl_action' => 'view',
                ),
                array(
                    'type' => 'pdfaction',
                    'name' => 'download-pdf',
                    'label' => 'LBL_PDF_VIEW',
                    'action' => 'download',
                    'acl_action' => 'view',
                ),
                array(
                    'type' => 'pdfaction',
                    'name' => 'email-pdf',
                    'label' => 'LBL_PDF_EMAIL',
                    'action' => 'email',
                    'acl_action' => 'view',
                ),
                array(
                    'type' => 'divider',
                ),
                array(
                    'type' => 'rowaction',
                    'event' => 'button:find_duplicates_button:click',
                    'name' => 'find_duplicates_button',
                    'label' => 'LBL_DUP_MERGE',
                    'acl_action' => 'edit',
                ),
                array(
                    'type' => 'rowaction',
                    'event' => 'button:duplicate_button:click',
                    'name' => 'duplicate_button',
                    'label' => 'LBL_DUPLICATE_BUTTON_LABEL',
                    'acl_module' => $module,
                    'acl_action' => 'create',
                ),
                array(
                    'type' => 'rowaction',
                    'event' => 'button:audit_button:click',
                    'name' => 'audit_button',
                    'label' => 'LNK_VIEW_CHANGE_LOG',
                    'acl_action' => 'view',
                ),
                array(
                    'type' => 'divider',
                ),
                array(
                    'type' => 'rowaction',
                    'event' => 'button:delete_button:click',
                    'name' => 'delete_button',
                    'label' => 'LBL_DELETE_BUTTON_LABEL',
                    'acl_action' => 'delete',
                ),
            ),
        ),
        array(
            'name' => 'sidebar_toggle',
            'type' => 'sidebartoggle',
        ),
    ),
    'last_state' => array(
        'id' => 'record_view',
        'defaults' => array(
            'show_more' => 'more'
        ),
    ),
    
    'panels' =>
    array(
        0 =>
        array(
            'name' => 'panel_header',
            'header' => true,
            'fields' =>
            array(
                0 =>
                array(
                    'name' => 'picture',
                    'type' => 'avatar',
                    'size' => 'large',
                    'dismiss_label' => true,
                ),
                1 =>
                array(
                    'name' => 'full_name',
                    'type' => 'fullname',
                    'label' => 'LBL_NAME',
                    'dismiss_label' => true,
                    'fields' =>
                    array(
                        0 => 'name',
                    ),
                ),
            ),
        ),
        1 =>
        array(
            'newTab' => true,
            'panelDefault' => 'expanded',
            'name' => 'LBL_RECORDVIEW_PANEL30',
            'label' => 'LBL_RECORDVIEW_PANEL30',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' =>
            array(
                0 =>
                array(
                    0 => 'opportunity_name',
                    1 => 'contracts_leads_1_name'
                ),
                1 =>
                array(
                    0 => 'account_name'
                ),
                2 =>
                array(
                    0 => 'contracts_leads_1_name'
                ),
                3 =>
                array(
                    0 =>
                    array(
                        'name' => 'provider_id_c',
                        'label' => 'LBL_PROVIDER_ID',
                    ),
                    1 =>
                    array(
                        'name' => 'credit_amount_c',
                        'label' => 'LBL_CREDIT_AMOUNT',
                    ),
                ),
                4 =>
                array(
                    0 =>
                    array(
                        'name' => 'credit_duration_c',
                        'label' => 'LBL_CREDIT_DURATION',
                    ),
                    1 =>
                    array(
                        'name' => 'contract_date_c',
                        'label' => 'LBL_CONTRACT_DATE',
                    ),
                ),
                5 =>
                array(
                    0 =>
                    array(
                        'name' => 'promo_bonus_c',
                        'label' => 'LBL_PROMO_BONUS',
                    ),
                    1 =>
                    array(
                        'name' => 'contract_complete_c',
                        'label' => 'LBL_CONTRACT_COMPLETE',
                    ),
                ),
                6 =>
                array(
                    0 =>
                    array(
                        'name' => 'paying_date_c',
                        'label' => 'LBL_PAYING_DATE',
                    ),
                    1 =>
                    array(
                        'name' => 'provision_confirmed_c',
                        'label' => 'LBL_PROVISION_CONFIRMED',
                    ),
                ),
                7 =>
                array(
                    0 =>
                    array(
                        'name' => 'interest_rate_c',
                        'label' => 'LBL_INTEREST_RATE',
                    ),
                    1 =>
                    array(
                        'name' => 'storno_c',
                        'label' => 'LBL_STORNO',
                    ),
                ),
                8 =>
                array(
                    0 =>
                    array(
                        'name' => 'storno_date_c',
                        'label' => 'LBL_STORNO_DATE',
                    ),
                    1 =>
                    array(
                        'name' => 'ppi_provision_c',
                        'label' => 'LBL_PPI_PROVISION',
                    ),
                ),
                9 =>
                array(
                    0 =>
                    array(
                        'name' => 'ppi_c',
                        'label' => 'LBL_PPI',
                    ),
                    1 =>
                    array(
                        'name' => 'payment_option_id_c',
                        'label' => 'LBL_PAYMENT_OPTION_ID',
                    ),
                ),
                10 =>
                array(
                    0 =>
                    array(
                        'name' => 'dotb_soko_c',
                    ),
                ),

                11 =>
                array(
                    0 =>
                    array(
                        'name' => 'customer_credit_amount_c',
                    ),
                    1 =>
                    array(
                        'name' => 'customer_credit_duration_c',
                    ),
                ),
                12 =>
                array(
                    0 =>
                    array(
                        'name' => 'customer_interest_rate_c',
                    ),
                    1 =>
                    array(
                        'name' => 'customer_ppi_c',
                    ),
                ),
				13 =>
                array(
                    0 =>
                    array(
                        'name' => 'contract_ppi_plus',
                    ),
                    1 =>
                    array(
                        'name' => 'contract_transfer_fee',
                    ),
                ),
            ),
        ),
    ),
);
