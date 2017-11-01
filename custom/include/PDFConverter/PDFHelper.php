<?php

require_once 'include/SugarQuery/SugarQuery.php';
require_once 'custom/include/PDFConverter/PDFConverter.php';

class PDFHelper {

    public $fileName;

    public function __contruct() {
        $this->fileName = '';
    }

    public $doc_extensions = array(
        'pdf' => 'application/pdf',
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'html' => 'text/html',
        'txt' => 'text/plain',
        'tiff' => 'image/tiff',
        'gif' => 'image/gif',
        'rtf' => 'application/rtf',
        'rtf_' => 'text/rtf',
        'vcf' => 'text/x-vcard',
        'bmp' => 'image/x-ms-bmp',
        'xls_' => 'application/vnd.ms-office',
        'odt' => 'application/vnd.oasis.opendocument.text',
        'dxf' => 'image/vnd.dxf',
        'bmp' => 'image/bmp',
        'btif' => 'image/prs.btif',
        'sub' => 'image/vnd.dvb.subtitle',
        'djvu' => 'image/vnd.djvu',
        'fpx' => 'image/vnd.fpx',
        'ico' => 'image/x-icon',
        'mdi' => 'image/vnd.ms-modi',
        'pbm' => 'image/x-portable-bitmap',
        'pcx' => 'image/x-pcx',
        'pgm' => 'image/x-portable-graymap',
        'ppm' => 'image/x-portable-pixmap',
        'psd' => 'image/vnd.adobe.photoshop',
        'ras' => 'image/x-cmu-raster',
        'wmf' => 'application/x-msmetafile',
        'xbm' => 'image/x-xbitmap',
        'xpm' => 'image/x-xpixmap',
        'xwd' => 'image/x-xwindowdump',
        'csv' => 'text/csv',
        'csv' => 'text/spreadsheet',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
        'odc' => 'application/vnd.oasis.opendocument.chart',
        'odf' => 'application/vnd.oasis.opendocument.formula',
        'odg' => 'application/vnd.oasis.opendocument.graphics',
        'odi' => 'application/vnd.oasis.opendocument.image',
        'odm' => 'application/vnd.oasis.opendocument.text-master',
        'odp' => 'application/vnd.oasis.opendocument.presentation',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        'odt' => 'application/vnd.oasis.opendocument.text',
        'otg' => 'application/vnd.oasis.opendocument.graphics-template',
        'oth' => 'application/vnd.oasis.opendocument.text-web',
        'otp' => 'application/vnd.oasis.opendocument.presentation-template',
        'ots' => 'application/vnd.oasis.opendocument.spreadsheet-template',
        'sgl' => 'application/vnd.stardivision.writer-global',
        'smf' => 'application/vnd.stardivision.math',
        'stc' => 'application/vnd.sun.xml.calc.template',
        'sti' => 'application/vnd.sun.xml.impress.template',
        'stw' => 'application/vnd.sun.xml.writer.template',
        'sxc' => 'application/vnd.sun.xml.calc',
        'sxg' => 'application/vnd.sun.xml.writer.global',
        'sxi' => 'application/vnd.sun.xml.impress',
        'sxm' => 'application/vnd.sun.xml.math',
        'sxw' => 'application/vnd.sun.xml.writer',
        'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
        'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'wpd' => 'application/vnd.wordperfect',
        'wps' => 'application/vnd.ms-works',
        'wri' => 'application/x-mswrite',
        'xps' => 'application/vnd.ms-xpsdocument',
    );

    public function convertDocumentsToPDF() {
        $documentsToBeConverted = $this->getSugarQueryData();
        return $this->processDocuments($documentsToBeConverted);
    }

