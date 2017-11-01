<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class fillCreditAmount {

    function creditAmount($bean, $event, $arguments) {
		if($bean->parent_type=='Leads'){
			$lead_id = $bean->parent_id;
			$leadObj = BeanFactory::getBean("Leads", $lead_id);
			$non_rejected_app = 0;
            $task_credit_amount = '';
			if ($leadObj->load_relationship('leads_opportunities_1')) {
                $relatedApplications = $leadObj->leads_opportunities_1->getBeans();
                foreach ($relatedApplications as $application) {
                    if ($application->provider_status_id_c != 'rejected') {
                        $task_credit_amount = $application->contract_credit_amount;
                        $non_rejected_app++;
                    }
                }
            }
			//if 1 non-rejected application then fill with application amount other wise fill with lead credit amount
			if($non_rejected_app == 1){
				$bean->lead_amount_c = $task_credit_amount;
                $bean->is_lead_amount_from_app = true;
			} else {
				$bean->lead_amount_c = $leadObj->credit_amount_c;
			}
		}
		
    }

}
