<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class contactEmailAddresses {

    function syncEmailAddresses($bean, $event, $arguments) {
         require_once 'custom/modules/Leads/syncLead.php';
        if (syncLead::$triggeredFromLead == true)
            return true;
        $sea = new SugarEmailAddress;
        $contact_email_addresses = $sea->getAddressesByGUID($bean->id, $bean->module_name);
        $opt_emails = '';
        foreach ($contact_email_addresses as $contact_email_address) {
            if ($contact_email_address['email_address'] != $bean->email1) {
                $opt_emails.=$contact_email_address['email_address'] . ', ';
            }
        }
        $opt_emails=trim($opt_emails);
        $opt_emails=rtrim($opt_emails, ',');
        $bean->opt_emails = $opt_emails;
    }

}
