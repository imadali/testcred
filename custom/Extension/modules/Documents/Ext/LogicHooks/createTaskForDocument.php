<?php

$hook_array['after_relationship_add'][] = Array(
        2,
        'Create Task when Document is Added to Lead',
        'custom/modules/Documents/createTaskForDocument.php',
        'CreateTaskForDocument',
        'createNewTask'
    );

?>
