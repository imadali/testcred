<?php

$viewdefs['base']['view']['leadactivities'] = array(
    'dashlets' => array(
        array(
            'label' => 'Lead Activities',
            'description' => 'This dashlet acts as a quick view for lead activities',
            'config' => array(
            ),
            'preview' => array(
            ),
            'filter' => array(
                'module' => array(
                    'Leads',
                ),
                'view' => array(
                    'record',
                )
            )
        )
    )
);
