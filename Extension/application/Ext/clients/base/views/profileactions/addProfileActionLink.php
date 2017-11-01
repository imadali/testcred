<?php

$viewdefs['base']['view']['profileactions'][] =     array(
		'route' => '#bwc/index.php?entryPoint=xperidoEntryPoint',
        'label' => 'LNK_XPERIDO_ADMIN',
        'css_class' => 'profileactions-xperidoconfig',
        'acl_action' => 'view',
        'icon' => 'fa-cog',
        'submenu' => array(
            array(
                'route' => '#bwc/index.php?entryPoint=xperidoEntryPoint',
                'label' => 'LNK_XPERIDO_CONSOLE',
                'css_class' => 'profileactions-xperidoconsole',
                'acl_action' => 'view',
                'icon' => 'fa-cog',
                'submenu' => '',
            ),
            array(
                'route' => '#X01_XperiDoConnection',
                'label' => 'LNK_XPERIDO_CONNECTIONS',
                'css_class' => 'profileactions-xperidoconnections',
                'acl_action' => 'view',
                'icon' => 'fa-signal',
                'submenu' => '',
            ),
			array(
                'route' => '#X01_XperiDoMetaData',
                'label' => 'LNK_XPERIDO_METADATA',
                'css_class' => 'profileactions-xperidometadata',
                'acl_action' => 'view',
                'icon' => 'fa-list-alt',
                'submenu' => '',
            ),
        ),
);