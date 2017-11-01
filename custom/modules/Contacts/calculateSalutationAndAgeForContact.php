<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class calculateSalutationAndAgeForContact {

    /**
     * Update saluatation and age for lead coming from API 
     */
    function updateSalutationAndAgeForContact($bean, $event, $arguments) {
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
        $no_gender = array(
            'de' => 'Guten Tag',
            'fr' => 'Bonjour,',
            'it' => 'Buongiorno,',
            'en' => 'Hello,'
        );
//        if (!empty($_REQUEST['__sugar_url']) && !isset($_REQUEST['viewed'])) {
//            $check = explode('/', $_REQUEST['__sugar_url']);
//            if ($check[0] == "v10" && $check[4] == "contacts") {
        //salutation
        $corresponding_language = $bean->dotb_correspondence_language;
        if (!empty($corresponding_language)) {
            $gender = $bean->dotb_gender_id;
            $key = $corresponding_language . "_" . $gender;
            $bean->salutation = $salutationMapping[$key];
            if (empty($gender)) {
                $bean->salutation_text_c = $no_gender[$corresponding_language];
            } else {
                if(isset($app_list_strings['dependent_salutation_dom'][$bean->salutation]))
                $bean->salutation_text_c = $app_list_strings['dependent_salutation_dom'][$bean->salutation];
            }
        }
        //age calculation
        $dob = $bean->birthdate;
        $birthdate = new DateTime($dob);
        $today = new DateTime('today');
        $age = $birthdate->diff($today)->y;
        $bean->dotb_age_c = $age;
        //    }
        // }
    }

}

?>