<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

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
 
$viewdefs['dotb6_contact_activities']['base']['view']['panel-top'] = array(
    'buttons' => array(
        array(
            'type' => 'actiondropdown',
            'name' => 'panel_dropdown',
            'css_class' => 'pull-right',
            'buttons' => array(
                array(
                    'type' => 'sticky-rowaction',
                    'icon' => 'fa-plus',
                    'name' => 'disabled_button',
                    'label' => ' ',
                    'acl_action' => 'admin',
                    'tooltip' => 'LBL_CHOOSE_IN_DROPDOWN',
                ),
                array(
                    'type' => 'rowaction',
                    'tooltip' => 'LBL_SCHEDULE_CALL',
                    'label' => 'LBL_SCHEDULE_CALL',
                    'event' => 'paneltop:create-call:fire',
                    'icon' => 'fa-plus',
                    'acl_action' => 'create',
                    'allow_bwc' => false
                ),
                array(
                    'type' => 'emailaction-paneltop',
                    'icon' => 'fa-plus',
                    'name' => 'email_compose_button',
                    'label' => 'LBL_COMPOSE_EMAIL_BUTTON_LABEL',
                    'acl_action' => 'create',
                    'set_recipient_to_parent' => true,
                    'set_related_to_parent' => true,
                    'tooltip' => 'LBL_COMPOSE_EMAIL_BUTTON_TITLE',
                ),
                /* array(
                    'type' => 'rowaction',
                    'tooltip' => 'LBL_SCHEDULE_MEETING',
                    'label' => 'LBL_SCHEDULE_MEETING',
                    'event' => 'paneltop:create-meeting:fire',
                    'icon' => 'fa-plus',
                    'acl_action' => 'create',
                    'allow_bwc' => false
                ), */
                array(
                    'type' => 'rowaction',
                    'tooltip' => 'LBL_CREATE_NOTE',
                    'label' => 'LBL_CREATE_NOTE',
                    'event' => 'paneltop:create-note:fire',
                    'icon' => 'fa-plus',
                    'acl_action' => 'create',
                    'allow_bwc' => false
                ),
                array(
                    'type' => 'rowaction',
                    'tooltip' => 'LBL_CREATE_TASK',
                    'label' => 'LBL_CREATE_TASK',
                    'event' => 'paneltop:create-task:fire',
                    'icon' => 'fa-plus',
                    'acl_action' => 'create',
                    'allow_bwc' => false
                ),
                array(
                    'type' => 'rowaction',
                    'tooltip' => 'LBL_CLOSE_TASKS',
                    'label' => 'LBL_CLOSE_TASKS',
                    'event' => 'paneltop:close_tasks:fire',
                    'icon' => 'fa-cogs',
                    'acl_action' => 'close_tasks',
                    'allow_bwc' => false,
                    'css_class' => 'btn-link',
                ),
            ),
        ),
    ),
);
