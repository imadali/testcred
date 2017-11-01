<?php

$hook_array['before_save'][] = Array(
    1,
    'Assignation automatique selon la langue et les jours de travail',
    'custom/modules/Leads/autoAssign.php',
    'autoAssign',
    'autoAssignUser'
);

?>