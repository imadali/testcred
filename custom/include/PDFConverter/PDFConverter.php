<?php

require_once('custom/include/SetaPDFMerger/library/SetaPDF/Autoload.php');

class PDFConverter {

    private static $apiKey;
    private static $imageExtension = '.jpg'; //image extension, when we convert the pdf into umages 
    public $convertedPdfDir; // will have the converted PDF documents
    public $tobeProcessedDir;  // having all the documents which will convert into PDF
    public $pdfToImageDir; // having all the images of PDF which will use in merging concept
    public $upload_dir;
    public $pdfNewTab;
    private $emailAttachmentSize = 14;
    //public $mergedPDF;

    private $imagePathInfo;
    private $categoryData;
    private $docNameData;
    private $newDocName;
    private $mergedDocumentName;
    // used for Merged Documents
    private $category_ids;
    private $document_ids;
    private $doc_revision_ids;
    
    private $extractPagesFromDoc;
    public $exceptionMessage;
    public $docRevID;

    public function __construct() {

        $upload = $GLOBALS['sugar_config']['upload_dir'];
        $upload = rtrim($upload, '/') . '/';
        $this->upload_dir = $upload;

        $parent_path = $GLOBALS['sugar_config']['pdf_processing'];
        $parent_path = rtrim($parent_path, '/');

        $convertedPDFPath = $GLOBALS['sugar_config']['temporary_conversion'];
        $convertedPDFPath = rtrim($convertedPDFPath, '/');
        $this->convertedPdfDir = $convertedPDFPath . '/';

        $tempPath = $GLOBALS['sugar_config']['temporary_processing'];
        $tempPath = rtrim($tempPath, '/');
        $this->tobeProcessedDir = $tempPath . '/';

        $pdfToImage = $GLOBALS['sugar_config']['thumbnails'];
        $pdfToImage = rtrim($pdfToImage, '/');
        $this->pdfToImageDir = $pdfToImage . '/';

        $pdfNewTab = $GLOBALS['sugar_config']['new_tab'];
        $pdfNewTab = rtrim($pdfNewTab, '/');
        $this->pdfNewTab = $pdfNewTab . '/';

        /* $mergePDF = $GLOBALS['sugar_config']['merged_pdf'];
          $mergePDF = rtrim($mergePDF,'/');
          $this->mergedPDF = $mergePDF.'/'; */

        $this->newDocName = 'Kundenunterlagen.pdf';
        $this->mergedDocumentName = 'Antragsunterlagen.pdf';

        $this->imagePathInfo = array();
        $this->document_ids = array();
        $this->category_ids = array();
        $this->extractPagesFromDoc = array();
        $this->exceptionMessage = '';
        $this->docRevID = '';
        
        PDFConverter::$apiKey = $GLOBALS['sugar_config']['convertPDFApiKey'];
        /* $this->image_extension = array('avs','bmp','dcx','dib','dpx','fax','fits',
          'fpx','gif','ico','iptc','jbig','jp2',
          'jpeg','jpg','mdi','miff','mng','mpc','mtv','otb','pbm',
          'pcd','pcds','pct','pcx','pgm',
          'pict','png','pnm','ppm','psd','p7','ras','rgba','sun','tga','tiff',
          'tif','vicar','vid','viff','wmf','xbm','xpm','xwd');
         */
        // Creating folder for PDF processing
        $this->createFolder($parent_path);
        $this->createFolder($convertedPDFPath);
        $this->createFolder($tempPath);
        $this->createFolder($pdfToImage);
        $this->createFolder($pdfNewTab);
    }

    public function getApiEndPoint($extension) {
        switch (strtolower($extension)) {
            // excel to PDF
            case "csv":
            case "xls":
            case "xlsb":
            case "xlsx":
            case "xlt":
            case "xltx":
                return "https://do.convertapi.com/Excel2Pdf";

            // image to pdf
            case "avs":
            case "bmp":
            case "dcx":
            case "dib":
            case "dpx":
            case "fax":
            case "fits":
            case "fpx":
            case "gif":
            case "ico":
            case "iptc":
            case "jbig":
            case "jp2":
            case "jpeg":
            case "jpg":
            case "mdi":
            case "miff":
            case "mng":
            case "mpc":
            case "mtv":
            case "otb":
            case "pbm":
            case "pcd":
            case "pcds":
            case "pct":
            case "pcx":
            case "pgm":
            case "pict":
            case "png":
            case "pnm":
            case "ppm":
            case "psd":
            case "p7":
            case "ras":
            case "rgba":
            case "sun":
            case "tga":
            case "tiff":
            case "tif":
            case "vicar":
            case "vid":
            case "viff":
            case "wmf":
            case "xbm":
            case "xpm":
            case "xwd":
                return "https://do.convertapi.com/Image2Pdf";

            //open office to PDF
            case "mml":
            case "odc":
            case "odf":
            case "odg":
            case "odi":
            case "odm":
            case "odp":
            case "ods":
            case "odt":
            case "otg":
            case "oth":
            case "otp":
            case "ots":
            case "pxl":
            case "sgl":
            case "smf":
            case "srw":
            case "stc":
            case "sti":
            case "stw":
            case "sxc":
            case "sxg":
            case "sxi":
            case "sxm":
            case "sxw":
            case "vor":
            case "wv2":
                return "https://do.convertapi.com/OpenOffice2Pdf";

            //PowerPoint to PDF
            case "pot":
            case "potx":
            case "pps":
            case "ppsx":
            case "ppt":
            case "pptx":
                return "https://do.convertapi.com/PowerPoint2Pdf";

            //Text to PDF
            case "txt":
            case "log":
                return "https://do.convertapi.com/Text2Pdf";

            //Word to PDF
            case "doc":
            case "docx":
            case "dot":
            case "dotx":
            case "wpd":
            case "wps":
            case "wri":
                return "https://do.convertapi.com/Word2Pdf";

            //XPS to PDF
            case "xps":
                return "https://do.convertapi.com/Xps2Pdf";
            default :
                break;
        }
    }

