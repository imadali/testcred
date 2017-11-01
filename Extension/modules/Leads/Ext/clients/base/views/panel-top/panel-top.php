<?php

    $viewdefs['Leads']['base']['view']['panel-top']['buttons']=array(
         array(
            'type' => 'button',
            'css_class' => 'btn-invisible',
            'icon' => 'icon-chevron-up',
            'tooltip' => 'LBL_TOGGLE_VISIBILITY',
         ),
         array(
             'type' => 'actiondropdown',
            'name' => 'panel_dropdown',
            //'css_class' => 'pull-right',
            'css_class'=>'disabled',
            'buttons' => array(
                array(
                    'type' => 'sticky-rowaction',
                    'icon' => 'icon-plus',
                    'name' => 'create_button',
                    'label' => ' ',
                    'acl_action' => 'create',
                    'tooltip' => 'LBL_CREATE_BUTTON_LABEL',
                    'css_class'=>'disabled',
                ),
                array(
                    'type' => 'link-action',
                    'name' => 'select_button',
                    'label' => 'LBL_ASSOC_RELATED_RECORD',
                    'css_class'=>'disabled',
                    
                ),
             ),
         ),
);   