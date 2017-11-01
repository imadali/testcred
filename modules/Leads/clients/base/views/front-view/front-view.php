<?php

$viewdefs['Leads']['base']['view']['front-view'] = array(
    'panels' => array(
        array(
            'name' => 'panel_front',
            'label' => 'LBL_PANEL_FRONT',
            'fields' => array(
                'first_name' => array(
                    'name' => 'first_name',
                    'label' => 'LBL_FIRST_NAME',
                    'span' => '6',
                    'type' => 'varchar',
                ),
                'last_name' => array(
                    'name' => 'last_name',
                    'label' => 'LBL_LAST_NAME',
                    'span' => '6',
                    'type' => 'varchar',
                ),
                'birthdate' => array(
                    'name' => 'birthdate',
                    'label' => 'LBL_BIRTHDATE',
                    'span' => '6',
                    'type' => 'date',
                ),
                'dotb_correspondence_language_c' => array(
                    'name' => 'dotb_correspondence_language_c',
                    'label' => 'LBL_DOTB_CORRESPONDENCE_LANGUAGE',
                    'span' => '6',
                    'type' => 'enum',
                ),
                'dotb_gender_id_c' => array(
                    'name' => 'dotb_gender_id_c',
                    'label' => 'LBL_DOTB_GENDER_ID',
                    'span' => '6',
                    'type' => 'enum',
                ),
                                'phone_other' => array(
                    'name' => 'phone_other',
                    'label' => 'LBL_OTHER_PHONE',
                    'span' => '6',
                    'type' => 'phone',
                ),
                'phone_mobile' => array(
                    'name' => 'phone_mobile',
                    'label' => 'LBL_MOBILE_PHONE',
                    'span' => '6',
                    'type' => 'phone',
                ),
                'phone_work' => array(
                    'name' => 'phone_work',
                    'label' => 'LBL_OFFICE_PHONE',
                    'span' => '6',
                    'type' => 'phone',
                ),
                'custom_notes' => array(
                    'name' => 'custom_notes',
                    'label' => 'LBL_NOTES',
                    'span' => '6',
                    'type' => 'custom_notes',
                ),
            )
        ),
    )
);
