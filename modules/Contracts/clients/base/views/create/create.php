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

$viewdefs['Contracts']['base']['view']['create'] = array(
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
                1 =>
                array(
                    'name' => 'provider_id_c',
                    'label' => 'LBL_PROVIDER_ID',
                ),
                2 =>
                array(
                    'name' => 'contract_date_c',
                    'label' => 'LBL_CONTRACT_DATE',
                ),
                3 =>
                array(
                    'name' => 'credit_amount_c',
                    'label' => 'LBL_CREDIT_AMOUNT',
                ),
                4 => 
                array(
                    'name' => 'credit_duration_c',
                    'label' => 'LBL_CREDIT_DURATION',
                ),
                5 =>
                array(
                    'name' => 'credit_amount_flex',
                    'label' => 'LBL_CREDIT_AMOUNT_FLEX',  
                ),
                6 =>
                array (
                    'name' => 'credit_duration_flex',
                    'label' => 'LBL_CREDIT_DURATION_FLEX',
                ),
                7 =>
                array(
                    'name' => 'promo_bonus_c',
                    'label' => 'LBL_PROMO_BONUS',
                ),
                8 =>
                array(
                    'name' => 'interest_rate_c',
                    'label' => 'LBL_INTEREST_RATE',
                ),
                9 => 
                array (
                  'name' => 'promo_bonus_flex',
                  'label' => 'LBL_PROMO_BONUS_FLEX',
                ),
                10 =>
                array (
                    'name' => 'interest_rate_flex',
                    'label' => 'LBL_INTEREST_RATE_FLEX',
                ),
                11 =>
                array(
                    'name' => 'contract_complete_c',
                    'label' => 'LBL_CONTRACT_COMPLETE',
                ),
                12 =>
                array(
                    'name' => 'paying_date_c',
                    'label' => 'LBL_PAYING_DATE',
                ),
                13 =>
                array(
                    'name' => 'provision_confirmed_c',
                    'label' => 'LBL_PROVISION_CONFIRMED',
                ),
                14 =>
                array(
                    'name' => 'first_payment_flex',
                    'label' => 'LBL_FIRST_PAYMENT_FLEX',
                ),
                15 =>
                array(
                    'name' => 'storno_c',
                    'label' => 'LBL_STORNO',
                ),
                16 =>
                array(
                    'name' => 'storno_date_c',
                    'label' => 'LBL_STORNO_DATE',
                ),
                17 =>
                array(
                    'name' => 'provider_contract_no',
                ),
                18 =>
                array(
                    'name' => 'ppi_provision_c',
                    'label' => 'LBL_PPI_PROVISION',
                ),
                19 =>
                array (
                    'name' => 'ppi_provision_flex',
                    'label' => 'LBL_PPI_PROVISION_FLEX',
                ),
                20 =>
                array(
                    'name' => 'provision_c',
                    'label' => 'LBL_PROVISION',
                ),
                21 => 
                array (
                  'name' => 'provision_flex',
                  'label' => 'LBL_PROVISION_FLEX',
                ),
                22 => 
                array (
                  'name' => 'bestand_bonus_flex',
                  'label' => 'LBL_BESTAND_BONUS_FLEX',
                ),
                23 =>
                array(
                    'name' => 'ppi_c',
                    'label' => 'LBL_PPI',
                ),
                24 =>
                array(
                    'name' => 'payment_option_id_c',
                    'label' => 'LBL_PAYMENT_OPTION_ID',
                ),
                25 => 
                array (
                  'name' => 'ppi_flex',
                  'label' => 'LBL_PPI_FLEX',
                ),
                26 => 
                array(  
                ),
                27 =>
                array(
                    'name' => 'dotb_soko_c',
                ), 
                28 => 
                array (
                    'name' => 'soko_flex',
                    'label' => 'LBL_SOKO_FLEX',
                ),
                29 =>
                array(
                    'name' => 'basic_commission_agent',
                ), 
                30 => 
                array(
                    'name' => 'basic_payout_date',
                ), 
                31 =>
                array(
                    'name' => 'basic_commission_agent_flex',
                ),
                32 => 
                array(
                    'name' => 'basic_payout_date_flex',
                ),
                33 =>
                array(
                    'name' => 'volume_commission_agent',
                ), 
                34 => 
                array(
                    'name' => 'volume_payout_date',
                ),
                35 =>
                array(
                   'name' => 'volume_commission_agent_flex',
                ),
                36 => 
                array(
                    'name' => 'volume_payout_date_flex',
                ),
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
                    'name' => 'customer_credit_amount_flex',
                ),
                3 =>
                array(
                    'name' => 'customer_credit_duration_flex',
                ),
                4 =>
                array(
                    'name' => 'customer_first_payment_flex',
                ),
                5 =>
                array(
                ),
                6 =>
                array(
                    'name' => 'customer_interest_rate_c',
                ),
                7 =>
                array(
                    'name' => 'customer_ppi_c',
                ),
                8 =>
                array(
                    'name' => 'customer_interest_rate_flex',
                ),
                9 =>
                array(
                    'name' => 'customer_ppi_flex',
                ),
                10 =>
                array(
                    'name' => 'contract_ppi_plus',
                ),
                11 =>
                array(
                    'name' => 'credit_card_commission',
                ),
                12 =>
                array(
                    'name' => 'contract_ppi_plus_flex',
                ),
                13 =>
                array(
                    'name' => 'credit_card_commission_flex',
                ),
                14 =>
                array(
                    'name' => 'contract_transfer_fee',
                ),
                15 =>
                array(
                ),
                16 =>
                array(
                    'name' => 'contract_transfer_fee_flex',
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
