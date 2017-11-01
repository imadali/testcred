<?php

//require_once ('modules/Currencies/Currency.php');

class populateDescription {

	function getDescription($bean, $event, $arguments) {

			//$currency = new Currency();

			global $app_list_strings;
			$provider_list = $app_list_strings['dotb_credit_provider_list'];

			$first_name = explode(" ", $bean->leads_opportunities_1_name);
			$provider = $provider_list[$bean->provider_id_c];
			$credit_amount = (double)$bean->credit_amount_c;
			$credit_amount = number_format($credit_amount, 2);
			$credit_duration = $bean->credit_duration_c;
			$interest_rate = $bean->interest_rate_c;
			$ppi = $bean->ppi_c;

			if($provider == "RCI"){
				$bean->description = $provider." ".$credit_amount." ".$interest_rate;	
			}
			else{
					if($ppi == 0){
						$bean->description = $provider." ".$credit_amount." ".$interest_rate." NOPPI";		
					}
					else{
						$bean->description = $provider." ".$credit_amount." ".$interest_rate." PPI";	
					}
					
					
			}
			
			
			

	}

}

?>