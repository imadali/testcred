<?php

class linkBank {

    function setProvierAsBank($bean, $event, $arguments) {
        if (!empty($bean->provider_id_c)) {
            global $app_list_strings, $timedate;
            $provider_list = $app_list_strings['dotb_credit_provider_list'];
            $provider = $provider_list[$bean->provider_id_c];
            $bank = new Account();
            $bank->retrieve_by_string_fields(array('name' => $provider));
            if (empty($bank->id)) {
                if ($bean->account_id) {
                    $bank = new Account();
                    $bank->retrieve($bean->account_id);
                    $bank->load_relationship('opportunities');
                    $bank->opportunities->delete($bean->id);
                }
            } else {
                $bank->load_relationship('opportunities');
                $bank->opportunities->add($bean->id);
            }
        }
    }

}

?>