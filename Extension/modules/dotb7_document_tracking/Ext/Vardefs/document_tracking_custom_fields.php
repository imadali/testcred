<?php


$dictionary['dotb7_document_tracking']['fields']['documents_checked'] = array(
            'name' => 'documents_checked',
            'label' => 'LBL_DOCUMENT_CHECKED',
            'vname' => 'LBL_DOCUMENT_CHECKED', //CRED-999 : Missing Label in Filter
            'type' => 'bool',
            'module' => 'dotb7_document_tracking',
            'default_value' => false, 
            'audited' => false, 
            'mass_update' => false, 
            'duplicate_merge' => false, 
            'reportable' => true, 
            'importable' => 'true', 
        );

$dictionary['dotb7_document_tracking']['fields']['documents_recieved'] = array(
            'name' => 'documents_recieved',
            'label' => 'LBL_DOCUMENT_RECIEVED',
            'vname' => 'LBL_DOCUMENT_RECIEVED', //CRED-999 : Missing Label in Filter
            'type' => 'bool',
            'module' => 'dotb7_document_tracking',
            'default_value' => false, 
            'audited' => false, 
            'mass_update' => false, 
            'duplicate_merge' => false, 
            'reportable' => true, 
            'importable' => 'true', 
        );

$dictionary['dotb7_document_tracking']['fields']['status'] = array(
            'name' => 'status',
            'label' => 'LBL_STATUS',
            'vname' => 'LBL_STATUS', //CRED-999 : Missing Label in Filter
            'type' => 'enum',
            'module' => 'dotb7_document_tracking',
            'options' => 'status_list', 
            'default_value' => 'fehlt', 
            'mass_update' => false, 
            'required' => false, 
            'reportable' => true,
            'audited' => false, 
            'importable' => 'true', 
            'duplicate_merge' => false, 
        );

$dictionary['dotb7_document_tracking']['fields']['category'] = array(
            'name' => 'category',
            'label' => 'LBL_CATEGORY',
            'vname' => 'LBL_CATEGORY', //CRED-999 : Missing Label in Filter
            'type' => 'enum',
            'module' => 'dotb7_document_tracking', 
            'options' => 'dotb_document_category_list', 
            'default_value' => '',
            'mass_update' => false, 
            'required' => true, 
            'reportable' => true, 
            'audited' => false, 
            'importable' => 'true', 
            'duplicate_merge' => false,
        );




?>