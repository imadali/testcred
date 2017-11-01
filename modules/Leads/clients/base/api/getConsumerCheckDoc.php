<?php

require_once('include/api/SugarApi.php');
require_once('include/SugarQuery/SugarQuery.php');
require_once('custom/include/PDFConverter/PDFConverter.php');
require_once('include/utils.php');

class getConsumerCheckDoc extends SugarApi
{

    /**
     * @function registerApiRest
     * @description registering the API call to make project status field read-only or not
     * @return type
     */
    public function registerApiRest()
    {
        return array(
            'getCCDoc' => array(
                'reqType' => 'GET',
                'path' => array('Leads', 'getCCDoc', '?'),
                'pathVars' => array('', '', 'id'),
                'method' => 'getConsumerCheckDocument',
                'shortHelp' => 'This api will return the latest document linked to lead with category Credit Check Consumer',
                'longHelp' => '',
            ),
        );
    }

    /**
     * CRED-767 : api to get latest document with category 'Credit Check Customer'
     * @param ServiceBase $api
     * @param array $args
     */
    public function getConsumerCheckDocument(ServiceBase $api, array $args)
    {
        $leadId = $args['id'];
        $resultant_doc = '';
        $lead_doc_query = 'SELECT documents.id, documents.document_name FROM documents '
                . 'INNER JOIN documents_dotb7_document_tracking_1_c ON documents.id = documents_dotb7_document_tracking_1_c.documents_dotb7_document_tracking_1documents_ida '
                . 'INNER JOIN dotb7_document_tracking ON documents_dotb7_document_tracking_1_c. documents_dotb7_document_tracking_1dotb7_document_tracking_idb = dotb7_document_tracking.id AND dotb7_document_tracking.deleted=0 AND dotb7_document_tracking.category = "credit_check_consumer" '
                . 'INNER JOIN leads_documents_1_c ON documents.id = leads_documents_1_c.leads_documents_1documents_idb AND documents.deleted = 0 AND leads_documents_1_c.leads_documents_1leads_ida = "' . $leadId . '" ORDER BY documents.date_entered DESC LIMIT 0,1';

        $lead_doc_result = $GLOBALS['db']->query($lead_doc_query);
        $leads_cc_doc = $GLOBALS['db']->fetchByAssoc($lead_doc_result);
        if (!empty($leads_cc_doc['id']) && !empty($leads_cc_doc['document_name'])) {
            $resultant_doc = $leads_cc_doc['id'] . '/' . $leads_cc_doc['document_name'];
        }

        return $resultant_doc;
    }
}

?>