<?php
// created: 2017-07-10 14:06:40
$viewdefs['Documents']['EditView'] = array (
  'templateMeta' => 
  array (
    'form' => 
    array (
      'enctype' => 'multipart/form-data',
      'hidden' => 
      array (
        0 => '<input type="hidden" name="old_id" value="{$fields.document_revision_id.value}">',
        1 => '<input type="hidden" name="contract_id" value="{$smarty.request.contract_id}">',
      ),
    ),
    'maxColumns' => '2',
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
    'javascript' => '{sugar_getscript file="include/javascript/popup_parent_helper.js"}
{sugar_getscript file="modules/Documents/documents.js"}',
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
            'onchangeSetFileNameTo' => 'document_name',
          ),
        ),
      ),
      1 => 
      array (
        0 => 
        array (
          'name' => 'active_date',
        ),
      ),
      2 => 
      array (
        0 => 
        array (
          'name' => 'description',
        ),
        1 => 
        array (
          'name' => 'accounts_documents_1_name',
        ),
      ),
    ),
  ),
);