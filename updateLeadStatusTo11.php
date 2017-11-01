<?php

/**
 * CRED-970 : Promotion of Leads in Status 00 to 11 after 30 days
 */
if (isset($_GET['offset'])) {
    $offset = $_GET['offset'];
    if ($offset < 0 || !isset($offset)) {
        echo "Please provide a valid positive offset number";
        exit;
    }
    if (empty($offset)) {
        $offset = 0;
    }

    global $timedate, $current_user;
    $current_date = new DateTime($timedate->nowDb());
    $current_date = $current_date->format('Y-m-d');

    $leads_query = "SELECT
    l.id
    FROM
	   leads l 
	   LEFT JOIN
		  leads_cstm lcstm 
		  ON l.id = lcstm.id_c 
    WHERE
	   lcstm.credit_request_status_id_c = '00_pendent_geschlossen'
	   AND lcstm.closing_date_c IS NULL 
	   AND l.deleted = 0 limit 5000 offset $offset";

    $leads_result = $GLOBALS['db']->query($leads_query);
    $lead_changed_status_count = 0;
    $lead_closing_date_count = 0;

    while ($lead_row = $GLOBALS["db"]->fetchByAssoc($leads_result)) {
        // Query to get date status was changed to 00
        $lead_status_change_query = "SELECT l_audit.date_created 
            FROM leads_audit l_audit
            WHERE l_audit.field_name = 'credit_request_status_id_c' 
            AND l_audit.after_value_string = '00_pendent_geschlossen'  
            AND l_audit.parent_id = '" . $lead_row['id'] ."' ORDER BY l_audit.date_created DESC LIMIT 0,1";

        $lead_status_change_result = $GLOBALS['db']->query($lead_status_change_query);
        $lead_status_change_row = $GLOBALS["db"]->fetchByAssoc($lead_status_change_result);

        // adding 30 days to the date status was changed to 00
        $date_status_changed = $lead_status_change_row['date_created'];
        $status_changed_date = new DateTime($date_status_changed);
        $status_changed_date->add(new DateInterval('P30D'));
        $status_changed_date = $status_changed_date->format('Y-m-d');
		
        // if date is less than today change status to 11 and update closing date
        if($status_changed_date < $current_date){
            $leads_cstm_query = "UPDATE leads_cstm SET closing_date_c='$status_changed_date', credit_request_status_id_c = '11_closed'  WHERE id_c='".$lead_row['id']."'";

            $GLOBALS['db']->query($leads_cstm_query);

            $CurrenrDateTime = $timedate->getInstance()->nowDb();
            $date = date('Y-m-d H:i:s', strtotime($CurrenrDateTime));
            $audit_id = create_guid();
            $audit_insert = 'INSERT INTO leads_audit (id, parent_id, date_created, data_type, created_by, field_name, before_value_string, after_value_string) VALUES ("' . $audit_id . '", "' . $lead_row['id'] . '","' . $date . '","enum", "' . $current_user->id . '", "credit_request_status_id_c","00_pendent_geschlossen","11_closed")';

            $GLOBALS['db']->query($audit_insert);
            $lead_changed_status_count++;
        } else {
            // if date is greater than today update closing date
            $leads_cstm_query = "UPDATE leads_cstm SET closing_date_c='$status_changed_date' WHERE id_c='".$lead_row['id']."'";

            $GLOBALS['db']->query($leads_cstm_query);
            $lead_closing_date_count++;
        }
    }

    echo $lead_changed_status_count  . " leads status changed to 11 Closed<br><br>";
    echo $lead_closing_date_count  . " leads closing date updated. They will be closed in future<br><br>";

    if ($lead_changed_status_count > 0 || $lead_closing_date_count > 0) {
        $new_offset = $offset + 5000;
        echo "<br>Please change offset to $new_offset<br>";
    } else {
        echo "<br>Script has been completed successfully!";
    }
}
?>