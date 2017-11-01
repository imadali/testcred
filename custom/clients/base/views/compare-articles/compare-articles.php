<?php

$viewdefs['base']['view']['compare-articles'] = array(
    'dashlets' => array(
        array(
            'label' => 'LBL_COMPARE_ARTICLES',
            'description' => 'LBL_COMPARE_ARTICLES_DESCRIPTION',
            'config' => array(
            ),
            'preview' => array(
            ),
            'filter' => array(
                'module' => array(
                    'KBContents',
                ),
                'view' => array(
                    'record',
                )
            )
        )
    )
);
