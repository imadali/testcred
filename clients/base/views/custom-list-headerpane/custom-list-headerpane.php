<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

$viewdefs['base']['view']['custom-list-headerpane'] = array(
    'template' => 'headerpane',
    'last_state' => array(
        'id' => 'consolidated-list',
    ),
    'fields' => array(
        array(
            'name' => 'title',
            'type' => 'label',
            'default_value' => 'LBL_CONSOLIDATED_HEADER',
        ),
        array(
            'name' => 'consolidated-collection-count',
            'type' => 'consolidated-collection-count',
        ),
    ),
    'buttons' => array(),
);
