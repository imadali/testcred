<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once ('include/TimeDate.php');

class addPartner {

    protected static $fetchedRow = array();

    /**
     * Called as before_save logic hook to grab the fetched_row values
     */
    public function addPartnerToContact($bean, $event, $arguments) {
        if(isset($_REQUEST['__sugar_url'])) {
            $check = explode('/', $_REQUEST['__sugar_url']);
            if ($arguments['link'] == 'leads_contacts_1' && $arguments['related_module'] == 'Contacts' && !empty($arguments['related_id']) && !empty($bean->contact_id) && $check[1] == "Leads") {
                $contact = new Contact();
                $contact->retrieve($bean->contact_id);
                $contact->load_relationship('contacts_contacts_1');
                $contact->contacts_contacts_1->add($arguments['related_id']);
                //$GLOBALS['log']->fatal("The Lead's Partner was added to the Contact where Lead : $bean->id and Contact: $bean->contact_id");
            }
        }
    }

}