    public function processDocuments($documentsToBeConverted) {
        global $db;
        $converterObj = new PDFConverter();
        foreach ($documentsToBeConverted as $doc) {
            if (!empty($doc['document_revision_id']) && file_exists($converterObj->upload_dir . $doc['document_revision_id'])) {
                $GLOBALS['log']->debug("Doc id: " . $doc['id']);
                $endPoint = $converterObj->getApiEndPoint($doc['file_ext']);
                
                /*
                 * Checking if file type is image and its dimensions are smaller than 500x500 then
                 * Do not create thumbnail but display it as it is.
                 */
                if ($endPoint == 'https://do.convertapi.com/Image2Pdf') {
                    $upload_file_path = 'upload/' . $doc['document_revision_id'];
                    $size = getimagesize($upload_file_path);
                    if ($size[0] < 500 || $size[1] < 500) {
                        $converterObj->createFolder($converterObj->pdfToImageDir);
                        $converterObj->createFolder($converterObj->pdfToImageDir . $doc['document_revision_id']);
                        $thumbnail_path = $converterObj->pdfToImageDir . $doc['document_revision_id'] . '/' . $doc['document_revision_id'] . '.' . $doc['file_ext'];
                        copy($upload_file_path, $thumbnail_path);
                    }
                }
                
                /*
                  retrieve the document revision bean related to the document
                  $doc_revision_bean = BeanFactory::getBean("DocumentRevisions", $doc['document_revision_id']);
                  $doc_revision_bean = new DocumentRevision();
                  $doc_revision_bean->retrieve($doc['document_revision_id']);
                 */
                
                $fileExt = strtolower($doc['file_ext']);
                $fileMimeType = $doc['file_mime_type'];
                $filename = $this->clean($doc['filename']);
                $revFileName = $this->clean($doc['rev_file_name']);

                $GLOBALS['log']->debug('Before Priority function :: ' . $fileExt . ' :: ' . $fileMimeType . ' :: ' . $filename . ' :: ' . $revFileName);

                // Getting File Extension according to Priority Wise
                $currentFileExt = $this->getExtensionByPriority($fileExt, $fileMimeType, $filename, $revFileName, $doc['revision_id']);
                $GLOBALS['log']->debug('CurrentFileExt :: ' . $currentFileExt);
                
                if (empty($filename)) {
                    $this->fileName = create_guid() . '.' . $currentFileExt;
                } else {
                    $this->fileName = str_replace('.'.$currentFileExt,"",$filename).'.'.$currentFileExt;
                }

                if ($currentFileExt != 'pdf' && !empty($currentFileExt)) {
                    // for converting attachments to PDF using convertApi
                    $endPoint = $converterObj->getApiEndPoint($currentFileExt);

                    $GLOBALS['log']->debug("Endpoint: " . $endPoint);
                    if ($endPoint) {
                        $original_file = $converterObj->tobeProcessedDir . $doc['document_revision_id'] . '/' . $this->fileName;
                        $pathCreated = $converterObj->tobeProcessedDir . $doc['document_revision_id'] . '/';

                        $converterObj->createFolder($pathCreated);

                        // First reading those files from upload/ and save it to some Other Folder i.e Temporary folder
                        if (!file_exists($original_file)) {
                            $current = file_get_contents($converterObj->upload_dir . $doc['document_revision_id']);
                            file_put_contents($original_file, $current);
                        }

                        $converterObj->resizeImage($converterObj->tobeProcessedDir . $doc['document_revision_id'] . '/' . $this->fileName, $converterObj->tobeProcessedDir . $doc['document_revision_id'] . '/' . $this->fileName, 1024, 1024);

                        $fileName = $this->fileName;
                        $original_file = $converterObj->tobeProcessedDir . $doc['document_revision_id'] . '/' . $fileName;

                        $converterObj->createFolder($converterObj->convertedPdfDir . $doc['document_revision_id'] . '/');
                        $baseName = basename($fileName, '.' . $currentFileExt) . '.pdf';
                        $GLOBALS['log']->debug('Base Name :: ' . $baseName . ' :: ' . $converterObj->convertedPdfDir . $doc['document_revision_id'] . '/' . $baseName);
                        $GLOBALS['log']->debug('Original File :: ' . $original_file);

                        if (!file_exists($converterObj->convertedPdfDir . $doc['document_revision_id'] . '/' . $baseName)) {
                            // $original_file = $base_path . $original_file; 
                            $result = $converterObj->callToConvertApi($original_file, $converterObj->convertedPdfDir . $doc['document_revision_id'] . '/', $endPoint, basename($doc['document_revision_id'], '.' . $currentFileExt));
                            $GLOBALS['log']->debug("Convert API result: " . $result['status']);
                            $GLOBALS['log']->debug('Result of Api :: ' . print_r($result, 1));
                            if ($result['status']) {
                                $source_path = $converterObj->convertedPdfDir . $doc['document_revision_id'] . '/' . $doc['document_revision_id'] . '.pdf';
                                $dest_path = $converterObj->convertedPdfDir . $doc['document_revision_id'] . '/' . $doc['document_revision_id'];

                                if (file_exists($source_path) && copy($source_path, $dest_path)) {
                                    $converterObj->createFolder($GLOBALS['sugar_config']['upload_original']);
                                    //move original to temp
                                    $original_file_path = $GLOBALS['sugar_config']['upload_dir'] . $doc['document_revision_id'];
                                    if (rename($original_file_path, $GLOBALS['sugar_config']['upload_original'] . $doc['document_revision_id'])) {
                                        //move converted to upload
                                        if (rename($dest_path, $GLOBALS['sugar_config']['upload_dir'] . $doc['document_revision_id'])) {
                                            //update converted bit in documents record 
                                            $revision_file_name = $this->fileName;
                                            
                                            $GLOBALS['log']->debug('revision file Name :: '.$revision_file_name);
                                            // Sugar storing mime type pf jpg and jpeg to image/jpeg
                                            if($currentFileExt == 'jpg' || $currentFileExt == 'jpeg'){
                                                $revision_file_name = str_replace("." . 'jpg', '.pdf', $revision_file_name);
                                                $revision_file_name = str_replace("." . 'jpeg', '.pdf', $revision_file_name);
                                            }else{
                                                    $revision_file_name = str_replace("." . $currentFileExt, '.pdf', $revision_file_name);
                                            }
                                            $GLOBALS['log']->debug('Update revision file Name :: '.$revision_file_name);
                                            $update_doc_record = "UPDATE documents SET converted='1',rev_file_name='" . $revision_file_name . "' WHERE id='" . $doc['id'] . "'";
                                            $GLOBALS['log']->debug('Update Query :: '.$update_doc_record);
                                            $db->query($update_doc_record);

                                            //update document revision record
                                            $update_doc_revision_record = "UPDATE document_revisions SET file_ext='pdf', file_mime_type='application/pdf',filename='" . $revision_file_name . "' WHERE id='" . $doc['document_revision_id'] . "'";
                                            $db->query($update_doc_revision_record);

                                            $GLOBALS['log']->debug("Updated document bit and doc revision extension!");

                                            $this->createDocumentLogRecord($doc['id'], $revision_file_name, 'converted', $result['message']);
                                        }
                                    }
                                }
                            } else {
                                $update_doc_record = "UPDATE documents SET converted='10' WHERE id='" . $doc['id'] . "'";
                                $db->query($update_doc_record);

                                $this->createDocumentLogRecord($doc['id'], $this->fileName, 'error', $result['message']);
                            }
                        }
                    } else {
                        $update_doc_record = "UPDATE documents SET converted='3' WHERE id='" . $doc['id'] . "'";
                        $db->query($update_doc_record);
                        $this->createDocumentLogRecord($doc['id'], $this->fileName, 'error', 'File extension not supported by API');
                    }
                } else if ($currentFileExt == 'pdf') {
                    // Assigning Custom Name to Document if the name is empty
                    if(empty($doc['rev_file_name'])){
                        $doc_name = $this->clean($this->fileName);
                    }else{
                        $doc_name = $this->clean($doc['rev_file_name']);
                    }
                    // For making updated file to be downloadable
                    if(empty($doc['rev_file_name']) || empty($doc['filename']) ){
                        $sql_update_doc_revision = 'UPDATE document_revisions SET filename ="'.$doc_name.'", file_ext = "pdf", file_mime_type = "application/pdf"  WHERE id = "'.$doc['revision_id'].'" AND deleted = 0 ';
                    }else{
                        $sql_update_doc_revision = 'UPDATE document_revisions SET file_ext = "pdf", file_mime_type = "application/pdf"  WHERE id = "'.$doc['revision_id'].'" AND deleted = 0 ';
                    }
                    
                    $GLOBALS['log']->debug('SL Query :: '.$sql_update_doc_revision);
                    $db->query($sql_update_doc_revision);
                    
                    $doc_record_update = "UPDATE documents SET converted = 2, rev_file_name = '".$doc_name."'  WHERE converted!='5' AND converted!='6' AND  id='" . $doc['id'] . "'";
                    $GLOBALS['log']->debug('Update COntents :: ' . $doc_record_update);
                    $db->query($doc_record_update);

                    $this->createDocumentLogRecord($doc['id'], $doc_name, 'pdf');
                    
                } else if (empty($currentFileExt)) {
                    $update_doc_record = "UPDATE documents SET converted='12' WHERE id='" . $doc['id'] . "'";
                    $db->query($update_doc_record);
                    $message = 'File ext is empty and mime type is not pdf. Mime type: ' . $doc['file_mime_type'];
                    $this->createDocumentLogRecord($doc['id'], $doc['rev_file_name'], 'error', $message);
                }

                //unlink to be processed folder
                $this->recursiveRemoveDirectoryPDFWithFiles($converterObj->tobeProcessedDir . $doc['document_revision_id']);
                $this->recursiveRemoveDirectoryPDFWithFiles($converterObj->convertedPdfDir . $doc['document_revision_id']);
            } else {
                $update_doc_record = "UPDATE documents SET converted='11' WHERE id='" . $doc['id'] . "'";
                $db->query($update_doc_record);
                $this->createDocumentLogRecord($doc['id'], $doc['rev_file_name'], 'error', 'Document revision do not exist');
            }
        } //end of foreach loop

        return true;
    }

