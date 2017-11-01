<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class setClosingDate {

    function setClosingDateForLead($bean, $event, $arguments) {
        global $timedate;
        if ($bean->fetched_row['credit_request_status_id_c'] != $bean->credit_request_status_id_c && $bean->credit_request_status_id_c == '00_pendent_geschlossen') {
			// status 00 is set, set the closing_date_c
			$closing_date = new DateTime($timedate->nowDb());
            $closing_date->add(new DateInterval('P30D'));
            $closing_date = $closing_date->format('Y-m-d');
			$bean->closing_date_c = $closing_date;
        }
    }

}
