<?php
// created: 2016-10-05 14:40:45
$viewdefs['dot10_addresses']['base']['view']['record'] = array (
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
          'type' => 'shareaction',
          'name' => 'share',
          'label' => 'LBL_RECORD_SHARE_BUTTON',
          'acl_action' => 'view',
        ),
        2 => 
        array (
          'type' => 'divider',
        ),
        3 => 
        array (
          'type' => 'rowaction',
          'event' => 'button:find_duplicates_button:click',
          'name' => 'find_duplicates_button',
          'label' => 'LBL_DUP_MERGE',
          'acl_action' => 'edit',
        ),
        4 => 
        array (
          'type' => 'rowaction',
          'event' => 'button:duplicate_button:click',
          'name' => 'duplicate_button',
          'label' => 'LBL_DUPLICATE_BUTTON_LABEL',
          'acl_module' => 'dot10_addresses',
          'acl_action' => 'create',
        ),
        5 => 
        array (
          'type' => 'rowaction',
          'event' => 'button:audit_button:click',
          'name' => 'audit_button',
          'label' => 'LNK_VIEW_CHANGE_LOG',
          'acl_action' => 'view',
        ),
        6 => 
        array (
          'type' => 'divider',
        ),
        7 => 
        array (
          'type' => 'rowaction',
          'event' => 'button:delete_button:click',
          'name' => 'delete_button',
          'label' => 'LBL_DELETE_BUTTON_LABEL',
          'acl_action' => 'delete',
        ),
        8 => 
        array (
          'type' => 'vcard',
          'name' => 'vcard_button',
          'label' => 'LBL_VCARD_DOWNLOAD',
          'acl_action' => 'edit',
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
      'label' => 'LBL_RECORD_HEADER',
      'header' => true,
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'picture',
          'type' => 'avatar',
          'width' => 42,
          'height' => 42,
          'dismiss_label' => true,
        ),
        1 => 
        array (
          'name' => 'full_name',
          'label' => 'LBL_NAME',
          'dismiss_label' => true,
          'type' => 'fullname',
          'fields' => 
          array (
            1 => 'first_name',
            2 => 'last_name',
          ),
        ),
        2 => 
        array (
          'name' => 'favorite',
          'label' => 'LBL_FAVORITE',
          'type' => 'favorite',
          'readonly' => true,
          'dismiss_label' => true,
        ),
        3 => 
        array (
          'name' => 'follow',
          'label' => 'LBL_FOLLOW',
          'type' => 'follow',
          'readonly' => true,
          'dismiss_label' => true,
        ),
      ),
    ),
    1 => 
    array (
      'name' => 'panel_body',
      'label' => 'LBL_RECORD_BODY',
      'columns' => 2,
      'labelsOnTop' => true,
      'placeholders' => true,
      'newTab' => false,
      'panelDefault' => 'expanded',
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'contacts_dot10_addresses_1_name',
        ),
        1 => 
        array (
          'name' => 'leads_dot10_addresses_1_name',
        ),
        2 => 
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
          ),
        ),
        3 => 
        array (
        ),
        4 => 
        array (
          'name' => 'dotb_resident_since_c',
          'label' => 'LBL_DOTB_RESIDENT_SINCE',
          'span' => 6,
        ),
        5 => 
        array (
          'name' => 'dotb_resident_till_c',
          'label' => 'LBL_DOTB_RESIDENT_TILL',
        ),
      ),
    ),
    2 => 
    array (
      'columns' => 2,
      'name' => 'panel_hidden',
      'label' => 'LBL_SHOW_MORE',
      'hide' => true,
      'labelsOnTop' => true,
      'placeholders' => true,
      'newTab' => false,
      'panelDefault' => 'expanded',
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'date_modified_by',
          'readonly' => true,
          'inline' => true,
          'type' => 'fieldset',
          'label' => 'LBL_DATE_MODIFIED',
          'fields' => 
          array (
            0 => 
            array (
              'name' => 'date_modified',
            ),
            1 => 
            array (
              'type' => 'label',
              'default_value' => 'LBL_BY',
            ),
            2 => 
            array (
              'name' => 'modified_by_name',
            ),
          ),
        ),
        1 => 
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
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'useTabs' => false,
  ),
);