<?php

class populateName {

	function getApplicationName($bean, $event, $arguments) {

			global $app_list_strings,$timedate;
			$provider_list = $app_list_strings['dotb_credit_provider_list'];
                        if(!empty($bean->leads_opportunities_1leads_ida)){
                            $lead=new Lead();
                            $lead->retrieve($bean->leads_opportunities_1leads_ida);
                            
			$first_name = explode(" ", $bean->leads_opportunities_1_name);
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
				$bean->name = $lead->first_name." ".$provider." ".$interest_rate." ".$credit_duration." NOPPI ".$date_entered."  Antrag";		
			}
			else{
				$bean->name = $lead->first_name." ".$provider." ".$interest_rate." ".$credit_duration." PPI  ".$date_entered."  Antrag";	
			}
			
                        }
			

	}

}

?>