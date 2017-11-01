<?php

/**
 * Class DocConversionToPDFApi
 * @extends SugarApi
 */

require_once 'custom/include/PDFConverter/PDFConverter.php';
require_once 'custom/include/PDFConverter/PDFHelper.php';

class DocConversionToPDFApi extends SugarApi {

    public function registerApiRest() {
        return [
            'convertDocToPDF' => [
                'reqType' => 'GET',
                'path' => array('convertDocToPDF','?','?'),
                'pathVars' => array('','id','module_name'),
                'method' => 'convertDocToPDF',
                'shortHelp' => 'This method will convert any document into PDF',
                'longHelp' => '',
            ],
            
            'getRelatedDocumentCategory' => [
                'reqType' => 'GET',
                'path' => array('getRelatedDocumentCategory','?','?','?'),
                'pathVars' => array('','module','record','link_name'),
                'method' => 'getRelatedDocumentCategory',
                'shortHelp' => 'This method returns all the related Categories to Document',
                'longHelp' => '',
            ],
            
            'mergeSelectedPagesIntoPDF' => [
                'reqType' => 'POST',
                'path' => array('mergeSelectedPagesIntoPDF'),
                'pathVars' => array(''),
                'method' => 'mergeSelectedPagesIntoPDF',
                'shortHelp' => 'This method will merge selected pages into one PDF in sequence',
                'longHelp' => '',
            ],
            'rotateImageOnDocPreview' => [
                'reqType' => 'POST',
                'path' => array('rotateImageOnDocPreview'),
                'pathVars' => array(''),
                'method' => 'rotateImageOnDocPreview',
                'shortHelp' => 'This method rotate the image clock/anti-clockwise',
                'longHelp' => '',
            ],

            'convertToPDFPreview' => [
                'reqType' => 'GET',
                'path' => array('convertToPDFPreview','?','?'),
                'pathVars' => array('','id','module_name'),
                'method' => 'convertToPDFPreview',
                'shortHelp' => 'This method will convert any non-pdf document into PDF on manuall button click',
                'longHelp' => '',
            ],
            
            'saveSaluationAndPayOff' =>[
                'reqType' => 'POST',
                'path' => array('saveSaluationAndPayOff'),
                'pathVars' => array(''),
                'method' => 'saveSaluationAndPayOff',
                'longHelp' => '',
            ],
            
            'retrieveSaluationAndPayOff' => [
                'reqType' => 'GET',
                'path' => array('retrieveSaluationAndPayOff'),
                'pathVars' => array(''),
                'method' => 'retrieveSaluationAndPayOff',
                'longHelp' => '',
            ],
            
            'swapPagesInDocument' => [
                'reqType' => 'POST',
                'path' => array('swapPagesInDocument'),
                'pathVars' => array(''),
                'method' => 'swapPagesInDocument',
                'shortHelp' => 'This method rearranges specified pages in the pdf',
                'longHelp' => '',
            ],
        ];
    }
    
    
    public function swapPagesInDocument(ServiceBase $api, array $args){
        $this->requireArgs($args, array('image_path', 'direction'));
        $image_path_temp = json_decode($args['image_path'], true);
        $image_path = substr($image_path_temp, 0, strrpos($image_path_temp, '?'));
        
        $document_path = explode('/', $image_path);
        $document_revision_id = $document_path[2];
        
        $page = explode('-', $document_path[3]);
        $page_number = explode('.', $page[sizeof($page)-1]);
        
        $obj = new PDFConverter();
        $response = $obj->swapSpecifiedPages($document_revision_id, (int)$page_number[0]+1, $args['direction']);
        
        return $response;
    }
    
    public function rotateImageOnDocPreview(ServiceBase $api, array $args) {
        $this->requireArgs($args, array('image_path', 'rotation'));

        $image_path_temp = json_decode($args['image_path'], true);
        $image_path = substr($image_path_temp, 0, strrpos($image_path_temp, '?'));

        $document_path = explode('/', $image_path);
        $document_revision_id = $document_path[2];

        $page = explode('-', $document_path[3]);
        $page_number = explode('.', $page[sizeof($page)-1]);
        $rotation = '';

         if ($args['rotation'] == 'clockwise') {
            $rotation = 90;
         }else{
            $rotation = -90;
         }

        $obj = new PDFConverter();
        $obj->rotateSpecifiedPage($document_revision_id, (int)$page_number[0]+1, $rotation);

        if ($args['rotation'] == 'clockwise') {
            exec('convert -rotate "90" ' . $image_path . ' ' . $image_path . ' ');
        } else {
            exec('convert -rotate "-90" ' . $image_path . ' ' . $image_path . ' ');
        }

        return true;
    }

