<?php

class populateContractDate{

	 function populateDate($bean, $event, $arguments){	
	 	if ($bean->load_relationship('contracts_leads_1'))
	 	{
	 		$relatedBeans = $bean->contracts_leads_1->getBeans();
	 		$parentLead = false;
	 		if (!empty($relatedBeans))
	 		{
	 			reset($relatedBeans);        
	 			$parentLead = current($relatedBeans);
	 			if($parentLead && !empty($bean->contract_date_c)){
	 				$date = new DateTime($bean->contract_date_c);
					$nextDay = clone $date;
					$nextDay->add(new DateInterval('P15D'));
					if($nextDay->format('D') == 'Sat'){
						$nextDay->add(new DateInterval('P2D'));
					}
					else if($nextDay->format('D') == 'Sun'){
						$nextDay->add(new DateInterval('P1D'));
					}
	 				$parentLead->calc_contract_date_c = $nextDay->format('Y-m-d');
	 			}
	 			if($parentLead && !empty($bean->paying_date_c)){
	 				$date = new DateTime($bean->paying_date_c);
	 				$parentLead->contract_paying_date_c = $date->format('Y-m-d');
	 			}
                                
	 			if($parentLead && !empty($bean->date_entered)){
	 				$date = new DateTime($bean->date_entered);
	 				$parentLead->contract_date_entered = $date->format('Y-m-d');
	 			}
	 			if($parentLead && !empty($bean->fetched_row['date_entered'])){
					$date = new DateTime($bean->fetched_row['date_entered']);
	 				$parentLead->contract_date_entered = $date->format('Y-m-d');
	 			}
				$parentLead->processed = true;
	 			$parentLead->save();
	 		}
	 	}
	}

}

?>