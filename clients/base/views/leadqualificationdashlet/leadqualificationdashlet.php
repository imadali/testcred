<?php

$viewdefs['base']['view']['leadqualificationdashlet'] = array(
    'dashlets' => array(
        array(
            'label' => 'Lead Qualification',
            'description' => 'This dashlet acts as a quick view for lead qualification.',
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
