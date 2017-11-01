<?php

/**
 * CRED-986 : Relation / Promotion of Documents from Lead to Contact
 */
if (isset($_GET['offset'])) {
    echo "<br><b>Copying Documents from Lead Changed to Status 10 via Workflow to Contacts.</b></br></br>";
    $offset = $_GET['offset'];
    if ($offset < 0 || !isset($offset)) {
        echo "Please provide a valid positive offset number";
        exit;
    }
    if (empty($offset)) {
        $offset = 0;
    }

    /*
     * Processing Identifeir
     */
    $processed = false;

    /*
     * Variables for Counts
     */
    $totalCount = 0;
    $offsetCheck = 0;
    $documentsCopied = 0;
    $creditHistoryCopied = 0;
    $partnerCopied = 0;
    $contractCopied = 0;
    $applicationCopied = 0;
    $addressCopied = 0;

    /*
     * Lists for Records to be copied
     */
    $documentstoCopy = array();
    $creditHistoryToCopy = array();
    $partnerToCopy = array();
    $contractToCopy = array();
    $applicationsToCopy = array();
    $addressesToCopy = array();

    /**
     * Fetching All Leads in Status 10-Active
     */
    $query = "SELECT l.id,l.contact_id FROM leads l JOIN leads_cstm lc ON l.id = lc.id_c"
            . "  WHERE l.deleted = 0 AND lc.credit_request_status_id_c IN('10_active','11_closed')"
            . " AND l.date_modified > '2017-06-21 00:00:00'  limit 5000 offset $offset";

    $result = $GLOBALS['db']->query($query);
    while ($row = $GLOBALS["db"]->fetchByAssoc($result)) {

        /**
         * Fetching Documents Related to Lead
         */
        $doc_query = "SELECT leads_documents_1documents_idb FROM leads_documents_1_c"
                . " WHERE leads_documents_1leads_ida = '" . $row['id'] . "' AND deleted = 0";

        $result_doc = $GLOBALS['db']->query($doc_query);
        while ($row_doc = $GLOBALS["db"]->fetchByAssoc($result_doc)) {
            $documentstoCopy[] = $row_doc['leads_documents_1documents_idb'];
        }

        //List of documents to be copied
        $docIds = "'" . implode("','", $documentstoCopy) . "'";

        /**
         * Fetching relation of documents listed with contacts
         */
        $contact_query = "SELECT document_id,contact_id FROM documents_contacts"
                . " WHERE document_id IN(" . $docIds . ") AND contact_id = '" . $row['contact_id'] . "' AND deleted = 0";

        $contact_doc = $GLOBALS['db']->query($contact_query);
        while ($row_contact = $GLOBALS["db"]->fetchByAssoc($contact_doc)) {

            /**
             * Unsetting document from List if present in contact
             */
            if (in_array($row_contact['document_id'], $documentstoCopy)) {
                $index = array_search($row_contact['document_id'], $documentstoCopy);
                unset($documentstoCopy[$index]);
            }
        }

        /**
         * Creating Relation with contacts for missing documents
         */
        if (!empty($documentstoCopy)) {
            $documentstoCopy = array_values($documentstoCopy);
            global $timedate;
            $currenrDateTime = $timedate->getInstance()->nowDb();
            $date = date('Y-m-d H:i:s', strtotime($currenrDateTime));
            foreach ($documentstoCopy as $doc) {
                $inser_query = "INSERT INTO documents_contacts(id,date_modified,deleted,document_id,contact_id)"
                        . " VALUES('" . create_guid() . "','" . $date . "',0,'" . $doc . "','" . $row['contact_id'] . "')";
                $success = $GLOBALS['db']->query($inser_query) ? 1 : 0;
                if ($success == 1) {
                    $documentsCopied += 1;
                }
            }

            $documentstoCopy = array();
            $processed = true;
        }

        /* ------------------------------------------------------ */

        /**
         * Fetching Credit History Related to Lead
         */
        $doc_query = "SELECT leads_dotb5_credit_history_1dotb5_credit_history_idb FROM leads_dotb5_credit_history_1_c"
                . " WHERE leads_dotb5_credit_history_1leads_ida = '" . $row['id'] . "' AND deleted = 0";

        $result_doc = $GLOBALS['db']->query($doc_query);
        while ($row_doc = $GLOBALS["db"]->fetchByAssoc($result_doc)) {
            $creditHistoryToCopy[] = $row_doc['leads_dotb5_credit_history_1dotb5_credit_history_idb'];
        }

        //List of  credit history to be copied
        $credIds = "'" . implode("','", $creditHistoryToCopy) . "'";

        /**
         * Fetching relation of  credit history listed with contacts
         */
        $contact_query = "SELECT dotb5_credit_history_contactscontacts_ida,dotb5_credit_history_contactsdotb5_credit_history_idb"
                . " FROM dotb5_credit_history_contacts_c"
                . " WHERE dotb5_credit_history_contactsdotb5_credit_history_idb IN(" . $credIds . ")"
                . " AND dotb5_credit_history_contactscontacts_ida = '" . $row['contact_id'] . "' AND deleted = 0";

        $contact_doc = $GLOBALS['db']->query($contact_query);
        while ($row_contact = $GLOBALS["db"]->fetchByAssoc($contact_doc)) {

            /**
             * Unsetting credit history from List if present in contact
             */
            if (in_array($row_contact['dotb5_credit_history_contactsdotb5_credit_history_idb'], $creditHistoryToCopy)) {
                $index = array_search($row_contact['dotb5_credit_history_contactsdotb5_credit_history_idb'], $creditHistoryToCopy);
                unset($creditHistoryToCopy[$index]);
            }
        }

        /**
         * Creating Relation with contacts for missing CreditHistory
         */
        if (!empty($creditHistoryToCopy)) {
            $creditHistoryToCopy = array_values($creditHistoryToCopy);

            foreach ($creditHistoryToCopy as $cred) {
                $inser_query = "INSERT INTO dotb5_credit_history_contacts_c(id,date_modified,deleted,dotb5_credit_history_contactscontacts_ida,	dotb5_credit_history_contactsdotb5_credit_history_idb)"
                        . " VALUES('" . create_guid() . "','" . $date . "',0,'" . $row['contact_id'] . "','" . $cred . "')";
                $success = $GLOBALS['db']->query($inser_query) ? 1 : 0;
                if ($success == 1) {
                    $creditHistoryCopied += 1;
                }
            }

            $creditHistoryToCopy = array();
            $processed = true;
        }

        /* ------------------------------------------------------ */

        /**
         * Fetching Partner Related to Lead
         */
        $doc_query = "SELECT leads_contacts_1contacts_idb FROM leads_contacts_1_c"
                . " WHERE leads_contacts_1leads_ida = '" . $row['id'] . "' AND deleted = 0";

        $result_doc = $GLOBALS['db']->query($doc_query);
        while ($row_doc = $GLOBALS["db"]->fetchByAssoc($result_doc)) {
            $partnerToCopy[] = $row_doc['leads_contacts_1contacts_idb'];
        }

        //List of  partners to be copied
        $credIds = "'" . implode("','", $partnerToCopy) . "'";

        /**
         * Fetching relation of  partners listed with contacts
         */
        $contact_query = "SELECT contacts_contacts_1contacts_ida,contacts_contacts_1contacts_idb"
                . " FROM contacts_contacts_1_c"
                . " WHERE contacts_contacts_1contacts_idb IN(" . $credIds . ")"
                . " AND contacts_contacts_1contacts_ida = '" . $row['contact_id'] . "' AND deleted = 0";

        $contact_doc = $GLOBALS['db']->query($contact_query);
        while ($row_contact = $GLOBALS["db"]->fetchByAssoc($contact_doc)) {

            /**
             * Unsetting partners from List if present in contact
             */
            if (in_array($row_contact['contacts_contacts_1contacts_idb'], $partnerToCopy)) {
                $index = array_search($row_contact['contacts_contacts_1contacts_idb'], $partnerToCopy);
                unset($partnerToCopy[$index]);
            }
        }

        /**
         * Creating Relation with contacts for missing partners
         */
        if (!empty($partnerToCopy)) {
            $partnerToCopy = array_values($partnerToCopy);

            foreach ($partnerToCopy as $cred) {
                $inser_query = "INSERT INTO contacts_contacts_1_c(id,date_modified,deleted,contacts_contacts_1contacts_ida,contacts_contacts_1contacts_idb)"
                        . " VALUES('" . create_guid() . "','" . $date . "',0,'" . $row['contact_id'] . "','" . $cred . "')";
                $success = $GLOBALS['db']->query($inser_query) ? 1 : 0;
                if ($success == 1) {
                    $partnerCopied += 1;
                }
            }

            $partnerToCopy = array();
            $processed = true;
        }

        /* ------------------------------------------------------ */

        /**
         * Fetching Applications Related to Lead
         */
        $doc_query = "SELECT leads_opportunities_1opportunities_idb FROM leads_opportunities_1_c"
                . " WHERE leads_opportunities_1leads_ida = '" . $row['id'] . "' AND deleted = 0";

        $result_doc = $GLOBALS['db']->query($doc_query);
        while ($row_doc = $GLOBALS["db"]->fetchByAssoc($result_doc)) {
            $applicationsToCopy[] = $row_doc['leads_opportunities_1opportunities_idb'];
        }

        //List of  Applications to be copied
        $credIds = "'" . implode("','", $applicationsToCopy) . "'";

        /**
         * Fetching relation of  Applications listed with contacts
         */
        $contact_query = "SELECT contact_id,opportunity_id"
                . " FROM opportunities_contacts"
                . " WHERE opportunity_id IN(" . $credIds . ")"
                . " AND contact_id = '" . $row['contact_id'] . "' AND deleted = 0";

        $contact_doc = $GLOBALS['db']->query($contact_query);
        while ($row_contact = $GLOBALS["db"]->fetchByAssoc($contact_doc)) {

            /**
             * Unsetting Applications from List if present in contact
             */
            if (in_array($row_contact['opportunity_id'], $applicationsToCopy)) {
                $index = array_search($row_contact['opportunity_id'], $applicationsToCopy);
                unset($applicationsToCopy[$index]);
            }
        }

        /**
         * Creating Relation with contacts for missing Applications
         */
        if (!empty($applicationsToCopy)) {
            $applicationsToCopy = array_values($applicationsToCopy);

            foreach ($applicationsToCopy as $cred) {
                $inser_query = "INSERT INTO opportunities_contacts(id,contact_id,opportunity_id,contact_role,date_modified,deleted)"
                        . " VALUES('" . create_guid() . "','" . $row['contact_id'] . "','" . $cred . "',NULL,'" . $date . "',0)";
                $success = $GLOBALS['db']->query($inser_query) ? 1 : 0;
                if ($success == 1) {
                    $applicationCopied += 1;
                }
            }

            $applicationsToCopy = array();
            $processed = true;
        }

        /* ------------------------------------------------------ */

        /**
         * Fetching Addresses Related to Lead
         */
        $doc_query = "SELECT leads_dot10_addresses_1dot10_addresses_idb FROM leads_dot10_addresses_1_c"
                . " WHERE leads_dot10_addresses_1leads_ida = '" . $row['id'] . "' AND deleted = 0";

        $result_doc = $GLOBALS['db']->query($doc_query);
        while ($row_doc = $GLOBALS["db"]->fetchByAssoc($result_doc)) {
            $addressesToCopy[] = $row_doc['leads_dot10_addresses_1dot10_addresses_idb'];
        }

        //List of  Addresses to be copied
        $credIds = "'" . implode("','", $addressesToCopy) . "'";

        /**
         * Fetching relation of  Addresses listed with contacts
         */
        $contact_query = "SELECT contacts_dot10_addresses_1contacts_ida,contacts_dot10_addresses_1dot10_addresses_idb"
                . " FROM contacts_dot10_addresses_1_c"
                . " WHERE contacts_dot10_addresses_1dot10_addresses_idb IN(" . $credIds . ")"
                . " AND contacts_dot10_addresses_1contacts_ida = '" . $row['contact_id'] . "' AND deleted = 0";

        $contact_doc = $GLOBALS['db']->query($contact_query);
        while ($row_contact = $GLOBALS["db"]->fetchByAssoc($contact_doc)) {

            /**
             * Unsetting Addresses from List if present in contact
             */
            if (in_array($row_contact['contacts_dot10_addresses_1dot10_addresses_idb'], $addressesToCopy)) {
                $index = array_search($row_contact['contacts_dot10_addresses_1dot10_addresses_idb'], $addressesToCopy);
                unset($addressesToCopy[$index]);
            }
        }

        /**
         * Creating Relation with contacts for missing Addresses
         */
        if (!empty($addressesToCopy)) {
            $addressesToCopy = array_values($addressesToCopy);

            foreach ($addressesToCopy as $cred) {
                $inser_query = "INSERT INTO contacts_dot10_addresses_1_c(id,date_modified,deleted,contacts_dot10_addresses_1contacts_ida,contacts_dot10_addresses_1dot10_addresses_idb)"
                        . " VALUES('" . create_guid() . "','" . $date . "',0,'" . $row['contact_id'] . "','" . $cred . "')";
                $success = $GLOBALS['db']->query($inser_query) ? 1 : 0;
                if ($success == 1) {
                    $addressCopied += 1;
                }
            }

            $addressesToCopy = array();
            $processed = true;
        }

        /* ------------------------------------------------------ */

        /**
         * Fetching Contracts Related to Lead
         */
        $doc_query = "SELECT contracts_leads_1contracts_ida FROM contracts_leads_1_c"
                . " WHERE contracts_leads_1leads_idb = '" . $row['id'] . "' AND deleted = 0";

        $result_doc = $GLOBALS['db']->query($doc_query);
        while ($row_doc = $GLOBALS["db"]->fetchByAssoc($result_doc)) {
            $contractToCopy[] = $row_doc['contracts_leads_1contracts_ida'];
        }

        //List of  Contracts to be copied
        $credIds = "'" . implode("','", $contractToCopy) . "'";

        /**
         * Fetching relation of  Contracts listed with contacts
         */
        $contact_query = "SELECT contact_id,contract_id"
                . " FROM contracts_contacts"
                . " WHERE contract_id IN(" . $credIds . ")"
                . " AND contact_id = '" . $row['contact_id'] . "' AND deleted = 0";

        $contact_doc = $GLOBALS['db']->query($contact_query);
        while ($row_contact = $GLOBALS["db"]->fetchByAssoc($contact_doc)) {

            /**
             * Unsetting Contracts from List if present in contact
             */
            if (in_array($row_contact['contract_id'], $contractToCopy)) {
                $index = array_search($row_contact['contract_id'], $contractToCopy);
                unset($contractToCopy[$index]);
            }
        }

        /**
         * Creating Relation with contacts for missing Contracts
         */
        if (!empty($contractToCopy)) {
            $contractToCopy = array_values($contractToCopy);

            foreach ($contractToCopy as $cred) {
                $inser_query = "INSERT INTO  contracts_contacts(id,contact_id,contract_id,date_modified,deleted)"
                        . " VALUES('" . create_guid() . "','" . $row['contact_id'] . "','" . $cred . "','" . $date . "',0)";
                $success = $GLOBALS['db']->query($inser_query) ? 1 : 0;

                if ($success == 1) {
                    $contractCopied += 1;
                }
            }

            $contractToCopy = array();
            $processed = true;
        }

        if ($processed == true) {
            $totalCount += 1;
            $processed = false;
        }
        if ($totalCount > 0) {
            $offsetCheck += 1;
        }
    }


    echo "<br> Records from <b> $totalCount Leads </b> have been moved to related Contacts.<br>";
    echo "<br> $documentsCopied Documents Records have been copied.<br>";
    echo "<br> $creditHistoryCopied Credit History Records have been copied.<br>";
    echo "<br> $partnerCopied Partner Records have been copied.<br>";
    echo "<br> $applicationCopied Application Records have been copied.<br>";
    echo "<br> $addressCopied Address Records have been copied.<br>";
    echo "<br> $contractCopied Contract Records have been copied.<br>";
    if ($offsetCheck) {
        $new_offset = $offset + 5000;
        echo "<br>Please change offset to $new_offset<br>";
    } else {
        echo "<br>Script has been completed successfully!";
    }
}