    public function getExtensionByPriority($fileExt, $fileMimeType, $filename, $revFileName, $revision_id) {
        if (empty($fileExt)) {
            if (!empty($fileMimeType) && in_array($fileMimeType, $this->doc_extensions)) {
                $key = array_search($fileMimeType, $this->doc_extensions);
                $key = str_replace('_', '', $key);
                if (!empty($key)) {
                    $GLOBALS['log']->debug('Into IF 1 :: ' . $key);
                    return $key;
                }
            }
            if (!empty($filename)) {
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if (!empty($ext)) {
                    $GLOBALS['log']->debug('Into IF 2 :: ' . $ext);
                    return $ext;
                }
            }
            if (!empty($revFileName)) {
                $ext = pathinfo($revFileName, PATHINFO_EXTENSION);
                if (!empty($ext)) {
                    $GLOBALS['log']->debug('Into IF 3 :: ' . $ext);
                    return $ext;
                }
            }
            if (!empty($revision_id)) {
                $uploadFilePath = rtrim($GLOBALS['sugar_config']['upload_dir'], '/') . '/' . $revision_id;
                $ext = trim(mime_content_type($uploadFilePath));
                $GLOBALS['log']->debug('FileMime Type ::  ' . $ext.'::TYpe');
                if (in_array($ext, $this->doc_extensions)) {
                    $GLOBALS['log']->debug('Into If 4 Before :: ');
                    $key = array_search($ext, $this->doc_extensions);
                    $key = str_replace('_', '', $key);
                    $GLOBALS['log']->debug('Into IF 4 :: ' . $key);
                    if(!empty($key)){
                        return $key;
                    }
                }
            }
            return "";
        } else {
            return $fileExt;
        }
    }

