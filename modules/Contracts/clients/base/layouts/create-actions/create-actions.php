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

$viewdefs['Contracts']['base']['layout']['create-actions'] = array(
    'components' =>
    array(
        array(
            'layout' =>
            array(
                'components' =>
                array(
                    array(
                        'layout' =>
                        array(
                            'components' =>
                            array(
                                array(
                                    'view' => 'create-actions',
                                ),
                            ),
                            'type' => 'simple',
                            'name' => 'main-pane',
                            'span' => 8,
                        ),
                    ),
                    array(
                        'layout' =>
                        array(
                            'components' =>
                            array(),
                            'type' => 'simple',
                            'name' => 'side-pane',
                            'span' => 4,
                        ),
                    ),
                    array(
                        'layout' =>
                        array(
                            'components' =>
                            array(),
                            'type' => 'simple',
                            'name' => 'dashboard-pane',
                            'span' => 4,
                        ),
                    ),
                    array(
                        'layout' =>
                        array(
                            'components' =>
                            array(
                                array(
                                    'layout' => 'preview',
                                ),
                            ),
                            'type' => 'simple',
                            'name' => 'preview-pane',
                            'span' => 8,
                        ),
                    ),
                ),
                'type' => 'default',
                'name' => 'sidebar',
                'span' => 12,
                'last_state' => array(
                    'id' => 'create-default',
                ),
            ),
        ),
    ),
    'type' => 'create-actions',
    'name' => 'base',
    'span' => 12,
);
