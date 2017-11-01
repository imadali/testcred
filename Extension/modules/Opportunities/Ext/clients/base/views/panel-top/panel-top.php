<?php

    $viewdefs['Opportunities']['base']['view']['panel-top']['buttons']=array(
         array(
            'type' => 'button',
            'css_class' => 'btn-invisible',
            'icon' => 'icon-chevron-up',
            'tooltip' => 'LBL_TOGGLE_VISIBILITY',
         ),
         array(
             'type' => 'actiondropdown',
            'name' => 'panel_dropdown',
            'css_class' => 'pull-right',
            'buttons' => array(
                /*
                 * Disable Create Action
                 */
                array(
                    'type' => 'sticky-rowaction',
                    'icon' => 'icon-plus',
                    'name' => 'create_button',
                    'label' => ' ',
                    'acl_action' => 'create',
                    'tooltip' => 'LBL_CREATE_BUTTON_LABEL',
                    'css_class'=>'disabled',//Disable Create Action
                ),
                array(
                    'type' => 'link-action',
                    'name' => 'select_button',
                    'label' => 'LBL_ASSOC_RELATED_RECORD',
                    
                ),
             ),
         ),
);   