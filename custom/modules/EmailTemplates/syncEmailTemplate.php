<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once ('include/TimeDate.php');

class syncEmailTemplate {

    public function sync($bean, $event, $arguments) {
        global $timedate;
        if ($bean->type == 'workflow') {
            $mapping = array(
                '{::future::Leads::salutation::}' => '$contact_salutation_text_c',
                '{::future::Leads::salutation_text_c::}' => '$contact_salutation_text_c',
                '{::future::Leads::last_name::}' => '$contact_last_name',
                '{::future::Leads::assigned_user_name::}' => '$contact_customer_contact_user_name_db',
                //'{::future::Leads::customer_contact_user_id_c::}' => '$contact_customer_contact_user_name_db',
                '{::future::Leads::date_entered_date_c::}' => '$contact_date_entered_date_c',
                '{::future::Leads::contracts_leads_1::date_entered::}' => '$contact_contract_date_entered',
                '{::future::Leads::contracts_leads_1::paying_date_c::}' => '$contact_contract_paying_date_c',
                '{::future::Leads::contract_date_entered::}' => '$contact_contract_date_entered',
            );
            $alert_template_body = $bean->body;
            $alert_template_html = $bean->body_html;
            foreach ($mapping as $key => $value) {
                $alert_template_body = str_replace($key, $value, $alert_template_body);
                $alert_template_html = str_replace($key, $value, $alert_template_html);
            }
            $templateObj = new EmailTemplate();
            $templateObj->retrieve_by_string_fields(array('name' => $bean->name, 'type' => 'email'));
            if (empty($templateObj->id)) {
                $email_template = $bean;
                $email_template->id = '';
                $email_template->base_module = NULL;
                $email_template->type = 'email';
                $email_template->body = $alert_template_body;
                $email_template->body_html = $alert_template_html;
                $email_template->save();
            } else {
                $templateObj->subject = $bean->subject;
                $templateObj->body = $alert_template_body;
                $templateObj->body_html = $alert_template_html;
                $templateObj->save();
            }
        }
    }
}