    public function createDocumentLogRecord($doc_id, $doc_name, $document_status, $description = '') {
        $GLOBALS['log']->debug('Into the Function of createDocumentLogRecord :: ');
        $documentLogBean = BeanFactory::newBean('dot11_document_log');
        $documentLogBean->name = $doc_name;
        $documentLogBean->job_status = $document_status;
        $documentLogBean->dot11_document_log_documentsdocuments_ida = $doc_id;
        $documentLogBean->description = $this->clean($description);

        $documentLogBean->save();
    }

    public function getSugarQueryData() {
        global $db;
        //Getting all docuemnts with converted bit 0
        $limit = $GLOBALS['sugar_config']['convert_limit'];
        if (empty($limit)) {
            $limit = 100;
        }
        /* $query = new SugarQuery();
          $query->select(array('documents.id','documents.document_revision_id','documents.rev_file_name','document_revisions.file_ext','document_revisions.file_mine_type','document_revisions.filename'));
          $query->from(BeanFactory::getBean('Documents'), array('team_security' => false));
          $query->joinTable('document_revisions', array('joinType' => 'LEFT', 'linkingTable' => true))->on()->equalsField('document_revisions.document_id', 'documents.id');
          $query->where()->equals('documents.converted', 0);
          // $query->where()->equals('documents.deleted', 0);
          // $query->where()->notEquals('document_revisions.file_ext', '');
          $query->orderBy('documents.date_entered', 'DESC');
          $query->limit($limit);
          $GLOBALS['log']->debug($query->compileSql());
          $documentsToBeConverted = $query->execute(); */

        $document_sql = 'SELECT documents.id,documents.document_revision_id,documents.rev_file_name,'
                . ' document_revisions.file_ext,document_revisions.file_mime_type,document_revisions.filename, '
                . ' document_revisions.id AS revision_id  '
                . ' FROM documents '
                . ' LEFT JOIN document_revisions '
                . ' ON (document_revisions.id = documents.document_revision_id) '
                . ' WHERE documents.deleted = 0 '
                . ' AND ( documents.converted = 0 OR documents.converted IS NULL) AND document_revisions.deleted = 0'
                . ' ORDER BY documents.date_entered DESC ';

        $document_sql .= 'LIMIT 0,' . $limit;
        $documentsToBeConverted = $db->query($document_sql);
        return $documentsToBeConverted;
    }