    public function retrieveSaluationAndPayOff(ServiceBase $api, array $args){
        $sql_data = 'SELECT name, value FROM config WHERE name = "signature_salutation" OR name = "signature_payoff"  ';
        $result = $GLOBALS['db']->query($sql_data);
        
        $saluation = '';
        $payOff = '';
        
        while($row = $GLOBALS['db']->fetchByAssoc($result)) {
            if($row['name'] == 'signature_salutation'){
               $saluation = $row['value'];
            }else if($row['name'] == 'signature_payoff'){
               $payOff = $row['value'];
            }
        }

        if(empty($saluation) && empty($payOff) ){
            return false;
        }
        return array('saluation'=> $saluation,'payOff' => $payOff);
    }
    
    public function saveSaluationAndPayOff(ServiceBase $api, array $args) {
        $this->requireArgs(
           $args,
           array('salutation',
                'payOff',
           )
        );
        
        if($args['data_type']){
            $sql_saluation = "UPDATE config SET value = '".$args['salutation']."' WHERE name = 'signature_salutation' ";
            $sql_payOff  = "UPDATE config SET value = '".$args['payOff']."' WHERE name = 'signature_payoff' ";
        }
        else{
            $sql_saluation = 'INSERT INTO config (category, name, value, platform) '
                          . " VALUES ( 'custom_email_signature', 'signature_salutation' , '".$args['salutation']."', NULL ) ";
            $sql_payOff = 'INSERT INTO config (category, name, value, platform) '
                          .  "VALUES ( 'custom_email_signature', 'signature_payoff' , '".$args['payOff']."', NULL )" ;
        }

        $GLOBALS['db']->query($sql_saluation);
        $GLOBALS['db']->query($sql_payOff);
        
        return true;
    }
    
    public function getRelatedDocumentCategory(ServiceBase $api, $args){
        global $app_list_strings;
        $data = array();
        $beanDoc = BeanFactory::getBean($args['module'],$args['record']);
        $beanDoc->retrieve();
        
        $docTracking = array();
        $manualDocTracking = array();
        
        if($beanDoc){
            
            $categories = $beanDoc->get_linked_beans($args['link_name'],'dotb7_document_tracking');
            
            foreach($categories as $category){
                $GLOBALS['log']->debug('Category :: '.$app_list_strings['dotb_document_category_list'][$category->category]);
                if($app_list_strings['dotb_document_category_list'][$category->category]){
                    $docTracking[] = $category;
                }else{
                    $manualDocTracking[] = $category;
                }
            }
            
            $data['doc_tracking'] = $this->formatBeans($api, $args, $docTracking);
            $data['manual_tracking'] = $this->formatBeans($api, $args, $manualDocTracking);
            
        }
        
        $GLOBALS['log']->debug('returned Data ::  '.print_r($data,1));
        return $data;
        
    }
   
    public function convertDocToPDF(ServiceBase $api, $args) {
        $obj = new PDFConverter();
        return $obj->convertDocToPDF($args);
    }

    public function mergeSelectedPagesIntoPDF(ServiceBase $api, $args) {
        $this->requireArgs($args, array('record_id'));
        
        $pdfObj = new PDFConverter();
        return $pdfObj->mergeSelectedPagesIntoPDF($args);
    }
    
     public function convertToPDFPreview(ServiceBase $api, $args) {
        $GLOBALS['log']->debug('convertToPDFPreview Arguments Data :: '.print_r($args,1));
        $pdfObj = new PDFHelper();
        $returnData = $pdfObj->getRelatedDocumentsData($args['id'], $args['module_name']);
        $GLOBALS['log']->debug('data :: '.print_r($returnData,1));
        if(!empty($returnData)){
            return $pdfObj->processDocuments($returnData);
        }
        else{
            return false;
        }
    }
}
