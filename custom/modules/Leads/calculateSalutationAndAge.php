<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class calculateSalutationAndAge {

    /**
     * Update saluatation and age for lead coming from API 
     */
    function updateSalutationAndAge($bean, $event, $arguments) {
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
//        if (!empty($_REQUEST['__sugar_url']) && !isset($_REQUEST['viewed'])) {
//            $check = explode('/', $_REQUEST['__sugar_url']);
//            if ($check[0] == "v10" && $check[4] == "leads") {
        //salutation
        $corresponding_language = $bean->dotb_correspondence_language_c;
        $gender = $bean->dotb_gender_id_c;
        $key = $corresponding_language . "_" . $gender;
        if(isset($salutationMapping[$key])) {
            $bean->salutation = $salutationMapping[$key];
        }
        //$GLOBALS['log']->fatal("Salutation: " . $bean->salutation);
        //age calculation
        $dob = $bean->birthdate;
        $birthdate = new DateTime($dob);
        $today = new DateTime('today');
        $age = $birthdate->diff($today)->y;
        $bean->dotb_age_c = $age;
        //$GLOBALS['log']->fatal("Age: " . $bean->dotb_age_c);
        // }
        //}
		
		/**
		* Update additional phone fields 
		*/
		$this->updateAdditionalPhoneFields($bean, $event, $arguments);
    }
	
	function updateAdditionalPhoneFields($bean, $event, $arguments) {
		$mobile_number = $bean->phone_mobile;
		$mobile_number = str_replace(' ', '', $mobile_number);
        $bean->p_mobile = $mobile_number;
		
		$work_number = $bean->phone_work;
		$work_number = str_replace(' ', '', $work_number);
        $bean->p_work = $work_number;
		
		$other_number = $bean->phone_other;
		$other_number = str_replace(' ', '', $other_number);
        $bean->p_other = $other_number;
		
		$fax_number = $bean->phone_fax;
		$fax_number = str_replace(' ', '', $fax_number);
        $bean->p_fax = $fax_number;
		
		$home_number = $bean->phone_home;
		$home_number = str_replace(' ', '', $home_number);
        $bean->p_home = $home_number;
 
    }

}

?>