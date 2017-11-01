<?php

$viewdefs['base']['view']['tasks-lead-status'] = array(
    'dashlets' => array(
        array(
            //Display label for this dashlet
            'label' => 'LBL_LEADS_TASKS_DASHLET',
            //Description label for this Dashlet
            'description' => 'LBL_LEADS_TASKS_DASHLET_DESCRIPTION',
            'config' => array(
            ),
            'preview' => array(
            ),
            //Filter array decides where this dashlet is allowed to appear
            'filter' => array(
                'module' => array(
                    'Home',
                    
                ),
                'view' => 'record',
            ),
        ),
    ),
);