<?php
$mod_strings['LBL_FORM_CONFIG_HEADLINE'] = 'Einstellungen';
$mod_strings['LBL_FORM_CONFIG_INFO'] = 'Evalanche-Connector Einstellungen festlegen (sync. mit SugarCRM)';
$mod_strings['LBL_OS_CONTACTS_SYNC_PRIMARY'] = 'Nur prim&auml;re E-Mail Adressen sync.';
$mod_strings['LBL_CONTACTS_POOL_ID'] = 'Pool-ID\'s';
$mod_strings['LBL_CONTACTS_POOL_ID_HELP'] = 'Pool-IDs mit Beistrich getrennt eingeben (ohne Abst&auml;nde)';
$mod_strings['LBL_CONTACTS_POOL_ID_LEAD'] = 'Pool-ID (Lead)';
$mod_strings['LBL_CONTACTS_POOL_ID_LEAD_HELP'] = 'In diesem Pool landen Kontakte die sich auf der Homepage eintragen (wird in Sugar markiert)';
$mod_strings['LBL_CONTACTS_POOL_ID_ADDTO'] = 'Ziel-Pool-ID';
$mod_strings['LBL_CONTACTS_POOL_ID_ADDTO_HELP'] = 'Bei der Anlage von Kontakten in SugarCRM werden diese in folgenden Evalanche-Pool synchronisiert';
$mod_strings['LBL_OS_CONTACTS_SYNC_PRIMARY_HELP'] = 'Nur E-Mail Adressen nach Evalanche &uuml;bertragen die als Prim&auml;radresse markiert sind (ansonsten ALLE falls CBX nicht markiert)';
$mod_strings['LBL_CONTACTS_TIME_OFFSET'] = 'Evalanche-Zeit Offset';
$mod_strings['LBL_CONTACTS_TIME_OFFSET_HELP'] = 'Zeit die zur Sugar-Serverzeit addiert werden muss um synchron mit der Evalanche-Serverzeit zu laufen (in Sekunden einzugeben: 3600 entspricht 1 Std., Defaultwert = 0)';
$mod_strings['LBL_CONTACTS_MASTER'] = 'E-Mail Master';

// MOD 2016/01/25 semmlale @changeno. 2.4.102
$mod_strings['LBL_EVA_URL'] = 'Evalanche-URL';
$mod_strings['LBL_EVA_URL_HELP'] = 'Die URL des Evalanche-Webportals (dient zur Statistikabfrage und Report-Verlinkung)';
/******************************************************************************/

$mod_strings['LBL_CONTACTS_MASTER_HELP'] = 'Im Falle eines zeitgleichen &Auml;nderungsdatums werden E-Mail Kontakte des Masters bevorzugt und &uuml;berschreiben die Kontakte des unterprivilegierten Systems (Vorrang f&uuml;r Evalanche oder SugarCRM)';
$mod_strings['LBL_CONTACTS_MAIN_TYPE'] = 'Standard Anlagetyp';
$mod_strings['LBL_CONTACTS_MAIN_TYPE_HELP'] = 'Wenn ein neues Kontaktprofil von Evalanche nach SugarCRM übertragen wird, wird dann ein Kontakt oder ein Interessent in SugarCRM erstellt?';
$mod_strings['LBL_CONTACTS_SYNC_TYPE'] = 'Sync-Typ';
$mod_strings['LBL_CONTACTS_SYNC_TYPE_HELP'] = 'Bei der &Uuml;bertragung zwischen SugarCRM und Evalanche werden nur Kontakte, nur Interessenten oder Beide synchronisiert (bitte ausw&auml;hlen)';
$mod_strings['LBL_SYNC_SUGAR_EVA'] = 'Sync. Sugar => Eva';
$mod_strings['LBL_SYNC_SUGAR_EVA_HELP'] = 'Crontab: &Auml;nderungen von SugarCRM nach Evalanche synchronisieren (ja/nein)';
$mod_strings['LBL_SYNC_EVA_SUGAR'] = 'Sync. Eva => Sugar';
$mod_strings['LBL_SYNC_EVA_SUGAR_HELP'] = 'Crontab: &Auml;nderungen von Evalanche nach SugarCRM synchronisieren (ja/nein)';
$mod_strings['LBL_MERGE_EMAILS'] = 'Dubletten zusammenf&uuml;hren';
$mod_strings['LBL_MERGE_EMAILS_HELP'] = 'Kontakte mit gleicher E-Mail Adresse (z.B. bei mehreren Pools) werden in SugarCRM zu einem Kontakt zusammengefasst';
$mod_strings['LBL_IGNORE_EMAIL'] = 'Ignorelist (E-Mail Adressen)';
$mod_strings['LBL_IGNORE_EMAIL_HELP'] = 'Folgende E-Mail Adressen werden beim Sync. ausgeschlossen (z.B. &uuml;bereinstimmende Office-Adressen, Eingabe mit Beistrich getrennt)';
$mod_strings['LBL_CONTACTS_CATEGORY_ID'] = 'Default Kategorie-ID';
$mod_strings['LBL_CONTACTS_CATEGORY_ID_HELP'] = 'Zielgruppen werden alle automatisch innerhalb dieser Kategorie erzeugt (Kategorie-ID eingeben)';
$mod_strings['LBL_CONTACTS_USERNAME'] = 'Evalanche Benutzer';
$mod_strings['LBL_CONTACTS_USERNAME_HELP'] = 'Benutzername f&uuml;r den Evalanche-Login';
$mod_strings['LBL_CONTACTS_PASSWORD'] = 'Evalanche Passwort';
$mod_strings['LBL_CONTACTS_PASSWORD_HELP'] = 'Passwort f&uuml;r den Evalanche-Login';
$mod_strings['LBL_CONTACTS_IGNORE_LEADS'] = 'Ignoriere konvertierte Leads';
$mod_strings['LBL_CONTACTS_IGNORE_LEADS_HELP'] = 'Konvertierte Leads werden nicht zwischen Sugar und Evalanche synchronisiert (nur der zugeh&ouml;rige Kontakt)';
$mod_strings['LBL_CUSTOM_MAPPINGS'] = 'Benutzerdefinierte Feld-Mappings';
$mod_strings['LBL_CUSTOM_MAPPINGS_DETAIL'] = 'Legen Sie hier Spaltendefinitionen f&uuml;r Zusatzfelder fest (SugarCRM <> Evalanche)';
$mod_strings['LBL_FORM_MAPPING_SUGAR'] = 'Spaltenname Sugar';
$mod_strings['LBL_FORM_MAPPING_EVA'] = 'Spaltenname Eva';
$mod_strings['LBL_FORM_MAPPING_DEFAULT'] = 'Defaultwert';
$mod_strings['LBL_CONFIG_SAVED'] = 'Konfiguration wurde gespeichert';
?>