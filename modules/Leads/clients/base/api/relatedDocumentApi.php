<?php


require_once('include/api/SugarApi.php');
require_once('include/SugarQuery/SugarQuery.php');

class relatedDocumentApi extends SugarApi {
	/**
	* @function registerApiRest
	* @description registering the API call to make project status field read-only or not
	* @return type
	*/
	public function registerApiRest() {
		return array(
			'getRelatedDocuments' => array(
				'reqType' => 'GET',
				'path' => array('Leads','GetRelatedDocuments','?'),
                                'pathVars' => array('','','id'),
				'method' => 'getRelatedDocuments',
				'shortHelp' => 'This api will return the related documents and their status for a Lead',
				'longHelp' => '',
			),
		);
	}
	
	/**
     *
     * @param ServiceBase $api
     * @param array $args
     */
        public function getRelatedDocuments(ServiceBase $api, array $args)
        {
            
                $leadId = $args['id'];
                $seed = BeanFactory::newBean('Leads' );
                $q = new SugarQuery();
                $q->from($seed);
                
                
                $q->joinTable('leads_documents_1_c', array('alias' => 'ld', 'joinType' => 'INNER', 'linkingTable' => true))
                    ->on()
                    ->equalsField('ld.leads_documents_1leads_ida ' , "leads.id");
                
                $q->joinTable('documents', array('alias' => 'd', 'joinType' => 'INNER', 'linkingTable' => true))
                    ->on()
                    ->equalsField('ld.leads_documents_1documents_idb', 'd.id'); 
                
                $q->joinTable('documents_dotb7_document_tracking_1_c', array('alias' => 'dt', 'joinType' => 'INNER', 'linkingTable' => true))
                    ->on()
                    ->equalsField('dt.documents_dotb7_document_tracking_1documents_ida' , "d.id");
                
                $q->joinTable('dotb7_document_tracking', array('alias' => 'track', 'joinType' => 'INNER', 'linkingTable' => true))
                    ->on()
                    ->equalsField('dt.documents_dotb7_document_tracking_1dotb7_document_tracking_idb', 'track.id'); 
                
                $q->select(array("track.category" , "track.status" , "track.description"));

                $q->where()->equals("leads.id" , "$leadId");
                $q->where()->equals("track.status" , "nok");
                
                $q->where()->equals("ld.deleted" , "0");
                $q->where()->equals("leads.deleted" , "0");
                $q->where()->equals("d.deleted" , "0");
                $q->where()->equals("dt.deleted" , "0");
                $q->where()->equals("track.deleted" , "0");
                
                return $q->execute();
        }
    }