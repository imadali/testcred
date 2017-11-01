<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

/**
** update contact with the contact fields CRED-740
**/
class updateContactWithContractFields 
{
    public function updateContact($bean, $event, $arguments)
    {
        if(!empty($bean->contracts_leads_1leads_idb)) {
            $leadBean = BeanFactory::getBean('Leads', $bean->contracts_leads_1leads_idb);
            $contact_id = $leadBean->contact_id;
            $contactBean = BeanFactory::getBean('Contacts', $contact_id);
            $contactBean->provider = $bean->provider_id_c;
            $contactBean->credit_amount = $bean->credit_amount_c;
            $contactBean->duration = $bean->credit_duration_c;
            $contactBean->provider_contract_number = $bean->provider_contract_no;
            $contactBean->save();	
	    }
    }
}

?>