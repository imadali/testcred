<?php
$mod_strings['LBL_FORM_CONFIG_HEADLINE'] = 'Configuration';
$mod_strings['LBL_FORM_CONFIG_INFO'] = 'Set Evalanche-Connector Configuration (sync. with SugarCRM)';
$mod_strings['LBL_OS_CONTACTS_SYNC_PRIMARY'] = 'Sync only primary E-Mails';
$mod_strings['LBL_CONTACTS_POOL_ID'] = 'Pool-ID\'s';
$mod_strings['LBL_CONTACTS_POOL_ID_HELP'] = 'Pool-IDs separated by comma (without spaces)';
$mod_strings['LBL_CONTACTS_POOL_ID_LEAD'] = 'Pool-ID (Lead)';
$mod_strings['LBL_CONTACTS_POOL_ID_LEAD_HELP'] = 'This pool is the target pool for contacts from the homepage (marked in sugar)';
$mod_strings['LBL_CONTACTS_POOL_ID_ADDTO'] = 'Destination-Pool-ID';
$mod_strings['LBL_CONTACTS_POOL_ID_ADDTO_HELP'] = 'New contacts created in SugarCRM will be synchronized to this Evalanche Pool-ID';
$mod_strings['LBL_OS_CONTACTS_SYNC_PRIMARY_HELP'] = 'Sync. only E-Mail Addresses to Evalanche which are marked as primary (otherwise all if this box is left unchecked)';
$mod_strings['LBL_CONTACTS_TIME_OFFSET'] = 'Evalanche-Time Offset';
$mod_strings['LBL_CONTACTS_TIME_OFFSET_HELP'] = 'Add this time offset to Sugar Servertime for a matching Evalanche-Timestamps (type in seconds: 3600 equals one hour, Default value = 0)';
$mod_strings['LBL_CONTACTS_MASTER'] = 'E-Mail Master';
$mod_strings['LBL_CONTACTS_MASTER_HELP'] = 'In case of matching modified date (SugarCRM/Evalanche) the selected application is the master system and its changes are applied (Master-Flag for Evalanche or SugarCRM)';

// MOD 2016/01/25 semmlale @changeno. 2.4.102
$mod_strings['LBL_EVA_URL'] = 'Evalanche-URL';
$mod_strings['LBL_EVA_URL_HELP'] = 'The URL from Evalanche (its needed to get the charts and reports)';
/******************************************************************************/

$mod_strings['LBL_CONTACTS_MAIN_TYPE'] = 'Main creation type';
$mod_strings['LBL_CONTACTS_MAIN_TYPE_HELP'] = 'If a new profile gets synchronized from Evalanche, will it become a Contact or a Lead?';
$mod_strings['LBL_CONTACTS_SYNC_TYPE'] = 'Sync-Type';
$mod_strings['LBL_CONTACTS_SYNC_TYPE_HELP'] = 'When synchronizing between SugarCRM and Evalanche only contacts OR only leads OR both of them (contacts &amp; leads) become synchronized (please choose)';
$mod_strings['LBL_SYNC_SUGAR_EVA'] = 'Sync. Sugar => Eva';
$mod_strings['LBL_SYNC_SUGAR_EVA_HELP'] = 'Crontab: Sync. changes from SugarCRM to Evalanche (yes/no)';
$mod_strings['LBL_SYNC_EVA_SUGAR'] = 'Sync. Eva => Sugar';
$mod_strings['LBL_SYNC_EVA_SUGAR_HELP'] = 'Crontab: Sync. changes from Evalanche to SugarCRM (yes/no)';
$mod_strings['LBL_MERGE_EMAILS'] = 'Merge Doublets';
$mod_strings['LBL_MERGE_EMAILS_HELP'] = 'Contacts with the same E-Mail Address (exp. multiple pools) will get merged in sugar to one contact';
$mod_strings['LBL_IGNORE_EMAIL'] = 'Ignorelist (E-Mail Addresses)';
$mod_strings['LBL_IGNORE_EMAIL_HELP'] = 'The following E-Mail Addresses are excluded from the Sync. (exp. Office-Addresses, Input separated by comma)';
$mod_strings['LBL_CONTACTS_CATEGORY_ID'] = 'Default Category-ID';
$mod_strings['LBL_CONTACTS_CATEGORY_ID_HELP'] = 'Create targetgroups all inside this default category (enter Category-ID here)';
$mod_strings['LBL_CONTACTS_USERNAME'] = 'Evalanche User';
$mod_strings['LBL_CONTACTS_USERNAME_HELP'] = 'Username for the Evalanche-Login';
$mod_strings['LBL_CONTACTS_PASSWORD'] = 'Evalanche Password';
$mod_strings['LBL_CONTACTS_PASSWORD_HELP'] = 'Password for the Evalanche-Login';
$mod_strings['LBL_CONTACTS_IGNORE_LEADS'] = 'Ignore converted Leads';
$mod_strings['LBL_CONTACTS_IGNORE_LEADS_HELP'] = 'Converted Leads are excluded from Sync. (sync. only dependend contact)';
$mod_strings['LBL_CUSTOM_MAPPINGS'] = 'Custom field mappings';
$mod_strings['LBL_CUSTOM_MAPPINGS_DETAIL'] = 'Define field mappings here (SugarCRM <> Evalanche)';
$mod_strings['LBL_FORM_MAPPING_SUGAR'] = 'Columnname Sugar';
$mod_strings['LBL_FORM_MAPPING_EVA'] = 'Columnname Eva';
$mod_strings['LBL_FORM_MAPPING_DEFAULT'] = 'Default Value';
$mod_strings['LBL_CONFIG_SAVED'] = 'Configuration saved';
?>