<?php

//module name
$moduleUsed = 'ProductBundles';

//buttons to append
$addButtons = array(
                array(
                    'type' => 'divider',
                ),
                array(
                    'type' => 'xdrecordgenbutton',
                    'name' => 'xperido_generate_button',
                    'label' => 'LBL_XPERIDO_GENERATE_RECORD_LABEL',
                    'acl_action' => 'view',
                ),
                array(
                    'type' => 'xdrecordquickgenbutton',
                    'name' => 'xperido_quickgenerate_button',
                    'label' => 'LBL_XPERIDO_QUICKGENERATE_RECORD_LABEL',
                    'acl_action' => 'view',
                )
);

//if the buttons are missing in our base modules metadata, include core buttons
if (!isset($viewdefs[$moduleUsed]['base']['view']['record']['buttons']))
{
    require('clients/base/views/record/record.php');
    $viewdefs[$moduleUsed]['base']['view']['record']['buttons'] = $viewdefs['base']['view']['record']['buttons'];
    unset($viewdefs['base']);
}

foreach($viewdefs[$moduleUsed]['base']['view']['record']['buttons'] as $outerKey => $outerButton)
{
    if (
        isset($outerButton['type'])
        && $outerButton['type'] == 'actiondropdown'
        && isset($outerButton['name'])
        && $outerButton['name'] == 'main_dropdown'
        && isset($outerButton['buttons'])
    )
    {
        //appending buttons
        foreach ($addButtons as $addButton)
        {
            $viewdefs[$moduleUsed]['base']['view']['record']['buttons'][$outerKey]['buttons'][]=$addButton;
        }
    }
}


?>