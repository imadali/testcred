<?php
/**
* CRED-990 : Update SQL for initial Lead-Score
* CRED-1041 : Update SQL for initial Lead-Score: FollowUp
*/
if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require_once 'include/entryPoint.php';
/**
** get leads linked to this record i.e Number of Older leads of this customer
*/
function numberOfOlderLeads($lead_id)
{
    $count = 0;
    $leads_leads_query = "SELECT COUNT(id) as old_lead FROM leads_leads_1_c WHERE leads_leads_1leads_ida ='".$lead_id."' AND deleted = 0";
    $leads_leads_result = $GLOBALS["db"]->query($leads_leads_query);
    $leads_leads_row = $GLOBALS["db"]->fetchByAssoc($leads_leads_result);
    $count = $leads_leads_row['old_lead'];
    return $count;
}
/**
** older leads with a contract i.e Number of Older leads of this customer which resulted in a contract (success)
*/
function numberOfLeadsWithContract($lead_id)
{
    $leads_leads_query = "SELECT leads_leads_1leads_idb FROM leads_leads_1_c WHERE leads_leads_1leads_ida ='".$lead_id."' AND deleted = 0";
    $leads_leads_result = $GLOBALS["db"]->query($leads_leads_query);
    $leads_with_contract = 0;
    while ($lead_row = $GLOBALS["db"]->fetchByAssoc($leads_leads_result)) {
        $linked_lead_id = $lead_row['leads_leads_1leads_idb'];
        $lead_contract_query = "SELECT c.id FROM contracts_leads_1_c lead_cont LEFT JOIN contracts c ON c.id = lead_cont.contracts_leads_1contracts_ida WHERE c.deleted=0 AND lead_cont.deleted=0 AND lead_cont.contracts_leads_1leads_idb='".$linked_lead_id."'";
		
        $lead_contract_result = $GLOBALS["db"]->query($lead_contract_query);
        while ($lead_contract_row = $GLOBALS["db"]->fetchByAssoc($lead_contract_result)) {
            $leads_with_contract++;
        }
    }
    return $leads_with_contract;
}
/**
** Contract linked to lead (in the selection there must be a column which reflects the outcome of a lead assuming it is completely matured (result = contract  as a flag, yes = 1, no = 0))
** In addition: pls check the definition/code of the success-flag. a success is a lead which results in commission producing credit contract. - Field: Contract.ProvisionConfirmed is flagged.
*/
function contractLinkedToLead($lead_id)
{
    $lead_contract_query = "SELECT c.id, c_cstm.provision_confirmed_c FROM contracts_leads_1_c lead_cont LEFT JOIN contracts c ON c.id = lead_cont.contracts_leads_1contracts_ida  LEFT JOIN contracts_cstm c_cstm ON c.id = c_cstm.id_c WHERE c.deleted=0 AND lead_cont.deleted=0 AND lead_cont.contracts_leads_1leads_idb='".$lead_id."'";
    $contract_linked = 0;
    $provision_confirmed = 0;
    $lead_contract_result = $GLOBALS["db"]->query($lead_contract_query);
    while ($lead_contract_row = $GLOBALS["db"]->fetchByAssoc($lead_contract_result)) {
        $contract_linked = 1;
        $provision_confirmed = $lead_contract_row['provision_confirmed_c'];
    }
    return array('contract_linked' => $contract_linked, 'provision_confirmed' => $provision_confirmed );
}
/**
** get credit history linked to this i.e Number of active credits belonging to older leads
** Average amount of active credits belonging to older leads (if no credits then null)
*/
function numberOfActiveCredits($lead_id)
{
    $leads_leads_query = "SELECT leads_leads_1leads_idb FROM leads_leads_1_c WHERE leads_leads_1leads_ida ='".$lead_id."' AND deleted = 0";
    $leads_leads_result = $GLOBALS["db"]->query($leads_leads_query);
    $linked_lead_id = '';
    $no_of_active_credits = 0;
    $old_credit_amount = 0;
    while ($lead_row = $GLOBALS["db"]->fetchByAssoc($leads_leads_result)) {
        $linked_lead_id = $lead_row['leads_leads_1leads_idb'];
        $credits_linked_to_lead_query = "SELECT chist.id, chist.credit_balance FROM leads_dotb5_credit_history_1_c lead_chist LEFT JOIN dotb5_credit_history chist ON  chist.id= lead_chist.leads_dotb5_credit_history_1dotb5_credit_history_idb WHERE lead_chist.leads_dotb5_credit_history_1leads_ida = '".$linked_lead_id."' AND lead_chist.deleted=0 AND chist.deleted=0;";
        $credits_linked_to_lead_result = $GLOBALS["db"]->query($credits_linked_to_lead_query);
        while ($lead_ch_row = $GLOBALS["db"]->fetchByAssoc($credits_linked_to_lead_result)) {
            $no_of_active_credits++;
            $old_credit_amount = $old_credit_amount + $lead_ch_row['credit_balance'];
        }
    }
    $average_credit_amount = 0;
    if ($no_of_active_credits != 0) {
        $average_credit_amount = $old_credit_amount/$no_of_active_credits;
    }
    return array('no_of_active_credits' => $no_of_active_credits, 'average_credit_amount' => $average_credit_amount );
}
/**
** older addresses i.e Number of different addresses in the last 360 days before the lead in scope/record
*/
function numberOfAddresses($lead_id, $lead_date_created)
{
    $address_count = 0;
    $lead_address_query = "SELECT addr.id, addr_cstm.dotb_resident_since_c FROM dot10_addresses addr LEFT JOIN dot10_addresses_cstm addr_cstm ON addr.id = addr_cstm.id_c  LEFT JOIN leads_dot10_addresses_1_c la ON la.leads_dot10_addresses_1dot10_addresses_idb = addr.id WHERE la.leads_dot10_addresses_1leads_ida = '" .$lead_id . "' AND la.deleted=0 AND addr.deleted=0";
    $lead_address_result = $GLOBALS["db"]->query($lead_address_query);
    $date_created_lead = strtotime($lead_date_created);
    $address_date_since = '';
    $address_count = 0;
    while ($lead_add_row = $GLOBALS["db"]->fetchByAssoc($lead_address_result)) {
        $address_date_since = strtotime($lead_add_row['dotb_resident_since_c']);
        $datediff = $date_created_lead - $address_date_since;
        $days = floor($datediff / (60 * 60 * 24));
        if ($days <= 360) {
            $address_count++;
        }
        $address_date_since = '';
    }
    return $address_count;
}
/**
** older lead difference i.e Days since last older lead if older leads exists
*/
function daysSinceOlderLead($lead_id, $lead_date_created)
{
    $days = 0;
    $lead_linked_query = "SELECT leads_leads_1leads_idb as old_lead FROM leads_leads_1_c WHERE leads_leads_1leads_ida ='".$lead_id."' AND deleted = 0 ORDER BY date_modified DESC LIMIT 0, 1";
    $lead_linked_result = $GLOBALS["db"]->query($lead_linked_query);
    $lead_address_row = $GLOBALS["db"]->fetchByAssoc($lead_linked_result);
    $linked_lead_id = $lead_address_row['old_lead'];
    if (!empty($linked_lead_id)) {
        // echo "Lead id: " . $lead_address_row['old_lead'] . "</br>";
        $lead_date_modified_query = "SELECT date_modified FROM leads WHERE id='".$linked_lead_id."' AND deleted = 0";
        $lead_date_modified_result = $GLOBALS["db"]->query($lead_date_modified_query);
        $lead_date_modified_row = $GLOBALS["db"]->fetchByAssoc($lead_date_modified_result);
        $old_lead_dm = strtotime($lead_date_modified_row['date_modified']);
        $current_lead_dc = strtotime($lead_date_created);
        if ($old_lead_dm < $current_lead_dc) {
            $datediff = $current_lead_dc - $old_lead_dm;
            $days = floor($datediff / (60 * 60 * 24));
        }
    }
    return $days;
}

