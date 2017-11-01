<?php
$viewdefs['Leads'] = 
array (
  'base' => 
  array (
    'view' => 
    array (
      'record' => 
      array (
        'buttons' => 
        array (
          0 => 
          array (
            'type' => 'button',
            'name' => 'cancel_button',
            'label' => 'LBL_CANCEL_BUTTON_LABEL',
            'css_class' => 'btn-invisible btn-link',
            'showOn' => 'edit',
          ),
          1 => 
          array (
            'type' => 'rowaction',
            'event' => 'button:save_button:click',
            'name' => 'save_button',
            'label' => 'LBL_SAVE_BUTTON_LABEL',
            'css_class' => 'btn btn-primary',
            'showOn' => 'edit',
            'acl_action' => 'edit',
          ),
          2 => 
          array (
            'type' => 'actiondropdown',
            'name' => 'main_dropdown',
            'primary' => true,
            'showOn' => 'view',
            'buttons' => 
            array (
              0 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:edit_button:click',
                'name' => 'edit_button',
                'label' => 'LBL_EDIT_BUTTON_LABEL',
                'acl_action' => 'edit',
              ),
              1 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:delete_button:click',
                'name' => 'delete_button',
                'label' => 'LBL_DELETE_BUTTON_LABEL',
                'acl_action' => 'delete',
              ),
              2 => 
              array (
                'type' => 'pdfaction',
                'name' => 'download-pdf',
                'label' => 'LBL_PDF_VIEW',
                'action' => 'download',
                'acl_action' => 'view',
              ),
              3 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:audit_button:click',
                'name' => 'audit_button',
                'label' => 'LNK_VIEW_CHANGE_LOG',
                'acl_action' => 'view',
              ),
              4 => 
              array (
                'type' => 'divider',
              ),
              5 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:dotb_request_deltavista_button:click',
                'name' => 'dotb_request_deltavista_button',
                'label' => 'LBL_DOTB_DELTAVISTA_REQUEST_BUTTON',
                'acl_action' => 'view',
              ),
              6 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:dotb_request_intrum_button:click',
                'name' => 'dotb_request_intrum_button',
                'label' => 'LBL_DOTB_INTRUM_REQUEST_BUTTON',
                'acl_action' => 'view',
              ),
              7 => 
              array (
                'type' => 'divider',
              ),
              8 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:dotb_cembra_pdf_export_button:click',
                'name' => 'dotb_cembra_pdf_export_button',
                'label' => 'LBL_DOTB_CEMBRA_PDF_EXPORT_BUTTON',
                'acl_action' => 'view',
              ),
              9 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:generate_briefing_pdf_eny:click',
                'name' => 'generate_briefing_pdf_eny',
                'label' => 'LBL_GENERATE_PDF_ENY',
                'acl_action' => 'view',
              ),
              10 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:generate_briefing_pdf_bob:click',
                'name' => 'generate_briefing_pdf_bob',
                'label' => 'LBL_GENERATE_PDF_BOB',
                'acl_action' => 'view',
              ),
              11 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:generate_briefing_pdf_bank_now_casa:click',
                'name' => 'generate_briefing_pdf_bank_now_casa',
                'label' => 'LBL_GENERATE_PDF_BANK_NOW_CASA',
                'acl_action' => 'view',
              ),
              12 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:generate_briefing_pdf_bank_now_car:click',
                'name' => 'generate_briefing_pdf_bank_now_car',
                'label' => 'LBL_GENERATE_PDF_BANK_NOW_CAR',
                'acl_action' => 'view',
              ),
              13 => 
              array (
                'type' => 'divider',
              ),
              14 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:send_documents:click',
                'name' => 'send_documents',
                'label' => 'LBL_SEND_DOCUMENTS',
                'acl_action' => 'view',
              ),
              15 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:email_to_crif:click',
                'name' => 'email_to_crif',
                'label' => 'LBL_EMAIL_TO_CRIF',
                'acl_action' => 'view',
              ),
              16 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:historical_summary_button:click',
                'name' => 'historical_summary_button',
                'label' => 'LBL_HISTORICAL_SUMMARY_CUSTOM',
                'acl_action' => 'view',
              ),
              17 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:remove_extra_categories:click',
                'name' => 'remove_extra_categories',
                'label' => 'LBL_REMOVE_EXTRA_CATEGORIES',
                'acl_action' => 'view',
              ),
              18 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:convert_to_pdf_preview:click',
                'name' => 'convert_to_pdf_preview',
                'label' => 'LBL_CONVERT_TO_PDF_PREVIEW',
                'acl_action' => 'view',
              ),
              19 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:rci_portal:click',
                'name' => 'rci_portal',
                'label' => 'LBL_RCI_PORTAL',
                'acl_action' => 'view',
              ),
            ),
          ),
          3 => 
          array (
            'name' => 'sidebar_toggle',
            'type' => 'sidebartoggle',
          ),
        ),
        'panels' => 
        array (
          0 => 
          array (
            'name' => 'panel_header',
            'header' => true,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'picture',
                'type' => 'avatar',
                'size' => 'large',
                'dismiss_label' => true,
              ),
              1 => 
              array (
                'name' => 'full_name',
                'type' => 'fullname',
                'label' => 'LBL_NAME',
                'dismiss_label' => true,
                'fields' => 
                array (
                  0 => 
                  array (
                    'name' => 'salutation',
                    'type' => 'enum',
                    'enum_width' => 'auto',
                    'searchBarThreshold' => 7,
                  ),
                  1 => 'first_name',
                  2 => 'last_name',
                ),
              ),
              2 => 
              array (
                'type' => 'favorite',
              ),
              3 => 
              array (
                'type' => 'follow',
                'readonly' => true,
              ),
              4 => 
              array (
                'name' => 'badge',
                'type' => 'badge',
                'readonly' => true,
                'related_fields' => 
                array (
                  0 => 'converted',
                  1 => 'account_id',
                  2 => 'contact_id',
                  3 => 'contact_name',
                  4 => 'opportunity_id',
                  5 => 'opportunity_name',
                ),
              ),
            ),
          ),
          1 => 
          array (
            'newTab' => true,
            'panelDefault' => 'expanded',
            'name' => 'LBL_RECORDVIEW_PANEL30',
            'label' => 'LBL_RECORDVIEW_PANEL30',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
              ),
              1 => 
              array (
              ),
            ),
          ),
          2 => 
          array (
            'newTab' => false,
            'panelDefault' => 'expanded',
            'name' => 'LBL_RECORDVIEW_PANEL5',
            'label' => 'LBL_RECORDVIEW_PANEL5',
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'columns' => 2,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'leads_documents',
                'readonly' => 'true',
                'dismiss_label' => true,
                'type' => 'subpanel',
                'linkField' => 'leads_documents_1',
                'DocTrackingLink' => 'documents_dotb7_document_tracking_1',
                'relatedModule' => 'Documents',
                'columns' => 
                array (
                  0 => 
                  array (
                    'name' => 'document_name',
                    'label' => 'LBL_NAME',
                  ),
                ),
                'span' => 12,
              ),
            ),
          ),
          3 => 
          array (
            'newTab' => true,
            'panelDefault' => 'expanded',
            'name' => 'LBL_RECORDVIEW_PANEL26',
            'label' => 'LBL_RECORDVIEW_PANEL26',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
              ),
              1 => 
              array (
              ),
            ),
          ),
          4 => 
          array (
            'newTab' => false,
            'panelDefault' => 'expanded',
            'name' => 'LBL_RECORDVIEW_PANEL33',
            'label' => 'LBL_RECORDVIEW_PANEL33',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'credit_request_status_id_c',
                'label' => 'LBL_CREDIT_REQUEST_STATUS_ID',
              ),
              1 => 
              array (
                'name' => 'credit_request_substatus_id_c',
                'label' => 'LBL_CREDIT_REQUEST_SUBSTATUS_ID',
              ),
              2 => 
              array (
                'name' => 'input_process_type_id_c',
                'label' => 'LBL_INPUT_PROCESS_TYPE_ID',
              ),
              3 => 
              array (
                'name' => 'contact_type_option_id_c',
                'label' => 'LBL_CONTACT_TYPE_OPTION_ID',
              ),
              4 => 
              array (
                'name' => 'assigned_user_name',
                'label' => 'LBL_ASSIGNED_TO_NAME',
              ),
              5 => 
              array (
                'name' => 'team_name',
              ),
              /* CRED-972 : Using many-to-many relationshio between leads and evalanche campaign
              6 => 
              array (
                'name' => 'campaign_name',
              ),*/
            ),
          ),
          5 => 
          array (
            'newTab' => false,
            'panelDefault' => 'collapsed',
            'name' => 'LBL_RECORDVIEW_PANEL8',
            'label' => 'LBL_RECORDVIEW_PANEL8',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'credit_amount_c',
                'label' => 'LBL_CREDIT_AMOUNT',
              ),
              1 => 
              array (
                'name' => 'credit_duration_c',
                'label' => 'LBL_CREDIT_DURATION',
              ),
              2 => 
              array (
                'name' => 'ppi_id_c',
                'label' => 'LBL_PPI_ID',
              ),
              3 => 
              array (
                'name' => 'calculated_margin',
                'label' => 'LBL_KREMO_MARGIN',
              ),
              4 => 
              array (
                'name' => 'credit_usage_type_id_c',
                'label' => 'LBL_CREDIT_USAGE_TYPE_ID',
              ),
              5 => 
              array (
                'name' => 'other_credit_reason_c',
                'label' => 'LBL_OTHER_CREDIT_REASON',
              ),
              6 => 
              array (
                'name' => 'has_applied_for_other_credit_c',
                'label' => 'LBL_HAS_APPLIED_FOR_OTHER_CREDIT',
              ),
              7 => 
              array (
              ),
              8 => 
              array (
                'name' => 'description',
                'span' => 12,
              ),
            ),
          ),
          6 => 
          array (
            'newTab' => false,
            'panelDefault' => 'collapsed',
            'name' => 'LBL_RECORDVIEW_PANEL19',
            'label' => 'LBL_RECORDVIEW_PANEL19',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'dotb_had_past_credit_c',
                'label' => 'LBL_DOTB_HAD_PAST_CREDIT',
              ),
              1 => 
              array (
                'name' => 'dotb_payment_behaviour_type_c',
                'label' => 'LBL_DOTB_PAYMENT_BEHAVIOUR_TYPE',
              ),
              2 => 
              array (
                'name' => 'dotb_credit_anomaly_provider_c',
                'label' => 'LBL_DOTB_CREDIT_ANOMALY_PROVIDER',
              ),
              3 => 
              array (
              ),
              4 => 
              array (
                'name' => 'dotb_credit_denial_in_last_2_c',
                'label' => 'LBL_DOTB_CREDIT_DENIAL_IN_LAST_2',
                'span' => 12,
              ),
              5 => 
              array (
                'name' => 'dotb_credit_denial_in_last_6_c',
                'label' => 'LBL_DOTB_CREDIT_DENIAL_IN_LAST_6',
              ),
              6 => 
              array (
                'name' => 'dotb_denial_provider_c',
                'label' => 'LBL_DOTB_DENIAL_PROVIDER',
              ),
              7 => 
              array (
                'name' => 'dotb_had_warnings_in_last_3_c',
                'label' => 'LBL_DOTB_HAD_WARNINGS_IN_LAST_3',
                'span' => 12,
              ),
            ),
          ),
          7 => 
          array (
            'newTab' => false,
            'panelDefault' => 'collapsed',
            'name' => 'LBL_RECORDVIEW_PANEL28',
            'label' => 'LBL_RECORDVIEW_PANEL28',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'dotb_has_enforcements_c',
                'label' => 'LBL_DOTB_HAS_ENFORCEMENTS',
              ),
              1 => 
              array (
                'name' => 'dot_enforcements_description_c',
                'label' => 'LBL_DOT_ENFORCEMENTS_DESCRIPTION',
              ),
              2 => 
              array (
              ),
              3 => 
              array (
                'name' => 'dotb_current_enforcement_num_c',
                'label' => 'LBL_DOTB_CURRENT_ENFORCEMENT_NUM',
              ),
              4 => 
              array (
              ),
              5 => 
              array (
                'name' => 'dotb_current_enforcement_amo_c',
                'label' => 'LBL_DOTB_CURRENT_ENFORCEMENT_AMO',
              ),
              6 => 
              array (
                'name' => 'dotb_past_enforcements_c',
                'label' => 'LBL_DOTB_PAST_ENFORCEMENTS',
              ),
              7 => 
              array (
                'name' => 'dotb_past_enforcement_number_c',
                'label' => 'LBL_DOTB_PAST_ENFORCEMENT_NUMBER',
              ),
              8 => 
              array (
              ),
              9 => 
              array (
                'name' => 'dotb_past_enforcement_amount_c',
                'label' => 'LBL_DOTB_PAST_ENFORCEMENT_AMOUNT',
              ),
              10 => 
              array (
                'name' => 'dotb_has_open_attachment_c',
                'label' => 'LBL_DOTB_HAS_OPEN_ATTACHMENT',
              ),
              11 => 
              array (
                'name' => 'dotb_date_of_loss_c',
                'label' => 'LBL_DATE_OF_LOSS',
              ),
            ),
          ),
          8 => 
          array (
            'newTab' => true,
            'panelDefault' => 'expanded',
            'name' => 'LBL_RECORDVIEW_PANEL27',
            'label' => 'LBL_RECORDVIEW_PANEL27',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
              ),
              1 => 
              array (
              ),
            ),
          ),
          9 => 
          array (
            'newTab' => false,
            'panelDefault' => 'collapsed',
            'name' => 'LBL_RECORDVIEW_PANEL9',
            'label' => 'LBL_RECORDVIEW_PANEL9',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'contact_name',
                'label' => 'LBL_CONTACT_NAME',
              ),
              1 => 
              array (
                'name' => 'dotb_correspondence_language_c',
                'label' => 'LBL_DOTB_CORRESPONDENCE_LANGUAGE',
              ),
              2 => 'email',
              3 => 'phone_mobile',
              4 => 
              array (
                'name' => 'phone_other',
                'comment' => 'Other phone number for the contact',
                'label' => 'LBL_OTHER_PHONE',
              ),
              5 => 
              array (
                'name' => 'phone_work',
              ),
            ),
          ),
          10 => 
          array (
            'name' => 'panel_body',
            'label' => 'LBL_RECORD_BODY',
            'columns' => 2,
            'labels' => true,
            'labelsOnTop' => true,
            'placeholders' => true,
            'newTab' => false,
            'panelDefault' => 'collapsed',
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'birthdate',
                'comment' => 'The birthdate of the contact',
                'label' => 'LBL_BIRTHDATE',
              ),
              1 => 
              array (
                'name' => 'dotb_age_c',
                'label' => 'LBL_DOTB_AGE',
              ),
              2 => 
              array (
                'name' => 'dotb_gender_id_c',
                'label' => 'LBL_DOTB_GENDER_ID',
              ),
              3 => 
              array (
                'name' => 'dotb_civil_status_id_c',
                'label' => 'LBL_DOTB_CIVIL_STATUS_ID',
              ),
              4 => 
              array (
                'name' => 'dotb_iso_nationality_code_c',
                'label' => 'LBL_DOTB_ISO_NATIONALITY_CODE',
              ),
              5 => 
              array (
                'name' => 'dotb_work_permit_type_id_c',
                'label' => 'LBL_DOTB_WORK_PERMIT_TYPE_ID',
              ),
              6 => 
              array (
                'name' => 'dotb_work_permit_since_c',
                'label' => 'LBL_DOTB_WORK_PERMIT_SINCE',
              ),
              7 => 
              array (
                'name' => 'dotb_work_permit_until_c',
                'label' => 'LBL_DOTB_WORK_PERMIT_UNTIL',
              ),
            ),
          ),
          11 => 
          array (
            'newTab' => false,
            'panelDefault' => 'collapsed',
            'name' => 'LBL_RECORDVIEW_PANEL10',
            'label' => 'LBL_RECORDVIEW_PANEL10',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'primary_address',
                'type' => 'fieldset',
                'css_class' => 'address',
                'label' => 'LBL_PRIMARY_ADDRESS',
                'fields' => 
                array (
                  0 => 
                  array (
                    'name' => 'address_c_o',
                    'css_class' => 'address_c_o',
                    'placeholder' => 'LBL_ADDRESS_C_O',
                  ),
                  1 => 
                  array (
                    'name' => 'primary_address_street',
                    'css_class' => 'address_street',
                    'placeholder' => 'LBL_PRIMARY_ADDRESS_STREET',
                  ),
                  2 => 
                  array (
                    'name' => 'primary_address_postalcode',
                    'css_class' => 'address_zip',
                    'placeholder' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
                  ),
                  3 => 
                  array (
                    'name' => 'primary_address_city',
                    'css_class' => 'address_city',
                    'placeholder' => 'LBL_PRIMARY_ADDRESS_CITY',
                  ),
                  4 => 
                  array (
                    'name' => 'primary_address_country',
                    'css_class' => 'address_country',
                    'placeholder' => 'LBL_PRIMARY_ADDRESS_COUNTRY',
                  ),
                ),
              ),
              1 => 
              array (
                'name' => 'correspondence_address',
                'type' => 'fieldset',
                'css_class' => 'address',
                'label' => 'LBL_CORRESPONDENCE_ADDRESS',
                'fields' => 
                array (
                  0 => 
                  array (
                    'name' => 'correspondence_address_c_o',
                    'css_class' => 'correspondence_address_c_o',
                    'placeholder' => 'LBL_CORRESPONDENCE_ADDRESS_C_O',
                  ),
                  1 => 
                  array (
                    'name' => 'correspondence_address_street',
                    'css_class' => 'address_street',
                    'placeholder' => 'LBL_CORRESPONDENCE_ADDRESS_STREET',
                  ),
                  2 => 
                  array (
                    'name' => 'correspondence_address_postalcode',
                    'css_class' => 'address_zip',
                    'placeholder' => 'LBL_CORRESPONDENCE_ADDRESS_POSTALCODE',
                  ),
                  3 => 
                  array (
                    'name' => 'correspondence_address_city',
                    'css_class' => 'address_city',
                    'placeholder' => 'LBL_CORRESPONDENCE_ADDRESS_CITY',
                  ),
                  4 => 
                  array (
                    'name' => 'correspondence_address_country',
                    'css_class' => 'address_country',
                    'placeholder' => 'LBL_CORRESPONDENCE_ADDRESS_COUNTRY',
                  ),
                ),
              ),
              2 => 
              array (
                'name' => 'add_address_to_history_c',
                'label' => 'LBL_ADD_ADDRESS_TO_HISTORY',
              ),
              3 => 
              array (
                'name' => 'dotb_resident_since_c',
                'label' => 'LBL_DOTB_RESIDENT_SINCE',
              ),
            ),
          ),
          12 => 
          array (
            'newTab' => false,
            'panelDefault' => 'collapsed',
            'name' => 'LBL_RECORDVIEW_PANEL20',
            'label' => 'LBL_RECORDVIEW_PANEL20',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'dotb_bank_name_c',
                'label' => 'LBL_DOTB_BANK_NAME',
              ),
              1 => 
              array (
                'name' => 'dotb_bank_zip_code_c',
                'label' => 'LBL_DOTB_BANK_ZIP_CODE',
              ),
              2 => 
              array (
                'name' => 'dotb_bank_city_name_c',
                'label' => 'LBL_DOTB_BANK_CITY_NAME',
              ),
              3 => 
              array (
                'name' => 'dotb_iban_c',
                'label' => 'LBL_DOTB_IBAN',
              ),
              4 => 
              array (
                'name' => 'dotb_payout_option_id_c',
                'label' => 'LBL_DOTB_PAYOUT_OPTION_ID',
              ),
              5 => 
              array (
                'name' => 'settlement_type',
              ),
            ),
          ),
          13 => 
          array (
            'newTab' => true,
            'panelDefault' => 'expanded',
            'name' => 'LBL_RECORDVIEW_PANEL23',
            'label' => 'LBL_RECORDVIEW_PANEL23',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
              ),
              1 => 
              array (
              ),
            ),
          ),
          14 => 
          array (
            'newTab' => false,
            'panelDefault' => 'collapsed',
            'name' => 'LBL_RECORDVIEW_PANEL34',
            'label' => 'LBL_RECORDVIEW_PANEL34',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'dotb_employment_type_id_c',
                'label' => 'LBL_DOTB_EMPLOYMENT_TYPE_ID',
                'span' => 12,
              ),
              1 => 
              array (
                'name' => 'dotb_is_pensioner_c',
                'label' => 'LBL_DOTB_IS_PENSIONER',
              ),
              2 => 
              array (
                'name' => 'dotb_pension_type_id_c',
                'label' => 'LBL_DOTB_PENSION_TYPE_ID',
              ),
              3 => 
              array (
                'name' => 'dotb_is_unable_to_work_c',
                'label' => 'LBL_DOTB_IS_UNABLE_TO_WORK',
              ),
              4 => 
              array (
                'name' => 'dotb_unable_to_work_in_last_c',
                'label' => 'LBL_DOTB_UNABLE_TO_WORK_IN_LAST',
              ),
              5 => 
              array (
                'name' => 'dotb_partner_agreement_c',
                'label' => 'LBL_DOTB_PARTNER_AGREEMENT',
              ),
              6 => 
              array (
              ),
            ),
          ),
          15 => 
          array (
            'newTab' => false,
            'panelDefault' => 'collapsed',
            'name' => 'LBL_RECORDVIEW_PANEL12',
            'label' => 'LBL_RECORDVIEW_PANEL12',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'dotb_employer_name_c',
                'label' => 'LBL_DOTB_EMPLOYER_NAME',
              ),
              1 => 
              array (
              ),
              2 => 
              array (
                'name' => 'dotb_employer_npa_c',
                'label' => 'LBL_DOTB_EMPLOYER_NPA',
              ),
              3 => 
              array (
                'name' => 'dotb_employer_town_c',
                'label' => 'LBL_DOTB_EMPLOYER_TOWN',
              ),
              4 => 
              array (
                'name' => 'dotb_employed_since_c',
                'label' => 'LBL_DOTB_EMPLOYED_SINCE',
              ),
              5 => 
              array (
                'name' => 'dotb_employed_until_c',
                'label' => 'LBL_DOTB_EMPLOYED_UNTIL',
              ),
              6 => 
              array (
                'name' => 'dotb_is_in_probation_period_c',
                'label' => 'LBL_DOTB_IS_IN_PROBATION_PERIOD',
              ),
              7 => 
              array (
              ),
              8 => 
              array (
                'name' => 'dotb_monthly_net_income_c',
                'label' => 'LBL_DOTB_MONTHLY_NET_INCOME_2',
              ),
              9 => 
              array (
                'name' => 'dotb_monthly_gross_income_c',
                'label' => 'LBL_DOTB_MONTHLY_GROSS_INCOME_2',
              ),
              10 => 
              array (
                'name' => 'dotb_has_thirteenth_salary_c',
                'label' => 'LBL_DOTB_HAS_THIRTEENTH_SALARY',
              ),
              11 => 
              array (
                'name' => 'dotb_direct_withholding_tax_c',
                'label' => 'LBL_DOTB_DIRECT_WITHHOLDING_TAX',
              ),
              12 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'dotb_bonus_gratuity_c',
                'label' => 'LBL_BONUS_GRATUITY',
              ),
              13 => 
              array (
              ),
            ),
          ),
          16 => 
          array (
            'newTab' => false,
            'panelDefault' => 'collapsed',
            'name' => 'LBL_RECORDVIEW_PANEL36',
            'label' => 'LBL_RECORDVIEW_PANEL36',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'dotb_has_second_job_c',
                'label' => 'LBL_DOTB_HAS_SECOND_JOB',
              ),
              1 => 
              array (
                'name' => 'dotb_second_job_description_c',
                'label' => 'LBL_DOTB_SECOND_JOB_DESCRIPTION',
              ),
              2 => 
              array (
                'name' => 'dot_second_job_employer_name_c',
                'label' => 'LBL_DOT_SECOND_JOB_EMPLOYER_NAME',
              ),
              3 => 
              array (
              ),
              4 => 
              array (
                'name' => 'dotb_second_job_employer_npa_c',
                'label' => 'LBL_DOTB_SECOND_JOB_EMPLOYER_NPA',
              ),
              5 => 
              array (
                'name' => 'dot_second_job_employer_town_c',
                'label' => 'LBL_DOT_SECOND_JOB_EMPLOYER_TOWN',
              ),
              6 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'dotb_monthly_net_income_nb_c',
                'label' => 'LBL_DOTB_MONTHLY_NET_INCOME',
              ),
              7 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'dotb_second_job_gross_income_c',
                'label' => 'LBL_DOTB_SECOND_JOB_GROSS_INCOME',
              ),
              8 => 
              array (
                'name' => 'dotb_second_job_has_13th_c',
                'label' => 'LBL_DOTB_SECOND_JOB_HAS_13TH',
              ),
              9 => 
              array (
              ),
              10 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'dotb_sideline_bonus_gratuity_c',
                'label' => 'LBL_BONUS_GRATUITY',
              ),
              11 => 
              array (
                'name' => 'sideline_hired_since_c',
                'label' => 'LBL_SIDELINE_HIRED_SINCE',
              ),
            ),
          ),
          17 => 
          array (
            'newTab' => false,
            'panelDefault' => 'collapsed',
            'name' => 'LBL_RECORDVIEW_PANEL35',
            'label' => 'LBL_RECORDVIEW_PANEL35',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'dotb_rent_alimony_income_c',
                'label' => 'LBL_DOTB_RENT_ALIMONY_INCOME',
              ),
              1 => 
              array (
                'name' => 'dotb_additional_income_desc_c',
                'label' => 'LBL_DOTB_ADDITIONAL_INCOME_DESC',
              ),
              2 => 
              array (
                'name' => 'dotb_rent_or_alimony_income_c',
                'label' => 'LBL_DOTB_RENT_OR_ALIMONY_INCOME',
              ),
              3 => 
              array (
                'name' => 'dotb_andere_c',
                'label' => 'LBL_ANDERE',
              ),
            ),
          ),
          18 => 
          array (
            'newTab' => true,
            'panelDefault' => 'collapsed',
            'name' => 'LBL_RECORDVIEW_PANEL17',
            'label' => 'LBL_RECORDVIEW_PANEL17',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
              ),
              1 => 
              array (
              ),
            ),
          ),
          19 => 
          array (
            'newTab' => false,
            'panelDefault' => 'collapsed',
            'name' => 'LBL_RECORDVIEW_PANEL25',
            'label' => 'LBL_RECORDVIEW_PANEL25',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'dotb_housing_situation_id_c',
                'label' => 'LBL_DOTB_HOUSING_SITUATION_ID',
                'span' => 12,
              ),
              1 => 
              array (
                'name' => 'dotb_is_home_owner_c',
                'label' => 'LBL_DOTB_IS_HOME_OWNER',
              ),
              2 => 
              array (
                'name' => 'dotb_mortgage_amount_c',
                'label' => 'LBL_DOTB_MORTGAGE_AMOUNT',
              ),
              3 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'dotb_housing_costs_rent_c',
                'label' => 'LBL_DOTB_HOUSING_COSTS_RENT',
              ),
              4 => 
              array (
                'name' => 'dotb_is_rent_split_c',
                'label' => 'LBL_DOTB_IS_RENT_SPLIT',
              ),
            ),
          ),
          20 => 
          array (
            'newTab' => false,
            'panelDefault' => 'collapsed',
            'name' => 'LBL_RECORDVIEW_PANEL2',
            'label' => 'LBL_RECORDVIEW_PANEL2',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'dot_health_insurance_premium_c',
                'label' => 'LBL_DOT_HEALTH_INSURANCE_PREMIUM',
              ),
              1 => 
              array (
                'name' => 'dotb_has_premium_reduction_c',
                'label' => 'LBL_DOTB_HAS_PREMIUM_REDUCTION',
              ),
              2 => 
              array (
                'name' => 'dotb_has_alimony_payments_c',
                'label' => 'LBL_DOTB_HAS_ALIMONY_PAYMENTS',
              ),
              3 => 
              array (
                'name' => 'dotb_aliments_c',
                'label' => 'LBL_DOTB_ALIMENTS',
              ),
              4 => 
              array (
                'name' => 'dotb_additional_expenses_c',
                'label' => 'LBL_DOTB_ADDITIONAL_EXPENSES',
              ),
              5 => 
              array (
              ),
            ),
          ),
          21 => 
          array (
            'newTab' => false,
            'panelDefault' => 'collapsed',
            'name' => 'LBL_RECORDVIEW_PANEL16',
            'label' => 'LBL_RECORDVIEW_PANEL16',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'no_of_dependent_children_c',
                'label' => 'LBL_NO_OF_DEPENDENT_CHILDREN',
              ),
              1 => 
              array (
                'name' => 'children_birth_years_c',
                'label' => 'LBL_CHILDREN_BIRTH_YEARS',
                'type' => 'multiple-birthyears',
              ),
            ),
          ),
          22 => 
          array (
            'newTab' => true,
            'panelDefault' => 'expanded',
            'name' => 'LBL_RECORDVIEW_PANEL29',
            'label' => 'LBL_RECORDVIEW_PANEL29',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
              ),
              1 => 
              array (
              ),
            ),
          ),
          23 => 
          array (
            'newTab' => false,
            'panelDefault' => 'collapsed',
            'name' => 'LBL_RECORDVIEW_PANEL4',
            'label' => 'LBL_RECORDVIEW_PANEL4',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'has_deltavista_response_c',
                'studio' => 'visible',
                'label' => 'LBL_HAS_DELTAVISTA_RESPONSE',
              ),
              1 => 
              array (
                'name' => 'deltavista_score_c',
                'label' => 'LBL_DELTAVISTA_SCORE',
              ),
              /**
              * CRED-767 additional fields
              */
			  2 => 
              array (
                'name' => 'credit_consumer_check',
                'studio' => 'visible',
                'label' => 'LBL_CREDIT_CONSUMER_CHECK',
                'type' => 'previewable-document',
                'readonly' => true,
              ),
              3 =>
              array (
                'name' => 'dv_risk_class',
                'studio' => 'visible',
                'label' => 'LBL_DV_RISK_CLASS',
              ),
              4 => 
              array (
                'name' => 'deltavista_request_id_c',
                'studio' => 'visible',
                'label' => 'LBL_DELTAVISTA_REQUEST_ID',
              ),
              5 => 
              array (
                'name' => 'dotb_deltavista_response_c',
                'studio' => 'visible',
                'label' => 'LBL_DOTB_DELTAVISTA_RESPONSE',
              ),
            ),
          ),
          24 => 
          array (
            'newTab' => false,
            'panelDefault' => 'collapsed',
            'name' => 'LBL_RECORDVIEW_PANEL31',
            'label' => 'LBL_RECORDVIEW_PANEL31',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'has_intrum_response_c',
                'studio' => 'visible',
                'label' => 'LBL_HAS_INTRUM_RESPONSE',
              ),
              1 => 
              array (
                'name' => 'intrum_score_c',
                'label' => 'LBL_INTRUM_SCORE',
              ),
              2 => 
              array (
                'name' => 'intrum_request_id_c',
                'studio' => 'visible',
                'label' => 'LBL_INTRUM_REQUEST_ID',
              ),
              3 => 
              array (
                'name' => 'dotb_intrum_response_c',
                'studio' => 'visible',
                'label' => 'LBL_DOTB_INTRUM_RESPONSE',
              ),
            ),
          ),
          25 => 
          array (
            'newTab' => false,
            'panelDefault' => 'collapsed',
            'name' => 'LBL_SCORE_CREDARIS',
            'label' => 'LBL_SCORE_CREDARIS',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'initial_score',
                'studio' => 'visible',
                'label' => 'LBL_INITIAL_SCORE',
                'readonly' => true,
              ),
            ),
          ),
          26 => 
          array (
            'newTab' => true,
            'panelDefault' => 'expanded',
            'name' => 'LBL_RECORDVIEW_PANEL32',
            'label' => 'LBL_RECORDVIEW_PANEL32',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'cstm_last_name_c',
              ),
              1 => 
              array (
                'name' => 'application_number',
                'label' => 'LBL_APPLICATION_NUMBER',
              ),
              2 => 
              array (
                'name' => 'date_entered_by',
                'readonly' => true,
                'inline' => true,
                'type' => 'fieldset',
                'label' => 'LBL_DATE_ENTERED',
                'fields' => 
                array (
                  0 => 
                  array (
                    'name' => 'date_entered',
                  ),
                  1 => 
                  array (
                    'type' => 'label',
                    'default_value' => 'LBL_BY',
                  ),
                  2 => 
                  array (
                    'name' => 'created_by_name',
                  ),
                ),
              ),
              3 => 'reference_number_c',
              4 => 
              array (
                'name' => 'credit_request_number_c',
                'label' => 'LBL_CREDIT_REQUEST_NUMBER',
              ),
              5 => 
              array (
                'name' => 'comparis_ref_gid_c',
                'label' => 'LBL_COMPARIS_REF_GID',
              ),
              6 => 
              array (
                'name' => 'legal_terms_accepted_date_c',
                'label' => 'LBL_LEGAL_TERMS_ACCEPTED_DATE',
              ),
              7 => 
              array (
                'name' => 'lead_type_id_c',
                'label' => 'LBL_LEAD_TYPE_ID',
              ),
              8 => 
              array (
                'name' => 'lead_type_assignment_date_c',
                'label' => 'LBL_LEAD_TYPE_ASSIGNMENT_DATE',
              ),
              9 => 
              array (
                'name' => 'date_modified',
                'comment' => 'Date record last modified',
                'studio' => 
                array (
                  'portaleditview' => false,
                ),
                'readonly' => true,
                'label' => 'LBL_DATE_MODIFIED',
              ),
              10 => 
              array (
                'name' => 'dotb_is_reset_c',
                'label' => 'LBL_DOTB_IS_RESET',
              ),
              11 => 
              array (
                'name' => 'bank_contact_user_id_c',
                'studio' => 'visible',
                'label' => 'LBL_BANK_CONTACT_USER_ID',
              ),
              12 => 
              array (
                'name' => 'cc_id',
                'label' => 'LBL_CC_ID',
              ),
              13 => 
              array (
              ),
            ),
          ),
        ),
        'templateMeta' => 
        array (
          'useTabs' => true,
        ),
      ),
    ),
  ),
);
