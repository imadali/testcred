<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

use Sugarcrm\Sugarcrm\Util\Serialized;

class customParserApi extends SugarApi 
{
    public $userPrimaryTeam;
    public $replyTo;
    public $replyToName;
    public $fromName;
    public $fromAddress;
    public $selected_level = 1;
    public $exclude_status = array(
          '01_new', '2a_not_reached_first_round',
          '2b_not_reached_second_round',
          '2c_not_reached_third_round',
          '3a_document_sent_first_round',
          '3b_document_sent_second_round','13_customer_center');
    public function registerApiRest() 
    {
        return array(
            'customParserApi' => array(
                'reqType' => 'GET',
                'path' => array('Emails', 'EmailParser', '?', 'lead', '?'),
                'pathVars' => array('', '', 'id', '', 'leadId'),
                'method' => 'parseEamil',
                'shortHelp' => 'This api will parse an email template and return the parsed string',
                'longHelp' => '',
            ),
            'crifParserApi' => array(
                'reqType' => 'GET',
                'path' => array('Emails', 'CrifEmailParser', '?', 'lead', '?'),
                'pathVars' => array('', '', 'id', '', 'leadId'),
                'method' => 'crifParseEamil',
                'shortHelp' => 'This api will parse an email template and return the parsed string',
                'longHelp' => '',
            ),
            'signatureParserApi' => array(
                'reqType' => 'GET',
                'path' => array('Emails', 'signatureParserApi', '?', '?','?','?'),
                'pathVars' => array('', '', 'templateid', 'assigned_user_id','language','lead_id'),
                'method' => 'signatureParserApi',
                'longHelp' => '',
            ),
        );
    }
    
    public function signatureParserApi(ServiceBase $api, array $args) 
    {
        global $current_user;
        $this->requireArgs(
           $args,
           array('templateid',
                 'assigned_user_id',
                 'language',
                 'lead_id'
           )
        );
        $bean_email_template = BeanFactory::getBean('EmailTemplates', $args['templateid'], array('disable_row_level_security' => true));
        $html_return = $this->getUserSignature($args['assigned_user_id'], $bean_email_template->body_html, $args['language']);
        
        /**
         * CRED-997 : Workflows for the new Provider Flex
         */
        $leadBean = BeanFactory::getBean("Leads", $args['lead_id']);
        $dateEntered = new DateTime($leadBean->date_entered);
        $dateEntered = $dateEntered->format($current_user->getPreference('datef'));
        $html_return = str_replace('$lead_date_created', $dateEntered, $html_return);
        
        return $html_return;
    }
    
