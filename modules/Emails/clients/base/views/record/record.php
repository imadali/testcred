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

$viewdefs['Emails']['base']['view']['record'] = array(
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
            'type' => 'rowaction',
            'event' => 'button:close_button:click',
            'name' => 'close_button',
            'label' => 'LBL_CLOSE_BUTTON_LABEL',
            'css_class' => 'btn btn-primary',
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
              1 => 'name',
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
            'fields' => 
            array (
              1 => 
              array (
                'name' => 'assigned_user_name',
                'readonly' => true,
              ),
              2 => 
              array (
                'name' => 'date_sent',
                'readonly' => true,
              ),
              3 => 
              array (
                'name' => 'status',
                'label' => 'LBL_STATUS',
                'readonly' => true,
              ),  
              4 => 
              array (
                'name' => 'from_addr_name',
                'label' => 'LBL_FROM',
                'readonly' => true,
              ),
              5 => 
              array (
                'name' => 'to_addrs_names',
                'label' => 'LBL_TO',
                'readonly' => true,
              ),
              6 => 
              array (
                'name' => 'cc_addrs_names',
                'label' => 'LBL_CC',
                'readonly' => true,
              ),
              7 => 
              array (
                'name' => 'bcc_addrs_names',
                'label' => 'LBL_BCC',
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
              2 => array(
                'name' => 'team_name',
                'label' => 'LBL_TEAMS',
              ),  
            ),
          ),
          3 => 
            array (
              'name' => 'panel_hidden',
              'label' => 'LBL_RECORD_SHOWMORE',
              'hide' => true,
              'columns' => 2,
              'labelsOnTop' => true,
              'newTab' => false,
              'panelDefault' => 'expanded',
              'placeholders' => 1,
              'fields' => 
              array (
                0 => 
                array (
                  'name' => 'date_modified_by',
                  'readonly' => true,
                  'inline' => true,
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
                1 => 
                array (
                  'name' => 'date_entered_by',
                  'readonly' => true,
                  'inline' => true,
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
              ),
            ),  
        ),
        'templateMeta' => 
        array (
          'useTabs' => false,
        ),
);
