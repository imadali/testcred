<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once ('include/TimeDate.php');

class addPartnerToLead {

    protected static $fetchedRow = array();

    /**
     * Called as before_save logic hook to grab the fetched_row values
     */
    public function add($bean, $event, $arguments) {
        if (isset($_REQUEST['__sugar_url'])) {
            $check = explode('/', $_REQUEST['__sugar_url']);
            if ($arguments['link'] == 'contacts_contacts_1' && $arguments['related_module'] == 'Contacts' && !empty($arguments['related_id']) && $check[1] == "Contacts") {
                global $timedate;
                $partner_id = $arguments['id'];
                $contact_id = $arguments['related_id'];
                $q = "SELECT leads.id id,leads_cstm.credit_request_status_id_c status FROM leads JOIN leads_cstm ON leads.id=leads_cstm.id_c WHERE leads.deleted = 0 AND leads.contact_id='$contact_id' AND leads_cstm.credit_request_status_id_c!='11_closed' ";
                $q_result = $GLOBALS['db']->query($q);
                while ($lead = $GLOBALS['db']->fetchByAssoc($q_result)) {
                    $leadobj = new Lead();
                    $leadobj->retrieve($lead['id']);
                    $leadobj->load_relationship('leads_contacts_1');
                    $leadobj->leads_contacts_1->add($partner_id);
                }
            }
        }
    }

}
