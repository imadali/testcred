<?php

// created: 2016-02-19 18:24:05
$dictionary["Lead"]["fields"]["filename"] = array(
    'name' => 'filename',
    'vname' => 'LBL_FILENAME',
    'type' => 'file',
    'dbType' => 'varchar',
    'len' => '255',
    'reportable' => true,
    'comment' => 'File name associated with the note (attachment)',
    'importable' => false,
);
$dictionary["Lead"]["fields"]["file_mime_type"] = array(
    'name' => 'file_mime_type',
    'vname' => 'LBL_FILE_MIME_TYPE',
    'type' => 'varchar',
    'len' => '100',
    'comment' => 'Attachment MIME type',
    'importable' => false,
);

$dictionary["Lead"]["fields"]["file_url"] = array(
    'name' => 'file_url',
    'vname' => 'LBL_FILE_URL',
    'type' => 'varchar',
    'source' => 'non-db',
    'reportable' => false,
    'comment' => 'Path to file (can be URL)',
    'importable' => false,
);

