<?php

if (!defined('sugarEntry'))
    define('sugarEntry', true);
// chdir(realpath(dirname(__FILE__)));
require_once('include/entryPoint.php');
global $timedate, $app_list_strings;
$sql = "SELECT id FROM contacts where deleted=0";
$result = $GLOBALS["db"]->query($sql);
echo "Seting Assigned User in Last Leads from Contacts";
$contact_count = 1;
while ($contact = $GLOBALS["db"]->fetchByAssoc($result)) {
    $contact_id = $contact['id'];
        $sql = "SELECT id,assigned_user_id, MAX(date_entered) as date_entered FROM leads where contact_id='$contact_id' AND deleted=0 and status!='11_closed' and date_entered=(SELECT MAX(date_entered) FROM leads where contact_id='$contact_id' AND deleted=0 and status!='11_closed')";
        $qresult = $GLOBALS["db"]->query($sql);
        $lead = $GLOBALS["db"]->fetchByAssoc($qresult);
        $lead_id=$lead['id'];
        $assigned_user_id = $lead['assigned_user_id'];
        if (!empty($lead_id) && !empty($assigned_user_id)) {
            $GLOBALS['db']->query("UPDATE contacts SET assigned_user_id='$assigned_user_id' WHERE id='$contact_id'");
            $contact_count++;
        }
}
echo "<br> $contact_count contacts were updated";
exit;
