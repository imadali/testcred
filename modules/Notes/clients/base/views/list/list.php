<?php
$viewdefs['Notes'] = 
array (
  'base' => 
  array (
    'view' => 
    array (
      'list' => 
      array (
        'panels' => 
        array (
          0 => 
          array (
            'name' => 'panel_header',
            'label' => 'LBL_PANEL_1',
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'name',
                'label' => 'LBL_LIST_SUBJECT',
                'link' => true,
                'enabled' => true,
                'default' => true,
              ),
              1 => 
              array (
                'name' => 'description',
                'label' => 'LBL_DESCRIPTION',
                'enabled' => true,
                'sortable' => false,
                'default' => true,
              ),
              2 => 
              array (
                'name' => 'contact_name',
                'label' => 'LBL_LIST_CONTACT',
                'link' => true,
                'id' => 'CONTACT_ID',
                'module' => 'Contacts',
                'enabled' => true,
                'default' => true,
                'ACLTag' => 'CONTACT',
                'related_fields' => 
                array (
                  0 => 'contact_id',
                ),
              ),
              3 => 
              array (
                'name' => 'parent_name',
                'label' => 'LBL_LIST_RELATED_TO',
                'dynamic_module' => 'PARENT_TYPE',
                'id' => 'PARENT_ID',
                'link' => true,
                'enabled' => true,
                'default' => true,
                'sortable' => false,
                'ACLTag' => 'PARENT',
                'related_fields' => 
                array (
                  0 => 'parent_id',
                  1 => 'parent_type',
                ),
              ),
              4 => 
              array (
                'name' => 'filename',
                'label' => 'LBL_LIST_FILENAME',
                'enabled' => true,
                'default' => true,
                'type' => 'file',
                'related_fields' => 
                array (
                  0 => 'file_url',
                  1 => 'id',
                  2 => 'file_mime_type',
                ),
                'displayParams' => 
                array (
                  'module' => 'Notes',
                ),
              ),
              5 => 
              array (
                'name' => 'dotb_flag',
                'enabled' => true,
                'default' => true,
                'label' => 'LBL_DOTB_FLAG',  
              ),   
              6 => 
              array (
                'name' => 'created_by_name',
                'type' => 'relate',
                'label' => 'LBL_CREATED_BY',
                'enabled' => true,
                'default' => true,
                'related_fields' => 
                array (
                  0 => 'created_by',
                ),
              ),
              7 => 
              array (
                'name' => 'date_modified',
                'enabled' => true,
                'default' => true,
              ),
              8 => 
              array (
                'name' => 'date_entered',
                'enabled' => true,
                'default' => true,
              ),
            ),
          ),
        ),
      ),
    ),
  ),
);
