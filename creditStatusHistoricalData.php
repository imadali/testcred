<?php

/**
 * CRED-950 : Script for historical data "Credit Status" tab in Contacts
 */
if (isset($_GET['offset'])) {
    echo "<br><b>Updating values in Credit Status tab in Contacts from Contracts.</b></br></br>";
    $offset = $_GET['offset'];
    if ($offset < 0 || !isset($offset)) {
        echo "Please provide a valid positive offset number";
        exit;
    }
    if (empty($offset)) {
        $offset = 0;
    }

    $totalCount = 0;
    $modified = false;
    $nextPhase = true;
    $query = "SELECT id FROM contacts WHERE deleted = 0 limit 5000 offset $offset";
    $result = $GLOBALS['db']->query($query);

    while ($row = $GLOBALS["db"]->fetchByAssoc($result)) {
        $contact = BeanFactory::getBean("Contacts", $row['id']);

        $query_lead = "SELECT ls.id, lc.credit_request_status_id_c FROM leads ls LEFT JOIN leads_cstm lc ON ls.id = lc.id_c  WHERE ls.contact_id = '" . $row['id'] . "' AND ls.deleted = 0 ORDER BY ls.date_entered DESC";
        $result_lead = $GLOBALS['db']->query($query_lead);
        while ($row_lead = $GLOBALS["db"]->fetchByAssoc($result_lead)) {
            /**
             * Checking for Contract if only one Lead
             */
            $modified = false;
            if ($result_lead->num_rows == 1) {
                
                if ($row_lead['credit_request_status_id_c'] == '10_active') {
                    $contract_id = getContract($row_lead['id']);
                    $modified = analyzeContract($row['id'], $contract_id, $contact);
                }
            } else if ($result_lead->num_rows > 1) {
                /**
                 * Checking for Contract if multiple Leads
                 */

                if ($row_lead['credit_request_status_id_c'] == '10_active') {
                    $contract_id = getContract($row_lead['id']);
                    $modified = analyzeContract($row['id'], $contract_id, $contact);
                    break;
                }
            }
        }
        /**
         * Idenfifying if no Contract was found and search in Contact is needed.
         */
        if ($modified == true) {
            $totalCount += 1;
            $nextPhase = false;
            $modified = false;
        }

        /**
         * Searching for Contract in Parent Contact
         */
        $modified = false;
        if ($nextPhase == true) {
            $contact_join = "SELECT * FROM contracts_contacts"
                    . " WHERE contact_id = '" . $row['id'] . "' AND deleted = 0 ORDER BY date_modified DESC LIMIT 1";

            $contact_result = $GLOBALS['db']->query($contact_join);
            $contracts = $GLOBALS["db"]->fetchByAssoc($contact_result);
            $modified = analyzeContract($row['id'], $contracts['contract_id'], $contact);
            if ($modified == true) {
                $totalCount += 1;
            }
        }
    }

    echo "<br> $totalCount Contacts have been updated.<br>";
    if ($totalCount) {
        $new_offset = $offset + 5000;
        echo "<br>Please change offset to $new_offset<br>";
    } else {
        echo "<br>Script has been completed successfully!";
    }
}

/**
 *  Funtion to find the contract related to a Lead.
 */
function getContract($lead_id) {
    $contract_join = "SELECT contracts_leads_1contracts_ida, contracts_leads_1leads_idb"
            . " FROM contracts_leads_1_c"
            . " WHERE contracts_leads_1leads_idb = '$lead_id' AND deleted = 0";
    $contract_result = $GLOBALS['db']->query($contract_join);
    return $GLOBALS["db"]->fetchByAssoc($contract_result)['contracts_leads_1contracts_ida'];
}

/**
 *  Funtion to get values from Contract and Update to Contacts.
 */
function analyzeContract($contact_id, $contract_id, $contact) {
    $status = '';
    if (!empty($contract_id)) {
        $contract_query = "SELECT date_entered, credit_amount_c, provider_id_c, credit_duration_c, provider_contract_no "
                . " FROM contracts JOIN contracts_cstm  ON contracts.id = contracts_cstm.id_c"
                . " WHERE contracts.id = '$contract_id' AND deleted = 0";

        $contract_rezult = $GLOBALS['db']->query($contract_query);
        $contract = $GLOBALS["db"]->fetchByAssoc($contract_rezult);

        if (empty($contact->credit_amount) && empty($contact->duration) && empty($contact->provider) && empty($contact->provider_contract_number)) {
            $credit_amount = 0;
            if (!empty($contract['credit_amount_c'])) {
                $credit_amount = $contract['credit_amount_c'];
            }
            $duration = 0;
            if (!empty($contract['credit_duration_c'])) {
                $duration = $contract['credit_duration_c'];
            }

            $insert_query = "UPDATE contacts SET credit_amount = " . $credit_amount . ", duration = " . $duration
                    . ", provider = '" . $contract['provider_id_c'] . "', provider_contract_number = '" . $contract['provider_contract_no']
                    . "', profile_id = '" . $contact->id . "' WHERE id = '$contact_id'";

            $GLOBALS['db']->query($insert_query);
            insertRecordInAuditTable('Contacts', 'profile_id', '', $contact->id, $contact_id, 'varchar');
            insertRecordInAuditTable('Contacts', 'credit_amount', '', $contract['credit_amount_c'], $contact_id, 'currency');
            insertRecordInAuditTable('Contacts', 'duration', '', $contract['credit_duration_c'], $contact_id, 'int');
            insertRecordInAuditTable('Contacts', 'provider', '', $contract['provider_id_c'], $contact_id, 'enum');
            insertRecordInAuditTable('Contacts', 'provider_contract_number', '', $contract['provider_contract_no'], $contact_id, 'varchar');
            return true;
        } else {
            $profile_query = "UPDATE contacts SET profile_id = '" . $contact->id . "' WHERE id = '$contact_id'";
            $GLOBALS['db']->query($profile_query);
            insertRecordInAuditTable('Contacts', 'profile_id', '', $contact->id, $contact_id, 'varchar');
        }

        return false;
    }
}

?>