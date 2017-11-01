<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$viewdefs['Prospects']['base']['view']['panel-top'] = array(
    'buttons' => array(
        array(
            'type' => 'actiondropdown',
            'name' => 'panel_dropdown',
            'css_class' => 'pull-right',
            'buttons' => array(  
            ),
        ),
    ),
    'fields' => array(
        array(
            'name' => 'collection-count',
            'type' => 'collection-count',
        ),
    ),
);
