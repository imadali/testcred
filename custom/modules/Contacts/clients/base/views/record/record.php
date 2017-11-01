<?php

$viewdefs['Contacts'] = array(
            'base' =>
            array(
                'view' =>
                array(
                    'record' =>
                    array(
                        'buttons' =>
                        array(
                            0 =>
                            array(
                                'type' => 'button',
                                'name' => 'cancel_button',
                                'label' => 'LBL_CANCEL_BUTTON_LABEL',
                                'css_class' => 'btn-invisible btn-link',
                                'showOn' => 'edit',
                            ),
                            1 =>
                            array(
                                'type' => 'rowaction',
                                'event' => 'button:save_button:click',
                                'name' => 'save_button',
                                'label' => 'LBL_SAVE_BUTTON_LABEL',
                                'css_class' => 'btn btn-primary',
                                'showOn' => 'edit',
                                'acl_action' => 'edit',
                            ),
                            2 =>
                            array(
                                'type' => 'actiondropdown',
                                'name' => 'main_dropdown',
                                'primary' => true,
                                'showOn' => 'view',
                                'buttons' =>
                                array(
                                    0 =>
                                    array(
                                        'type' => 'rowaction',
                                        'event' => 'button:edit_button:click',
                                        'name' => 'edit_button',
                                        'label' => 'LBL_EDIT_BUTTON_LABEL',
                                        'acl_action' => 'edit',
                                    ),
                                    1 =>
                                    array(
                                        'type' => 'rowaction',
                                        'event' => 'button:create_lead:click',
                                        'name' => 'create_lead',
                                        'label' => 'LBL_CREATE_LEAD_BUTTON_LABEL',
                                        'acl_action' => 'view',
                                    ),
                                    2 =>
                                    array(
                                        'type' => 'shareaction',
                                        'name' => 'share',
                                        'label' => 'LBL_RECORD_SHARE_BUTTON',
                                        'acl_action' => 'view',
                                    ),
                                    3 =>
                                    array(
                                        'type' => 'pdfaction',
                                        'name' => 'download-pdf',
                                        'label' => 'LBL_PDF_VIEW',
                                        'action' => 'download',
                                        'acl_action' => 'view',
                                    ),
                                    4 =>
                                    array(
                                        'type' => 'pdfaction',
                                        'name' => 'email-pdf',
                                        'label' => 'LBL_PDF_EMAIL',
                                        'action' => 'email',
                                        'acl_action' => 'view',
                                    ),
                                    5 =>
                                    array(
                                        'type' => 'divider',
                                    ),
                                    6 =>
                                    array(
                                        'type' => 'manage-subscription',
                                        'name' => 'manage_subscription_button',
                                        'label' => 'LBL_MANAGE_SUBSCRIPTIONS',
                                        'showOn' => 'view',
                                        'value' => 'edit',
                                    ),
                                    7 =>
                                    array(
                                        'type' => 'vcard',
                                        'name' => 'vcard_button',
                                        'label' => 'LBL_VCARD_DOWNLOAD',
                                        'acl_action' => 'edit',
                                    ),
                                    8 =>
                                    array(
                                        'type' => 'divider',
                                    ),
                                    9 =>
                                    array(
                                        'type' => 'rowaction',
                                        'event' => 'button:find_duplicates_button:click',
                                        'name' => 'find_duplicates',
                                        'label' => 'LBL_DUP_MERGE',
                                        'acl_action' => 'edit',
                                    ),
                                    10 =>
                                    array(
                                        'type' => 'rowaction',
                                        'event' => 'button:duplicate_button:click',
                                        'name' => 'duplicate_button',
                                        'label' => 'LBL_DUPLICATE_BUTTON_LABEL',
                                        'acl_module' => 'Contacts',
                                        'acl_action' => 'create',
                                    ),
                                    11 =>
                                    array(
                                        'type' => 'rowaction',
                                        'event' => 'button:historical_summary_button:click',
                                        'name' => 'historical_summary_button',
                                        'label' => 'LBL_HISTORICAL_SUMMARY',
                                        'acl_action' => 'view',
                                    ),
                                    12 =>
                                    array(
                                        'type' => 'rowaction',
                                        'event' => 'button:audit_button:click',
                                        'name' => 'audit_button',
                                        'label' => 'LNK_VIEW_CHANGE_LOG',
                                        'acl_action' => 'view',
                                    ),
                                    13 =>
                                    array(
                                        'type' => 'divider',
                                    ),
                                    14 =>
                                    array(
                                        'type' => 'rowaction',
                                        'event' => 'button:delete_button:click',
                                        'name' => 'delete_button',
                                        'label' => 'LBL_DELETE_BUTTON_LABEL',
                                        'acl_action' => 'delete',
                                    ),
                                ),
                            ),
                            3 =>
                            array(
                                'name' => 'sidebar_toggle',
                                'type' => 'sidebartoggle',
                            ),
                        ),
                        'panels' =>
                        array(
                            0 =>
                            array(
                                'name' => 'panel_header',
                                'header' => true,
                                'fields' =>
                                array(
                                    0 =>
                                    array(
                                        'name' => 'picture',
                                        'type' => 'avatar',
                                        'size' => 'large',
                                        'dismiss_label' => true,
                                    ),
                                    1 =>
                                    array(
                                        'name' => 'full_name',
                                        'label' => 'LBL_NAME',
                                        'dismiss_label' => true,
                                        'type' => 'fullname',
                                        'fields' =>
                                        array(
                                            0 => 'salutation',
                                            1 => 'first_name',
                                            2 => 'last_name',
                                        ),
                                    ),
                                    2 =>
                                    array(
                                        'name' => 'favorite',
                                        'label' => 'LBL_FAVORITE',
                                        'type' => 'favorite',
                                        'dismiss_label' => true,
                                    ),
                                    3 =>
                                    array(
                                        'name' => 'follow',
                                        'label' => 'LBL_FOLLOW',
                                        'type' => 'follow',
                                        'readonly' => true,
                                        'dismiss_label' => true,
                                    ),
                                ),
                            ),
                            1 =>
                            array(
                                'newTab' => true,
                                'panelDefault' => 'expanded',
                                'name' => 'LBL_RECORDVIEW_PANEL10',
                                'label' => 'LBL_RECORDVIEW_PANEL10',
                                'columns' => 2,
                                'labelsOnTop' => 1,
                                'placeholders' => 1,
                                'fields' =>
                                array(
                                    0 =>
                                    array(
                                    ),
                                    1 =>
                                    array(
                                    ),
                                ),
                            ),
                            2 =>
                            array(
                                'newTab' => false,
                                'panelDefault' => 'collapsed',
                                'name' => 'LBL_RECORDVIEW_PANEL11',
                                'label' => 'LBL_RECORDVIEW_PANEL11',
                                'columns' => 2,
                                'labelsOnTop' => 1,
                                'placeholders' => 1,
                                'fields' =>
                                array(
                                    0 =>
                                    array(
                                        'name' => 'leads_contacts_1_name',
                                        'label' => 'LBL_LEAD',
                                    ),
                                    1 =>
                                    array(
                                        'name' => 'dotb_correspondence_language',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_CORRESPONDENCE_LANGUAGE',
                                    ),
                                    2 => 'email',
                                    3 => 'phone_mobile',
                                    4 =>
                                    array(
                                        'name' => 'phone_other',
                                        'comment' => 'Other phone number for the contact',
                                        'label' => 'LBL_OTHER_PHONE',
                                    ),
                                    5 =>
                                    array(
                                        'name' => 'phone_work',
                                    ),
                                    6 =>
                                    array(
                                        'name' => 'relative_type_c',
                                        'label' => 'LBL_RELATIVE_TYPE',
                                    ),
                                    7 =>
                                    array(
                                        'name' => 'assigned_user_name',
                                        'label' => 'LBL_ASSIGNED_TO_NAME',
                                    ),
									8 => 
									array (
										'name' => 'team_name',
									),
									/* 9 => 
									array (
										'name' => 'k_evalanche_id_c',
										'label' => 'LBL_EVALANCHE_ID',
									),
									10 => 
									array (
										'name' => 'k_evalanche_sync_c',
										'label' => 'LBL_EVALANCHE_SYNC',
									),
									11 => 
									array (
										'name' => 'k_evalanche_state_c',
										'label' => 'LBL_EVALANCHE_SYNC_FLAG',
									), */
                                ),
                            ),
                            3 =>
                            array(
                                'newTab' => false,
                                'panelDefault' => 'collapsed',
                                'name' => 'LBL_RECORDVIEW_PANEL12',
                                'label' => 'LBL_RECORDVIEW_PANEL12',
                                'columns' => 2,
                                'labelsOnTop' => 1,
                                'placeholders' => 1,
                                'fields' =>
                                array(
                                    0 =>
                                    array(
                                        'name' => 'birthdate',
                                        'comment' => 'The birthdate of the contact',
                                        'label' => 'LBL_BIRTHDATE',
                                    ),
                                    1 =>
                                    array(
                                        'name' => 'dotb_age_c',
                                        'label' => 'LBL_DOTB_AGE',
                                    ),
                                    2 =>
                                    array(
                                        'name' => 'dotb_gender_id',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_GENDER_ID',
                                    ),
                                    3 =>
                                    array(
                                        'name' => 'dotb_civil_status_id',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_CIVIL_STATUS_ID',
                                    ),
                                    4 =>
                                    array(
                                        'name' => 'dotb_iso_nationality_code',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_ISO_NATIONALITY_CODE',
                                    ),
                                    5 =>
                                    array(
                                        'name' => 'dotb_work_permit_type_id',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_WORK_PERMIT_TYPE_ID',
                                    ),
                                    6 =>
                                    array(
                                        'name' => 'dotb_work_permit_since',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_WORK_PERMIT_SINCE',
                                    ),
                                    7 =>
                                    array(
                                        'name' => 'dotb_work_permit_until',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_WORK_PERMIT_UNTIL',
                                    ),
                                    8 =>
                                    array(
                                        'name' => 'dotb_is_patronized_or_has_adviser',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_IS_PATRONIZED_OR_HAS_ADVISER',
                                    ),
                                    9 =>
                                    array(
                                        'name' => 'duplicate_check',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DUPLICATE_CHECK',
                                    ),
                                ),
                            ),
                            4 =>
                            array(
                                'newTab' => false,
                                'panelDefault' => 'collapsed',
                                'name' => 'LBL_RECORDVIEW_PANEL13',
                                'label' => 'LBL_RECORDVIEW_PANEL13',
                                'columns' => 2,
                                'labelsOnTop' => 1,
                                'placeholders' => 1,
                                'fields' =>
                                array(
                                    0 =>
                                    array(
                                        'name' => 'primary_address',
                                        'type' => 'fieldset',
                                        'css_class' => 'address',
                                        'label' => 'LBL_PRIMARY_ADDRESS',
                                        'fields' =>
                                        array(
                                            0 =>
                                            array(
                                                'name' => 'address_c_o',
                                                'css_class' => 'address_c_o',
                                                'placeholder' => 'LBL_ADDRESS_C_O',
                                            ),
                                            1 =>
                                            array(
                                                'name' => 'primary_address_street',
                                                'css_class' => 'address_street',
                                                'placeholder' => 'LBL_PRIMARY_ADDRESS_STREET',
                                            ),
                                            2 =>
                                            array(
                                                'name' => 'primary_address_postalcode',
                                                'css_class' => 'address_zip',
                                                'placeholder' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
                                            ),
                                            3 =>
                                            array(
                                                'name' => 'primary_address_city',
                                                'css_class' => 'address_city',
                                                'placeholder' => 'LBL_PRIMARY_ADDRESS_CITY',
                                            ),
                                            4 =>
                                            array(
                                                'name' => 'primary_address_country',
                                                'css_class' => 'address_country',
                                                'placeholder' => 'LBL_PRIMARY_ADDRESS_COUNTRY',
                                            ),
                                        ),
                                    ),
                                    1 =>
                                    array(
                                        'name' => 'correspondence_address',
                                        'type' => 'fieldset',
                                        'css_class' => 'address',
                                        'label' => 'LBL_CORRESPONDENCE_ADDRESS',
                                        'fields' =>
                                        array(
                                            0 =>
                                            array(
                                                'name' => 'correspondence_address_c_o',
                                                'css_class' => 'correspondence_address_c_o',
                                                'placeholder' => 'LBL_CORRESPONDENCE_ADDRESS_C_O',
                                            ),
                                            1 =>
                                            array(
                                                'name' => 'correspondence_address_street',
                                                'css_class' => 'address_street',
                                                'placeholder' => 'LBL_CORRESPONDENCE_ADDRESS_STREET',
                                            ),
                                            2 =>
                                            array(
                                                'name' => 'correspondence_address_postalcode',
                                                'css_class' => 'address_zip',
                                                'placeholder' => 'LBL_CORRESPONDENCE_ADDRESS_POSTALCODE',
                                            ),
                                            3 =>
                                            array(
                                                'name' => 'correspondence_address_city',
                                                'css_class' => 'address_city',
                                                'placeholder' => 'LBL_CORRESPONDENCE_ADDRESS_CITY',
                                            ),
                                            4 =>
                                            array(
                                                'name' => 'correspondence_address_country',
                                                'css_class' => 'address_country',
                                                'placeholder' => 'LBL_CORRESPONDENCE_ADDRESS_COUNTRY',
                                            ),
                                        ),
                                    ),
                                    2 =>
                                    array(
                                        'name' => 'add_address_to_history_c',
                                        'label' => 'LBL_ADD_ADDRESS_TO_HISTORY',
                                    ),
                                    3 =>
                                    array(
                                        'name' => 'dotb_resident_since',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_RESIDENT_SINCE',
                                    ),
                                ),
                            ),
                            5 =>
                            array(
                                'newTab' => false,
                                'panelDefault' => 'collapsed',
                                'name' => 'LBL_RECORDVIEW_PANEL7',
                                'label' => 'LBL_RECORDVIEW_PANEL7',
                                'columns' => 2,
                                'labelsOnTop' => 1,
                                'placeholders' => 1,
                                'fields' =>
                                array(
                                    0 =>
                                    array(
                                        'name' => 'dotb_bank_name',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_BANK_NAME',
                                    ),
                                    1 =>
                                    array(
                                        'name' => 'dotb_bank_zip_code',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_BANK_ZIP_CODE',
                                    ),
                                    2 =>
                                    array(
                                        'name' => 'dotb_bank_city_name',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_BANK_CITY_NAME',
                                    ),
                                    3 =>
                                    array(
                                        'name' => 'dotb_iban',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_IBAN',
                                    ),
                                    4 =>
                                    array(
                                        'name' => 'dotb_payout_option_id',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_PAYOUT_OPTION_ID',
                                    ),
                                    5 =>
                                    array(
                                        'name' => 'settlement_type',
                                    ),
                                ),
                            ),
                            6 =>
                            array(
                                'newTab' => true,
                                'panelDefault' => 'expanded',
                                'name' => 'LBL_RECORDVIEW_PANEL14',
                                'label' => 'LBL_RECORDVIEW_PANEL14',
                                'columns' => 2,
                                'labelsOnTop' => 1,
                                'placeholders' => 1,
                                'fields' =>
                                array(
                                    0 =>
                                    array(
                                    ),
                                    1 =>
                                    array(
                                    ),
                                ),
                            ),
                            7 =>
                            array(
                                'newTab' => false,
                                'panelDefault' => 'collapsed',
                                'name' => 'LBL_RECORDVIEW_PANEL15',
                                'label' => 'LBL_RECORDVIEW_PANEL15',
                                'columns' => 2,
                                'labelsOnTop' => 1,
                                'placeholders' => 1,
                                'fields' =>
                                array(
                                    0 =>
                                    array(
                                        'name' => 'dotb_employment_type_id',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_EMPLOYMENT_TYPE_ID',
                                        'span' => 12,
                                    ),
                                    1 =>
                                    array(
                                        'name' => 'dotb_is_pensioner',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_IS_PENSIONER',
                                    ),
                                    2 =>
                                    array(
                                        'name' => 'dotb_pension_type_id',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_PENSION_TYPE_ID',
                                    ),
                                    3 =>
                                    array(
                                        'name' => 'dotb_is_unable_to_work',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_IS_UNABLE_TO_WORK',
                                    ),
                                    4 =>
                                    array(
                                        'name' => 'dotb_unable_to_work_in_last_5_years',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_UNABLE_TO_WORK_IN_LAST_5_YEARS',
                                    ),
                                    5 =>
                                    array(
                                        'name' => 'dotb_partner_agreement_c',
                                        'label' => 'LBL_DOTB_PARTNER_AGREEMENT',
                                    ),
                                    6 =>
                                    array(
                                    ),
                                ),
                            ),
                            8 =>
                            array(
                                'newTab' => false,
                                'panelDefault' => 'collapsed',
                                'name' => 'LBL_RECORDVIEW_PANEL27',
                                'label' => 'LBL_RECORDVIEW_PANEL27',
                                'columns' => 2,
                                'labelsOnTop' => 1,
                                'placeholders' => 1,
                                'fields' =>
                                array(
                                    0 =>
                                    array(
                                        'name' => 'dotb_employer_name',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_EMPLOYER_NAME',
                                    ),
                                    1 =>
                                    array(
                                    ),
                                    2 =>
                                    array(
                                        'name' => 'dotb_employer_npa',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_EMPLOYER_NPA',
                                    ),
                                    3 =>
                                    array(
                                        'name' => 'dotb_employer_town',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_EMPLOYER_TOWN',
                                    ),
                                    4 =>
                                    array(
                                        'name' => 'dotb_employed_since',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_EMPLOYED_SINCE',
                                    ),
                                    5 =>
                                    array(
                                        'name' => 'dotb_employed_until',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_EMPLOYED_UNTIL',
                                    ),
                                    6 =>
                                    array(
                                        'name' => 'dotb_is_in_probation_period',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_IS_IN_PROBATION_PERIOD',
                                    ),
                                    7 =>
                                    array(
                                    ),
                                    8 =>
                                    array(
                                        'name' => 'dotb_monthly_net_income',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_MONTHLY_NET_INCOME',
                                    ),
                                    9 =>
                                    array(
                                        'name' => 'dotb_monthly_gross_income',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_MONTHLY_GROSS_INCOME',
                                    ),
                                    10 =>
                                    array(
                                        'name' => 'dotb_has_thirteenth_salary',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_HAS_THIRTEENTH_SALARY',
                                    ),
                                    11 =>
                                    array(
                                        'name' => 'dotb_direct_withholding_tax',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_DIRECT_WITHHOLDING_TAX',
                                    ),
                                    12 =>
                                    array(
                                        'name' => 'dotb_bonus_gratuity_c',
                                        'related_fields' =>
                                        array(
                                            0 => 'currency_id',
                                            1 => 'base_rate',
                                        ),
                                        'label' => 'LBL_BONUS_GRATUITY',
                                    ),
                                    13 =>
                                    array(
                                    ),
                                ),
                            ),
                            9 =>
                            array(
                                'newTab' => false,
                                'panelDefault' => 'collapsed',
                                'name' => 'LBL_RECORDVIEW_PANEL28',
                                'label' => 'LBL_RECORDVIEW_PANEL28',
                                'columns' => 2,
                                'labelsOnTop' => 1,
                                'placeholders' => 1,
                                'fields' =>
                                array(
                                    0 =>
                                    array(
                                        'name' => 'dotb_has_second_job',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_HAS_SECOND_JOB',
                                    ),
                                    1 =>
                                    array(
                                        'name' => 'dotb_second_job_description',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_SECOND_JOB_DESCRIPTION',
                                    ),
                                    2 =>
                                    array(
                                        'name' => 'dotb_second_job_employer_name',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_SECOND_JOB_EMPLOYER_NAME',
                                    ),
                                    3 =>
                                    array(
                                    ),
                                    4 =>
                                    array(
                                        'name' => 'dotb_second_job_employer_npa',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_SECOND_JOB_EMPLOYER_NPA',
                                    ),
                                    5 =>
                                    array(
                                        'name' => 'dotb_second_job_employer_town',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_SECOND_JOB_EMPLOYER_TOWN',
                                    ),
                                    6 =>
                                    array(
                                        'name' => 'dotb_monthly_net_income_nb_c',
                                        'related_fields' =>
                                        array(
                                            0 => 'currency_id',
                                            1 => 'base_rate',
                                        ),
                                        'label' => 'LBL_DOTB_MONTHLY_NET_INCOME',
                                    ),
                                    7 =>
                                    array(
                                        'name' => 'dotb_second_job_gross_income',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_SECOND_JOB_GROSS_INCOME',
                                    ),
                                    8 =>
                                    array(
                                        'name' => 'dotb_second_job_has_13th',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_SECOND_JOB_HAS_13TH',
                                    ),
                                    9 =>
                                    array(
                                    ),
                                    10 =>
                                    array(
                                        'name' => 'dotb_sideline_bonus_gratuity_c',
                                        'related_fields' =>
                                        array(
                                            0 => 'currency_id',
                                            1 => 'base_rate',
                                        ),
                                        'label' => 'LBL_BONUS_GRATUITY',
                                    ),
                                    11 =>
                                    array(
                                        'name' => 'sideline_hired_since_c',
                                        'label' => 'LBL_SIDELINE_HIRED_SINCE',
                                    ),
                                ),
                            ),
                            10 =>
                            array(
                                'newTab' => false,
                                'panelDefault' => 'collapsed',
                                'name' => 'LBL_RECORDVIEW_PANEL29',
                                'label' => 'LBL_RECORDVIEW_PANEL29',
                                'columns' => 2,
                                'labelsOnTop' => 1,
                                'placeholders' => 1,
                                'fields' =>
                                array(
                                    0 =>
                                    array(
                                        'name' => 'dotb_rent_alimony_income_c',
                                        'label' => 'LBL_DOTB_RENT_ALIMONY_INCOME',
                                    ),
                                    1 =>
                                    array(
                                        'name' => 'dotb_additional_income_desc',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_ADDITIONAL_INCOME_DESC',
                                    ),
                                    2 =>
                                    array(
                                        'name' => 'dotb_rent_or_alimony_income',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_RENT_OR_ALIMONY_INCOME',
                                    ),
                                    3 =>
                                    array(
                                        'name' => 'dotb_andere_c',
                                        'label' => 'LBL_ANDERE',
                                    ),
                                ),
                            ),
                            11 =>
                            array(
                                'newTab' => true,
                                'panelDefault' => 'expanded',
                                'name' => 'LBL_RECORDVIEW_PANEL19',
                                'label' => 'LBL_RECORDVIEW_PANEL19',
                                'columns' => 2,
                                'labelsOnTop' => 1,
                                'placeholders' => 1,
                                'fields' =>
                                array(
                                    0 =>
                                    array(
                                    ),
                                    1 =>
                                    array(
                                    ),
                                ),
                            ),
                            12 =>
                            array(
                                'newTab' => false,
                                'panelDefault' => 'collapsed',
                                'name' => 'LBL_RECORDVIEW_PANEL20',
                                'label' => 'LBL_RECORDVIEW_PANEL20',
                                'columns' => 2,
                                'labelsOnTop' => 1,
                                'placeholders' => 1,
                                'fields' =>
                                array(
                                    0 =>
                                    array(
                                        'name' => 'dotb_housing_situation_id',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_HOUSING_SITUATION_ID',
                                        'span' => 12,
                                    ),
                                    1 =>
                                    array(
                                        'name' => 'dotb_is_home_owner',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_IS_HOME_OWNER',
                                    ),
                                    2 =>
                                    array(
                                        'name' => 'dotb_mortgage_amount',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_MORTGAGE_AMOUNT',
                                    ),
                                    3 =>
                                    array(
                                        'name' => 'dotb_housing_costs_rent_c',
                                        'related_fields' =>
                                        array(
                                            0 => 'currency_id',
                                            1 => 'base_rate',
                                        ),
                                        'label' => 'LBL_DOTB_HOUSING_COSTS_RENT',
                                    ),
                                    4 =>
                                    array(
                                        'name' => 'dotb_is_rent_split',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_IS_RENT_SPLIT',
                                    ),
                                ),
                            ),
                            13 =>
                            array(
                                'newTab' => false,
                                'panelDefault' => 'collapsed',
                                'name' => 'LBL_RECORDVIEW_PANEL21',
                                'label' => 'LBL_RECORDVIEW_PANEL21',
                                'columns' => 2,
                                'labelsOnTop' => 1,
                                'placeholders' => 1,
                                'fields' =>
                                array(
                                    0 =>
                                    array(
                                        'name' => 'dotb_health_insurance_premium',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_HEALTH_INSURANCE_PREMIUM',
                                    ),
                                    1 =>
                                    array(
                                        'name' => 'dotb_has_premium_reduction',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_HAS_PREMIUM_REDUCTION',
                                    ),
                                    2 =>
                                    array(
                                        'name' => 'dotb_has_alimony_payments',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_HAS_ALIMONY_PAYMENTS',
                                    ),
                                    3 =>
                                    array(
                                        'name' => 'dotb_aliments',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_ALIMENTS',
                                    ),
                                    4 =>
                                    array(
                                        'name' => 'dotb_additional_expenses',
                                        'studio' => 'visible',
                                        'label' => 'LBL_DOTB_ADDITIONAL_EXPENSES',
                                        'span' => 12,
                                    ),
                                ),
                            ),
                            14 =>
                            array(
                                'newTab' => false,
                                'panelDefault' => 'collapsed',
                                'name' => 'LBL_RECORDVIEW_PANEL22',
                                'label' => 'LBL_RECORDVIEW_PANEL22',
                                'columns' => 2,
                                'labelsOnTop' => 1,
                                'placeholders' => 1,
                                'fields' =>
                                array(
                                    0 =>
                                    array(
                                        'name' => 'no_of_dependent_children_c',
                                        'label' => 'LBL_NO_OF_DEPENDENT_CHILDREN',
                                    ),
                                    1 =>
                                    array(
                                        'name' => 'children_birth_years_c',
                                        'label' => 'LBL_CHILDREN_BIRTH_YEARS',
                                        'type' => 'multiple-birthyears',
                                    ),
                                ),
                            ),
						  15 =>
							array(
								'newTab' => true,
								'panelDefault' => 'expanded',
								'name' => 'LBL_CREDIT_STATUS',
								'label' => 'LBL_CREDIT_STATUS',
								'columns' => 2,
								'labelsOnTop' => 1,
								'placeholders' => 1,
								'fields' =>
								array(
									0 =>
									array(
										'name' => 'profile_id',
										'label' => 'LBL_PROFILE_ID',
									),
									1 =>
									array(
										'name' => 'provider',
										'label' => 'LBL_PROVIDER',
									),
									2 =>
									array(
										'name' => 'credit_amount',
										'label' => 'LBL_CREDIT_AMOUNT',
									),
									3 =>
									array(
										'name' => 'duration',
										'label' => 'LBL_DURATION',
									),
									4 =>
									array(
										'name' => 'credit_potential_amount',
										'label' => 'LBL_CREDIT_POTENTIAL_AMOUNT',
									),
									5 => array(
										'name' => 'credit_potential_interest',
										'label' => 'LBL_CREDIT_POTENTIAL_INTEREST',
									),
									6 =>
									array(
										'name' => 'provider_contract_number',
										'label' => 'LBL_PROVIDER_CONTRACT_NUMBER',
									),
								),
							),
                            16 =>
							array(
								'newTab' => true,
								'panelDefault' => 'expanded',
								'name' => 'LBL_RECORDVIEW_PANEL31',
								'label' => 'LBL_RECORDVIEW_PANEL31',
								'columns' => 2,
								'labelsOnTop' => 1,
								'placeholders' => 1,
								'fields' =>
								array(
									0 =>
									array(
										//'name' => 'relative_type_dup_c',
										'name' => 'relative_type_c',
										'label' => 'LBL_RELATIVE_TYPE',
									//'label' => 'LBL_RELATIVE_TYPE_DUP',
									),
									1 =>
									array(
										//'name' => 'dotb_correspondence_language_dup_c',
										'name' => 'dotb_correspondence_language',
										'studio' => 'visible',
										'label' => 'LBL_DOTB_CORRESPONDENCE_LANGUAGE_DUP_C',
									),
									2 =>
									array(
										//'name' => 'dotb_gender_id_dup',
										'name' => 'dotb_gender_id',
										'label' => 'LBL_DOTB_GENDER_ID',
									//'label' => 'LBL_DOTB_GENDER_ID_DUP',
									),
									3 =>
									array(
										//'name' => 'dotb_birthdate_dup_c',
										'name' => 'birthdate',
										'label' => 'LBL_BIRTHDATE',
									//'label' => 'LBL_BIRTHDATE_DUP',
									),
									4 =>
									array(
										//'name' => 'dotb_nationality_code_dup_c',
										'name' => 'dotb_iso_nationality_code',
										'label' => 'LBL_DOTB_ISO_NATIONALITY_CODE',
									//'label' => 'LBL_DOTB_ISO_NATIONALITY_CODE_DUP',
									),
									5 => array(
									),
									6 =>
									array(
										//'name' => 'dotb_work_permit_since_dup_c',
										'name' => 'dotb_work_permit_since',
										'label' => 'LBL_DOTB_WORK_PERMIT_SINCE',
									//'label' => 'LBL_DOTB_WORK_PERMIT_SINCE_DUP',
									),
									7 =>
									array(
										//'name' => 'dotb_work_permit_until_dup_c',
										'name' => 'dotb_work_permit_until',
										'label' => 'LBL_DOTB_WORK_PERMIT_UNTIL',
									//'label' => 'LBL_DOTB_WORK_PERMIT_UNTIL_DUP',
									),
									8 =>
									array(
										//'name' => 'dotb_employment_type_id_dup',
										'name' => 'dotb_employment_type_id',
										'label' => 'LBL_DOTB_EMPLOYMENT_TYPE_ID',
									//'label' => 'LBL_DOTB_EMPLOYMENT_TYPE_ID_DUP',
									),
									9 =>
									array(
										//'name' => 'dotb_employer_name_dup',
										'name' => 'dotb_employer_name',
										'label' => 'LBL_DOTB_EMPLOYER_NAME',
									//'label' => 'LBL_DOTB_EMPLOYER_NAME_DUP',
									),
									10 =>
									array(
										//'name' => 'dotb_employer_npa_dup',
										'name' => 'dotb_employer_npa',
										'label' => 'LBL_DOTB_EMPLOYER_NPA',
									//'label' => 'LBL_DOTB_EMPLOYER_NPA_DUP',
									),
									11 =>
									array(
										//'name' => 'dotb_employer_town_dup',
										'name' => 'dotb_employer_town',
										'label' => 'LBL_DOTB_EMPLOYER_TOWN',
									//'label' => 'LBL_DOTB_EMPLOYER_TOWN_DUP',
									),
									12 =>
									array(
										//'name' => 'dotb_employed_since_dup',
										'name' => 'dotb_employed_since',
										'label' => 'LBL_DOTB_EMPLOYED_SINCE',
									//'label' => 'LBL_DOTB_EMPLOYED_SINCE_DUP',
									),
									13 => array(
									),
									14 =>
									array(
										//'name' => 'dotb_monthly_income_dup_c',/////////////
										'name' => 'dotb_monthly_net_income',
										'related_fields' =>
										array(
											0 => 'currency_id',
											1 => 'base_rate',
										),
										'label' => 'LBL_DOTB_MONTHLY_NET_INCOME',
									//'label' => 'LBL_DOTB_MONTHLY_NET_INCOME_DUP',
									),
									15 =>
									array(
										//'name' => 'dotb_monthly_gross_income_dup',
										'name' => 'dotb_monthly_gross_income',
										'label' => 'LBL_DOTB_MONTHLY_GROSS_INCOME',
									//'label' => 'LBL_DOTB_MONTHLY_GROSS_INCOME_DUP',
									),
									16 =>
									array(
										//'name' => 'dotb_has_thirteenth_dup_c',
										'name' => 'dotb_has_thirteenth_salary',
										'label' => 'LBL_DOTB_HAS_THIRTEENTH_SALARY',
									//'label' => 'LBL_DOTB_HAS_THIRTEENTH_SALARY_DUP',
									),
								),
							),
                        ),
                        'templateMeta' =>
                        array(
                            'useTabs' => true,
                        ),
                    ),
                ),
            ),
);