    public function getUserSignature($user_id, $email_template_html, $language, $workFlow = false, $lead_status = '') 
    {
        $this->replyTo = null;
        $saluation = '';
        $payOff = '';
        $salution_text = '';
        $payOff_text = '';

        $mapping_index = array('0' => 'de', '1' => 'fr','2' => 'it' ,'3' => 'en');
        
        $tel_label = 'Tel. ';
        if ($language == 'fr') {
            $tel_label = 'Tél. ';
        }
        
        $sql_config = 'SELECT * FROM config WHERE name = "signature_salutation"  || name="signature_payoff" ';
        $results_conf = $GLOBALS['db']->query($sql_config);
        
        while ($row = $GLOBALS['db']->fetchByAssoc($results_conf)) {
            if ($row['name'] == 'signature_salutation') {
                $saluation = json_decode($row['value'],true);
            } else if ($row['name'] == 'signature_payoff') {
                $payOff = json_decode($row['value'], true);
            }
        }
        
        if (!empty($saluation) && !empty($payOff)) {
            $key = array_search($language, $mapping_index);
            $salution_text = $saluation[$key]['sal'];
            $payOff_text = $payOff[$key]['payOff'];
        }
        
        if (!empty($user_id)) {
            $bean_user = BeanFactory::getBean('Users', $user_id, array('disable_row_level_security' => true));
            $this->userPrimaryTeam = $bean_user->default_team;
        } else {
            $this->userPrimaryTeam = '1';
            $bean_user = BeanFactory::getBean('Users'); // initalizing with empty Users object
        }
        
        $department = (!empty($bean_user->department) ? $bean_user->department: '');
        $address_street = (!empty($bean_user->address_street) ? $bean_user->address_street : '');
        $address_postal_code = (!empty($bean_user->address_postalcode) ? $bean_user->address_postalcode  : '');
        $address_city = (!empty($bean_user->address_city) ? $bean_user->address_city  : '');
        $telephone = (!empty($bean_user->phone_other) ? $bean_user->phone_other  : '');
        $fax = (!empty($bean_user->phone_fax) ? $bean_user->phone_fax  : '');
        $primary_email = (!empty($bean_user->email1) ? $bean_user->email1  : '');
        $work_phone = '';
        
        if (!in_array($lead_status, $this->exclude_status) && $workFlow ) {
            $work_phone = (!empty($bean_user->phone_work) ? ' | '.$bean_user->phone_work  : '');
        } else if (!$workFlow) {
            $work_phone = (!empty($bean_user->phone_work) ? ' | '.$bean_user->phone_work  : '');
        }
        $full_name = (!empty($bean_user->full_name) ? $bean_user->full_name  : '');
        
        if ($workFlow) {
            //if(!empty($this->userPrimaryTeam)) {
                $this->getReplyToFromGroupEmail($this->userPrimaryTeam, $primary_email);
            /*}else{
                $this->replyTo = $primary_email;
            }*/
        }
        
        if (is_null($this->replyTo) && $this->selected_level) {
            
            $emailObj = new Email();
            $defaults = $emailObj->getSystemDefaultEmail();
            
            if (!empty($defaults) && isset($defaults['email'])) {
                $this->replyTo = $defaults['email'];
            }
        } 
        // If Assigned To of Record is empty
        if (empty($primary_email) && !is_null($this->replyTo)) {
            $primary_email = $this->replyTo;
        }
        
        $html_replace = '<p dir="ltr"><span style="font-size:x-small; font-family: arial, helvetica, sans-serif;">'.$salution_text.'</span>
                        </p>
                        <p dir="ltr"><span style="font-size:x-small; font-family: arial, helvetica, sans-serif;">'.$full_name.$work_phone.'</span>
                        </p>
                        <p dir="ltr"><span style="font-size:x-small; font-family: arial, helvetica, sans-serif;">'.$department.'</span>
                            <br /><span style="font-size:x-small; font-family: arial, helvetica, sans-serif;">'.$address_street.'</span>
                            <br /><span style="font-size:x-small; font-family: arial, helvetica, sans-serif;">'.$address_postal_code.' '.$address_city.'</span>
                            <br /><span style="font-size:x-small; font-family: arial, helvetica, sans-serif;">'.$tel_label.$telephone.'</span>
                            <br /><span style="font-size:x-small; font-family: arial, helvetica, sans-serif;">Fax '.$fax.'</span>    
                        </p>
                        <p>
                            <span style="font-size:x-small; font-family: arial, helvetica, sans-serif;" >E-Mail: <a href="mailto:'.$this->replyTo.'">'.$primary_email.'</a>
                            <br /> Web: <a href="http://www.credaris.ch/">www.credaris.ch</a></span>
                        </p>
                        <p ><span style="font-size:x-small; font-family: arial, helvetica, sans-serif;">'.$payOff_text.'</span>
                        </p>';
        
        $html_return = str_replace('$custom_contact_signature', $html_replace,$email_template_html);
        
        return $html_return;
    }
    
