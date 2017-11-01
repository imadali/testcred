<?php

    $hook_array['before_save'][] = array(
        1,
        'Create revision of documents manually',
        'custom/modules/Documents/SaveDocAttachment.php',
        'SaveDocAttachment',
        'save'
    );
	
    $hook_array['before_save'][] = array(
        3,
        'update converted bit revision id changes',
        'custom/modules/Documents/updateConvertable.php',
        'updateConvertable',
        'updateDocumentBit'
    );
    
    $hook_array['after_save'][] = array(
        1,
        'Set document teams and Assigned user same as parent user',
        'custom/modules/Documents/syncAssign.php',
        'syncAssign',
        'sync'
    );

     $hook_array['after_relationship_add'][] = array(
       1,
       'Set documents name',
       'custom/modules/Documents/SetDocName.php',
       'SetDocName',
       'set'
   );
?>