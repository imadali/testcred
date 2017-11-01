<?php

if (!defined('sugarEntry'))
    define('sugarEntry', true);
// chdir(realpath(dirname(__FILE__)));
require_once('include/entryPoint.php');
global $timedate, $app_list_strings;
$sql = "SELECT id,credit_request_status_id_c FROM leads JOIN leads_cstm ON id=id_c where deleted=0";
$result = $GLOBALS["db"]->query($sql);
echo '<pre>';
echo "Setting Task's Lead status";
$leads_count = 1;
$tasks_count = 1;
while ($lead = $GLOBALS["db"]->fetchByAssoc($result)) {
    $status = trim($lead['credit_request_status_id_c']);
    $id = $lead['id'];
    $task_sql = "SELECT id FROM tasks WHERE parent_type='Leads' AND parent_id='$id' AND deleted=0";
    $task_result = $GLOBALS["db"]->query($task_sql);
    while ($task = $GLOBALS["db"]->fetchByAssoc($task_result)) {
        $task_id=$task['id'];
        $GLOBALS['db']->query("UPDATE tasks_cstm SET lead_status_c='$status' WHERE id_c='$task_id'");
        $task_id=null;
        $tasks_count++;
    }
    $id=null;
    $leads_count++;
}
echo "<br> Total Leads Founds = $leads_count";
echo "<br> Total Tasks Founds = $tasks_count";
exit;