    public function getRelatedDocumentsData($lead_id, $module_name) {
        if($module_name == 'Leads') {
            $sql_documents = 'SELECT documents.id, documents.document_revision_id, documents.rev_file_name, document_revisions.file_ext ,document_revisions.file_mime_type,document_revisions.filename, document_revisions.id AS revision_id FROM document_revisions'
                    . ' INNER JOIN documents ON document_revisions.document_id = documents.id AND documents.deleted = 0 AND documents.converted = 0 '
                    . ' INNER JOIN leads_documents_1_c ON documents.id = leads_documents_1_c.leads_documents_1documents_idb  AND leads_documents_1_c.leads_documents_1leads_ida = "' . $lead_id . '" AND leads_documents_1_c.deleted = 0 '
                    . ' WHERE document_revisions.deleted = 0';
        } else { 
            $sql_documents = 'SELECT documents.id, documents.document_revision_id, documents.rev_file_name, document_revisions.file_ext ,document_revisions.file_mime_type,document_revisions.filename, document_revisions.id AS revision_id FROM document_revisions'
                    . ' INNER JOIN documents ON document_revisions.document_id = documents.id AND documents.deleted = 0 AND documents.converted = 0 '
                    . ' INNER JOIN documents_contacts ON documents.id = documents_contacts.document_id  AND documents_contacts.contact_id = "' . $lead_id . '" AND documents_contacts.deleted = 0 '
                    . ' WHERE document_revisions.deleted = 0';
        }
        
        $results = $GLOBALS['db']->query($sql_documents);
        $GLOBALS['log']->debug('getRelatedDocuments Query :: ' . $sql_documents);
        $returnData = array();
        while ($row = $GLOBALS['db']->fetchByAssoc($results)) {
            $returnData[] = $row;
        }

        return $returnData;
    }

