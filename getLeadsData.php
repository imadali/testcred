<?php
/**
* CRED-1003 : get Leads data for linear database
**/
if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}
require_once 'include/entryPoint.php';

if (!file_exists('LeadsData')) {
    mkdir('LeadsData', 0777, true);
}

/**
* function: getAddresses
* description: get 2 most recent addresses records related to lead
*/
function getAddresses ($lead_id, $field_name) 
{
    $address_id = $GLOBALS["db"]->query("select leads_dot10_addresses_1dot10_addresses_idb from leads_dot10_addresses_1_c where deleted=0 and leads_dot10_addresses_1leads_ida='".$lead_id."' order by date_modified DESC limit 0,2");
    $address_fields = "";
    $address_arr = array();
    while ($rec = $GLOBALS["db"]->fetchByAssoc($address_id)) {

        $address_rec =  $GLOBALS["db"]->fetchByAssoc($GLOBALS["db"]->query("select salutation,first_name,last_name,address_c_o,primary_address_street,primary_address_postalcode,primary_address_city,date_entered,date_modified from dot10_addresses where id='".$rec["leads_dot10_addresses_1dot10_addresses_idb"]."' and deleted=0")); 
        $address_dates = $GLOBALS["db"]->fetchByAssoc($GLOBALS["db"]->query("select dotb_resident_since_c,dotb_resident_till_c from dot10_addresses_cstm where id_c='".$rec["leads_dot10_addresses_1dot10_addresses_idb"]."'"));
        $addr_contact = $GLOBALS["db"]->fetchByAssoc($GLOBALS["db"]->query("select contacts_dot10_addresses_1contacts_ida from contacts_dot10_addresses_1_c where deleted=0 and contacts_dot10_addresses_1dot10_addresses_idb='".$rec["leads_dot10_addresses_1dot10_addresses_idb"]."'"));
        if (!empty($addr_contact["contacts_dot10_addresses_1contacts_ida"])) {
            $contact_name = $GLOBALS["db"]->fetchByAssoc($GLOBALS["db"]->query("select first_name, last_name from contacts where id='".$addr_contact["contacts_dot10_addresses_1contacts_ida"]."'"));
            $cntct = $contact_name["first_name"]." ".$contact_name["last_name"];
            
        } else {
            $cntct = "NULL";            
        }        

        $address_arr[] = $rec["leads_dot10_addresses_1dot10_addresses_idb"];
        $address_arr[] = $address_rec["salutation"];
        $address_arr[] = $address_rec["first_name"];
        $address_arr[] = $address_rec["last_name"];
        $address_arr[] = $address_rec["address_c_o"];
        $address_arr[] = $address_rec["primary_address_street"];
        $address_arr[] = $address_rec["primary_address_postalcode"];
        $address_arr[] = $address_rec["primary_address_city"];
        $address_arr[] = $address_rec["date_entered"];
        $address_arr[] = $address_rec["date_modified"];
        $address_arr[] = $address_dates["dotb_resident_since_c"];
        $address_arr[] = $address_dates["dotb_resident_till_c"];
        $address_arr[] = $cntct;
        $address_arr[] = $field_name;
           
    }
    if (count($address_arr) < 28) {
        for ($cntr = count($address_arr); $cntr < 28; $cntr++) {
            $address_arr[$cntr] = "NULL";      
        }        
    }
    $address_fields = implode(",",$address_arr);
    return $address_fields;
}
/**
* function: getApplication
* description: get 3 most recent application records related to lead
*/
function getApplication ($lead_id, $field_name) 
{
    $application = $GLOBALS["db"]->query("select leads_opportunities_1opportunities_idb from leads_opportunities_1_c where deleted=0 and leads_opportunities_1leads_ida='".$lead_id."' order by date_modified DESC limit 0,3");
    $application_fields = "";
    $application_arr = array();
    while ($rec = $GLOBALS["db"]->fetchByAssoc($application)) {
        $application_rec =  $GLOBALS["db"]->fetchByAssoc($GLOBALS["db"]->query("select provider_contract_no,approved_saldo from opportunities where id='".$rec["leads_opportunities_1opportunities_idb"]."' and deleted = 0"));
        $application_cstm = $GLOBALS["db"]->fetchByAssoc($GLOBALS["db"]->query("select provider_application_no_c,credit_amount_c,credit_duration_c,interest_rate_c,contract_credit_amount,contract_credit_duration,contract_interest_rate from opportunities_cstm where id_c='".$rec["leads_opportunities_1opportunities_idb"]."'"));
        $application_arr[] = $rec["leads_opportunities_1opportunities_idb"];
        $application_arr[] = $application_cstm["provider_application_no_c"];
        $application_arr[] = $application_rec["provider_contract_no"];
        $application_arr[] = $application_cstm["credit_amount_c"];
        $application_arr[] = $application_cstm["credit_duration_c"];
        $application_arr[] = $application_cstm["interest_rate_c"];
        $application_arr[] = str_replace(","," ",$application_rec["approved_saldo"]);   
        $application_arr[] = $application_cstm["contract_credit_amount"];   
        $application_arr[] = $application_cstm["contract_credit_duration"];   
        $application_arr[] = $application_cstm["contract_interest_rate"];    		
    }
    if (count($application_arr) < 30) {
        for ($cntr = count($application_arr); $cntr < 30; $cntr++) {
            $application_arr[$cntr] = "NULL";		
        }        
    }

    $application_fields = implode(",",$application_arr);
    return $application_fields;
}