    public function getReplyToFromGroupEmail($primaryTeam, $primary_email)
    {
        
        $inBoundEmail = '';
        // First checking the Primary Email address in InboundEmail Settings
        if (!empty($primary_email)) {
            $inBoundEmail = BeanFactory::getBean('InboundEmail')->retrieve_by_string_fields(array('name' => $primary_email,'deleted' => 0, 'status' => 'Active'));
        }
        // Secondly checking for Primary Team in inBoundEmail Settings
        if (empty($inBoundEmail->id)) {
            $inBoundEmail = BeanFactory::getBean('InboundEmail')->retrieve_by_string_fields(array('team_id' => $primaryTeam,'deleted' => 0, 'status' => 'Active'));
            if (empty($inBoundEmail->id)) {
                
                $global_team = (isset($GLOBALS['sugar_config']['global_team'])? $GLOBALS['sugar_config']['global_team'] : '1' );
                //for searching for Primary and Secondary Team
                $sql_global_inbound = 'SELECT team_sets_teams.team_set_id 
                                            FROM team_sets_teams 
                                        INNER JOIN team_sets_modules ON team_sets_modules.team_set_id = team_sets_teams.team_set_id  
                                            AND team_sets_modules.deleted = 0
                                            AND team_sets_modules.module_table_name = "inbound_email"
                                        INNER JOIN inbound_email ON inbound_email.team_set_id = team_sets_teams.team_set_id
                                            AND inbound_email.deleted = 0
                                        WHERE team_sets_teams.deleted = 0  AND team_sets_teams.team_id = "'.$global_team.'" LIMIT 1';
                $result = $GLOBALS['db']->query($sql_global_inbound);
                $row = $GLOBALS['db']->fetchByAssoc($result);
                
                if (!empty($row)) {
                    $inBoundEmail = BeanFactory::getBean('InboundEmail')->retrieve_by_string_fields(array('team_set_id' => $row['team_set_id'],'deleted' => 0, 'status' => 'Active'));
                }
            }
        }
        
        $storedOptions = array();
        if (!empty($inBoundEmail->id)) {
            $this->selected_level = 0;
            $storedOptions = Serialized::unserialize($inBoundEmail->stored_options, array(), true);
        } 
        
        $this->fromName = (isset($storedOptions['from_name']) ? $storedOptions['from_name'] : null);
        $this->fromAddress = (isset($storedOptions['from_addr']) ? $storedOptions['from_addr'] : null);
        $this->replyTo = (isset($storedOptions['reply_to_addr'])) ? $storedOptions['reply_to_addr'] : null;
        $this->replyToName = (!empty($storedOptions['reply_to_name']))? from_html($storedOptions['reply_to_name']) : null ;
    }
    
    public function parseEamil(ServiceBase $api, array $args) 
    {

        global $app_list_strings;
        //$user_name = $current_user->full_name;

        $templateId = $args["id"];
        $leadId = $args["leadId"];
        $doc_tracking_count = 0;
        $leadBean = BeanFactory::getBean("Leads", $leadId);
        if ($leadBean->load_relationship('leads_documents_1')) {
            $relatedDocumentsBeans = $leadBean->leads_documents_1->getBeans();
            $categoryList = $app_list_strings['dotb_document_category_list'];
            $missing_docs_html = '<ul>';
            //get category of all documents related to the lead in 'docsTrackList' array
            foreach ($relatedDocumentsBeans as $doc) {
                $doc->load_relationship('documents_dotb7_document_tracking_1');
                $relatedDocumentTrackBeans = $doc->documents_dotb7_document_tracking_1->getBeans();
                $add_doc = array();
                foreach ($relatedDocumentTrackBeans as $docTrack) {
                    if ($docTrack->status != 'ok') {
                        //if (!in_array($docTrack->category, $add_doc)) {
                        $missing_docs_html.= '<li>' . $categoryList[$docTrack->category] . '</li>';
                        $add_doc[] = $docTrack->category;
                        $doc_tracking_count++;
                        //}
                    }
                }
            }
            $missing_docs_html.='</ul>';
        }
        if ($doc_tracking_count) {
            if ($templateId == 1) {
                return 'docs_found';
            } else {
                $templateBean = BeanFactory::getBean("EmailTemplates", $templateId);
                $html_body = str_replace('$contact_salutation_text_c', $leadBean->salutation_text_c, $templateBean->body_html);
                $html_body = str_replace('$contact_last_name', $leadBean->last_name, $html_body);
                $html_body = str_replace('$missing_documents', $missing_docs_html, $html_body);
                return $html_body;
            }
        } else {
            return 'no_doc_found';
        }
    }

    public function crifParseEamil(ServiceBase $api, array $args) 
    {
        $templateId = $args["id"];
        $leadId = $args["leadId"];
        $leadBean = BeanFactory::getBean("Leads", $leadId);
        $templateBean = BeanFactory::getBean("EmailTemplates", $templateId);

        $html_body = str_replace('$contact_first_name', $leadBean->first_name, $templateBean->body_html);
        $html_body = str_replace('$contact_last_name', $leadBean->last_name, $html_body);
        $html_body = str_replace('$contact_primary_address_street', $leadBean->primary_address_street, $html_body);
        $html_body = str_replace('$contact_primary_address_postalcode', $leadBean->primary_address_postalcode, $html_body);
        $html_body = str_replace('$contact_primary_address_city', $leadBean->primary_address_city, $html_body);
        return $html_body;
    }

}

?>