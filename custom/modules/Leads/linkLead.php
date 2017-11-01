<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class linkLead {
    /*
     * When a lead is added in the system then this lead must be linked to all the leads related to its contact
     */

    public static $newLead = false;
    public static $old_contact_id = null;

    public function checkIsLeadNew($bean, $event, $arguments) {
        self::$old_contact_id = $bean->fetched_row['contact_id'];
        if (!isset($bean->fetched_row['id']))
            self::$newLead = true;
    }

    function linkLeadToContactLeads($bean, $event, $arguments) {
        if (self::$old_contact_id != $bean->contact_id && !empty($bean->contact_id)) {
            $relatedContact = BeanFactory::getBean("Contacts", $bean->contact_id);
            if ($relatedContact->load_relationship("leads")) {
                $contactLeads = $relatedContact->leads->getBeans();
                if ($bean->load_relationship("leads_leads_1")) {
                    foreach ($contactLeads as $contactLead) {
                        if ($bean->id != $contactLead->id)
                            $bean->leads_leads_1->add($contactLead->id);
                    }
                }
            }
        }

        /*
         * If related contact is unlinked from Lead
         */
        if (!empty(self::$old_contact_id) && empty($bean->contact_id)) {
            $relatedContact = BeanFactory::getBean("Contacts", self::$old_contact_id);
            if ($relatedContact->load_relationship("leads")) {
                $contactLeads = $relatedContact->leads->getBeans();
                if ($bean->load_relationship("leads_leads_1")) {
                    foreach ($contactLeads as $contactLead) {
                        if ($bean->id != $contactLead->id) {
                            $bean->leads_leads_1->delete($contactLead->id);
                        }
                    }
                }
            }
        }
    }

    function unLinkLeadFromContact($bean, $event, $arguments) {
        if ($arguments['link'] == 'contacts' && $arguments['related_module'] == 'Contacts' && !empty($arguments['related_id']) && $arguments['relationship'] == "contact_leads") {
            $relatedContact = BeanFactory::getBean("Contacts", $arguments['related_id']);
            if ($relatedContact->load_relationship("leads")) {
                $contactLeads = $relatedContact->leads->getBeans();
                if ($bean->load_relationship("leads_leads_1")) {
                    foreach ($contactLeads as $contactLead) {
                        if ($bean->id != $contactLead->id) {
                            $bean->leads_leads_1->delete($contactLead->id);
                        }
                    }
                }
            }
        }
    }

}

?>