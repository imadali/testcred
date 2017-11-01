<?php

require_once('include/api/SugarApi.php');
require_once('include/SugarQuery/SugarQuery.php');
require_once('custom/include/PDFConverter/PDFConverter.php');
require_once('include/utils.php');

class getRelatedAppApi extends SugarApi {

    /**
     * @function registerApiRest
     * @description registering the API call to make project status field read-only or not
     * @return type
     */
    public function registerApiRest() {
        return array(
            'getRelatedApp' => array(
                'reqType' => 'GET',
                'path' => array('Leads', 'getRelatedApp', '?'),
                'pathVars' => array('', '', 'id'),
                'method' => 'getRelatedApp',
                'shortHelp' => 'This api will return the applications relatd to the lead',
                'longHelp' => '',
            ),
            'getRelatedBank' => array(
                'reqType' => 'POST',
                'noLoginRequired' => false,
                'path' => array('Leads', 'getRelatedBank'),
                'pathVars' => array('', ''),
                'method' => 'getRelatedBank',
                'shortHelp' => 'This api will return the applications relatd to the lead',
                'longHelp' => '',
            ),
        );
    }

    /**
     *
     * @param ServiceBase $api
     * @param array $args
     */
    public function getRelatedApp($api, $args) {
        $leadId = $args['id'];
        $lead = new Lead();
        $lead->retrieve($leadId);
        $apps = $lead->get_linked_beans('leads_opportunities_1', 'Opportunity');
        $arr = array();
        foreach ($apps as $app) {
            $arr[$app->id] = $app->name.'=-=-='.$app->provider_id_c;
        }
        return $arr;
    }

    public function getRelatedBank($api, $args) {
        $AppId = $args['app_id'];
        $leadId = $args['lead_id'];
        $lead = new Lead();
        $lead->retrieve($leadId);
         if (!empty($lead->contact_id)) {
         $name_subject= $lead->contact_name;   
         }else{
          $name_subject= $lead->first_name . ' ' . $lead->last_name;
         }
        $arr = array();
        $app = new Opportunity();
        $app->retrieve($AppId);
        $arr['subject'] = $name_subject;
        $arr['emails'] = array();
        $bank = new Account();
        $bank->retrieve($app->account_id);
        if (!empty($app->provider_id_c) && !empty($bank->id)) {
            if (strtolower($app->provider_id_c) == 'cembra') {
                $arr['subject'] = $app->provider_application_no_c;
                $arr['email_template'] ='bef99df7-a597-c230-9f0b-57973a03a963';
            } else if (strtolower($app->provider_id_c) == 'rci') {
                $arr['subject'] = $app->provider_application_no_c;
                $arr['email_template'] ='ab2c372d-16e5-2e80-de12-57973bc83690';
            } else if (strtolower($app->provider_id_c) == 'cash_gate') {
                $arr['subject'] = $app->provider_application_no_c;
                $arr['email_template'] ='c21408fc-f447-1203-2c66-57973c5b3fb4';
            } else if (strtolower($app->provider_id_c) == 'bob') {
                $arr['subject'] = $lead->credit_request_number_c; 
                $arr['email_template'] ='19c4235e-540d-d23d-f81c-57973c76b5cb';
            } else if (strtolower($app->provider_id_c) == 'bank_now') {
                $arr['subject'] = $app->provider_application_no_c;
                $arr['email_template'] ='2cdc0e77-6487-9ebd-d2d9-57973c5ba9e7';
            } else if ($app->provider_id_c == 'bank_now_casa') {
                $arr['subject'] = $app->provider_application_no_c;
                $arr['email_template'] ='7da7c152-bf9a-f4b9-75ae-57973c9d1d18';
            } else if ($app->provider_id_c == 'bank_now_car') {
                $arr['subject'] = $app->provider_application_no_c;
                $arr['email_template'] ='f24eabe3-6d79-c1c6-68c4-57973c77f719';
            } else if (strtolower($app->provider_id_c) == 'eny_finance') {
                $arr['subject'] = $lead->credit_request_number_c;
                $arr['email_template'] ='a757619e-c801-1900-43f4-57973cc29d47';
            }
            $sea = new SugarEmailAddress;
            $bank_email_addresses = $sea->getAddressesByGUID($bank->id, $bank->module_name);
            foreach ($bank_email_addresses as $bank_email_address) {
               $arr['emails'][]=$bank_email_address['email_address'];
            }
            $GLOBALS['db']->query("UPDATE leads SET app_approval_user='$app->dotb_user_approval_c' WHERE id='$leadId'");
        }
		
		// Creating Dummy Document record for adding them in Compose Email Drawer
        $pdf_convertor = new PDFConverter();
        $document_id = $pdf_convertor->createMergedPDFForSendDocuments($args['pdf_info']);
        
        if($document_id == 'error'){
            $message = translate('LBL_LIMIT_EMAIL_ATTACHMENTS', 'Leads');
            throw new Exception($message);
        } 
        else if($document_id == 'exception'){
            $message = translate('LBL_SETASIGN_MERGE_EXCEPTION_SEND_DOC', 'Leads');
            throw new Exception($message);
        }
        else{
            $arr['document_id'] = $document_id;
            $arr['document_revision_id'] = $pdf_convertor->docRevID;
        }
        
        $GLOBALS['log']->debug('Bank Returned Data :: '.print_r($arr,1));
        return $arr;
    }

}

?>