    public function callToConvertApi($fileToConvert, $pathToSaveOutputFile, $endPoint, $fileName) {
        try {
            $fileName = $fileName . '.pdf';
            $postdata = array('OutputFileName' => $fileName, 'ApiKey' => self::$apiKey, 'Timeout' => '60');
            //sending file according to php version of the server
            if ((version_compare(PHP_VERSION, '5.5') >= 0)) {
                $postdata['file'] = new CURLFile($fileToConvert);
            } else {
                $postdata['file'] = "@" . $fileToConvert;
            }

            $ch = curl_init($endPoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $result = curl_exec($ch);
            $headers = curl_getinfo($ch);

            $header = $this->ParseHeader(substr($result, 0, $headers["header_size"]));
            $body = substr($result, $headers["header_size"]);

            curl_close($ch);
            if (0 < $headers['http_code'] && $headers['http_code'] < 400) {
                // Check for Result = true
                if (in_array('Result', array_keys($header)) ? !$header['Result'] == "True" : true) {
                    return ['status' => false, 'message' => "Something went wrong with request, did not reach ConvertApi service"];
                }
                // Check content type 
                if ($headers['content_type'] <> "application/pdf") {
                    return ['status' => false, 'message' => "Exception Message : returned content is not PDF file."];
                }
                    $fp = fopen($pathToSaveOutputFile . $fileName, "wbx");
                    fwrite($fp, $body);
                return ['status' => true, 'message' => "The conversion was successful."];
            } else {
                return ['status' => false, 'message' => "Exception Message : " . $result . ".Status Code :" . $headers['http_code']];
            }
        } catch (Exception $e) {
            return ['status' => false, 'message' => "Exception Message :" . $e->getMessage()];
        }
    }

    private function ParseHeader($header = '') {
        $resArr = array();
        $headerArr = explode("\n", $header);
        foreach ($headerArr as $key => $value) {
            $tmpArr = explode(": ", $value);
            if (count($tmpArr) < 1)
                continue;
            $resArr = array_merge($resArr, array($tmpArr[0] => count($tmpArr) < 2 ? "" : $tmpArr[1]));
        }
        return $resArr;
    }

    function resizeImage($sourceImage, $targetImage, $maxWidth, $maxHeight, $quality = 80) {
        if(!empty($sourceImage)) {
            $size = getimagesize($sourceImage);
            if (isset($size['0']) && isset($size['1']) && $size['0'] > 1024 && $size['1'] > 1024) {
                if (!$image = @imagecreatefromjpeg($sourceImage)) {
                    return false;
                }
                // Get dimensions of source image.
                list($origWidth, $origHeight) = getimagesize($sourceImage);

                if ($maxWidth == 0) {
                    $maxWidth = $origWidth;
                }

                if ($maxHeight == 0) {
                    $maxHeight = $origHeight;
                }

                // Calculate ratio of desired maximum sizes and original sizes.
                $widthRatio = $maxWidth / $origWidth;
                $heightRatio = $maxHeight / $origHeight;

                // Ratio used for calculating new image dimensions.
                $ratio = min($widthRatio, $heightRatio);

                // Calculate new image dimensions.
                $newWidth = (int) $origWidth * $ratio;
                $newHeight = (int) $origHeight * $ratio;

                // Create final image with new dimensions.
                $newImage = imagecreatetruecolor($newWidth, $newHeight);
                imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
                imagejpeg($newImage, $targetImage, $quality);

                // Free up the memory.
                imagedestroy($image);
                imagedestroy($newImage);
            }
        }

        return true;
    }

    function previewPDFImages($folderId, $name, $convertedPdfFilePath, $converted, $doc_id = 1) {
        if (!file_exists($this->pdfToImageDir . $folderId)) {
            $this->createFolder($this->pdfToImageDir . $folderId);
        }
        $unsortedArray = $this->reOrdering($this->getFileNamesWithPaths($this->pdfToImageDir . $folderId), $name, $convertedPdfFilePath, $converted, $doc_id);
        
        return $unsortedArray;
    }

    public function reOrdering($fileNamesWithPaths, $pdfname, $pdfFilePath, $converted, $folderID = 1) {
        $numberedArray = [];
        $category = '';
        $pdf_number = 0;

        if (isset($this->categoryData[$folderID])) {
            $category = $this->categoryData[$folderID];
        }
        if (isset($this->docNameData[$folderID])) {
            $doc_name = $this->docNameData[$folderID];
        }
        $total_pages = sizeof($fileNamesWithPaths);
        if ($converted == 'pdf') {
            foreach ($fileNamesWithPaths as $imageName => $ImageFilePath) {
                $fileInfo = pathinfo($ImageFilePath);
                $size = explode("-", $fileInfo['filename']);
                if (sizeof($size) == 5) {
                    $pdf_number = 0;
                } else {
                    $pdf_number = end(explode("-", $fileInfo['filename']));
                }              
                list($width, $height) = getimagesize($ImageFilePath);
                $numberedArray[$pdf_number + 1] = ['pageNumber' => $pdf_number + 1, 'total_pages' => $total_pages,  'ImageURL' => $ImageFilePath.'?'.rand(), 'pdfName' => $pdfname, 'pdfFilePath' => $pdfFilePath, 'encodedPath' => base64_encode($pdfFilePath), 'encodedFileName' => base64_encode($pdfname), 'category' => $category, 'document_name' => $doc_name, 'converted' => 1, 'document_id' => $folderID, 'width' => $width, 'height' => $height];
            }
        } else {
            $numberedArray[$pdf_number + 1] = [ 'converted' => 0, 'document_name' => $doc_name];
        }
        
        return $numberedArray;
    }

    private function getFileNamesWithPaths($dir) {
        $fileNamesArray = [];
        $dir = rtrim($dir, '/') . '/';
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                $filePath = $dir . $file;
                if (is_file($filePath)) {
                    $fileNamesArray[$file] = $filePath;
                }
            }
            closedir($dh);
        }
        $GLOBALS['log']->debug('File Name Array in GetFile Name With Path function :: ' . print_r($fileNamesArray, 1));
        return $fileNamesArray;
    }

    function createFolder($path) {
        if (!file_exists($path)) {
            sugar_mkdir($path, 0755);
        }
    }

    public function convertDocToPDF($args) {
        $GLOBALS['log']->debug('Data in ConvtDoc APi :: ' . print_r($args, 1));
        $id = json_decode($args['id'], true);
        try {
            if ($args['module_name'] == 'Leads') {
                $sql_documents = 'SELECT documents.id AS doc_id, documents.converted, documents.document_name as doc_name, document_revisions.filename, document_revisions.id, document_revisions.file_ext, documents.category_id FROM documents'
                        . ' INNER JOIN document_revisions ON document_revisions.id = documents.document_revision_id AND document_revisions.deleted = 0 '
                        . ' INNER JOIN leads_documents_1_c ON documents.id = leads_documents_1_c.leads_documents_1documents_idb  AND leads_documents_1_c.leads_documents_1leads_ida = "' . $args['id'] . '" AND leads_documents_1_c.deleted = 0 '
                        . ' WHERE documents.deleted = 0 ';
            } else if ($args['module_name'] == 'Documents') {
                if (is_array($id)) {
                    $doc_ids = implode(',', array_map('add_quotes', $id));
                    $temp_sql = ' AND documents.id IN (' . $doc_ids . ')';
                } else {
                    $temp_sql = ' AND documents.id = "' . $args['id'] . '"';
                }
                $sql_documents = 'SELECT documents.id AS doc_id, documents.converted, documents.document_name as doc_name, document_revisions.filename, document_revisions.id, document_revisions.file_ext, documents.category_id FROM documents'
                        . ' INNER JOIN document_revisions ON document_revisions.id = documents.document_revision_id AND documents.deleted = 0 ' . $temp_sql
                        . ' WHERE documents.deleted = 0  ';
            } else if ($args['module_name'] == 'Contacts') {
                $sql_documents = 'SELECT documents.id AS doc_id, documents.converted,  documents.document_name as doc_name, document_revisions.filename, document_revisions.id, document_revisions.file_ext, documents.category_id FROM documents'
                        . ' INNER JOIN document_revisions ON document_revisions.id = documents.document_revision_id AND document_revisions.deleted = 0 '
                        . ' INNER JOIN documents_contacts ON documents.id = documents_contacts.document_id  AND documents_contacts.contact_id = "' . $args['id'] . '" AND documents_contacts.deleted = 0 '
                        . ' WHERE documents.deleted = 0 ';
            }

            $GLOBALS['log']->debug('Converted Documents :: ' . $sql_documents);

            $rowData = array();

            $result = $GLOBALS['db']->query($sql_documents);
            $record_id = array();

            while ($row = $GLOBALS['db']->fetchByAssoc($result)) {

                $rowData[] = $row;
                $record_id[] = $row['doc_id'];

                $this->docNameData[$row['doc_id']] = $row['doc_name'];
            } //end of while
            // For getting Categories related to Documents
            if (!empty($record_id)) {
                $rec_ids = implode(',', array_map('add_quotes', $record_id));
                $sql_doc_trac = ' SELECT tracking.category, doc_tracking.documents_dotb7_document_tracking_1documents_ida AS doc_id FROM documents_dotb7_document_tracking_1_c AS doc_tracking'
                        . ' INNER JOIN dotb7_document_tracking  AS tracking  ON doc_tracking.documents_dotb7_document_tracking_1dotb7_document_tracking_idb = tracking.id AND tracking.deleted = 0 '
                        . ' WHERE doc_tracking.deleted = 0 AND doc_tracking.documents_dotb7_document_tracking_1documents_ida IN (' . $rec_ids . ') ';

                $doc_track_results = $GLOBALS['db']->query($sql_doc_trac);

                while ($row = $GLOBALS['db']->fetchByAssoc($doc_track_results)) {
                    if (isset($this->categoryData[$row['doc_id']])) {
                        if (isset($GLOBALS['app_list_strings']['dotb_document_category_list'][$row['category']])) {
                            $this->categoryData[$row['doc_id']].= ', ' . $GLOBALS['app_list_strings']['dotb_document_category_list'][$row['category']];
                        } else {
                            $this->categoryData[$row['doc_id']].= ', ' . $row['category'];
                        }
                    } else {
                        if (isset($GLOBALS['app_list_strings']['dotb_document_category_list'][$row['category']])) {
                            $this->categoryData[$row['doc_id']] = $GLOBALS['app_list_strings']['dotb_document_category_list'][$row['category']];
                        } else {
                            $this->categoryData[$row['doc_id']] = $row['category'];
                        }
                    }
                }
            }

            $mark_converted_bit_inactive_lead = array();
            // for converting PDF to image for Preview
            foreach ($rowData as $row2) {
                $fileName = $row2['filename'];
                $final_file_path = $this->upload_dir . $row2['id'];

                if (file_exists($final_file_path) && $row2['converted'] == '6' && $row2['file_ext'] == 'pdf') {
                    $mark_converted_bit_inactive_lead[] = $row2['doc_id'];
                }
                // To Check either document has attachment or not
                if (file_exists($final_file_path)) {
                    $this->convertPDFToImages($row2['id'], $final_file_path/* , $row2['file_ext'] */, $row2['file_ext']);

                    $unique_id = create_guid();
                    
                    $this->imagePathInfo[$unique_id] = $this->previewPDFImages($row2['id'], $fileName, $this->upload_dir . $row2['id'], $row2['file_ext'], $row2['doc_id']);
                }
            }

            
            if (!empty($mark_converted_bit_inactive_lead)) {
                $doc_convert_ids = implode(',', array_map('add_quotes', $mark_converted_bit_inactive_lead));
                $sql_convert_doc_update = 'UPDATE documents SET converted = 1 WHERE id IN (' . $doc_convert_ids . ') AND deleted = 0';
                $GLOBALS['db']->query($sql_convert_doc_update);
            }

            $GLOBALS['log']->debug('Images Path  :: ' . print_r($this->imagePathInfo, 1));

            if (empty($this->imagePathInfo)) {
                return false;
            }

            return $this->imagePathInfo;
        } catch (Exception $ex) {
            return ['error_code' => $ex->getCode(), 'message' => $ex->getMessage()];
        }
    }

    public function mergeSelectedPagesIntoPDF($args) {
        //$trackingCategoryNames = array();
        $GLOBALS['log']->debug('Merged Categories Data :: ' . print_r($args, 1));
        $this->getRelatedMergedDocData($args);

        $GLOBALS['log']->debug('Document ID :: ' . print_r($this->document_ids, 1));
        $GLOBALS['log']->debug('Revision ID :: ' . print_r($this->doc_revision_ids, 1));
        $GLOBALS['log']->debug('Categories ID :: ' . print_r($this->category_ids, 1));
        // Creating Document
        $documentBean = BeanFactory::getBean('Documents', array('disable_row_level_security' => true));
        $documentBean->document_name = rtrim($this->newDocName, '.pdf');
        $documentBean->converted = 1; // merged newly created Merged PDF as converted
        $documentBean->assigned_user_id = $GLOBALS['current_user']->id;
        $documentBean->save();
        // Saving multiple categories aganist documents.
        $documentId = $documentBean->id;
        $documentRevId = '';
        if (!empty($args['pdf_info'])) {
            $bean_DocumentRevision = BeanFactory::getBean('DocumentRevisions');
            $bean_DocumentRevision->document_id = $documentId;
            $bean_DocumentRevision->doc_type = 'Sugar';
            $bean_DocumentRevision->filename = $this->newDocName;
            $bean_DocumentRevision->file_ext = 'pdf';
            $bean_DocumentRevision->file_mime_type = 'application/pdf';
            $bean_DocumentRevision->revision = '1';
            $bean_DocumentRevision->save();
            $documentRevId = $bean_DocumentRevision->id;
            /* $documentBean->document_revision_id = $bean_DocumentRevision->id;
              $documentBean->rev_file_name = $this->newDocName;
              $documentBean->converted = 1;
              $documentBean->save(); */
        }

        try {
            if (!empty($args['pdf_info'])) {
                $pdf = new SetaPDF_Merger();
                foreach ($args['pdf_info'] as $key => $pdfInfo) {
                    //$inDocument = SetaPDF_Core_Document::loadByFilename($pdfInfo['pdf_file_path']);
                    //$inDocument->setCacheReferencedObjects(true);
                    $pdf->addFile($pdfInfo['pdf_file_path'], $pdfInfo['page_number']);
                }
                try {
                    $pdf->merge();

                    $writer = new SetaPDF_Core_Writer_String();
                    $resDocument = $pdf->getDocument();
                    $resDocument->setCleanUpObjects(false);
                    $resDocument->setWriter($writer);
                    $resDocument->save()->finish();
                    $resDocument->cleanUp();
                    $mergedPdf = $this->upload_dir;
                    $mergedPdf.=$bean_DocumentRevision->id;
                    if (file_put_contents($mergedPdf, $writer)) {
                        $GLOBALS['log']->debug('Document Revision and Document ID :: ' . $bean_DocumentRevision->id . ' :: ' . $documentBean->id);
                        $leadObj = BeanFactory::getBean($args['module_name'], $args['record_id']);
                        // For removing Selected documents
                        
                        /*if (!empty($this->document_ids)) {
                            $doc_rel_deleted = implode(',', array_map('add_quotes', $this->document_ids));
                            $sql_leads_documents_1_c = 'UPDATE leads_documents_1_c SET deleted = 1 WHERE leads_documents_1documents_idb IN (' . $doc_rel_deleted . ')';
                            $GLOBALS['db']->query($sql_leads_documents_1_c);

                            $sql_get_revision = 'SELECT document_revision_id FROM documents WHERE id IN (' . $doc_rel_deleted . ') ';
                            $results = $GLOBALS['db']->query($sql_get_revision);

                            // for unlinking the PDF images during Merging document
                            $pdf_image = rtrim($GLOBALS['sugar_config']['thumbnails'], '/') . '/';
                            while ($row = $GLOBALS['db']->fetchByAssoc($results)) {
                                $filePath = $pdf_image . $row['document_revision_id'];
                                if (file_exists($filePath)) {
                                    $this->recursiveRemoveDirectoryPDFMergedThumbnails($filePath);
                                }
                            }
                        }
                        */

                        $mergedDocCategoires = array();
                        foreach ($this->category_ids as $k => $id) {
                            $docTrackItemBean = BeanFactory::getBean('dotb7_document_tracking', $id);
                            if ($docTrackItemBean->id) {
                                if (!isset($mergedDocCategoires[$docTrackItemBean->category])) {

                                    $mergedDocCategoires[$docTrackItemBean->category] = array(
                                        'status' => $docTrackItemBean->status,
                                        'notes' => $docTrackItemBean->description,
                                        'month' => $docTrackItemBean->month,
                                    );
                                } else {

                                    if ($docTrackItemBean->status == 'ok') {
                                        $mergedDocCategoires[$docTrackItemBean->category]['status'] = $docTrackItemBean->status;
                                    } else if ($docTrackItemBean->status == 'nok' && $mergedDocCategoires[$docTrackItemBean->category]['status'] != 'ok') {
                                        $mergedDocCategoires[$docTrackItemBean->category]['status'] = $docTrackItemBean->status;
                                    } else if ($docTrackItemBean->status == 'nok' && $docTrackItemBean->status == 'ok') {
                                        $mergedDocCategoires[$docTrackItemBean->category]['status'] = $docTrackItemBean->status;
                                    }

                                    $month1 = array();
                                    if (!empty($mergedDocCategoires[$docTrackItemBean->category]['month'])) {
                                        $month1 = explode(',', $mergedDocCategoires[$docTrackItemBean->category]['month']);
                                    }
                                    $month2 = array();
                                    if (!empty($docTrackItemBean->month)) {
                                        $month2 = explode(',', $docTrackItemBean->month);
                                    }

                                    $merged_months = array_unique(array_merge($month1, $month2));
                                    $mergedDocCategoires[$docTrackItemBean->category]['month'] = '';

                                    if (!empty($merged_months)) {
                                        $mergedDocCategoires[$docTrackItemBean->category]['month'] = implode(",", $merged_months);
                                    }
                                    $description = $mergedDocCategoires[$docTrackItemBean->category]['notes'] . "\r\n" . $docTrackItemBean->description;
                                    $mergedDocCategoires[$docTrackItemBean->category]['notes'] = $description;
                                }
                            }
                        }

                        $GLOBALS['log']->debug('Categories Array :: ' . print_r($mergedDocCategoires, 1));
                        $GLOBALS['log']->debug('Cateorgy ID Array :: ' . print_r($this->category_ids, 1));

                        foreach ($mergedDocCategoires as $key => $obj) {
                            $docTrackItemBean = BeanFactory::newBean('dotb7_document_tracking');
                            $docTrackItemBean->description = $obj['notes'];
                            $docTrackItemBean->month = $obj['month'];
                            $docTrackItemBean->status = $obj['status'];
                            $docTrackItemBean->category = $key;
                            $docTrackItemBean->name = $obj['status'];
                            $docTrackItemBean->save();

                            if ($docTrackItemBean->load_relationship('documents_dotb7_document_tracking_1')) {
                                $docTrackItemBean->documents_dotb7_document_tracking_1->add($documentId);
                            }
                        }

                        // For deleting the Category of that Documents
                        /*if (!empty($this->category_ids)) {
                            $rec_ids = implode(',', array_map('add_quotes', $this->category_ids));
                            $sql_del_tracking = 'UPDATE dotb7_document_tracking SET deleted = 1 WHERE id IN (' . $rec_ids . ')';
                            $GLOBALS['db']->query($sql_del_tracking);
                        }*/

                        // added new relationship of merged document and removing the old one
                        if ( $args['module_name'] == 'Leads' && $leadObj->load_relationship("leads_documents_1")) {
                            $leadObj->leads_documents_1->add($documentId);
                        } else if ( $args['module_name'] == 'Contacts' && $leadObj->load_relationship("documents")) {
                            $leadObj->documents->add($documentId);
                        } 

                        /* $docRevRecordsID = array();
                          $newFile = $GLOBALS['sugar_config']['originalUpload'];
                          // for moving the original documents to upload_original folder
                          $GLOBALS['log']->debug('Document Revision :: '.print_r($this->doc_revision_ids,1));
                          foreach($this->doc_revision_ids as $key => $obj){

                          $file1 = $this->upload_dir.$obj['revision_id'];
                          $file2 = $newFile.$obj['revision_id'];
                          $docRevRecordsID[] = $obj['revision_id'];
                          $GLOBALS['log']->debug('Unlink File Path :: '.$file1);

                          if(file_exists($file1)){
                          // If bit is converted means already files exist in upload_original folder
                          if($obj['converted']){
                          if(unlink($file1)){
                          $GLOBALS['log']->debug('PDF File Deleted with Success :: ');
                          }else{
                          $GLOBALS['log']->debug('PDF File not Deleted :: ');
                          }
                          }// need to move to upload_original folder
                          else{
                          if(rename($file1, $file2)){
                          $GLOBALS['log']->debug('PDF File Deleted with Success :: ');
                          }else{
                          $GLOBALS['log']->debug('PDF File not Deleted :: ');
                          }
                          }
                          }
                          } */

                        // For deleting the document revision records
                        /* if(!empty($docRevRecordsID)){
                          $documentRevIdDel = implode(',', array_map('add_quotes', $docRevRecordsID));
                          $sql_doc_rev = 'UPDATE document_revisions SET deleted = 1 WHERE id IN ('.$documentRevIdDel.')';
                          $GLOBALS['db']->query($sql_doc_rev);
                          } */

                        $sql_update = 'UPDATE documents SET document_revision_id = "' . $bean_DocumentRevision->id . '",'
                                . ' rev_file_name = "' . $this->newDocName . '", converted = "1" WHERE id = "' . $documentId . '" ';
                        $GLOBALS['log']->debug('Dcument Update :: ' . $sql_update);
                        $GLOBALS['db']->query($sql_update);
                        
                        $this->extractPagesFromPDFDoc();

                        //Delete old Documents
                        /*if (!empty($this->document_ids)) {
                            // marking converted =  5 to indicate that the records get deleted during merge
                            $documentIdDel = implode(',', array_map('add_quotes', $this->document_ids));
                            $sql_doc = 'UPDATE documents SET converted = 5 WHERE id IN (' . $documentIdDel . ')';
                            $GLOBALS['log']->debug('Update Query :: ' . $sql_doc);
                            $GLOBALS['db']->query($sql_doc);
                        }*/
                        
                        return ['level' => 'success', 'message' => 'Documents are merged successfully', 'pdf_path' => urlencode($mergedPdf)];
                    } else {
                        return ['level' => 'error', 'message' => 'Failed to merge the documents.'];
                    }
                } catch (Exception $pdf_e) {
                    
                    $this->removeDocumentIfExcetionOccurs($documentId, $documentRevId);
                    if($pdf_e->getMessage()){
                        return ['level' => 'error', 'message' => translate('LBL_SETASIGN_MERGE_EXCEPTION_MANUALLY', $args['module_name'])];
                    }
                    return ['level' => 'error', 'message' => 'Failed to merge the documents.'];
                }
            } else {
                $GLOBALS['log']->debug('No document/page is selected for merging');
            }
        } catch (Exception $e) {
            return ['error_code' => $e->getCode(), 'level' => 'error', 'message' => $e->getMessage()];
        }
    }
    
     private function removeDocumentIfExcetionOccurs($documentId, $documentRevId) {
        if(!empty($documentId)) {
            $sql_doc = 'UPDATE documents SET deleted = 1 WHERE id = "'.$documentId.'" ';
            $GLOBALS['db']->query($sql_doc);
        }
        if(!empty($documentRevId)) {
            $sql_doc_rev = 'UPDATE document_revisions SET deleted = 1 WHERE id = "'.$documentRevId.'" ';
            $GLOBALS['db']->query($sql_doc_rev);
        }
    }
    
    public function extractPagesFromPDFDoc() {
        $GLOBALS['log']->debug('Extract Pages From Doc :: '.print_r($this->extractPagesFromDoc,1));
        $errorDocInConversion = array();
        try{
            foreach($this->extractPagesFromDoc as $key => $val) {
                $pdf = new SetaPDF_Merger();
                $comm_sep_pages = explode(',',$val['page_number']);
                $GLOBALS['log']->debug('Array Data :: '.print_r($comm_sep_pages,1));
                $createPDF = false;
                for($i = 1 ; $i <= $val['total_pages']; $i++) {
                    if(!in_array($i, $comm_sep_pages)) {
                        $GLOBALS['log']->debug('Adding Page into PDF :: '.$i);
                        $createPDF = true;
                        $filePath = rtrim($GLOBALS['sugar_config']['upload_dir'],'/').'/'.$val['document_rev_id'];

                        $pdf->addFile($filePath, $i);
                    }
                }
                $old_name = rtrim($GLOBALS['sugar_config']['upload_dir'],'/').'/'.$val['document_rev_id'];
                if($createPDF) {
                    $pdf->merge();
                    $writer = new SetaPDF_Core_Writer_String();
                    $resDocument = $pdf->getDocument();
                    $resDocument->setCleanUpObjects(false);
                    $resDocument->setWriter($writer);
                    $resDocument->save()->finish();
                    $resDocument->cleanUp();

                    $upload_path = rtrim($GLOBALS['sugar_config']['temporary_processing'],'/').'/'.$val['document_rev_id'];
                    if (file_put_contents($upload_path, $writer)) {
                        $destination = rtrim($GLOBALS['sugar_config']['upload_dir'],'/').'/'.$val['document_rev_id'];
                        if(unlink($destination)) {
                            if(copy($upload_path, $destination)) {
                                $GLOBALS['log']->debug('File Copied with Success :: ');
                            }else{
                               throw new Exception('Failed to Extract Pages during Merge 1');
                            }
                        }
                    } else{
                        throw new Exception('Failed to Extract Pages during Merge');
                    }
                } else {
                    if(file_exists($old_name))
                    unlink($old_name);
                    $errorDocInConversion[] = $val['document_rev_id'];
                }
                
                $thumbnailsPath = rtrim($GLOBALS['sugar_config']['thumbnails'],'/').'/'.$val['document_rev_id'];
                $this->recursiveRemoveDirectoryPDFMergedThumbnails($thumbnailsPath);
            }
        } catch (Exception $ex) {
            throw new Exception('Failed to Extract Pages during Merge');
        }
        
        if(!empty($errorDocInConversion)){
            $doc_rev_id = implode(',', array_map('add_quotes', $errorDocInConversion));
            
            $updateRevisionRecord = 'UPDATE document_revisions SET deleted = 1 WHERE id IN ('.$doc_rev_id.')';
            $GLOBALS['db']->query($updateRevisionRecord);
            
            $updateDocuments = 'UPDATE documents SET document_revision_id = "", rev_file_name="", deleted = 1 WHERE document_revision_id IN ('.$doc_rev_id.')';
            $GLOBALS['db']->query($updateDocuments);
        }
        
    }

    public function recursiveRemoveDirectoryPDFMergedThumbnails($target) {
        if (is_dir($target)) {
            $files = glob($target . '/*');
            foreach ($files as $file) {
                $this->recursiveRemoveDirectoryPDFMergedThumbnails($file);
            }
            rmdir($target);
        } elseif (is_file($target)) {
            unlink($target);
        }
    }

    public function createLeadConversionPDFMerged($args, $upload_path) {
        try {
            if (!empty($args)) {
                $pdf = new SetaPDF_Merger();
                foreach ($args as $key => $pdfInfo) {
                    $pdf->addFile($pdfInfo['pdf_file_path'], $pdfInfo['page_number']);
                }
                try {
                    $pdf->merge();
                    $writer = new SetaPDF_Core_Writer_String();
                    $resDocument = $pdf->getDocument();
                    $resDocument->setCleanUpObjects(false);
                    $resDocument->setWriter($writer);
                    $resDocument->save()->finish();
                    $resDocument->cleanUp();

                    // $createID = create_guid();
                    if (file_put_contents($upload_path, $writer)) {
                        return ['level' => 'success', 'message' => 'Documents are merged successfully'/* ,'pdf_path' => urlencode($mergedPdf), 'pdf' => $mergedPDFPath.$createID */];
                    } else {
                        return ['level' => 'error', 'message' => 'Failed to merge the documents.'];
                    }
                } catch (Exception $pdf_e) {
                    return ['level' => 'error', 'message' => 'Failed to merge the documents.'];
                }
            } else {
                $GLOBALS['log']->debug('No document/page is selected for merging');
            }
        } catch (Exception $e) {
            return ['error_code' => $e->getCode(), 'level' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function getRelatedMergedDocData($args) {
        
        $this->category_ids = array();
        $this->document_ids = array();
        $this->doc_revision_ids = array();
        $this->extractPagesFromDoc = array();

        foreach ($args['pdf_info'] as $key => $obj) {
            if (!in_array($obj['document_id'], $this->document_ids)) {
                $this->document_ids[] = $obj['document_id'];
                $rev_id = $obj['pdf_file_path'];
                $rev_id = str_replace($GLOBALS['sugar_config']['upload_dir'], '', $rev_id);
                $this->extractPagesFromDoc[$obj['document_id']] = array('doc_id' => $obj['document_id'], 
                                                                        'total_pages' => $obj['total_pages'], 
                                                                        'document_rev_id' => $rev_id,    
                                                                        'page_number' => $obj['page_number']);
            } else {
                $comma_sep_page_number = $this->extractPagesFromDoc[$obj['document_id']]['page_number'].','.$obj['page_number'];
                $this->extractPagesFromDoc[$obj['document_id']]['page_number'] = $comma_sep_page_number;
            } 
        }
        
        if (!empty($this->document_ids)) {
            // For getting revision id's of related documents
            $doc_ids = implode(',', array_map('add_quotes', $this->document_ids));
            $sql_doc_rev = 'SELECT document_revision_id, converted FROM documents WHERE deleted = 0 AND id IN (' . $doc_ids . ')';
            $results = $GLOBALS['db']->query($sql_doc_rev);
            while ($row = $GLOBALS['db']->fetchByAssoc($results)) {
                $this->doc_revision_ids[] = array('revision_id' => $row['document_revision_id'], 'converted' => $row['converted']);
            }

            // For getting Doc tracking categories of related documents
            $sql_doc_trac = ' SELECT tracking.id, doc_tracking.documents_dotb7_document_tracking_1documents_ida AS doc_id FROM documents_dotb7_document_tracking_1_c AS doc_tracking'
                    . ' INNER JOIN dotb7_document_tracking  AS tracking  ON doc_tracking.documents_dotb7_document_tracking_1dotb7_document_tracking_idb = tracking.id AND tracking.deleted = 0 '
                    . ' WHERE doc_tracking.deleted = 0 AND doc_tracking.documents_dotb7_document_tracking_1documents_ida IN (' . $doc_ids . ') ';
            $doc_track_results = $GLOBALS['db']->query($sql_doc_trac);

            while ($row = $GLOBALS['db']->fetchByAssoc($doc_track_results)) {
                $this->category_ids[] = $row['id'];
            }
        }// end of if
    }

    function convertPDFToImages($folderId, $pdfPath, /* $file_type, */ $converted) {
        $output = '';
        $return_var = '';
        try {
            if ((!file_exists($this->pdfToImageDir . $folderId) || $this->is_dir_empty($this->pdfToImageDir . $folderId)) && $converted == 'pdf') {
                $this->createFolder($this->pdfToImageDir);
                $this->createFolder($this->pdfToImageDir . $folderId);

                $fileInfo = pathinfo($pdfPath);
                $pdfToImagePath = $this->pdfToImageDir . $folderId . '/';
                $pdfToImageFullPath = $pdfToImagePath . $fileInfo['filename'] . self::$imageExtension;

                //if(!in_array($file_type, $this->image_extension) ){
                //exec('convert "'.$pdfPath.'" -colorspace RGB -resize 300 "'.$pdfToImageFullPath.'"', $output, $return_var);
                exec("convert -density 70 '$pdfPath' -quality 60 '$pdfToImageFullPath' 2>&1", $output, $return_var);
                /* } else {
                  exec("convert '$pdfPath' -thumbnail 800x800  '$pdfToImageFullPath' 2>&1", $output, $return_var);
                  } */
            }
        } catch (Exception $e) {
            return ['error_code' => $e->getCode(), 'message' => $e->getMessage()];
        }

        return true;
    }

    public function createMergedPDFForSendDocuments($path1) {
        $GLOBALS['log']->debug('PDF Paths :: ' . print_r($path1, 1));
        $path = json_decode($path1, true);
        $GLOBALS['log']->debug('After convertion PDF Paths :: ' . print_r($path, 1));
        if (!empty($path)) {
            // Creating Document
            $documentBean = BeanFactory::getBean('Documents', array('disable_row_level_security' => true));
            $documentBean->document_name = $this->newDocName;
            $documentBean->converted = 1; // merged newly created Merged PDF as converted
            $documentBean->send_document = 1; // To merged this document as dummy to be deleted using Cron job
            $documentBean->save();

            $documentId = $documentBean->id;

            $bean_DocumentRevision = BeanFactory::getBean('DocumentRevisions');
            $bean_DocumentRevision->document_id = $documentId;
            $bean_DocumentRevision->doc_type = 'Sugar';
            $bean_DocumentRevision->filename = $this->mergedDocumentName;
            $bean_DocumentRevision->file_ext = 'pdf';
            $bean_DocumentRevision->file_mime_type = 'application/pdf';
            $bean_DocumentRevision->revision = '1';
            $bean_DocumentRevision->save();

            $documentBean->document_revision_id = $bean_DocumentRevision->id;
            $documentBean->rev_file_name = $this->mergedDocumentName;
            $documentBean->save();

            $GLOBALS['log']->debug('---------- For Sending Documents To bank ------');
            $GLOBALS['log']->debug('Document ID ' . $documentId);
            $GLOBALS['log']->debug('Document Revision ID ' . $bean_DocumentRevision->id);

            try {
                $pdf = new SetaPDF_Merger();
                foreach ($path as $key => $pdfInfo) {
                    $pdf->addFile($pdfInfo['pdf_file_path'], $pdfInfo['page_number']);
                }

                $pdf->merge();

                $writer = new SetaPDF_Core_Writer_String();
                $resDocument = $pdf->getDocument();
                $resDocument->setCleanUpObjects(false);
                $resDocument->setWriter($writer);
                $resDocument->save()->finish();
                $resDocument->cleanUp();

                $mergedPdf = $this->upload_dir;
                $mergedPdf.=$bean_DocumentRevision->id;
                
                if (file_put_contents($mergedPdf, $writer)) {
                    $filesize = $this->formatbytes($mergedPdf,'MB');
                    $GLOBALS['log']->debug('Size of Documents :: '.$filesize);
                    if($filesize < $this->emailAttachmentSize){
                        if(!empty($bean_DocumentRevision->id)){
                            $this->docRevID = $bean_DocumentRevision->id;
                        }
                        return $documentId;
                    } else{
                        return 'error'; 
                    }
                } else {
                    return false;
                }
            } catch (Exception $ex) {
                $this->exceptionMessage = $ex->getMessage();
                $GLOBALS['log']->debug('Error : ' . $ex->getMessage());
                return 'exception';
            } 
        }
        return false;
    }
    
    private function formatbytes($file, $type) {
        switch ($type) {
            case "KB":
                $filesize = filesize($file) * .0009765625; // bytes to KB
                break;
            case "MB":
                $filesize = (filesize($file) * .0009765625) * .0009765625; // bytes to MB
                break;
            case "GB":
                $filesize = ((filesize($file) * .0009765625) * .0009765625) * .0009765625; // bytes to GB
                break;
        }
        if ($filesize <= 0) {
            return $filesize = 'unknown file size';
        } else {
            return round($filesize, 2);
        }
    }

    private function is_dir_empty($dir) {
        if (!is_readable($dir))
            return NULL;
        return (count(scandir($dir)) == 2);
    }

    public function createPDFForFTPUpload($pdfInfo) {
        $GLOBALS['log']->debug('PDF info in Fuction :: ' . print_r($pdfInfo, 1));
        try {
            $pdf = new SetaPDF_Merger();
            foreach ($pdfInfo as $key => $pdfInfo) {
                $pdf->addFile($pdfInfo['pdf_file_path'], $pdfInfo['page_number']);
            }

            $pdf->merge();

            $writer = new SetaPDF_Core_Writer_String();
            $resDocument = $pdf->getDocument();
            $resDocument->setCleanUpObjects(false);
            $resDocument->setWriter($writer);
            $resDocument->save()->finish();
            $resDocument->cleanUp();

            $fileId = create_guid();
            $this->createFolder('custom/include/ftp');
            $file_name = "custom/include/ftp/" . $fileId . '.pdf';

            $GLOBALS['log']->debug('Merged PDF Path in PDFConvertor :: ' . $file_name);

            if (file_put_contents($file_name, $writer)) {
                $ret_arr = array('fileName' => $file_name, 'fileId' => $fileId);
                return $ret_arr;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            $GLOBALS['log']->debug('Error : ' . $ex->getMessage());
            return false;
        }
    }

    public function archievedPDFMerged($pdfFiles, $upload_path) {
        $GLOBALS['log']->debug('PDF file in archievedPDFMerged :: ' . print_r($pdfFiles, 1));
        try {
            $pdf = new SetaPDF_Merger();
            foreach ($pdfFiles as $key => $pdfFiles) {
                $inDocument = SetaPDF_Core_Document::loadByFilename($pdfFiles['pdf_file_path']);
                $inDocument->setCacheReferencedObjects(true);
                $pdf->addDocument($inDocument);
            }

            $pdf->merge();

            $writer = new SetaPDF_Core_Writer_String();
            $resDocument = $pdf->getDocument();
            $resDocument->setCleanUpObjects(false);
            $resDocument->setWriter($writer);
            $resDocument->save()->finish();
            $resDocument->cleanUp();


            if (file_put_contents($upload_path, $writer)) {
                $GLOBALS['log']->debug('Documents are merged successfully');
                return ['level' => 'success', 'message' => 'Documents are merged successfully'];
            }
        } catch (Exception $pdf_e) {
            return ['level' => 'error', 'message' => 'Failed to merge the documents.'];
        }
    }

    public function rotateSpecifiedPage($document_revision, $page_number, $degrees) {

        global $app_strings;
        try {
            $original_pdf = $this->upload_dir . $document_revision;
            $rotated_file = $this->convertedPdfDir . $document_revision . '.pdf';
            $writer = new SetaPDF_Core_Writer_File($rotated_file);
            $document = SetaPDF_Core_Document::loadByFilename($original_pdf, $writer);

            $pages = $document->getCatalog()->getPages();
            $pageCount = $pages->count();
            if((int)$pageCount==1){
            $page = $pages->getPage(1);    
            }else{
            $page = $pages->getPage($page_number);    
            }

            $page->rotateBy($degrees);
            $document->save()->finish();
            copy($rotated_file, $original_pdf);
            return ['level' => 'success', 'message' => $app_strings['LBL_DOCUMENT_UPDATED']];
        } catch (Exception $e) {
            return ['level' => 'error', 'message' => $app_strings['LBL_ORDER_CHANGE_FAILED']];
        }
    }
    
    public function swapSpecifiedPages($document_revision, $page_number, $direction) {
        
        global $app_strings;
        try {
            $original_pdf = $this->upload_dir . $document_revision;
            $swapped_file = $this->convertedPdfDir . $document_revision . '.pdf';
            $document = SetaPDF_Core_Document::loadByFilename($original_pdf);

            $pages = $document->getCatalog()->getPages();
            $pages->ensureAllPageObjects();

            $page = $pages->getPage($page_number);
            $pageCount = $pages->count();

            $start = 0;
            
            if($direction == "up"){
                $end = $page_number - 1;
            }
            else if($direction == "down"){
                $end = $page_number + 1;
            }
            if($pageCount == $page_number && $direction == "down"){
                return ['level' => 'error', 'message' => $app_strings['LBL_NO_PAGE_NEXT']];  
            }
            else{ 
                    $limit=0;
                    if($page_number > $end){
                        $limit = $end-1;
                    }
                    else if($page_number < $end && $page_number != 1){
                        $limit = $page_number-1;
                    }
                        
                    $pdf = new SetaPDF_Merger();
                    if($limit)
                    for ($pageNumber = 1; $pageNumber <= $limit; $pageNumber += 1) {
                        $pdf->addFile($original_pdf, $pageNumber);
                    }
                    
                    if($page_number > $end && $direction == "up"){
                        $pdf->addFile($original_pdf, $page_number);
                        $pdf->addFile($original_pdf, $end);
                        $lastPage = $page_number;
                    }
                    else if($page_number < $end && $direction == "down"){
                        $pdf->addFile($original_pdf, $end);
                        $pdf->addFile($original_pdf, $page_number);
                        $lastPage = $end;
                    }
                    
                    if($lastPage != $pageCount){
                        for ($pageNumber = $lastPage+1; $pageNumber <= $pageCount; $pageNumber += 1) {
                            $pdf->addFile($original_pdf, $pageNumber);
                        }
                    }
                    $pdf->merge();

                    $writer = new SetaPDF_Core_Writer_String();
                    $resDocument = $pdf->getDocument();
                    $resDocument->setCleanUpObjects(false);
                    $resDocument->setWriter($writer);
                    $resDocument->save()->finish();
                    $resDocument->cleanUp();
                    $mergedPdf = $swapped_file;
                    if (file_put_contents($mergedPdf, $writer)) {
                        copy($swapped_file, $original_pdf);
                        unlink($swapped_file);
                    }
                    
                    $image_path = $this->pdfToImageDir.$document_revision;
                    $image_path_1 = $this->pdfToImageDir.$document_revision.'/'.$document_revision.'-'.($page_number-1).'.jpg';
                    $image_path_2 = $this->pdfToImageDir.$document_revision.'/'.$document_revision.'-'.($end-1).'.jpg';                   
                    exec('mv ' . $image_path_1 . ' ' . $image_path .'/temp.jpg' . ' ');
                    exec('mv ' . $image_path_2 . ' ' . $image_path_1 . ' ');
                    exec('mv ' . $image_path .'/temp.jpg' . ' ' . $image_path_2 . ' ');
 
                    return ['level' => 'success', 'message' => $app_strings['LBL_DOCUMENT_UPDATED'], 'original_image' => $image_path_1 , 'new_image' => $image_path_2];
            }

            
        } catch (Exception $e) {
            return ['level' => 'error', 'message' => $app_strings['LBL_ORDER_CHANGE_FAILED']];
        }
    }

}

?>