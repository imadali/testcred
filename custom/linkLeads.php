<?php

if (!defined('sugarEntry'))
    define('sugarEntry', true);
require_once('include/entryPoint.php');
$GLOBALS['db']->query("truncate table leads_leads_1_c");
$sql = "SELECT l.id lead_id, l.contact_id contact_id FROM leads l JOIN contacts c ON l.contact_id=c.id where l.deleted=0 AND l.contact_id IS NOT NULL AND l.contact_id !='' AND c.deleted=0";
$result = $GLOBALS["db"]->query($sql);
while ($row = $GLOBALS["db"]->fetchByAssoc($result)) {
    $main_lead_id = $row['lead_id'];
    $contact_id = $row['contact_id'];
    $leads_sql = "SELECT id FROM leads WHERE deleted=0 AND contact_id ='$contact_id' AND id!='$main_lead_id'";
    $leads_result = $GLOBALS["db"]->query($leads_sql);
    while ($lead = $GLOBALS["db"]->fetchByAssoc($leads_result)) {
        $related_lead_id = $lead['id'];
        $new_id = create_guid();
        $date = date("Y-m-d H:i:s");
        $response = $GLOBALS['db']->query("INSERT INTO leads_leads_1_c VALUES('$new_id','$date',0,'$main_lead_id','$related_lead_id')");
        if (!$response)
            $response = $GLOBALS['db']->query("INSERT INTO leads_leads_1_c VALUES('$new_id','$date',0,'$main_lead_id','$related_lead_id')");
        if (!$response)
            $response = $GLOBALS['db']->query("INSERT INTO leads_leads_1_c VALUES('$new_id','$date',0,'$main_lead_id','$related_lead_id')");
        if (!$response)
            $response = $GLOBALS['db']->query("INSERT INTO leads_leads_1_c VALUES('$new_id','$date',0,'$main_lead_id','$related_lead_id')");
        if (!$response)
            $response = $GLOBALS['db']->query("INSERT INTO leads_leads_1_c VALUES('$new_id','$date',0,'$main_lead_id','$related_lead_id')");
        if (!$response)
            $response = $GLOBALS['db']->query("INSERT INTO leads_leads_1_c VALUES('$new_id','$date',0,'$main_lead_id','$related_lead_id')");
    }
}
echo "<br> Process Completed successfully";
