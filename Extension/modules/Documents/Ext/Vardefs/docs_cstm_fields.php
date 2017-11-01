<?php
/**
 *  Fields to keep and show a link of uploaded file
 */

$dictionary["Document"]["fields"]["rev_file_name"] = array (
      'name' => 'rev_file_name',
      'vname' => 'LBL_REV_FILE_NAME',
      'type' => 'varchar',
      'len' => '255',
    );


$dictionary["Document"]["fields"]["rev_file_link"] = array (
      'name' => 'rev_file_link',
      'vname' => 'LBL_REV_FILE_LINK',
      'type' => 'varchar',
      'len' => '255',
    );

$dictionary["Document"]["fields"]["filename"] =
    array (
      'name' => 'filename',
      'vname' => 'LBL_FILENAME',
      'type' => 'file',
      'source' => 'non-db',
      'comment' => 'The filename of the document attachment',
      'required' => false,
      'noChange' => true,
      'allowEapm' => true,
      'fileId' => 'document_revision_id',
      'docType' => 'doc_type',
      'docUrl' => 'doc_url',
      'docId' => 'doc_id',
      'sort_on' => 
      array (
        0 => 'document_name',
      ),
    );

 $dictionary["Document"]["fields"]['category_id'] = 
    array (
      'name' => 'category_id',
      'vname' => 'LBL_SF_CATEGORY',
      'type' => 'enum',
      'len' => 100,
      'options' => 'dotb_document_category_list',
      'reportable' => true,
      'required' => false,
    );
