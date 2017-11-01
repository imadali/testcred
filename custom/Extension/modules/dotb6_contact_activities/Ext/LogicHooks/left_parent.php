<?php


/**
 * Mise à jour des relations pour refléter les 2 Flex Relate
 */
$hook_array['process_record'][] = array(
    1,
    'Populate Left Parent Name',
    'custom/include/dotbase/dotb6_contact_activities.php',
    'dotb6_contact_activitiesHooks',
    'setLeftParentName'
);

$hook_array['after_retrieve'][] = array(
    10,
    'Populate Left Parent Name',
    'custom/include/dotbase/dotb6_contact_activities.php',
    'dotb6_contact_activitiesHooks',
    'setLeftParentName'
);