<?php


$dictionary['Task']['fields']['tasks_audit_custom'] = array(
    'name' => 'tasks_audit_custom',
    'type' => 'link',
    'relationship' => 'custom_tasks_audit_tasks',
    'module' => 'Tasks_Audit',
    'bean_name' => 'Tasks_Audit',
    'source' => 'non-db',
    'vname' => 'LBL_TASKS_AUDIT_CUSTOM',
);

?>