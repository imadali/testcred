<?php

require_once('include/api/SugarApi.php');
require_once('include/SugarQuery/SugarQuery.php');

class GetRiskProfilingDataApi extends SugarApi {
	/**
	* @function registerApiRest
	* @description registering the API call to make project status field read-only or not
	* @return type
	*/
	public function registerApiRest() {
		return array(
			'getRiskProfilingData' => array(
				'reqType' => 'GET',
				'path' => array('dotb9_risk_profiling', 'get_risk_profiling_data'),
				'pathVars' => array('', ''),
				'method' => 'getRiskProfilingData',
				'shortHelp' => 'Get risk profiling data for banks',
				'longHelp' => '',
			),
			'updateMeta' => array(
				'reqType' => 'GET',
				'path' => array('dotb9_risk_profiling', 'update_meta'),
				'pathVars' => array('', ''),
				'method' => 'updateMeta',
				'shortHelp' => 'update meta',
				'longHelp' => '',
			),
			'saveRiskProfilingData' => array(
				'reqType' => 'GET',
				'path' => array('dotb9_risk_profiling', 'save_risk_profiling_data'),
				'pathVars' => array('', ''),
				'method' => 'saveRiskProfilingData',
				'shortHelp' => 'Save records',
				'longHelp' => '',
			),
		);
	}
	
	/**
	** Get risk profiling data
	**/
	public function getRiskProfilingData($api, $args) {
		$riskData = array();
		$fieldName = array();

		$riskProfilingQuery = new SugarQuery();
		$riskProfilingQuery->select(array('id'));
		$riskProfilingQuery->from(BeanFactory::getBean('dotb9_risk_profiling'));
		$riskProfilingQuery->where()->equals('deleted','0');
		$riskProfilingQuery->join('accounts_dotb9_risk_profiling_1', array('alias' => 'accounts_dotb9_risk_profiling_1'));
		$riskProfilingQuery->orderBy('accounts_dotb9_risk_profiling_1.bank_order_c', 'ASC');
		$riskProfilingResults = $riskProfilingQuery->execute();
		
		// get field definition
		$riskBean = BeanFactory::getBean('dotb9_risk_profiling');
		$fieldDefs = $riskBean->getFieldDefinitions();
		
		//array of all enum field name
		foreach($fieldDefs as $field){
			if($field['type'] == 'enum' && $field['name'] != 'status_c'){
				$fieldName[] = $field['name'];
			}
		}
		
		foreach ($riskProfilingResults as $key => $value){
			$record = array();
			$riskBean = new dotb9_risk_profiling();
			$riskBean->retrieve($value['id']);
			
			// record id and bank name
			$record['recordId'] = $riskBean->id;
			$record['bankName'] = $riskBean->accounts_dotb9_risk_profiling_1_name;
			foreach ($fieldName as $fnKey => $fnValue){
				$record[$fnValue] = $riskBean->$fnValue;
			}
			$riskData[] = $record;
		}
		
		//get dummy record id
		$dummyRecordQuery = new SugarQuery();
		$dummyRecordQuery->select(array('id'));
		$dummyRecordQuery->from(BeanFactory::getBean('dotb9_risk_profiling'));
		$dummyRecordQuery->where()->equals('deleted','0');
		$dummyRecordQuery->where()->equals('name', 'RiskFactor');
		$dummyRecordQuery->where()->equals('status_c', 'Inactive');
		$dummyRecordResults = $dummyRecordQuery->execute();
		foreach ($dummyRecordResults as $key => $value){
			$record = array();
			$dummyRiskBean = new dotb9_risk_profiling();
			$dummyRiskBean->retrieve($value['id']);
			
			// record id and bank name
			$record['recordId'] = $dummyRiskBean->id;
			$record['bankName'] = '';
			foreach ($fieldName as $fnKey => $fnValue){
				$record[$fnValue] = $dummyRiskBean->$fnValue;
			}
			$riskData[] = $record;
		}
		
		// $GLOBALS['log']->fatal("Risk Data");
		// $GLOBALS['log']->fatal(print_r($riskData,true));

		return json_encode($riskData);
	}
	
	public function updateMeta($api, $args){
		/* require_once("include/entrypoint.php");
		require_once("modules/Administration/QuickRepairAndRebuild.php");

		$rac = new RepairAndClear();

		$rac->repairAndClearAll(array('clearAll'),array(translate('LBL_ALL_MODULES')), false,true); */ 
		if ( file_exists('cache/api/metadata/metadata_base_public.php') ) {
            unlink('cache/api/metadata/metadata_base_public.php');
        }
		ob_clean();
		return json_encode('true');
	}
	
	/**
	** Save one field for all risk profiling records
	**/
	public function saveRiskProfilingData($api, $args){
		$rpData = $args;
		
		foreach ($rpData as $key => $value){
			if($key != '__sugar_url'){
				$riskProfilingBean = new dotb9_risk_profiling();
				$fieldId = explode("/",$key);
				$riskProfilingBean->retrieve($fieldId[1]);
				$riskProfilingBean->$fieldId[0] = $value;
				$riskProfilingBean->save();
			}
		}
		return json_encode('true');
	}
}

?>