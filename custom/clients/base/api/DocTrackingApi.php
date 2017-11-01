<?php
require_once('include/SugarQuery/SugarQuery.php');

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class DocTrackingApi extends SugarApi
{
    public function registerApiRest()
    {
        return array(
            'SaveDocument' => array(
                'reqType' => 'POST',
                'noLoginRequired' => false,
                'path' => array('DocTracking', 'SaveDocument'),
                'pathVars' => array('', ''),
                'method' => 'saveDocument',
                'shortHelp' => 'Save Document and related Document Tracking Collection',
                'longHelp' => '',
                ),
            'RemoveDocRelTracks' => array(
                'reqType' => 'POST',
                'noLoginRequired' => false,
                'path' => array('DocTracking', 'removeDocRelTracks'),
                'pathVars' => array('', ''),
                'method' => 'removeDocRelTracks',
                'shortHelp' => 'Remove related Document Tracking Collection records',
                'longHelp' => '',
                ),
            );
    }

	/**
     * Save Document and related Document Tracking Collection
     */
    public function saveDocument($api, $args){
        
        $toBeDeleted = json_decode($args['toBeDeletedTrackCollecion'],true);
        $docTrackCollection = json_decode($args['docTrackCollection'],true);
        $manualDocTrackCollection = json_decode($args['manualDocTrackCollection'],true);
        
        $parent = $args['parent'];
        $documentId = $args['documentId'];
        //$parentModule = $parent['module'];
        $parentLink = $parent['link'];
        if(isset($parent['originalLink']) && !empty($parent['originalLink'])){
            $parentLink = $parent['originalLink'];
        }
        $parentId = $parent['id'];
        $docTrackModuleName = 'dotb7_document_tracking';
        global $app_list_strings;
		
        $GLOBALS['log']->debug('To be Deleted ::  '.print_r($toBeDeleted,1));
        $GLOBALS['log']->debug('Doc Track Collection :: '.print_r($docTrackCollection,1));
        $GLOBALS['log']->debug('Manual Doc Track Collection :: '.print_r($manualDocTrackCollection,1));
        
        // deleted the doc_track categories
        if(!empty($toBeDeleted)){
            $id_del = implode(',', array_map('add_quotes', $toBeDeleted));
            $sql_deleted = 'UPDATE dotb7_document_tracking SET deleted = 1 WHERE id IN ('.$id_del.')';
            $GLOBALS['log']->debug("SQL Query :: ".$sql_deleted);
            $GLOBALS['db']->query($sql_deleted); 
        }
                
        $documentBean = new Document();
        if($documentBean->retrieve($documentId)){
            if($documentBean->load_relationship($parentLink)){
                $documentBean->$parentLink->add($parentId);
                //$documentBean->save();
            }
        }

        $mergedDocTrackCollection = array();
        foreach($docTrackCollection as $docTrack){
            $mergedDocTrackCollection[] = $docTrack;
        }
        foreach($manualDocTrackCollection as $manualTrack){
            $mergedDocTrackCollection[] = $manualTrack;
        }

        $GLOBALS['log']->debug('Merged Doc Track Collection :: '.print_r($mergedDocTrackCollection,1));
        
        $categories_merged_names = array();
        $mergedDocCategoires = array();
        $categories_ids_deleted = array();
        
        foreach($mergedDocTrackCollection as $k => $objData){
            if(!isset($mergedDocCategoires[$objData['category']])){
                $mergedDocCategoires[$objData['category']] = array(
                        'status' => $objData['status'],
                        'description' => (empty($objData['description'])? "" : $objData['description']),
                        'month' => $objData['month'],
                        'id' => $objData['id'],
                        'category' => $objData['category'],
                );
            }else {

                if($objData['category'] == 'ok'){
                    $mergedDocCategoires[$objData['category']]['status'] = $objData['status'];
                } else if($objData['category'] == 'nok' && $mergedDocCategoires[$objData['category']]['status']!='ok'){
                    $mergedDocCategoires[$objData['category']]['status'] = $objData['status'];
                } else if($mergedDocCategoires[$objData['category']]['status']!='ok' 
                        && $mergedDocCategoires[$objData['category']]['status']!='nok'){
                    $mergedDocCategoires[$objData['category']]['status'] = $objData['status'];
                }
                
                $month1 = $mergedDocCategoires[$objData['category']]['month'];
                $month2 = $objData['month'];
                
                if(empty($month1)){
                    $month1 = array();
                }
                if(empty($month2)){
                    $month2 = array();
                }
                
                $GLOBALS['log']->debug('Month 1 ::  '.print_r($month1,1));
                $GLOBALS['log']->debug('Month 2 ::  '.print_r($month2,1));
                
                $merged_months = array_unique(array_merge($month1, $month2));
                $GLOBALS['log']->debug('Merged Months :: '.print_r($merged_months,1));
                $mergedDocCategoires[$objData['category']]['month'] = $merged_months;
                
                $description =  (isset($mergedDocCategoires[$objData['category']]['description']) ? $mergedDocCategoires[$objData['category']]['description'] : ""  ) 
                                . "\r\n" . (isset($objData['description']) ? $objData['description'] : "");
                $mergedDocCategoires[$objData['category']]['description'] = $description;
                $mergedDocCategoires[$objData['category']]['id'] =  '';
                // After merging deleted the old same categories to be merged
                $categories_merged_names[] = $mergedDocCategoires[$objData['category']]['category'];
            }
        }
        
        // Getting the IDs of tracking to be deleted
        $categories_merged_names1 = array_unique($categories_merged_names);
        
        foreach($mergedDocTrackCollection as $key => $record_arr){
            if(in_array($record_arr['category'], $categories_merged_names1)){
                $categories_ids_deleted[] = $record_arr['id'];
            }
        }
        
        $GLOBALS['log']->debug('Record ID to be deleted  :: '.print_r($categories_ids_deleted,1));
        $GLOBALS['log']->debug('Merged Categories Names :: '.print_r($categories_merged_names1,1));
        $GLOBALS['log']->debug('collection after merging the duplicates :: '.print_r($mergedDocCategoires,1));
        
        /** 
        * Create Document Tracking Records
        */
        
        if(!empty($categories_ids_deleted)){
            $id_del = implode(',', array_map('add_quotes', $categories_ids_deleted));
            $update_tracking = 'UPDATE dotb7_document_tracking SET deleted = 1 WHERE deleted = 0 AND id IN ('.$id_del.')';
            $GLOBALS['db']->query($update_tracking);
        }
        
        foreach ($mergedDocCategoires as $docTrack) {

            $docTrackItemBean = new $docTrackModuleName();
			/**
			* if already exit then fetch and update
			*/
			if(!empty($docTrack['id']) &&  $docTrackItemBean->retrieve($docTrack['id'])){
				foreach ($docTrack as $key => $value) {
					if($key == 'month'){
						$docTrackItemBean->$key = $this->convertArrayToMultiEnum($value);
					}
					else if($key == 'category'){
						if(array_search($value, $app_list_strings['dotb_document_category_list'])){
							$category_key = array_search($value, $app_list_strings['dotb_document_category_list']);
						}else{
							$category_key = $value;
						}
						
						$docTrackItemBean->$key = $category_key;
					}
					else{
						$docTrackItemBean->$key = $value;
					}
				}
				if(empty($docTrackItemBean->name)){
					$docTrackItemBean->name = $docTrackItemBean->category;
				}
				$docTrackItemBean->save();
			}else{
				/**
				* Create and relate new document tracking record
				*/
				$docTrackItemBean = BeanFactory::newBean($docTrackModuleName);
				$docTrackItemBean->id = $docTrack['id'];
				$docTrackItemBean->new_with_id = true;
				foreach ($docTrack as $key => $value) {
					if($key == "category"){
						if(array_search($value, $app_list_strings['dotb_document_category_list'])){
							$category_key = array_search($value, $app_list_strings['dotb_document_category_list']);
						}else{
							$category_key = $value;
						}

						$docTrackItemBean->$key = $category_key;
					}
					else if($key == 'month'){
						$docTrackItemBean->$key = encodeMultienumValue($value);
					}
					else {
							$docTrackItemBean->$key = $value;
					}
				}
				
				if(empty($docTrackItemBean->name)){
					$docTrackItemBean->name = $docTrackItemBean->category;
				}
				
				$docTrackItemBean->save();
				if($docTrackItemBean->load_relationship('documents_dotb7_document_tracking_1')){
					$docTrackItemBean->documents_dotb7_document_tracking_1->add($documentId);
					$docTrackItemBean->save();
				}
			}

			/* Removing Documents with same category And no files*/


			$leadBean = BeanFactory::getBean("Leads" , $parentId );

			if($leadBean->load_relationship($parentLink)){
				$relatedDocs = $leadBean->$parentLink->get();
			}

			foreach($relatedDocs as $k=>$v){
				$docBean = BeanFactory::getBean("Documents" , $v );
				$docBean->load_relationship('documents_dotb7_document_tracking_1'); 
				$relatedTrackings = $docBean->documents_dotb7_document_tracking_1->get();

				if(!empty($documentBean->rev_file_name)){

				if(count($relatedTrackings) == 1){
					 $trackBean = BeanFactory::getBean("dotb7_document_tracking" , $relatedTrackings[0] );
					
							if(empty($docBean->rev_file_name) && $trackBean->category == $docTrack['category']){
									$trackBean->deleted = 1;
									$trackBean->save();

									$docBean->deleted = 1;
									$docBean->save();
							}
					 }
				}

			}

		}
		$documentBean->save();
		return true;
    }

    /**
     * Remove related Document Tracking Collection records
     */
    public function RemoveDocRelTracks($api, $args){
        $documentId = $args['documentId'];
        $documentBean = new Document();
        if($documentBean->retrieve($documentId)){
            $docTracks = $documentBean->get_linked_beans('documents_dotb7_document_tracking_1','dotb7_document_tracking');
            foreach ( $docTracks as $dt ) { 
                $dt->deleted = 1;
                $dt->save();
            }
        }
    }
    /** 
    * Fetch Document and DocTracking Records
    */
    public function fetchDocuments($api, $args){
        
    }
    
    private function convertArrayToMultiEnum($monthArr){
        $updateMonths = array();
        foreach($monthArr as $key => $month){
            if(!empty($month)){
                $updateMonths[] = '^'.$month.'^';
            }
        }
        if(!empty($updateMonths)){
            return implode(',',$updateMonths);
        }else{
            return "";
        }
    }
}

?>