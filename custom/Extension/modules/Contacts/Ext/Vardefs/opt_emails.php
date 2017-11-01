<?php

$dictionary['Contact']['fields']['opt_emails'] = array(
    'name' => 'opt_emails',
    'vname' => 'Opt Emails',
    'type' => 'text',
    'comments' => 'This field is used to add all optional email addresses in Gloabl search',
    'unified_search' => true,
    'full_text_search' => 
      array (
        'enabled' => true,
        'searchable' => true,
        'boost' => 1,
      ),
);
?>