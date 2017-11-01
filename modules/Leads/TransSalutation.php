<?php

require_once('include/utils.php');
if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class TransSalutation {

    function translate($bean, $event, $arguments) {
        global $timedate, $app_list_strings;
        $langMap = array(
            'en' => 'en_us',
            'fr' => 'fr_FR',
            'it' => 'it_it',
            'de' => 'de_DE',
        );
        $no_gender = array(
            'de' => 'Guten Tag',
            'fr' => 'Bonjour,',
            'it' => 'Buongiorno,',
            'en' => 'Hello,'
        );
        if (!empty($bean->dotb_correspondence_language_c)) {
            if (empty($bean->dotb_gender_id_c)) {
                $bean->salutation_text_c = $no_gender[$bean->dotb_correspondence_language_c];
            } else {
                if(isset($app_list_strings['dependent_salutation_dom'][$bean->salutation]))
                $bean->salutation_text_c = $app_list_strings['dependent_salutation_dom'][$bean->salutation];
            }
        }
        /**
         * Save non db related user name to custom db field
         * This field would be used in email templates
         */
        if ((!empty($bean->assigned_user_id) && $bean->assigned_user_id != $bean->fetched_row['assigned_user_id']) || empty($bean->customer_contact_user_name_db)) {
            $user = new User();
            if ($user->retrieve($bean->assigned_user_id)) {
                $bean->customer_contact_user_name_db = $user->first_name . " " . $user->last_name;
            }
        }

        /**
         *  Copy Date part of date entered in 
         *  date_entered_date_c Custom Field of Date Type
         */
        if (!empty($bean->fetched_row['date_entered'])) {
            $date = new DateTime($bean->fetched_row['date_entered']);
            $bean->date_entered_date_c = $date->format('d.m.Y');
        } else {
            $date = new DateTime($timedate->nowDb());
            $bean->date_entered_date_c = $date->format('d.m.Y');
        }

        /**
         *  Copy Customer contact to assigned user field
         */
        // if(!empty($bean->user_id_c)) {
        // $bean->assigned_user_id = $bean->user_id_c;
        // }

        /**
         * Copy Original Last Name to custom last name field
         */
        if (!empty($bean->last_name)) {
            $temp = explode(" ", $bean->last_name);
            $size = count($temp);
            unset($temp[$size - 1]);
            unset($temp[$size - 2]);
        }
    }

}
