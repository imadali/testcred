<?php

// add the job key to the list of job strings
array_push($job_strings, 'getCreditCheckResponse');


/**
 * Format the Intrum or Deltavista answer to be correctly displayed within the note
 * @author : dotBase - frinaldi - #12034
 */
 
function handleJsonAnswerForLeads(&$description, $key, $value,$nbOfTab)
{
    $nbOfTab++;
    //En vue consultation, Sugar n'affiche ni les tabulations ni les leading spaces, donc on utilise des tirets
    for($i=0;$i<=$nbOfTab;$i++){$tab.="__"; /* $tab.="\t"; */}
    if (is_array($value)) {
            $description.="\n$tab$key";
            foreach ($value as $k => $v) {                
                handleJsonAnswerForLeads($description,$k,$v,$nbOfTab);
            }
    }else{
        
        $description .= "\n$tab$key => $value";
    }
}


/**
 * Appelle le WS Neolution pour chacune des requêtes en attente de réponse
 Neolution called WS for each response pending requests
 
 * Si la réponse est disponible, crée une note attachée sous la Credit Request
 
If the response is available , creates a note attached under the Credit Request
 */
function getCreditCheckResponse()
{
    global $sugar_config; // we need to know the url
    $db = DBManagerFactory::getInstance();
    
    // Liste des requêtes DeltaVista en attente
	// Deltavista list of pending requests
    $query = "SELECT leads.id, leads_cstm.deltavista_request_id_c as request_id
                  FROM leads LEFT JOIN leads_cstm ON leads.id=leads_cstm.id_c
                    WHERE NOT deleted
                    AND leads_cstm.deltavista_request_id_c IS NOT NULL
                    AND leads_cstm.deltavista_request_id_c !=''
                    AND leads_cstm.has_deltavista_response_c = 'no'";
    
    $rs = $db->query($query);
    
    while ($row = $db->fetchByAssoc($rs)) {
        // URL : http://domain-name/neolution/api/CreditCheckRequest/<id>
        $url = $sugar_config['neolution_url'] . $row['request_id'];
        
        // Call WS
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
        $result = curl_exec($ch);
        
        // Extraction du JSON dans un tableau
        $result_data = json_decode($result, true);
		
        // Si la réponse est finale (statut)
		// If the answer is final ( status)
        if ($result_data['Status'] == 'Response') {
            $leadBean = BeanFactory::getBean('Leads',$row['id']);
            if(!empty($leadBean->id)){
            $note = BeanFactory::newBean('Notes');
            $note->name = 'DeltaVista credit check response';
            $note->team_id = '1';
            $note->team_set_id = '1';
            $note->parent_type = 'Leads';
            $note->parent_id = $leadBean->id;
			
            foreach ($result_data as $key => $val) {
                handleJsonAnswerForLeads($note->description,$key,$val,0); //#12034
                //to be replace by previous line - begin 
                /*if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        $note->description .= "\n     $k => $v";
                    }
                } else
                    $note->description .= "\n $key => $val";*/
                //to be replace by previous line - end 
                
            }
            
            $note->save();
           
            // Update has_response field (to avoid confirmations 1000 )
            $leadBean->has_deltavista_response_c = 'yes';
            if (!empty($result_data['ResponseContent']) && is_array($result_data['ResponseContent']['DebtCollectionReport'])) {
                $result_details = array_shift($result_data['ResponseContent']['DebtCollectionReport']);
                if (!empty($result_details['RiskClass'])) {
                    $leadBean->deltavista_code_c = $result_details['RiskClass'];
                }
            }
			
			$response_data = json_decode($result_data['ResponseContent'], true);
			//save score
			//if (!empty($result_data['ResponseContent'])) {
				$leadBean->deltavista_score_c = $response_data['Score']; 
				$leadBean->dotb_traffic_light_c = $response_data['TrafficLight']; 
            //}
            
			$delta_vista_response = "Score: " . $response_data['Score'] . "  Traffic light: " . $response_data['TrafficLight'] . "  Enquiry date:" .$result_data['SentDate'] . "  Answer Date:" . $result_data['ResponseDate'] . " \n \n";
			$debt_collection = $response_data['DebtCollectionReport'];
			
			foreach($debt_collection as $key => $value){
				foreach($value as $inner_key => $inner_value){
					$delta_vista_response .= $inner_key . ": " . $inner_value . "  ";
				}
				$delta_vista_response .= " \n ";
			}

			$leadBean->dotb_deltavista_response_c = $delta_vista_response;            
            $leadBean->save(false);
        }
            // Mise à jour du champ has_response
        } elseif ($result_data['Status'] == 'Error') {
			$leadBean = BeanFactory::getBean('Leads',$row['id']);
            $note = BeanFactory::newBean('Notes');
            $note->name = 'DeltaVista error';
            $note->team_id = '1';
            $note->team_set_id = '1';
            $note->parent_type = 'Leads';
            $note->parent_id = $row['id'];
            $note->description = $result_data['StatusMessage'];
            
            $note->save();
            $leadBean->dotb_deltavista_response_c = $result;
            $leadBean->save(false);
            
            // Update has_response field (to avoid error 1000 Ratings)
            $db->query("UPDATE leads_cstm SET has_deltavista_response_c = 'yes' WHERE id_c = '" . $row['id'] . "'");
            insertRecordInAuditTable('Leads', 'has_deltavista_response_c', $leadBean->has_deltavista_response_c, 'yes', $row['id'], 'enum');
        } 
    }
    
	// Intrum list of pending requests			
	$query = "SELECT leads.id, leads_cstm.intrum_request_id_c as request_id
                  FROM leads LEFT JOIN leads_cstm ON leads.id=leads_cstm.id_c
                    WHERE NOT deleted
                    AND leads_cstm.intrum_request_id_c IS NOT NULL
                    AND leads_cstm.has_intrum_response_c = 'no'";
    
    $rs = $db->query($query);
    
    while ($row = $db->fetchByAssoc($rs)) {
        // URL : http://domain-name/neolution/api/CreditCheckRequest/<id>
        $url = $sugar_config['neolution_url'] . $row['request_id'];
        // Appel WS
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
        $result = curl_exec($ch);
        
        // Extraction of JSON in a table
        $result_data = json_decode($result, true);
        
        // If the answer is final ( status)
        if ($result_data['Status'] == 'Response') {
			$leadBeanIntrum = BeanFactory::getBean('Leads',$row['id']);
			
            $note = BeanFactory::newBean('Notes');
            $note->name = 'Intrum credit check response';
            $note->team_id = '1';
            $note->team_set_id = '1';
            $note->parent_type = 'Leads';
            $note->parent_id = $row['id'];
            foreach ($result_data as $key => $val) {
                handleJsonAnswerForLeads($note->description,$key,$val,0); //#12034
                
                //to be replace by previous line - begin 
                /*if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        $note->description .= "\n     $k => $v";
                    }
                } else
                    $note->description .= "\n $key => $val";*/
                //to be replace by previous line - end 
                
            }

            $note->save();
            
			$intrum_response_data = json_decode($result_data['ResponseContent'], true);
			$intrum_response = "Score: " . $intrum_response_data['Score'] . "  Sent Date:" .$result_data['SentDate'] . "  Response Date:" . $result_data['ResponseDate'] . " \n \n";
			$debt_collection = $intrum_response_data['DebtCollectionReport'];
			
			foreach($debt_collection as $key => $value){
				foreach($value as $inner_key => $inner_value){
					$intrum_response .= $inner_key . ": " . $inner_value . "  ";
				}
				$intrum_response .= " \n ";
			}
			
			$leadBeanIntrum->dotb_intrum_response_c = $intrum_response; 
			$leadBeanIntrum->intrum_score_c = $intrum_response_data['Score'];
			$leadBeanIntrum->save(false);
            // Update has_response field (to avoid confirmations 1000 ) and intrum score
            $db->query("UPDATE leads_cstm SET has_intrum_response_c = 'yes' WHERE id_c = '" . $row['id'] . "'");
            insertRecordInAuditTable('Leads', 'has_intrum_response_c', $leadBeanIntrum->has_intrum_response_c, 'yes', $row['id'], 'enum');
            
        } elseif ($result_data['Status'] == 'Error') {
			$leadBeanIntrum = BeanFactory::getBean('Leads',$row['id']);
            $note = BeanFactory::newBean('Notes');
            $note->name = 'Intrum error';
            $note->team_id = '1';
            $note->team_set_id = '1';
            $note->parent_type = 'Leads';
            $note->parent_id = $row['id'];
            $note->description = $result_data['StatusMessage'];
            
            $note->save();
            
			$leadBeanIntrum->dotb_intrum_response_c = $result;
            $leadBeanIntrum->save(false);
            // Mise à jour du champ has_response (pour éviter 1000 notes d'erreur)
            $db->query("UPDATE leads_cstm SET has_intrum_response_c = 'yes' WHERE id_c = '" . $row['id'] . "'");
        } 
    }
    
    // return true for completed
    return true;
}

?>