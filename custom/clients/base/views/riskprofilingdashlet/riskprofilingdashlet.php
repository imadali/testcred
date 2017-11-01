<?php

$viewdefs['base']['view']['riskprofilingdashlet'] = array(
     'dashlets' => array(
            array(
                'label' => 'Risk Profile',
                'description' => 'This dashlet acts as a quick view for risk profile.',
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
