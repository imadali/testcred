<?php
$dictionary['Task']['fields']['emails']= array(
            'name' => 'emails',
            'vname' => 'LBL_TASKS_EMAILS_REL',
            'type' => 'link',
            'relationship' => 'emails_tasks_rel',
            'module' => 'Emails',
            'bean_name' => 'Email',
            'source' => 'non-db',
        );
 ?>