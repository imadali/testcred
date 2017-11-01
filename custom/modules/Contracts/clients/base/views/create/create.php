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
                        1 => 'first_name',
                        2 => 'last_name',
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
                    ),
                    1 => 
                    array(
                        'name' => 'provider_contract_no',
                        'label' => 'LBL_PROVIDER_CONTRACT_NUMBER',
                    )
                ),
                7 =>
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
                8 =>
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
                9 =>
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
                10 =>
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
                11 =>
                array(
                    0 =>
                    array(
                        'name' => 'dotb_soko_c',
                    ),
                ),
                12 =>
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
                13 =>
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
		14 =>
                array(
                    0 =>
                    array(
                        'name' => 'contract_ppi_plus',
                    ),
                    1 =>
                    array(
                        'name' => 'credit_card_commission',
                    ),
                ),
		15 =>
                array(
                    0 =>
                    array(
                        'name' => 'contract_transfer_fee',
                    ),
                ),
            ),
        ),
    ),
);
