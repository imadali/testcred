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

$viewdefs['Emails']['base']['view']['emaildetail'] = array(
    'buttons' => array(
        array(
            'type' => 'rowaction',
            'event' => 'button:close_button:click',
            'name' => 'close_button',
            'label' => 'LBL_CLOSE_BUTTON_LABEL',
            'css_class' => 'btn btn-primary',
        ),
    ),
    'panels' => 
        array (
          0 => 
          array (
            'name' => 'panel_header',
            'label' => 'LBL_PANEL_HEADER',
            'header' => true,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'name',
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
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'name',
                'readonly' => true,
              ),
              1 => 
              array (
                'name' => 'assigned_user_name',
                'readonly' => true,
              ),
              2 => 
              array (
                'name' => 'date_sent',
                'type' => 'text',
                'readonly' => true,
              ),
              3 => 
              array (
                'name' => 'from_addr',
                'label' => 'LBL_FROM',
                'readonly' => true,
              ),
              4 => 
              array (
                'name' => 'status',
                'label' => 'LBL_STATUS',
                'readonly' => true,
              ),
              4 => 
              array (
                'name' => 'to_addrs',
                'label' => 'LBL_TO',
                'readonly' => true,
              ),
              5 => 
              array (
                'name' => 'cc_addrs',
                'label' => 'LBL_CC',
                'readonly' => true,
              ),
              6 => 
              array (
                'name' => 'bcc_addrs',
                'label' => 'LBL_BCC',
                'readonly' => true,
              ),
              7 => 
              array (
                'name' => 'type',
                'readonly' => true,
              ),
            ),
          ),
          2 => 
          array (
            'name' => 'panel_body',
            'label' => 'LBL_RECORD_BODY',
            'columns' => 1,
            'labels' => true,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => 
            array (
              1 => 
              array (
                'name' => 'description_html',
                'type' => 'html',
                'label' => 'LBL_BODY',
                'span' => 12
              ),
            ),
          ),
        ),
);
