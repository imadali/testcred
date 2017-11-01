<?php

if (!defined('sugarEntry'))
    define('sugarEntry', true);
// chdir(realpath(dirname(__FILE__)));
require_once('include/entryPoint.php');
global $timedate, $app_list_strings;
$salutationMapping = array(
    "de_" => "sehr_geehrter_herr",
    "de_m" => "sehr_geehrter_herr",
    "de_f" => "sehr_geehrte_frau",
    "en_" => "dear_mr",
    "en_m_" => "dear_mr",
    "en_f_" => "dear_mrs",
    "en_m" => "dear_mr",
    "en_f" => "dear_mrs",
    "it_" => "egregio_signor",
    "it_m" => "egregio_signor",
    "it_f" => "egregia_signora",
    "fr_" => "monsieur",
    "fr_m" => "monsieur",
    "fr_f" => "madame",
);
$no_gender=array(
    'de'=>'Guten Tag',
    'fr'=>'Bonjour,',
    'it'=>'Buongiorno,',
    'en'=>'Hello,'
);
$sql = "SELECT id, dotb_correspondence_language,dotb_gender_id FROM contacts where deleted=0";
$result = $GLOBALS["db"]->query($sql);
echo "Seting Salutation for Contacts";
$count=1;
while ($lead = $GLOBALS["db"]->fetchByAssoc($result)) {
$corresponding_language = $lead['dotb_correspondence_language'];
if (!empty($corresponding_language)) {
    $id = $lead['id'];
    $gender = $lead['dotb_gender_id'];
    if(empty($gender)){
    $salutation_text_c=$no_gender[$corresponding_language];    
    }else{
    $key = $corresponding_language . "_" . $gender;
    $salutation = $salutationMapping[$key];
    $salutation_text_c = $app_list_strings['dependent_salutation_dom'][$salutation];
    }
    $sql = "UPDATE contacts SET salutation_text_c = '$salutation_text_c' where id='$id';";
    $GLOBALS["db"]->query($sql);
    $count++;
}
}
echo "<br> $count records were updated";
exit;
