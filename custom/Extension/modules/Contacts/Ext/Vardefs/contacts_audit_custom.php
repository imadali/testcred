<?php

$dictionary['Contact']['fields']['contacts_audit_custom'] = array(
    'name' => 'contacts_audit_custom',
    'type' => 'link',
    'relationship' => 'custom_contacts_audit_contacts',
    'module' => 'Contacts_Audit',
    'bean_name' => 'Contacts_Audit',
    'source' => 'non-db',
    'vname' => 'LBL_CONTACTS_AUDIT_CUSTOM',
);

?>