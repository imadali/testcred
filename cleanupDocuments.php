<?php

if (!defined('sugarEntry'))
    define('sugarEntry', true);

/*require_once('include/entryPoint.php');
if (!file_exists('DanglingDoc')) {
    mkdir('DanglingDoc', 0777, true);
}*/

/*
 * Moving the files of the deleted documents from upload folder to the DanglingDoc folder.
 */
/*
 if (isset($_GET['deletedDocs'])) {
    echo "<br><b>Moving the files of the deleted documents from upload folder to the DanglingDoc folder</b></br>";
    $sql = "SELECT id,document_revision_id FROM documents where deleted <> 0 AND (document_revision_id IS NOT NULL OR document_revision_id <> '')";
    $result = $GLOBALS["db"]->query($sql);
    $doc_count = 0;
    while ($doc = $GLOBALS["db"]->fetchByAssoc($result)) {
        if (!empty($doc['document_revision_id'])) {
            $file = "upload/" . $doc['document_revision_id'];
            $move_to = "DanglingDoc/" . $doc['document_revision_id'];
            if (file_exists($file)) {
                $doc_count++;
                rename($file, $move_to);
            }
        }
    }
    echo "<br> $doc_count dangling files have been moved from upload to DanglingDoc folder";
}*/







/*
 * Moving the files which do not belong to any document from upload folder to the DanglingDoc folder.
 */
/*
 if (isset($_GET['danglingFiles'])) {
    echo "<br><b>Moving the files which do not belong to any document from upload folder to the DanglingDoc folder</b></br>";
    $dir = 'upload';
    $dang_count = 0;
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            $tempFile=trim($file);
            if (!empty($tempFile)) {
                $result = $GLOBALS["db"]->query("SELECT id FROM documents where deleted = 0 AND document_revision_id='$file'");
                if ($result->num_rows == 0) {
                    $upload_file = "upload/" . $tempFile;
                    $move_to = "DanglingDoc/" . $tempFile;
                    if (file_exists($upload_file)) {
                        rename($upload_file, $move_to);
                        $dang_count++;
                    }
                }
            }
        }
        echo "<br> $dang_count dangling files have been moved from upload to DanglingDoc folder";
        closedir($dh);
    }
}
*/







/*
 * Deleting dangling documents (and there respective files if exist) which are not linked to any module
 */

/*
if (isset($_GET['independentDocs'])) {
    echo "<br><b>Deleting dangling documents (and there respective files if exist) which are not linked to any module</b></br>";
    $offset = $_GET['offset'];
    if ($offset < 0 || !isset($offset)) {
        echo "Please provide a valid positive offset number";
        exit;
    }
    if (empty($offset))
        $offset = 0;
    /*     * ***************************************
     * Removing Duplicate Documents          *
     * *************************************** */
    /*$related_mod = array(
        'documents_contacts' => 'document_id',
        'documents_opportunities' => 'document_id',
        'linked_documents' => 'document_id',
        'leads_documents_1_c' => 'leads_documents_1documents_idb',
        'notes' => 'id',
    );
    $result = $GLOBALS["db"]->query("SELECT id,document_revision_id FROM documents where deleted = 0 limit 10000 OFFSET $offset");
    $deleted_docs = 0;
    $total = 0;
    while ($doc = $GLOBALS["db"]->fetchByAssoc($result)) {
        $total++;
        $doc_id = $doc['id'];
        $delete = true;
        foreach ($related_mod as $table => $column) {
            $exist = $GLOBALS["db"]->query("SELECT d.id FROM documents d JOIN $table t ON d.id=t.$column WHERE d.deleted = 0 AND d.id = '$doc_id' AND t.deleted = 0");
            if ($exist->num_rows > 0) {
                $delete = false;
            }
        }
        if ($delete) {
            $deleted_docs++;
            $document_revision_id = $doc['document_revision_id'];
            $GLOBALS["db"]->query("UPDATE documents SET deleted = 12 WHERE id='$doc_id'");
            $file = "upload/" . $document_revision_id;
            $move_to = "DanglingDoc/" . $document_revision_id;
            if (file_exists($file)) {
                rename($file, $move_to);
            }
        }
    }
    echo "<br> $deleted_docs dangling documents have been deleted and there respective files if existed";
    if ($total) {
        $new_offset = $offset + 10000;
        echo "<br>Please change offset to $new_offset";
    } else {
        echo "<br>Script has been completed successfully!";
    }
}
*/







/*
 * Removing the Duplicate documents (and there respective files if exist) which were created in the contact on lead closure
 */
/*if ($_GET['movedDocs']) {
    echo "This script is temporarily blocked";
    exit();
    /*echo "<br><b>Removing the Duplicate documents (and there respective files if exist) which were created in the contact on lead closure</b></br>";
    $offset = $_GET['offset'];
    if ($offset < 0 || !isset($offset)) {
        echo "<br>Please provide a valid positive offset number";
        exit;
    }
    if (empty($offset))
        $offset = 0;

    $leads_sql = "SELECT id FROM leads l join leads_cstm lc ON l.id=lc.id_c where l.deleted=0 AND (l.contact_id IS NOT NULL OR l.contact_id <> '') AND (lc.credit_request_status_id_c='10_active' OR lc.credit_request_status_id_c='11_closed')  limit 5000 OFFSET $offset";
    $leads_result = $GLOBALS["db"]->query($leads_sql);
    $lead_count = 0;
    while ($lead = $GLOBALS["db"]->fetchByAssoc($leads_result)) {
        $lead_count++;
        /*
         * Geting contact documents
         *
        $leadObj = BeanFactory::getBean("Leads", $lead['id']);

        $leadObj->load_relationship("leads_documents_1");
        $leadDocs = $leadObj->leads_documents_1->getBeans();
        $leadsDocsArr = array();
        foreach ($leadDocs as $leadDoc) {
            $leadsDocsArr[$leadDoc->id] = $leadDoc->name;
        }

        if (!empty($leadsDocsArr)) {
            /*
             * Geting contact documents
             *
            $contactObj = BeanFactory::getBean("Contacts", $leadObj->contact_id);
            $contactObj->load_relationship("documents");
            $contactDocs = $contactObj->documents->getBeans();
            foreach ($contactDocs as $contactDoc) {
                if (in_array($contactDoc->name, $leadsDocsArr)) {
                    $lead_doc_id = array_search($contactDoc->name, $leadsDocsArr);
                    $GLOBALS["db"]->query("UPDATE documents SET deleted = 11 WHERE id='$contactDoc->id'");
                    $file = "upload/" . $contactDoc->document_revision_id;
                    $move_to = "DanglingDoc/" . $contactDoc->document_revision_id;
                    if (file_exists($file)) {
                        rename($file, $move_to);
                    }
                    $contactObj->documents->add($lead_doc_id);
                }
            }
        }
    }
    echo "<br> Duplicate documents have been removed from $lead_count leads.";
    if ($lead_count) {
        $new_offset = $offset + 5000;
        echo "<br>Please change offset to $new_offset";
    } else {
        echo "<br>Script has been completed successfully!";
    }
}
*/

exit;