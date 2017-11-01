<?php

require_once('custom/include/SetaPDFMerger/library/SetaPDF/Autoload.php');
require_once('include/utils.php');

try{
    if (isset($_REQUEST['file'])) {

        // For downloading MergePDF
        if (isset($_REQUEST['type']) == 'download') 
        {
            $path = urldecode($_REQUEST['file']);
        } 
        else { 

            $temp = $_REQUEST['file'];
            $path = base64_decode($temp);
            $num = $_REQUEST['num'];
        }

        if(!empty($path)){

            $pdf = new SetaPDF_Merger();
            $inDocument = SetaPDF_Core_Document::loadByFilename($path);

            $inDocument->setCacheReferencedObjects(true);
            // For previewing PDF via Page number
            if(!empty($num)){
                $pdf->addDocument($inDocument, $num);

                $name = basename($path);
                $pdf->merge();

                $writer = new SetaPDF_Core_Writer_String();
                $resDocument = $pdf->getDocument();

                $resDocument->setCleanUpObjects(false);
                // Set the writer
                $resDocument->setWriter($writer);
                $resDocument->save()->finish();
                $resDocument->cleanUp();

                $newTabPath = $GLOBALS['sugar_config']['new_tab'];

                $unique_id = create_guid().'.pdf';
                $pdfName = $newTabPath.'/'.$unique_id;

                
                 if(file_put_contents($pdfName, $writer)){
                    header('Content-type: application/pdf');
                    header('Content-Disposition: inline; filename="' . $pdfName . '"');
                    header('Content-Transfer-Encoding: binary');
                    header('Content-Length: ' . filesize($pdfName));   
                    header('Accept-Ranges: bytes');
                    readfile($pdfName);
                    unlink($pdfName);
                    exit(0);
                 } else{
                     echo '<iframe src="'.$pdfName.'" width="100%" height="100%" >';
                 }
             }
             else{
                 echo '<iframe src="'.$path.'" width="100%" height="100%" >';
                }
         }
    }
    else if(isset($_REQUEST['pdf_path'])){
        $newTabPath = rtrim($GLOBALS['sugar_config']['new_tab'],'/').'/';
        $temp = $_REQUEST['pdf_path'];
        $path = base64_decode($temp);
        $unique_pdf_id = create_guid().'.pdf';
        $dest_path = $newTabPath.$unique_pdf_id;
        if(file_exists($path)){
            if (copy($path, $dest_path)) {
                $file = $dest_path;
                $filename = $unique_pdf_id;
                header('Content-type: application/pdf');
                header('Content-Disposition: inline; filename="' . $file . '"');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . filesize($file));   
                header('Accept-Ranges: bytes');
                readfile($file);
                unlink($file);
                exit(0);
                //echo '<iframe src="'.$dest_path.'" width="100%" height="100%" >';
            }else{
                echo '<iframe src="" width="100%" height="100%" >';
            }
        }else{
            echo '<iframe src="" width="100%" height="100%" >';
        }
    }

    else if(isset($_REQUEST['type']) /*&& $_REQUEST['type'] =='Notes'*/) {
        if($_REQUEST['type'] =='Notes'){
            $source = $GLOBALS['sugar_config']['upload_dir'].$_REQUEST['id'];
        } else if($_REQUEST['type'] =='upload'){
            $source = $GLOBALS['sugar_config']['upload_dir'].$_REQUEST['document_revision_id'];
        }
        $newTabPath = rtrim($GLOBALS['sugar_config']['new_tab'],'/').'/';
        $unique_pdf_id = create_guid().'.pdf';
        $dest_path = $newTabPath.$unique_pdf_id;
        if (copy($source, $dest_path)) {
            $file = $dest_path;
            $filename = $unique_pdf_id;
            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="' . $filename . '"');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($file));   
            header('Accept-Ranges: bytes');
            readfile($file);
            unlink($file);
            exit(0);  
            //echo '<iframe src="'.$dest_path.'" width="100%" height="100%">';
        }
    } 
} catch (Exception $ex) {
    $message = translate('LBL_SETA_SIGN_EXCEPTION_EXTRACT', 'Leads');
    echo '<font color="red">'.$message.'</font>';
}    