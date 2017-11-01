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
$viewdefs['Opportunities']['base']['view']['subpanel-for-leads'] = array(
    'type' => 'subpanel-list',
    'panels' => array(
        array(
            'name' => 'panel_header',
            'label' => 'LBL_PANEL_1',
            'fields' => array(
                
                0 => 
                array (
                    'name' => 'name',
                    'type' => 'appname',
                    'label' => 'LBL_LIST_OPPORTUNITY_NAME',
                    'enabled' => true,
                    'default' => true,
                    'link' => true,
                    ),
                
                1 => 
                array (
                  'name' => 'date_entered',
                  'label' => 'LBL_DATE_OF_REQUEST',
                  'enabled' => true,
                  'readonly' => true,
                  'default' => true,
                  ),
                
                2 => 
                array (
                  'name' => 'provider_status_id_c',
                  'label' => 'LBL_PROVIDER_STATUS_ID',
                  'enabled' => true,
                  'default' => true,
                  ),
                
                3 => 
                array (
                  'name' => 'assigned_user_name',
                  'target_record_key' => 'assigned_user_id',
                  'target_module' => 'Employees',
                  'label' => 'LBL_LIST_ASSIGNED_TO_NAME',
                  'enabled' => true,
                  'default' => true,
                  ),
                
                4 => 
                array (
                  'name' => 'dotb_user_approval_c',
                    'target_module' => 'Employees',
                    'label'=> 'LBL_USER_APPROVAL',
                    'enabled' => true,
                  ),
                5 =>
                array(
                    'name' => 'dotb_soko_c',
                    'label' => 'LBL_DOTB_SOKO',
                    'enabled' => true,
                    'default' => true,
                    'type' => 'bool_readonly',
                 ),
            ),
        ),
    ),
);