/**
 * Check audit entry for the specified value
 */
function checkAuditEntry ($lead_id, $field_name)
{
    $lead_audit_query = 'SELECT * FROM leads_audit WHERE parent_id="' . $lead_id . '" AND field_name = "' . $field_name . '" AND deleted=0 ORDER BY date_created ASC LIMIT 0,1';
    $audit_value = '';
    $lead_audit_result = $GLOBALS["db"]->query($lead_audit_query);
    if ($lead_audit_result->num_rows > 0) {
        //added check for deltavista_score as initially value is 0 by default so after value string should be used.
        if ($field_name == 'deltavista_score_c') {
            $leads_audit_row = $GLOBALS["db"]->fetchByAssoc($lead_audit_result);
            $audit_value = $leads_audit_row['after_value_string'];
        } else {
            $leads_audit_row = $GLOBALS["db"]->fetchByAssoc($lead_audit_result);
            $audit_value = $leads_audit_row['before_value_string'];
        }
    } else {
        $audit_value = 'no-audit-entry';
    }
    return $audit_value;
}

if (!file_exists('LeadDataExtraction')) {
    mkdir('LeadDataExtraction', 0777, true);
}
global $timedate, $db, $sugar_config;
$current_date = date('Y-m-d');
$offset = $_GET['offset'];
$file_name = 'LeadDataExtraction/Leads--' . $offset . '--' . $current_date . '.csv';
$lead_count = 0;
if (empty($offset))
    $offset = 0;

