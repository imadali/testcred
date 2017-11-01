<?php

require_once 'custom/include/PDFConverter/PDFConverter.php';
array_push($job_strings, 'cleanUpThumbnailsOfInActiveLeads');

function cleanUpThumbnailsOfInActiveLeads()
{
    
    $lead_status = array('10_active','11_closed');
    $cred_status = implode(',', array_map('add_quotes', $lead_status));
    
    $sql_lead_doc = 'SELECT leads.id, documents.document_revision_id, documents.id AS doc_id FROM leads '
            . ' INNER JOIN leads_cstm ON leads.id = leads_cstm.id_c AND leads_cstm.credit_request_status_id_c IN ('.$cred_status.')'
            . ' INNER JOIN leads_documents_1_c AS lead_doc ON leads.id = lead_doc.leads_documents_1leads_ida AND lead_doc.deleted = 0 '
            . ' INNER JOIN documents ON lead_doc.leads_documents_1documents_idb = documents.id AND documents.deleted = 0 AND documents.converted!=6'
            . ' WHERE leads.deleted = 0'
            . ' ORDER BY leads.date_entered DESC'
            . ' LIMIT 100';
    
    $GLOBALS['log']->debug('SQL Query :: '.$sql_lead_doc);
    
    $results = $GLOBALS['db']->query($sql_lead_doc);
    $pdf_image = rtrim($GLOBALS['sugar_config']['thumbnails'], '/') . '/';

    $lead_id =  array();
    $pdfConvertorObj = new PDFConverter();
    if (!empty($pdf_image)) {
        while ($row = $GLOBALS['db']->fetchByAssoc($results)) {
            
            $filePath = $pdf_image.$row['document_revision_id'];
            
            /**
             * CRED-904 : Thumbnail folder gets deleted on running scheduler for all Leads
             */
            if (file_exists($filePath) && !empty($row['document_revision_id'])) {
                $pdfConvertorObj->recursiveRemoveDirectoryPDFMergedThumbnails($filePath);
                if (!in_array($row['doc_id'], $lead_id)) {
                    $lead_id[] = $row['doc_id'];
                }
            }
        }
    }
    
    if (!empty($lead_id)) {
        $ids_arr = implode(',', array_map('add_quotes', $lead_id));
        $sql_doc_update = ' UPDATE documents SET converted = 6 WHERE id IN ('.$ids_arr.')';
        $GLOBALS['db']->query($sql_doc_update);
        $GLOBALS['log']->debug('SQL Doc Update :: '.$sql_doc_update);
    }
    
    return true;
}

