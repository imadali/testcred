<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class UpdateRequestNumberContact {

    function autoIncrement($bean, $event, $arguments) {
       if (!isset($bean->fetched_row['id'])) {
            $newcount = '';
            $query = "SELECT MAX(credit_request_number) as maxcount FROM contacts_cstm "
                    . " JOIN contacts ON contacts_cstm.id_c = contacts.id "
                    . " AND contacts.deleted = 0";
            $result = $GLOBALS['db']->query($query);
            $row = $GLOBALS['db']->fetchByAssoc($result);

            $newcount = $row['maxcount'] + 1;
            $bean->credit_request_number = $newcount;
        }
    }

}
