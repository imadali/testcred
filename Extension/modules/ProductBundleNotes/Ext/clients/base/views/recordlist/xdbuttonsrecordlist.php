<?php

//module name
$moduleUsed = 'ProductBundleNotes';

//buttons to append
$addButtons = array(
                array(
                'name' => 'xperido_list_generate_button',
                'type' => 'xdrecordlistgenbutton',
                'label' => 'LBL_XPERIDO_GENERATE_RECORDLIST_LABEL',
                'acl_action' => 'view',
            )
);

//if the buttons are missing in our base modules metadata, include core buttons
if (!isset($viewdefs[$moduleUsed]['base']['view']['recordlist']['selection']))
{
    require('clients/base/views/recordlist/recordlist.php');
    $viewdefs[$moduleUsed]['base']['view']['recordlist']['selection']= $viewdefs['base']['view']['recordlist']['selection'];
    unset($viewdefs['base']);
}


//appending buttons
foreach ($addButtons as $addButton)
{
    $viewdefs[$moduleUsed]['base']['view']['recordlist']['selection']['actions'][]=$addButton;
}

?>