<?php

if (!defined('sugarEntry'))
    define('sugarEntry', true);
// chdir(realpath(dirname(__FILE__)));
require_once('include/entryPoint.php');
require_once('modules/Teams/TeamSet.php');
global $timedate, $sugar_config;

/* * *************************************
 * Script for Contact:                 *
 * ************************************ */
$sql = "SELECT id FROM contacts where deleted=0 AND opt_emails IS NULL";
$result = $GLOBALS["db"]->query($sql);
echo "<b>Script Result:</b><br>";

$contact_count = 0;
while ($contact = $GLOBALS["db"]->fetchByAssoc($result)) {
    $contact_id = $contact['id'];
    /*
     * Getting Email Addresses
     */
    $emailAddresses = new SugarEmailAddress;

    $sea = new SugarEmailAddress;

    $contact_email_addresses = $sea->getAddressesByGUID($contact['id'], 'Contacts');
    $add = false;
    $opt_emails = '';
    $count=0;
    foreach ($contact_email_addresses as $contact_email_address) {
        if($count>0){
        $opt_emails.=$contact_email_address['email_address'] . ', ';
        $add = true;
        }
        $count++;
    }

    if ($add) {
        $opt_emails=rtrim($opt_emails, ',');
        $GLOBALS['db']->query("UPDATE contacts SET opt_emails='$opt_emails' WHERE id='$contact_id'");
    }else{
        $GLOBALS['db']->query("UPDATE contacts SET opt_emails='' WHERE id='$contact_id'");
    }
    $contact_count++;
}
echo "<br> $contact_count contacts were updated";


/* * *************************************
 * Script for Lead:                 *
 * ************************************ */
$sql = "SELECT id FROM leads where deleted=0 AND opt_emails IS NULL";
$result = $GLOBALS["db"]->query($sql);
$lead_count = 0;
while ($lead = $GLOBALS["db"]->fetchByAssoc($result)) {
    $lead_id = $lead['id'];
    /*
     * Getting Email Addresses
     */
    $emailAddresses = new SugarEmailAddress;

    $sea = new SugarEmailAddress;

    $lead_email_addresses = $sea->getAddressesByGUID($lead['id'], 'Leads');
    $add = false;
    $opt_emails = '';
     $count=0;
    foreach ($lead_email_addresses as $lead_email_address) {
        if($count>0){
        $opt_emails.=$lead_email_address['email_address'] . ', ';
        $add = true;
        }
        $count++;
    }
    if ($add) {
         $opt_emails=rtrim($opt_emails, ',');
        $GLOBALS['db']->query("UPDATE leads SET opt_emails='$opt_emails' WHERE id='$lead_id'");
    }else{
        $GLOBALS['db']->query("UPDATE leads SET opt_emails='' WHERE id='$lead_id'");
    }
    $lead_count++;
}
echo "<br> $lead_count leads were updated";

if($contact_count!=0 || $lead_count!=0){
    echo "<br><br><br> Please Run script again...";
}
exit;
