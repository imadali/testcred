<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class processRecord {

    public function processContract($bean, $event, $arguments) {
        global $app_list_strings;
        if ($bean->load_relationship('contracts_leads_1')) {
                        $relatedContracts = $bean->contracts_leads_1->getBeans();
                        foreach ($relatedContracts as $contract) {
                            if(isset($app_list_strings['dotb_credit_provider_list'][$contract->provider_id_c]))
                            $bean->contract_bank=$app_list_strings['dotb_credit_provider_list'][$contract->provider_id_c];
                            $bean->contract_paying_date=$contract->paying_date_c;
                            break;
                        }
                    }
    }
}

?>