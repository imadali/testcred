<?php

if (!defined('sugarEntry'))
    define('sugarEntry', true);
// chdir(realpath(dirname(__FILE__)));
require_once('include/entryPoint.php');
global $timedate, $sugar_config;
$myfile = fopen("custom/handlingNotes.txt", "a") or die("Unable to open file handlingNotes.txt!");
$offset=$_GET['offset'];
if($offset< 0 || !isset($offset)){
    echo "Please provide a valid positive offset number";
    exit;
}
if(empty($offset))
    $offset=0;
if($offset==0){
    fwrite($myfile, "Contact ID; Lead ID; Notes IDs;\n");
}
$sql = "SELECT id FROM contacts where deleted=0 limit 5000 OFFSET $offset";
$result = $GLOBALS["db"]->query($sql);
$total_contact_count=0;
$contact_count=0;
while ($contact = $GLOBALS["db"]->fetchByAssoc($result)) {
    $total_contact_count++;
    $count_contact=false;
    $contact_id = $contact['id'];
    $leads_sql = "SELECT id,date_modified,closing_date_c FROM leads l join leads_cstm lc ON l.id=lc.id_c where l.deleted=0 AND  l.contact_id='$contact_id' AND (lc.credit_request_status_id_c='10_active' OR lc.credit_request_status_id_c='11_closed') ORDER BY date_modified ASC";
    $leads_result = $GLOBALS["db"]->query($leads_sql);
    while ($lead = $GLOBALS["db"]->fetchByAssoc($leads_result)) {
        $lead_id = $lead['id'];
        $lead_close_date = $lead['date_modified'];
//        if (!empty($lead['closing_date_c'])) {
//            $lead_close_date = $lead['closing_date_c'] . " 00:00:00";
//        }
        $notes_sql = "SELECT id from notes where deleted=0 AND contact_id='$contact_id' AND date_entered <= '$lead_close_date' AND date_entered < '2016-11-27 00:00:00'";
        $notes_result = $GLOBALS["db"]->query($notes_sql);
        $notes_exist=false;
        $notes_ids='';
        while ($note = $GLOBALS["db"]->fetchByAssoc($notes_result)) {
            $note_id = $note['id'];
            $notes_ids="$contact_id; $lead_id; $note_id;\n";
            fwrite($myfile, $notes_ids);
            $notes_exist=true;
        }
        if($notes_exist){
        $count_contact=true;
        $notes_sql = "UPDATE notes set parent_type='Leads',parent_id='$lead_id',contact_id='' where deleted=0 AND contact_id='$contact_id' AND date_entered <= '$lead_close_date' AND date_entered < '2016-11-27 00:00:00'";
        $GLOBALS["db"]->query($notes_sql);
        fwrite($myfile, "\n");
        }

    }
    if($count_contact)
    $contact_count++;
}
fclose($myfile);
if($total_contact_count==0){
    echo "<br>Script has been completed successfully! Please check the logs in file handlingNotes.txt located in custom directory";
}else{
    $new_offset=$offset+5000;
echo "<br>Notes have been moved from $contact_count contacts to leads. Please change offset to $new_offset";
}
exit;
