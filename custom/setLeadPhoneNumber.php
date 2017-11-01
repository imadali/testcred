<?php

if (!defined('sugarEntry'))
    define('sugarEntry', true);
// chdir(realpath(dirname(__FILE__)));
require_once('include/entryPoint.php');
$fields = array('phone_other', 'phone_work', 'phone_mobile');
echo '<pre>';
echo "Setting Leads Phone numbers";
$leads_count = 0;
$lead_found = array();
foreach ($fields as $key => $field) {
    $sql = "SELECT id FROM leads leads JOIN leads_cstm ON id=id_c WHERE (credit_request_status_id_c='10_active' OR credit_request_status_id_c='11_closed') AND  $field LIKE '%@%' AND  deleted=0";
    $result = $GLOBALS["db"]->query($sql);
    while ($lead = $GLOBALS["db"]->fetchByAssoc($result)) {
        $id = $lead['id'];
        echo "<br>UPDATE leads SET $field = '' WHERE id='$id'";
        $GLOBALS['db']->query("UPDATE leads SET $field = '' WHERE id='$id'");
        if (!in_array($id, $lead_found)) {
            $lead_found[] = $id;
            $leads_count++;
        }
    }
}
echo "<br> Total Leads UPDATED = $leads_count";
exit;