/**
* function: getContract
* description: get most recent contract record related to lead
*/
function getContract ($lead_id, $field_name) 
{
    $contract = $GLOBALS["db"]->query("select contracts_leads_1contracts_ida from contracts_leads_1_c where deleted=0 and contracts_leads_1leads_idb='".$lead_id."' order by date_modified DESC limit 0,1");
    $contract_fields = "";
    $contrac_arr = array();
    while ($rec = $GLOBALS["db"]->fetchByAssoc($contract)) {
        $contract_cstm = $GLOBALS["db"]->fetchByAssoc($GLOBALS["db"]->query("select provider_id_c,credit_amount_c,interest_rate_c,credit_duration_c,contract_date_c,paying_date_c,customer_credit_amount_c,customer_credit_duration_c,customer_interest_rate_c from contracts_cstm where id_c='".$rec["contracts_leads_1contracts_ida"]."'"));
        $contact_id = $GLOBALS["db"]->fetchByAssoc($GLOBALS["db"]->query("select contact_id from contracts_contacts where deleted=0 and contract_id = '".$rec["contracts_leads_1contracts_ida"]."' order by date_modified DESC limit 0,1"));
        $contact_name = "";
        if (!empty($contact_id["contact_id"])) {
            $result = $GLOBALS["db"]->fetchByAssoc($GLOBALS["db"]->query("select first_name,last_name from contacts where deleted=0 and id = '".$contact_id["contact_id"]."'"));  
            $contact_name = $result["first_name"]." ".$result["last_name"];		
        }        
        $contrac_arr[] = $rec["contracts_leads_1contracts_ida"];
        $contrac_arr[] = $contract_cstm["provider_id_c"];
        $contrac_arr[] = $contract_cstm["credit_amount_c"];
        $contrac_arr[] = $contract_cstm["interest_rate_c"];
        $contrac_arr[] = $contract_cstm["credit_duration_c"];
        $contrac_arr[] = $contract_cstm["contract_date_c"];
        $contrac_arr[] = $contract_cstm["paying_date_c"]; 
        $contrac_arr[] = $contact_name;		
        $contrac_arr[] = $contract_cstm["customer_credit_amount_c"];   
        $contrac_arr[] = $contract_cstm["customer_credit_duration_c"];   
        $contrac_arr[] = $contract_cstm["customer_interest_rate_c"];    		
    }
    if (count($contrac_arr) < 11) {
        for ($cntr = count($contrac_arr); $cntr < 11; $cntr++) {
            $contrac_arr[$cntr] = "NULL";		
        }        
    }

    $contract_fields = implode(",",$contrac_arr);
    return $contract_fields;
}
/**
* function: getPartner
* description: get most recent partner record related to lead
*/
function getPartner ($lead_id, $field_name)
{
    $partner = $GLOBALS["db"]->query("select leads_contacts_1contacts_idb from leads_contacts_1_c where deleted=0 and leads_contacts_1leads_ida='".$lead_id."' order by date_modified DESC limit 0,1");
    $partner_fields = "";
    $partner_arr = array();
    while ($rec = $GLOBALS["db"]->fetchByAssoc($partner)) {
        $partner_info = $GLOBALS["db"]->fetchByAssoc($GLOBALS["db"]->query("select first_name,last_name,birthdate,phone_mobile,phone_other,phone_work,assigned_user_id,address_c_o,primary_address_street,primary_address_postalcode,primary_address_city,primary_address_country,correspondence_address_street,correspondence_address_postalcode,correspondence_address_city,correspondence_address_country,dotb_bank_name,dotb_bank_zip_code,dotb_bank_city_name,dotb_iban,dotb_employment_type_id,dotb_pension_type_id,dotb_employer_name,dotb_employer_npa,dotb_employer_town,dotb_is_in_probation_period,dotb_monthly_net_income,dotb_monthly_gross_income,dotb_has_thirteenth_salary,dotb_rent_or_alimony_income,dotb_mortgage_amount from contacts where id='".$rec["leads_contacts_1contacts_idb"]."'"));
        $partner_cstm = $GLOBALS["db"]->fetchByAssoc($GLOBALS["db"]->query("select dotb_is_pensioner,dotb_is_unable_to_work,dotb_unable_to_work_in_last_5_years,dotb_partner_agreement_c,sideline_hired_since_c,dotb_housing_costs_rent_c from contacts_cstm where id_c='".$rec["leads_contacts_1contacts_idb"]."'"));
        $assigned_user_id = $GLOBALS["db"]->fetchByAssoc($GLOBALS["db"]->query("select first_name,last_name from users where deleted=0 and id = '".$partner_info["assigned_user_id"]."'"));
        $user_name = "";
        $user_name = $assigned_user_id["first_name"]." ".$assigned_user_id["last_name"];
        if (empty($user_name)) {
            $user_name = "NULL";    
        }
        $contact_email = $GLOBALS["db"]->fetchByAssoc($GLOBALS["db"]->query("SELECT ea_scauth.email_address
            FROM email_addresses AS ea_scauth 
            INNER JOIN email_addr_bean_rel AS eabr_scauth
            ON ea_scauth.deleted = 0
            AND eabr_scauth.email_address_id = ea_scauth.id
            WHERE eabr_scauth.deleted = 0
            AND eabr_scauth.bean_module = 'Contacts'
			AND eabr_scauth.bean_id = '".$rec["leads_contacts_1contacts_idb"]."'
            AND eabr_scauth.primary_address = 1"));
        if (empty($contact_email["email_address"])) {
            $email_addr = "NULL";    
        } else {
            $email_addr = $contact_email["email_address"];        
        }
        
        $partner_arr[] = $rec["leads_contacts_1contacts_idb"];
        $partner_arr[] = $partner_info["first_name"];
        $partner_arr[] = $partner_info["last_name"];
        $partner_arr[] = $partner_info["birthdate"];
        $partner_arr[] = $email_addr;
        $partner_arr[] = $partner_info["phone_mobile"];
        $partner_arr[] = $partner_info["phone_other"]; 
        $partner_arr[] = $partner_info["phone_work"]; 
        $partner_arr[] = $user_name;   
        $partner_arr[] = $partner_info["address_c_o"];   
        $partner_arr[] = $partner_info["primary_address_street"]; 
        $partner_arr[] = $partner_info["primary_address_postalcode"];  
        $partner_arr[] = $partner_info["primary_address_city"];  
        $partner_arr[] = $partner_info["primary_address_country"]; 
        $partner_arr[] = $partner_info["correspondence_address_street"];  
        $partner_arr[] = $partner_info["correspondence_address_postalcode"];  
        $partner_arr[] = $partner_info["correspondence_address_city"];  
        $partner_arr[] = $partner_info["correspondence_address_country"];  
        $partner_arr[] = $partner_info["dotb_bank_name"];  
        $partner_arr[] = $partner_info["dotb_bank_zip_code"];  		
        $partner_arr[] = $partner_info["dotb_bank_city_name"]; 
        $partner_arr[] = $partner_info["dotb_iban"]; 
        $partner_arr[] = $partner_info["dotb_employment_type_id"]; 
        $partner_arr[] = $partner_cstm["dotb_is_pensioner"]; 
        $partner_arr[] = $partner_info["dotb_pension_type_id"];
        $partner_arr[] = $partner_cstm["dotb_is_unable_to_work"]; 
        $partner_arr[] = $partner_cstm["dotb_unable_to_work_in_last_5_years"]; 
        $partner_arr[] = $partner_cstm["dotb_partner_agreement_c"]; 
        $partner_arr[] = $partner_info["dotb_employer_name"]; 
        $partner_arr[] = $partner_info["dotb_employer_npa"];
        $partner_arr[] = $partner_info["dotb_employer_town"];
        $partner_arr[] = $partner_info["dotb_is_in_probation_period"];
        $partner_arr[] = $partner_info["dotb_monthly_net_income"];
        $partner_arr[] = $partner_info["dotb_monthly_gross_income"];
        $partner_arr[] = $partner_info["dotb_has_thirteenth_salary"];
        $partner_arr[] = $partner_cstm["sideline_hired_since_c"];
        $partner_arr[] = $partner_info["dotb_rent_or_alimony_income"];
        $partner_arr[] = $partner_info["dotb_mortgage_amount"];
        $partner_arr[] = $partner_cstm["dotb_housing_costs_rent_c"];	
    }
    if (count($partner_arr) < 39) {
        for ($cntr = count($partner_arr); $cntr < 39; $cntr++) {
            $partner_arr[$cntr] = "NULL";		
        }        
    }

    $partner_fields = implode(",",$partner_arr);
    return $partner_fields;
}

global $timedate, $db, $sugar_config;
$current_date = date('Y-m-d');
$offset = $_GET['offset'];
if (empty($offset)) {
    $offset = 0;
}
$file_name = 'LeadsData/Leads--' . $offset . '--' . $current_date . '.csv';
$lead_count = 0;
$lead_mysql_field_list = array (
    'id',
    'credit_request_status_id_c',
    'lq_next_best_steps_c',
    'first_name',
    'last_name',
    'birthdate',
    'dotb_correspondence_language_c',
    'dotb_gender_id_c',
    'phone_other',
    'phone_mobile',
    'phone_work',
    'credit_amount_c',
    'credit_duration_c',
    'primary_address_street',
    'primary_address_postalcode',
    'primary_address_city',
    'primary_address_country',
    'correspondence_address_street',
    'correspondence_address_postalcode',
    'correspondence_address_city',
    'correspondence_address_country',
    'dotb_resident_since_c',
    'dotb_bank_name_c',
    'dotb_bank_zip_code_c',
    'dotb_bank_city_name_c',
    'dotb_iban_c',
    'dotb_employer_name_c',
    'dotb_employer_npa_c',
    'dotb_employer_town_c',
    'dotb_is_in_probation_period_c',
    'dotb_monthly_net_income_c',
    'dotb_monthly_gross_income_c',
    'dot_second_job_employer_name_c',
    'dotb_second_job_employer_npa_c',
    'dot_second_job_employer_town_c',
    'dotb_monthly_net_income_nb_c',
    'dotb_second_job_gross_income_c',
    'sideline_hired_since_c',
    'dotb_rent_or_alimony_income_c',
    'dotb_mortgage_amount_c',
    'dotb_housing_costs_rent_c',
    'cstm_last_name_c',
    'cc_id',
);
$leads_related_columns = array(
    'leads_assigned_user_name',
    'contact_name',
    'lead_email1',
);
$lead_columns = '';
$lead_columns = implode(",",$lead_mysql_field_list);
$leads_query = "SELECT $lead_columns,assigned_user_id,team_id,contact_id FROM leads LEFT JOIN leads_cstm ON leads.id = leads_cstm.id_c  WHERE  deleted=0 and id IN ('a6d6d06a-aa09-11e7-a9af-005056a673da','987a7cd8-8fd8-11e7-8b9e-005056a673da','ee204912-a511-11e7-95a0-005056a673da','44efb730-a99c-11e7-980c-005056a673da','9c015ae4-a9cc-11e7-8683-005056a673da','fde58d00-a6c2-11e7-a227-005056a673da','d620a620-8aa1-11e7-8e54-005056a673da','bc7711fc-a868-11e7-a4ef-005056a673da','e95c27f6-a83c-11e7-b1a4-005056a673da','911f95f8-aa00-11e7-8d47-005056a673da','7bdab098-a9af-11e7-971b-005056a673da','45c78fca-724c-11e7-8ce8-005056a673da','c3d65f40-acd5-11e7-b793-005056a673da','2f826c68-7e2b-11e7-91f4-005056a673da','392c4dc0-9bcb-11e7-9bdc-005056a673da','5a484136-a435-11e7-b4cf-005056a673da','ed62b128-a9b1-11e7-b8e9-005056a673da','2660f200-a9d5-11e7-a1ac-005056a673da','465498e4-ab49-11e7-8912-005056a673da','a6d6d06a-aa09-11e7-a9af-005056a673da') ORDER BY leads.date_entered DESC LIMIT 100 OFFSET $offset";
$leads_result = $GLOBALS["db"]->query($leads_query);
$file = fopen($file_name,"w");
$leads_fields = '';
for ($i = 0; $i < count($lead_mysql_field_list); $i++) {
    $leads_fields .= $lead_mysql_field_list[$i] . ',';
}
$leads_fields = substr($leads_fields, 0, -1);
$leads_fields .= ",leads_assigned_user_name,contact_name,lead_email1,address1_id,address1_salutation,address1_first_name,address1_last_name,address1_address_c_o,address1_primary_address_street,address1_primary_address_postalcode,address1_primary_address_city,address1_date_entered,address1_date_modified,address1_dotb_resident_since_c,address1_dotb_resident_till_c,address1_contacts_dot10_addresses_1_name,address1_leads_dot10_addresses_1_name,address2_id,address2_salutation,address2_first_name,address2_last_name,address2_address_c_o,address2_primary_address_street,address2_primary_address_postalcode,address2_primary_address_city,address2_date_entered,address2_date_modified,address2_dotb_resident_since_c,address2_dotb_resident_till_c,address2_contacts_dot10_addresses_1_name,address2_leads_dot10_addresses_1_name,app1_id,app1_provider_application_no_c,app1_provider_contract_no,app1_credit_amount_c,app1_credit_duration_c,app1_interest_rate_c,app1_approved_saldo,app1_contract_credit_amount,app1_contract_credit_duration,app1_contract_interest_rate,app2_id,app2_provider_application_no_c,app2_provider_contract_no,app2_credit_amount_c,app2_credit_duration_c,app2_interest_rate_c,app2_approved_saldo,app2_contract_credit_amount,app2_contract_credit_duration,app2_contract_interest_rate,app3_id,app3_provider_application_no_c,app3_provider_contract_no,app3_credit_amount_c,app3_credit_duration_c,app3_interest_rate_c,app3_approved_saldo,app3_contract_credit_amount,app3_contract_credit_duration,app3_contract_interest_rate,cntr_id,cntr_provider_id_c,cntr_credit_amount_c,cntr_interest_rate_c,cntr_credit_duration_c,cntr_contract_date_c,cntr_paying_date_c,cntr_contacts_contracts_1_name,cntr_customer_credit_amount_c,cntr_customer_credit_duration_c,cntr_customer_interest_rate_c,ptr_id,ptr_first_name,ptr_last_name,ptr_birthdate,ptr_email1,ptr_phone_mobile,ptr_phone_other,ptr_phone_work,ptr_assigned_user_name,ptr_address_c_o,ptr_primary_address_street,ptr_primary_address_postalcode,ptr_primary_address_city,ptr_primary_address_country,ptr_correspondence_address_street,ptr_correspondence_address_postalcode,ptr_correspondence_address_city,ptr_correspondence_address_country,ptr_dotb_bank_name,ptr_dotb_bank_zip_code,ptr_dotb_bank_city_name,ptr_dotb_iban,ptr_dotb_employment_type_id,ptr_dotb_is_pensioner,ptr_dotb_pension_type_id,ptr_dotb_is_unable_to_work,ptr_dotb_unable_to_work_in_last_5_years,ptr_dotb_partner_agreement_c,ptr_dotb_employer_name,ptr_dotb_employer_npa,ptr_dotb_employer_town,ptr_dotb_is_in_probation_period,ptr_dotb_monthly_net_income,ptr_dotb_monthly_gross_income,ptr_dotb_has_thirteenth_salary,ptr_sideline_hired_since_c,ptr_dotb_rent_or_alimony_income,ptr_dotb_mortgage_amount,ptr_dotb_housing_costs_rent_c"; 
fputcsv($file,explode(',',$leads_fields));
while ($lead_row = $GLOBALS["db"]->fetchByAssoc($leads_result)) {
    $leads_fields = '';
    for ($k = 0; $k < count($lead_mysql_field_list);$k++) {
		
        $leads_fields .= str_replace(',' , ' ', $lead_row[$lead_mysql_field_list[$k]]) . ',';                  
    }

    //get relate field values for lead
    $assigned_user_name = $GLOBALS["db"]->fetchByAssoc($GLOBALS["db"]->query("select first_name,last_name from users where id='".$lead_row["assigned_user_id"]."'"));
    $asgnd_user_name = $assigned_user_name["first_name"]." ".$assigned_user_name["last_name"];
    $contact_nam = $GLOBALS["db"]->fetchByAssoc($GLOBALS["db"]->query("select first_name, last_name from contacts where id='".$lead_row["contact_id"]."' and deleted=0"));
    $contact_name = $contact_nam["first_name"]." ".$contact_nam["last_name"];
    if (empty($contact_name)) {
        $contact_name = "NULL";
    }
    $email = $GLOBALS["db"]->fetchByAssoc($GLOBALS["db"]->query("SELECT ea_scauth.email_address
            FROM email_addresses AS ea_scauth 
            INNER JOIN email_addr_bean_rel AS eabr_scauth
            ON ea_scauth.deleted = 0
            AND eabr_scauth.email_address_id = ea_scauth.id
            WHERE eabr_scauth.deleted = 0
            AND eabr_scauth.bean_module = 'Leads'
			AND eabr_scauth.bean_id = '".$lead_row["id"]."'
            AND eabr_scauth.primary_address = 1"));
    if (empty($email['email_address'])) {
        $email_addr =  "NULL";       
    } else {
        $email_addr =  $email['email_address'];            
    }
    $leads_fields .= $asgnd_user_name.",".$contact_name.",".$email_addr;
    //get relate field values for lead
    $lead_name = $lead_row["first_name"]." ".$lead_row["last_name"];
    //get related addresses logic
    $address = getAddresses($lead_row["id"],$lead_name);
    $leads_fields .= ",".$address;
    //get related addresses logic end
    //get related application data
    $application = getApplication($lead_row["id"],$lead_name);
    $leads_fields .= ",".$application;
    //get related application data end
    //get related contract data 
    $contract = getContract($lead_row["id"],$lead_name);
    $leads_fields .= ",".$contract;
    //get related contract data end
    //get related partner data 
    $partner = getPartner($lead_row["id"],$lead_name);
    $leads_fields .= ",".$partner;
    //get related partner data end
    fputcsv($file,explode(',',$leads_fields));
    $lead_count++;
}
fclose($file); 
echo "$lead_count leads are written to csv file.";
exit;