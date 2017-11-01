<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class CalculateSalutationAndAge
{

    /**
     * Update saluatation and age for lead coming from API 
     */
    function updateSalutationAndAge($bean, $event, $arguments) 
    {
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

        //salutation calculated if new lead or if language or gender changes CRED-780
        if (!isset($bean->fetched_row['id']) || ($bean->dotb_correspondence_language_c != $bean->fetched_row['dotb_correspondence_language_c']) || ($bean->dotb_gender_id_c != $bean->fetched_row['dotb_gender_id_c'])) {
            $corresponding_language = $bean->dotb_correspondence_language_c;
            $gender = $bean->dotb_gender_id_c;
            $key = $corresponding_language . "_" . $gender;
            if(isset($salutationMapping[$key])) {
                $bean->salutation = $salutationMapping[$key];
            }
        }
        
        //age calculation if lead is new or birthdate changes CRED-780
        if (!isset($bean->fetched_row['id']) || $bean->birthdate != $bean->fetched_row['birthdate']) {
            $dob = $bean->birthdate;
            $birthdate = new DateTime($dob);
            $today = new DateTime('today');
            $age = $birthdate->diff($today)->y;
            $bean->dotb_age_c = $age;
        }
		
		/**
		* Update additional phone fields if lead is new or any field changes CRED-780
		*/
        if (!isset($bean->fetched_row['id']) || ($bean->phone_mobile != $bean->fetched_row['phone_mobile']) || ($bean->phone_work != $bean->fetched_row['phone_work']) || ($bean->phone_other != $bean->fetched_row['phone_other']) || ($bean->phone_fax != $bean->fetched_row['phone_fax']) || ($bean->phone_home != $bean->fetched_row['phone_home'])) {
            $this->updateAdditionalPhoneFields($bean, $event, $arguments);
        }
    }
	
    function updateAdditionalPhoneFields($bean, $event, $arguments) 
    {
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