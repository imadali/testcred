<?php

require_once('include/api/SugarApi.php');
require_once('include/SugarQuery/SugarQuery.php');

class getDocumentsIdApi extends SugarApi {

    /**
     * @function registerApiRest
     * @description registering the API call to make project status field read-only or not
     * @return type
     */
    public function registerApiRest() {
        return array(
            'getRelatedDocumentsId' => array(
                'reqType' => 'GET',
                'path' => array('Leads', 'GetRelatedDocumentsId', '?'),
                'pathVars' => array('', '', 'id'),
                'method' => 'getRelatedDocumentsId',
                'shortHelp' => 'This api will return the related documents and their status for a Lead',
                'longHelp' => '',
            ),
            'removeExtraCategories' => array(
                'reqType' => 'GET',
                'path' => array('Leads', 'removeExtraCategories', '?','?'),
                'pathVars' => array('', '', 'record_id','module_name'),
                'method' => 'removeExtraCategories',
                'shortHelp' => 'This api will remove The Category-Item which is 
                                not referring to a physical Document can be removed as 
                                soon as the very same category is being set and relating to a physical Document',
                'longHelp' => '',
            ),
        );
    }

    /**
     *
     * @param ServiceBase $api
     * @param array $args
     */
    public function getRelatedDocumentsId(ServiceBase $api, array $args) {
        $path = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));

        $leadId = $args['id'];
        //$GLOBALS['log']->fatal("id: " . $leadId);
        $lead = new Lead();
        $lead->retrieve($leadId);
        $documents = $lead->get_linked_beans('leads_documents_1', 'Document');
        // $GLOBALS['log']->fatal("==============doc============");
        $arr = array();
        $doc_id = array();
        $doc_tracking_records = array();

        // For getting all the categories related to Document
        foreach ($documents as $docObj) {
            $doc_id[] = $docObj->id;
        }

        if (!empty($doc_id)) {
            $rec_ids = implode(',', array_map('add_quotes', $doc_id));
            $sql_doc_trac = ' SELECT tracking.category, tracking.month, tracking.description, doc_tracking.documents_dotb7_document_tracking_1documents_ida AS doc_id FROM documents_dotb7_document_tracking_1_c AS doc_tracking'
                    . ' INNER JOIN dotb7_document_tracking  AS tracking  ON doc_tracking.documents_dotb7_document_tracking_1dotb7_document_tracking_idb = tracking.id AND tracking.deleted = 0 '
                    . ' WHERE doc_tracking.deleted = 0 AND doc_tracking.documents_dotb7_document_tracking_1documents_ida IN (' . $rec_ids . ') ';

            $results = $GLOBALS['db']->query($sql_doc_trac);
            $category = '';
            while ($row = $GLOBALS['db']->fetchByAssoc($results)) {
                $category = $row['category'];
                if(isset($GLOBALS['app_list_strings']['dotb_document_category_list'][$row['category']])) {
                    $category = $GLOBALS['app_list_strings']['dotb_document_category_list'][$row['category']];
                }
                $doc_tracking_records[$row['doc_id']]['category'][] = is_null($category) ? "" : $category;
                $doc_tracking_records[$row['doc_id']]['notes'][] = is_null($row['description']) ? "" : $row['description'];

                $month = $this->translateMultiEnumFeild($row['month']);
                $doc_tracking_records[$row['doc_id']]['month'][] = $month;
            }
        }

        foreach ($documents as $document) {
            if (!empty($document->document_revision_id)) {
                $pathToFile = $path . "/upload/" . $document->document_revision_id;
                if (file_exists($pathToFile)) {
                    $arr[$document->id]['name'] = $document->name;
                    if (isset($doc_tracking_records[$document->id]['category'])) {
                        $arr[$document->id]['category'] = $doc_tracking_records[$document->id]['category'];
                    }
                    if (isset($doc_tracking_records[$document->id]['notes'])) {
                        $arr[$document->id]['notes'] = $doc_tracking_records[$document->id]['notes'];
                    }
                    if (isset($doc_tracking_records[$document->id]['month'])) {
                        $arr[$document->id]['month'] = $doc_tracking_records[$document->id]['month'];
                    }
                }
            }
        }
        return $arr;
    }
    
    private function multi_array_search($search_for, $search_in) {
        foreach($search_in as $k => $v1) {
            if($v1['status'] == $search_for) {
                return true;
            }
        }
        return false;
    }
    
    private function multi_array_search_revision($search_in) {
        foreach($search_in as $k => $v1) {
            if(!empty($v1['doc_revision_id'])) {
                return true;
            }
        }
        return false;
    }

    private function translateMultiEnumFeild($enumArr) {
        $commaSeperated = '';
        $i = 0;
        if (!empty($enumArr) || !is_null($enumArr)) {
            $convertedArr = explode(',', $enumArr);
            foreach ($convertedArr as $month) {
                if ($i != 0) {
                    $commaSeperated.=',';
                }
                $t_month = str_replace('^', '', $month);
                if (isset($GLOBALS['app_list_strings']['document_month_list'][$t_month]) && !empty($GLOBALS['app_list_strings']['document_month_list'][$t_month])) {
                    $commaSeperated.=$GLOBALS['app_list_strings']['document_month_list'][$t_month];
                    $i++;
                }
            }
        }
        return $commaSeperated;
    }
    
    public function removeExtraCategories(ServiceBase $api, array $args) {
        
        $this->requireArgs($args, array('record_id','module_name'));
  
        if($args['module_name'] == 'Leads') {
            $sql_get_doc_categories = ' SELECT doc.document_revision_id as doc_revision_id, categ_tracking.status as track_status,
                                                   doc_tracking.documents_dotb7_document_tracking_1documents_ida AS doc_id, 
                                                   categ_tracking.id AS tracking_id,
                                                   categ_tracking.category
                                    FROM leads_documents_1_c AS lead_doc
                                    INNER JOIN documents AS doc 
                                        ON doc.id=lead_doc.leads_documents_1documents_idb 
                                            AND doc.deleted = 0
                                    INNER JOIN documents_dotb7_document_tracking_1_c AS doc_tracking
                                        ON doc_tracking.documents_dotb7_document_tracking_1documents_ida = lead_doc.leads_documents_1documents_idb
                                            AND doc_tracking.deleted = 0
                                    INNER JOIN dotb7_document_tracking AS categ_tracking
                                        ON categ_tracking.id = doc_tracking.documents_dotb7_document_tracking_1dotb7_document_tracking_idb
                                            AND categ_tracking.deleted = 0
                                    WHERE lead_doc.deleted = 0 AND lead_doc.leads_documents_1leads_ida = "'.$args['record_id'].'" 
                                    ORDER BY categ_tracking.date_modified DESC';
        } else {
            $sql_get_doc_categories = ' SELECT doc.document_revision_id as doc_revision_id, categ_tracking.status as track_status,
                                                   doc_tracking.documents_dotb7_document_tracking_1documents_ida AS doc_id, 
                                                   categ_tracking.id AS tracking_id,
                                                   categ_tracking.category
                                    FROM documents_contacts AS contact_doc
                                    INNER JOIN documents AS doc 
                                        ON doc.id=contact_doc.document_id 
                                            AND doc.deleted = 0
                                    INNER JOIN documents_dotb7_document_tracking_1_c AS doc_tracking
                                        ON doc_tracking.documents_dotb7_document_tracking_1documents_ida = contact_doc.document_id
                                            AND doc_tracking.deleted = 0
                                    INNER JOIN dotb7_document_tracking AS categ_tracking
                                        ON categ_tracking.id = doc_tracking.documents_dotb7_document_tracking_1dotb7_document_tracking_idb
                                            AND categ_tracking.deleted = 0
                                    WHERE contact_doc.deleted = 0 AND contact_doc.contact_id = "'.$args['record_id'].'"
                                    ORDER BY categ_tracking.date_modified DESC';
        }
        
        $category_evaluation = array();
        $temp_category_evaluation = array();

        $result = $GLOBALS['db']->query($sql_get_doc_categories);
        while($row = $GLOBALS['db']->fetchByAssoc($result)) {
            $category_evaluation[$row['category']][] = array('doc_id' => $row['doc_id'],
                                                        'doc_revision_id' => $row['doc_revision_id'],
                                                        'tracking_id' => $row['tracking_id'],
                                                        'tracking_status' => $row['track_status']   
                                               );
  
            $temp_category_evaluation[$row['doc_id']][] = $row['tracking_id'];
        }
        
        $message = translate('LBL_FOUND_NO_CATEGORY_TO_UPDATE', 'Leads');
        $level = 'info';
        
        if(!empty($category_evaluation)) {
            $category_to_be_deleted = array();
            
            foreach($category_evaluation as $key => $arr) {
                if(sizeof($arr) > 1) {
                    $upload_dir = rtrim($GLOBALS['sugar_config']['upload_dir'],'/');
                    foreach($arr as $k => $val) {
                        $stat_val = 'empty_'.$k;
                        if(!empty($val['tracking_status'])) {
                            $stat_val = $val['tracking_status'].$k;
                        }
                        $category_to_be_deleted[$key][$stat_val] = array('tracking_id' => $val['tracking_id'] 
                                                                        ,'status' => $val['tracking_status'], 
                                                                        'doc_revision_id' => $val['doc_revision_id']);
                    }
                }
            }
            
            $category_ids = array();
            if(!empty($category_to_be_deleted)) {
                foreach($category_to_be_deleted as $stat => $val) {
                    $nok_counter = 0;
                    $ok_counter = 0;
                    $fehlt_counter = 0;
                    foreach($val as $k => $arr) {
                        if(empty($arr['doc_revision_id']) && sizeof($val)>1) {
                            if( $arr['status']!='ok' && $arr['status']!='fehlt' && $arr['status']!='nok' && sizeof($val) > 1 
                                                    && ($this->$this->multi_array_search('ok',$val) || $this->multi_array_search('nok',$val) 
                                                            || $this->multi_array_search('fehlt',$val)  ) ) {
                                $category_ids[] = $arr['tracking_id'];
                            } else if( $this->multi_array_search('ok',$val) &&  ( $arr['status'] == 'fehlt' || $arr['status'] == 'nok') ) {
                                $category_ids[] = $arr['tracking_id'];
                            } else if( $this->multi_array_search('nok',$val)  && $arr['status'] == 'fehlt') {
                                $category_ids[] = $arr['tracking_id'];
                            } else if($this->multi_array_search_revision($val) && ( $arr['status'] == 'ok' || 
                                    $arr['status'] == 'nok' || $arr['status'] == 'fehlt' )  ) {
                                $category_ids[] = $arr['tracking_id'];
                            } else if($arr['status'] == 'ok') {
                                if($ok_counter > 0) {
                                    $category_ids[] = $arr['tracking_id'];
                                }
                                $ok_counter++;
                            } else if($arr['status'] == 'nok') {
                                if($nok_counter > 0) {
                                    $category_ids[] = $arr['tracking_id'];
                                }
                                $nok_counter++;
                            } else if($arr['status'] == 'fehlt') {
                                if($fehlt_counter > 0) {
                                    $category_ids[] = $arr['tracking_id'];
                                }
                                $fehlt_counter++;
                            }
                        }
                    }
                }
                
                if(!empty($category_ids)) {
                    $tracking_ids = implode(',', array_map('add_quotes', $category_ids));
                    $sql_tracking_deleted = 'UPDATE dotb7_document_tracking SET deleted = 1 WHERE id IN ('.$tracking_ids.')';
                    $GLOBALS['db']->query($sql_tracking_deleted);

                    $message = translate('LBL_DOC_CATEGORIES_UPDATE_SUCCESS', 'Leads');
                    $level = 'success';
                }
            }

            $doc_id_removed = array();
            
            if(!empty($category_ids)) {
                foreach($temp_category_evaluation as $k1 => $v1) {
                    foreach($v1 as $k3 => $v3) {
                        if(in_array($v3, $category_ids)) {
                            unset($temp_category_evaluation[$k1][$k3]);
                        }
                    }
                }
                
                foreach($temp_category_evaluation as $k => $v) {
                    if(!sizeof($v)>0) {
                        $doc_id_removed[] = $k;
                    }
                }
                
                if(!empty($doc_id_removed)) {
                    $doc_ids = implode(',', array_map('add_quotes', $doc_id_removed));
                    $sql_doc_deleted = 'UPDATE documents SET deleted = 1 WHERE id IN ('.$doc_ids.')';
                    $GLOBALS['db']->query($sql_doc_deleted);
                }
            }
        }
        
        return array('level' => $level, 'message' => $message);
    }
}