    public function convertNoteToPDF($recordId, $recordFileName, $recordFileType, $parentID,$uninkOriginal=false) {
        $converterObj = new PDFConverter();

        $original_file = $converterObj->tobeProcessedDir . $parentID . '/' . $recordFileName;
        if (!file_exists($original_file)) {
            $current = file_get_contents($converterObj->upload_dir . $recordId);
            file_put_contents($original_file, $current);
        }

        $converterObj->resizeImage($converterObj->tobeProcessedDir . $parentID . '/' . $recordFileName, $converterObj->tobeProcessedDir . $parentID . '/' . $recordFileName, 2048, 2048);

        // for converting attachments to PDF using convertApi
        $endPoint = $converterObj->getApiEndPoint($recordFileType);
        $original_file = $converterObj->tobeProcessedDir . $parentID . '/' . $recordFileName;

        if ($endPoint) {
            $converterObj->createFolder($converterObj->convertedPdfDir . $parentID . '/');
            $baseName = basename($recordFileName, '.' . $recordFileType) . '.pdf';

            if (!file_exists($converterObj->convertedPdfDir . $parentID . '/' . $baseName)) {
                // $original_file = $base_path . $original_file;
                // $result = $converterObj->callToConvertApi($original_file, $base_path.$converterObj->convertedPdfDir.$parentID.'/', $endPoint, basename($recordId, '.'.$recordFileType));
                $result = $converterObj->callToConvertApi($original_file, $converterObj->convertedPdfDir . $parentID . '/', $endPoint, basename($recordId, '.' . $recordFileType));
                $GLOBALS['log']->debug("Conversion status: " . $result['status']);

                if ($result['status']) {
                    $source_path = $converterObj->convertedPdfDir . $parentID . '/' . $recordId . '.pdf';
                    $dest_path = $converterObj->convertedPdfDir . $parentID . '/' . $recordId;

                    if (copy($source_path, $dest_path)) {
                        $GLOBALS['log']->debug("Converted Successfully");
                        if($uninkOriginal){
                            /*
                             * We need to unlink the pervious temporary file due to the following issue
                             * Customer sends in email with attachements. 2 attachements are named identically. 
                             * users archives e-mail. Sugar links attachements to documents. number of documents 
                             * equals number of attachements in email. attachement with the same name is there but 
                             * sugar is taking the same document twice. 
                             */
                            unlink($original_file);
                        }
                        return $result;
                    }
                } else if ($result['status'] == false) {
                    $GLOBALS['log']->debug("Conversion Failed");
                    $GLOBALS['log']->debug($result);
                    return $result;
                }
            }
        }
    }

    public static function recursiveRemoveDirectory($target) {
        if (is_dir($target)) {
            $files = glob($target . '/*');
            foreach ($files as $file) {
                PDFHelper::recursiveRemoveDirectory($file);
            }
            //rmdir( $target );
        } elseif (is_file($target)) {
            unlink($target);
        }
    }

    public function recursiveRemoveDirectoryPDFWithFiles($target) {
        if (is_dir($target)) {
            $files = glob($target . '/*');
            foreach ($files as $file) {
                $this->recursiveRemoveDirectoryPDFWithFiles($file);
            }
            rmdir($target);
        } elseif (is_file($target)) {
            unlink($target);
        }
    }
    
    function clean($string) {
        return preg_replace("/[']/", '', $string);; // Removes special chars.
    }


}
