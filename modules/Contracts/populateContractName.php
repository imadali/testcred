<?php

class populateContractName{

	 function getContractName($bean, $event, $arguments){

	 	if($bean->create_contact == false){


		global $app_list_strings,$timedate;
			$provider_list = $app_list_strings['dotb_credit_provider_list'];
                if(!empty($bean->contracts_leads_1leads_idb)){
                    $lead=new Lead();
                    $lead->retrieve($bean->contracts_leads_1leads_idb);

					$first_name = explode(" ", $bean->contracts_leads_1_name);
					
					$provider = $provider_list[$bean->provider_id_c];
					$credit_amount = $bean->credit_amount_c;
					$interest_rate = $bean->interest_rate_c;
					$credit_duration = $bean->credit_duration_c;
					$ppi = $bean->ppi_c;
                    if(isset($bean->fetched_row['date_entered']) && !empty($bean->fetched_row['date_entered'])){
						$date_entered = $bean->fetched_row['date_entered'];
                    }else{
                    	$time = $timedate->asDbTime($timedate->getNow());
                        $date_entered = $timedate->nowDbDate()." ".$time;
                    }
	                        

					if($ppi == 0){
						$bean->name = $lead->first_name." ".$provider." ".$credit_amount." ".$interest_rate." ".$credit_duration." NOPPI ".$date_entered."  Vertrag";	
					}
					else{
						$bean->name = $lead->first_name." ".$provider." ".$credit_amount." ".$interest_rate." ".$credit_duration." PPI  ".$date_entered."  Vertrag";	
						
					}
			
                }
            }
		}
	}

?>