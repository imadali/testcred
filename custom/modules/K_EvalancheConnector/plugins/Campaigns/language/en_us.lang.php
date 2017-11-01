<?php
$mod_strings['LBL_FORM_CONFIG_HEADLINE'] = 'Configuration';
$mod_strings['LBL_FORM_CONFIG_INFO'] = 'Set Evalanche-Connector Configuration (sync. with SugarCRM)';
$mod_strings['LBL_MANDATE_ID'] = 'Mandate-ID';
$mod_strings['LBL_MANDATE_ID_HELP'] = 'Mandate number from Evalanche (this is the customer)';
$mod_strings['LBL_TYPE_ID'] = 'Type-ID Mailing';
$mod_strings['LBL_TYPE_ID_HELP'] = 'Internal Evalanche-Type-ID for Mailings (Default: 67)';
$mod_strings['LBL_EVA_TIME_OFFSET'] = 'Evalanche-Time Offset';
$mod_strings['LBL_EVA_TIME_OFFSET_HELP'] = 'Add this time offset to Sugar Servertime for a matching Evalanche-Timestamps (type in minutes, Default value = -120)';
// $mod_strings['LBL_REFRESHLIMIT'] = 'Refreshlimit in days';
// $mod_strings['LBL_REFRESHLIMIT_HELP'] = 'So much days after a mailing starts the depending campaign will get synchronized daily including statistics (Default: 1 Week)';
$mod_strings['LBL_REFRESHLIMIT'] = 'Sync-Interval (Days:Minutes)';
$mod_strings['LBL_REFRESHLIMIT_HELP'] = 'Declare here with comma separated how often the campaign statistics get synchronized (exp. 3:60,7:360 means until 3 days after Mailing all 60 mins and until 7 days after Mailing all 6 hours)';
$mod_strings['LBL_DAYLIMIT'] = 'History-Daylimit';
$mod_strings['LBL_DAYLIMIT_HELP'] = 'All mailings younger than X days will get synchronized to SugarCRM from Evalanche.';
$mod_strings['LBL_MAX_RUNNING_MINS'] = 'Max. Sync-Duration in Minutes';
$mod_strings['LBL_MAX_RUNNING_MINS_HELP'] = 'The Synchronization of campaings will run X minutes, after this time the Sync. stops running and the missing campaigns will get synchronized tommorow.';
$mod_strings['LBL_EVA_URL'] = 'Evalanche-URL';
$mod_strings['LBL_EVA_URL_HELP'] = 'The URL from Evalanche (its needed to get the charts and reports)';
$mod_strings['LBL_GET_STATISTICS'] = 'Synchronize Statistics';
$mod_strings['LBL_GET_STATISTICS_HELP'] = 'Synchronize Read-Chart and 6 hours-recipient-acitivities chart';
$mod_strings['LBL_GET_RECIPIENTS'] = 'Synchronize Recipients';
$mod_strings['LBL_GET_RECIPIENTS_HELP'] = 'Synchronize list of all recipients out of Evalanche (all Contacts have to exist in SugarCRM before this Sync.)';
$mod_strings['LBL_GET_IMPRESSIONS'] = 'Synchronize Opening info';
$mod_strings['LBL_GET_IMPRESSIONS_HELP'] = 'Synchronize all newsletter opening information out of Evalanche (its shown in recipient list and used for the calculation of the read-rates)';
$mod_strings['LBL_GET_ARTICLE_IMPRESSIONS'] = 'Synchronize Article-Details';
$mod_strings['LBL_GET_ARTICLE_IMPRESSIONS_HELP'] = 'Synchronize all Campaign-Articles including relation to readers (this are the recipients who read the articles)';
$mod_strings['LBL_GET_UNSUBSCRIBES'] = 'Synchronize unsubscriptions';
$mod_strings['LBL_GET_UNSUBSCRIBES_HELP'] = 'Synchronize all newsletter unsubscriptions out of Evalanche (its shown in recipient list)';
$mod_strings['LBL_EUSERNAME'] = 'Evalanche User';
$mod_strings['LBL_EUSERNAME_HELP'] = 'Username for the Evalanche-Login';
$mod_strings['LBL_EPASSWORD'] = 'Evalanche Password';
$mod_strings['LBL_EPASSWORD_HELP'] = 'Password for the Evalanche-Login';
$mod_strings['LBL_CONFIG_SAVED'] = 'Configuration saved';
?>