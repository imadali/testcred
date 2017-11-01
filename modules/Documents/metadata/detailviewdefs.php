<?php
// created: 2017-07-10 11:50:42
$viewdefs['Documents']['DetailView'] = array (
  'templateMeta' => 
  array (
    'maxColumns' => '2',
    'form' => 
    array (
      'hidden' => 
      array (
        0 => '<input type="hidden" name="old_id" value="{$fields.document_revision_id.value}">',
      ),
    ),
    'widths' => 
    array (
      0 => 
      array (
        'label' => '10',
        'field' => '30',
      ),
      1 => 
      array (
        'label' => '10',
        'field' => '30',
      ),
    ),
    'useTabs' => false,
    'tabDefs' => 
    array (
      'LBL_DOCUMENT_INFORMATION' => 
      array (
        'newTab' => false,
        'panelDefault' => 'expanded',
      ),
    ),
    'syncDetailEditViews' => true,
  ),
  'panels' => 
  array (
    'lbl_document_information' => 
    array (
      0 => 
      array (
        0 => 
        array (
          'name' => 'filename',
          'displayParams' => 
          array (
            'link' => 'filename',
            'id' => 'document_revision_id',
          ),
        ),
      ),
      1 => 
      array (
        0 => 'active_date',
      ),
      2 => 
      array (
        0 => 
        array (
          'name' => 'description',
          'label' => 'LBL_DOC_DESCRIPTION',
        ),
        1 => 
        array (
          'name' => 'accounts_documents_1_name',
        ),
      ),
      3 => 
      array (
        0 => 
        array (
          'name' => 'assigned_user_name',
          'label' => 'LBL_ASSIGNED_TO_NAME',
        ),
        1 => 
        array (
          'name' => 'team_name',
          'label' => 'LBL_TEAM',
        ),
      ),
    ),
  ),
);