//All leads that are are created after January 31 2017.
$leads_query = "SELECT l.*, l_cstm.* FROM leads l LEFT JOIN leads_cstm l_cstm ON l.id = l_cstm.id_c  WHERE l.date_entered > '2017-01-31 %' AND l.deleted=0 ORDER BY l.date_entered DESC LIMIT 5000 OFFSET $offset";
// echo "$leads_query</br>";
$leads_result = $GLOBALS["db"]->query($leads_query);
$file = fopen($file_name,"w");
$leads_fields = '';
$lead_info = $leads_result->fetch_fields();
$leads_language = return_module_language('en_us', 'Leads');
foreach ($lead_info as $val) {
    $field_label = $leads_language['LBL_'.strtoupper($val->name)];
    if(!empty($field_label))
        $leads_fields .= str_replace(';' , ' ', $field_label) . ';';
    else
        $leads_fields .= $val->name . ';';
}
$leads_fields = substr($leads_fields, 0, -1);
$leads_fields .= ';Lead in Status 10 (positive Success);Provision paid (positive Success);Lead in Status 11 (negative Success);Number of Older leads;Number of Older leads with contract;Number of active credits for older leads;Average amount of active credits belonging to older leads;Number of different addresses;Days since last older lead';
fputcsv($file,explode(';',$leads_fields), ";");
while ($lead_row = $GLOBALS["db"]->fetchByAssoc($leads_result)) {
    $leads_fields = '';
    $contract = array('contract_linked' => 0, 'provision_confirmed' => 0 );
    $status_11_flag = 0;
    $no_of_older_leads = '';
    $no_of_lead_with_contract = '';
    $credit_info = '';
    $no_of_addresses = '';
    $days_since = '';
    $lead_audit_value = '';
    foreach ($lead_info as $val) {
        if ($val->name != 'dotb_deltavista_response_c' && $val->name != 'dotb_intrum_response_c' && $val->name != 'salutation_text_c') {
            if ($val->name == 'deltavista_score_c' || $val->name == 'input_process_type_id_c' || $val->name == 'contact_type_option_id_c' || $val->name == 'dotb_housing_costs_rent_c' || $val->name == 'credit_amount_c' || $val->name == 'credit_usage_type_id_c') {
                $lead_audit_value = checkAuditEntry($lead_row['id'], $val->name);
                if ($lead_audit_value != 'no-audit-entry') {
                    $leads_fields .= str_replace(';' , ' ', $lead_audit_value) . ';';
                } else { 
                    $leads_fields .= str_replace(';' , ' ', $lead_row[$val->name]) . ';';
                }
            } else {
                $leads_fields .= str_replace(';' , ' ', $lead_row[$val->name]) . ';';
            }
        } else { 
            $leads_fields .= ';';
        }
    }
    if ($lead_row['credit_request_status_id_c'] == '10_active') {
        $contract = contractLinkedToLead($lead_row['id']);
    } else if ($lead_row['credit_request_status_id_c'] == '11_closed') {
        $status_11_flag = 1;
    }
    $no_of_older_leads = numberOfOlderLeads($lead_row['id']);
    $no_of_lead_with_contract = numberOfLeadsWithContract($lead_row['id']);
    $credit_info = numberOfActiveCredits($lead_row['id']);
    $no_of_addresses = numberOfAddresses($lead_row['id'], $lead_row['date_entered']);
    $days_since = daysSinceOlderLead($lead_row['id'], $lead_row['date_entered']);

    $leads_fields .= $contract['contract_linked'] . ';'  . $contract['provision_confirmed'] . ';'  . $status_11_flag . ';' . $no_of_older_leads . ';' . $no_of_lead_with_contract . ';' . $credit_info['no_of_active_credits'] . ';' . $credit_info['average_credit_amount'] . ';' . $no_of_addresses . ';' . $days_since;
    fputcsv($file,explode(';',$leads_fields), ";");
    $lead_count++;
}
fclose($file); 
echo "$lead_count leads are written to csv file.";
exit;