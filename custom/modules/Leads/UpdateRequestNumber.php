<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class UpdateRequestNumber {

    function autoIncrement($bean, $event, $arguments) {
        if (!isset($bean->fetched_row['id'])) {
            $newcount = '';
            $query = "SELECT MAX(credit_request_number_c) as maxcount FROM leads_cstm "
                    . " JOIN leads ON leads_cstm.id_c = leads.id "
                    . " AND leads.deleted = 0";
            $result = $GLOBALS['db']->query($query);
            $row = $GLOBALS['db']->fetchByAssoc($result);

            $newcount = $row['maxcount'] + 1;
            $bean->credit_request_number_c = $newcount;
        }
    }

}
