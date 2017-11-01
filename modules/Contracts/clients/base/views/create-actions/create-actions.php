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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$viewdefs['Contracts']['base']['view']['create-actions'] = array(
    'template' => 'record',
    'buttons' => array(
        array(
            'name' => 'cancel_button',
            'type' => 'button',
            'label' => 'LBL_CANCEL_BUTTON_LABEL',
            'css_class' => 'btn-invisible btn-link',
            'events' => array(
                'click' => 'button:cancel_button:click',
            ),
        ),
        array(
            'name' => 'restore_button',
            'type' => 'button',
            'label' => 'LBL_RESTORE',
            'css_class' => 'btn-invisible btn-link',
            'showOn' => 'select',
            'events' => array(
                'click' => 'button:restore_button:click',
            ),
        ),
        array(
            'type' => 'actiondropdown',
            'name' => 'main_dropdown',
            'primary' => true,
            'switch_on_click' => true,
            'showOn' => 'create',
            'buttons' => array(
                array(
                    'type' => 'rowaction',
                    'name' => 'save_button',
                    'label' => 'LBL_SAVE_BUTTON_LABEL',
                    'events' => array(
                        'click' => 'button:save_button:click',
                    ),
                ),
                /*array(
                    'type' => 'rowaction',
                    'name' => 'save_view_button',
                    'label' => 'LBL_SAVE_AND_VIEW',
                    'events' => array(
                        'click' => 'button:save_view_button:click',
                    ),
                ),*/
                array(
                    'type' => 'rowaction',
                    'name' => 'save_create_button',
                    'label' => 'LBL_SAVE_AND_CREATE_ANOTHER',
                    'events' => array(
                        'click' => 'button:save_create_button:click',
                    ),
                ),
            ),
        ),
        array(
            'type' => 'actiondropdown',
            'name' => 'duplicate_dropdown',
            'primary' => true,
            'showOn' => 'duplicate',
            'buttons' => array(
                array(
                    'type' => 'rowaction',
                    'name' => 'save_button',
                    'label' => 'LBL_IGNORE_DUPLICATE_AND_SAVE',
                    'events' => array(
                        'click' => 'button:save_button:click',
                    ),
                ),
            ),
        ),
        array(
            'type' => 'actiondropdown',
            'name' => 'select_dropdown',
            'primary' => true,
            'showOn' => 'select',
            'buttons' => array(
                array(
                    'type' => 'rowaction',
                    'name' => 'save_button',
                    'label' => 'LBL_SAVE_BUTTON_LABEL',
                    'events' => array(
                        'click' => 'button:save_button:click',
                    ),
                ),
            ),
        ),
        array(
            'name' => 'sidebar_toggle',
            'type' => 'sidebartoggle',
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
                0 => 'name',
            ),
        ),
        1 =>
        array(
            'newTab' => false,
            'panelDefault' => 'expanded',
            'name' => 'LBL_CONTRACT_INFORMATION',
            'label' => 'LBL_CONTRACT_INFORMATION',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' =>
            array(
//                0 =>
//                array(
//                    'name' => 'contacts_contracts_1_name',
//                    'label' => 'LBL_CONTACTS_CONTRACTS_1_FROM_CONTACTS_TITLE',
//                ),
//                1 =>
//                array(
//                    'name' => 'opportunity_name',
//                ),
//                2 =>
//                array(
//                    'name' => 'contracts_leads_1_name',
//                ),
//                3 =>
//                array(
//                    'name' => 'account_name',
//                ),
                4 =>
                array(
                    'name' => 'provider_id_c',
                    'label' => 'LBL_PROVIDER_ID',
                ),
                5 =>
                array(
                    'name' => 'credit_amount_c',
                    'label' => 'LBL_CREDIT_AMOUNT',
                ),
                6 =>
                array(
                    'name' => 'credit_duration_c',
                    'label' => 'LBL_CREDIT_DURATION',
                ),
                7 =>
                array(
                    'name' => 'contract_date_c',
                    'label' => 'LBL_CONTRACT_DATE',
                ),
                8 =>
                array(
                    'name' => 'promo_bonus_c',
                    'label' => 'LBL_PROMO_BONUS',
                ),
                9 =>
                array(
                    'name' => 'contract_complete_c',
                    'label' => 'LBL_CONTRACT_COMPLETE',
                ),
                10 =>
                array(
                    'name' => 'paying_date_c',
                    'label' => 'LBL_PAYING_DATE',
                ),
                11 =>
                array(
                    'name' => 'provision_confirmed_c',
                    'label' => 'LBL_PROVISION_CONFIRMED',
                ),
                12 =>
                array(
                    'name' => 'interest_rate_c',
                    'label' => 'LBL_INTEREST_RATE',
                ),
                13 =>
                array(
                    'name' => 'storno_c',
                    'label' => 'LBL_STORNO',
                ),
                14 =>
                array(
                    'name' => 'storno_date_c',
                    'label' => 'LBL_STORNO_DATE',
                ),
                15 =>
                array(
                    'name' => 'provider_contract_no',
                ),
                16 =>
                array(
                    'name' => 'ppi_provision_c',
                    'label' => 'LBL_PPI_PROVISION',
                ),
                17 =>
                array(
                    'name' => 'provision_c',
                    'label' => 'LBL_PROVISION',
                ),
                18 =>
                array(
                    'name' => 'ppi_c',
                    'label' => 'LBL_PPI',
                ),
                19 =>
                array(
                    'name' => 'payment_option_id_c',
                    'label' => 'LBL_PAYMENT_OPTION_ID',
                ),
                20 =>
                array(
                    'name' => 'dotb_soko_c',
                ), 
                21 =>
                array(
                    'name' => 'basic_commission_agent',
                ), 
                22 =>
                array(
                    'name' => 'basic_payout_date',
                ), 
                23 =>
                array(
                    'name' => 'volume_commission_agent',
                ), 
                24 =>
                array(
                    'name' => 'volume_payout_date',
                ),
                
                
//                20 =>
//                array(
//                    'name' => 'description',
//                ),
//                21 =>
//                array(
//                    'name' => 'customer_credit_amount_c',
//                ),
//                22 =>
//                array(
//                    'name' => 'customer_credit_duration_c',
//                ),
//                23 =>
//                array(
//                    'name' => 'customer_interest_rate_c',
//                ),
//                24 =>
//                array(
//                    'name' => 'customer_ppi_c',
//                ),
//                20 =>
//                array(
//                    'name' => 'create_contact',
//                ),
            ),
        ),
        2 =>
        array(
            'newTab' => false,
            'panelDefault' => 'expanded',
            'name' => 'LBL_EDITVIEW_PANEL1',
            'label' => 'LBL_EDITVIEW_PANEL1',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' =>
            array(
                0 =>
                array(
                    'name' => 'customer_credit_amount_c',
                ),
                1 =>
                array(
                    'name' => 'customer_credit_duration_c',
                ),
                2 =>
                array(
                    'name' => 'customer_interest_rate_c',
                ),
                3 =>
                array(
                    'name' => 'customer_ppi_c',
                ),
                4 =>
                array(
                    'name' => 'contract_ppi_plus',
                ),
                5 =>
                array(
                    'name' => 'credit_card_commission',
                ),
                6 =>
                array(
                    'name' => 'contract_transfer_fee',
                ),
            ),
        ),
        3 => array(
            'newTab' => false,
            'panelDefault' => 'expanded',
            'name' => 'LBL_PANEL_ASSIGNMENT',
            'label' => 'LBL_PANEL_ASSIGNMENT',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' =>
            array(
                0 => 'assigned_user_name',
                1 =>
                array(
                    'name' => 'team_name',
                    'displayParams' =>
                    array(
                        'required' => true,
                    ),
                ),
            ),
        ),
    ),
);
