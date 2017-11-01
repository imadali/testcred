<?php

array_push($job_strings, 'document_linking');
require_once 'custom/include/PDFConverter/PDFConverter.php';
require_once 'custom/include/PDFConverter/PDFHelper.php';

function document_linking() {
    global $db;
    $upload_directory = $GLOBALS['sugar_config']['upload_dir'];

    //Getting Notes with parent type as Emails grouped on parent id
    $query = new SugarQuery();
    $query->select(array('id', 'name', 'parent_id'));
    $query->from(BeanFactory::getBean('Notes'), array('team_security' => false));
    $query->where()->equals('parent_type', 'Emails');
    $query->where()->equals('dotb_flag', 0);
    $query->groupBy('parent_id');

    $notes_by_parent_id = $query->execute();
    
    //initiate PDFHelper object
    $helper = new PDFHelper();
    $converterObj = new PDFConverter();

    foreach ($notes_by_parent_id as $note_parent_id) {
        $converted_notes_path = array();
        $counter = 0;
        $converted_notes_counter = 0;
        $linked_notes_array = array();
        $conversion_failed_notes = array();

        $email_bean = BeanFactory::getBean("Emails", $note_parent_id['parent_id']);
        //check for emails that are archived only
        if (($email_bean->type == 'archived' && $email_bean->status == 'archived') || ($email_bean->type == 'inbound')) {
            if (!empty($email_bean->parent_id)) {
                $email_parent_id = $email_bean->parent_id;
                $email_parent_type = $email_bean->parent_type;
                $parent_id = $note_parent_id['parent_id'];

                $parentBean = BeanFactory::getBean($email_bean->parent_type, $email_bean->parent_id);

                //get all notes linked to an email
                $notes_query = new SugarQuery();
                $notes_query->select(array('id', 'name', 'file_mime_type', 'filename', 'parent_id'));
                $notes_query->from(BeanFactory::getBean('Notes'), array('team_security' => false));
                $notes_query->where()->equals('parent_type', 'Emails');
                $notes_query->where()->equals('dotb_flag', 0);
                $notes_query->where()->equals('parent_id', "$parent_id");

                $notes = $notes_query->execute();

                $note_count = count($notes);
                $GLOBALS['log']->debug("Number of notes: " . $note_count);

                /*
                 * Checking whether all email attached files have been archived in sugar or not
                 */
                $total_files = 0;
                $archived_files = 0;
                foreach ($notes as $notes_c) {
                    $total_files++;
                    $note_id = $notes_c['id'];
                    if (file_exists("upload/$note_id")) {
                        $archived_files++;
                    }
                }
                if ($total_files != $archived_files) {
                    continue;
                }

                /*
                 * All emial attachments have been archived succussfully so go ahead
                 */
                foreach ($notes as $notes_c) {
                    $note_id = $notes_c['id'];
                    // $file_type = explode(".", $notes_c['filename']);
                    $file_type = array_pop(explode('.', $notes_c['filename']));
                    $GLOBALS['log']->debug("Note extension: " . $file_type);
                    if ($file_type != 'pdf') {
                        $GLOBALS['log']->debug("File is not PDF. Convert");

                        // for converting attachments to PDF using convertApi
                        $endPoint = $converterObj->getApiEndPoint($file_type);
                        if ($endPoint) {
                            $pathCreated = $converterObj->tobeProcessedDir . $parent_id . '/';

                            $converterObj->createFolder($pathCreated);
                            $conversion_result = $helper->convertNoteToPDF($notes_c['id'], $notes_c['filename'], $file_type, $parent_id,true);
                            if ($conversion_result['status']) {
                                $converted_notes_path[$counter]['pdf_file_path'] = $converterObj->convertedPdfDir . $parent_id . '/' . $notes_c['id'];
                                $counter++;
                                $converted_notes_counter++;
                                $linked_notes_array[] = $notes_c['id'];
                            } elseif ($conversion_result['status'] == false) {
                                $conversion_failed_notes[] = array('id' => $notes_c['id'], 'message' => $conversion_result['message']);
                                $GLOBALS['log']->debug("Conversion Failed");
                            }
                        } else {
                            $GLOBALS['log']->debug("File extension not supported by API.");

                            // create a document for this note
                            $document = BeanFactory::getBean('Documents');
                            $document->id = create_guid();
                            $document->new_with_id = true;
                            $document->name = $notes_c['filename']; //$notes_c->id;
                            $document->note_id = $notes_c['id']; // linked note id

                            $revision = BeanFactory::getBean('DocumentRevisions');
                            $revision->id = create_guid(); //$notes_c->id; //
                            $revision->new_with_id = true;
                            $revision->document_id = $document->id;

                            empty($notes_c['filename']) ? $document->document_name = $notes_c['name'] : $document->document_name = $notes_c['filename'];
                            $revision->doc_type = 'Sugar';
                            $revision->filename = $notes_c['filename'];

                            $revision->file_ext = $file_type;
                            $revision->file_mime_type = $notes_c['file_mime_type'];
                            $revision->revision = 1;

                            $revision->save();
                            $document->document_revision_id = $revision->id;
                            $document->rev_file_name = $revision->filename;

                            //assign team and team set id of linked record
                            $parentBeanEmail = BeanFactory::getBean($email_parent_type, $email_parent_id, array('disable_row_level_security' => true));

                            $document->team_id = $parentBeanEmail->team_id;
                            $document->team_set_id = $parentBeanEmail->team_set_id;

                            $document->save();

                            if (file_exists("upload/$note_id")) {
                                if (copy("upload/$note_id", "upload/$revision->id")) {
                                    $GLOBALS['log']->debug("File copied from note");
                                }
                            }

                            //Relating Documents to Leads
                            if ($email_bean->parent_type == "Leads") {
                                $document->leads_documents_1leads_ida = $parentBean->id;
                                $parentBean->load_relationship('leads_documents_1');
                                $parentBean->leads_documents_1->add($document->id);
                            } else if ($email_bean->parent_type == "Contracts") {
                                $document->load_relationship('contracts');
                                $document->contracts->add($parentBean->id);
                            } else {
                                $parentBean->load_relationship('documents');
                                $parentBean->documents->add($document->id);
                            }

                            // extension is not supported by convert api so updated bit and counter
                            $converted_notes_counter++;
                            //Updating flag to keep track of notes that have been visited
                            $sql = "Update notes SET dotb_flag = 1 WHERE id = '$note_id'";
                            $result = $db->query($sql);
                        }
                    } else {
                        $GLOBALS['log']->debug("File is PDF.");
                        $converted_notes_path[$counter]['pdf_file_path'] = $upload_directory . $notes_c['id'];
                        $counter++;
                        $converted_notes_counter++;
                        $linked_notes_array[] = $notes_c['id'];
                    }
                }

                //if ($converted_notes_counter == $note_count) {
                $document_name = 'Kundenunterlagen.pdf';
                if (!empty($converted_notes_path)) {
                    // Creating Document
                    $documentBean = BeanFactory::getBean('Documents', array('disable_row_level_security' => true));
                    $documentBean->document_name = $document_name;

                    if (!empty($converted_notes_path)) {
                        $documentBean->converted = 1; // merged newly created Merged PDF as converted
                    }

                    $documentBean->save();

                    $documentId = $documentBean->id;
                    $documentRevision_bean = BeanFactory::getBean('DocumentRevisions');
                    $documentRevision_bean->document_id = $documentId;
                    $documentRevision_bean->doc_type = 'Sugar';
                    $documentRevision_bean->filename = $document_name;
                    $documentRevision_bean->file_ext = 'pdf';
                    $documentRevision_bean->file_mime_type = 'application/pdf';
                    $documentRevision_bean->revision = '1';
                    $documentRevision_bean->save();

                    $documentBean->document_revision_id = $documentRevision_bean->id;
                    $documentBean->rev_file_name = $document_name;

                    //assign team and team set id of linked record
                    $emailParentBean = BeanFactory::getBean($email_parent_type, $email_parent_id, array('disable_row_level_security' => true));

                    $documentBean->team_id = $emailParentBean->team_id;
                    $documentBean->team_set_id = $emailParentBean->team_set_id;

                    $documentBean->save();
                    $GLOBALS['log']->debug('Document Revision ID :: ' . $documentRevision_bean->id);

                    $upload_folder = $GLOBALS['sugar_config']['upload_dir'];
                    // merge all PDF
                    $dataReturn = $converterObj->archievedPDFMerged($converted_notes_path, $upload_folder . '/' . $documentRevision_bean->id);
                    if ($dataReturn['level'] == 'success') {
                        $GLOBALS['log']->debug('PDF Created With Success For Lead Conversion');

                        //Relating Documents to Leads
                        if ($email_bean->parent_type == "Leads") {
                            $documentBean->leads_documents_1leads_ida = $parentBean->id;
                            $parentBean->load_relationship('leads_documents_1');
                            $parentBean->leads_documents_1->add($documentBean->id);
                        } else if ($email_bean->parent_type == "Contracts") {
                            $documentBean->load_relationship('contracts');
                            $documentBean->contracts->add($parentBean->id);
                        } else {
                            $parentBean->load_relationship('documents');
                            $parentBean->documents->add($documentBean->id);
                        }

                        //Updating flag to keep track of notes that have been visited
                        foreach ($linked_notes_array as $key => $noteId) {
                            $sql = "Update notes SET dotb_flag = 1 WHERE id = '$noteId'";
                            $result = $db->query($sql);
                        }
                    } else if ($dataReturn['level'] == 'error') {
                        $GLOBALS['log']->debug($dataReturn['message']);
                        $documentBean->send_document = 1;
                        $documentBean->save();
                        
                        global $app_list_strings;
                        $merge_error = $app_list_strings['LBL_ISSUE_IN_MERGE'];
                        //updating bit to 6 for notes that failed to merge
                        foreach ($linked_notes_array as $key => $noteId) {
                            $sql = "Update notes SET dotb_flag = 6, error_message = '$merge_error' WHERE id = '$noteId'";
                            $result = $db->query($sql);
                        }
                    }
                    if (!empty($conversion_failed_notes)) {
                        foreach ($conversion_failed_notes as $key => $failedNoteId) {
                            $id = $failedNoteId['id'];
                            $message = $failedNoteId['message'];
                            $failed_notes_sql = "Update notes SET dotb_flag = 5, error_message = '$message' WHERE id = '$id'";
                            $result = $db->query($failed_notes_sql);
                        }
                    }
                } else {
                    $GLOBALS['log']->debug('All documents Failed :: ');
                    foreach ($conversion_failed_notes as $key => $failedNoteId) {
                        $id = $failedNoteId['id'];
                        $message = $failedNoteId['message'];
                        $failed_notes_sql = "Update notes SET dotb_flag = 5, error_message = '$message' WHERE id = '$id'";
                        $result = $db->query($failed_notes_sql);
                    }
                }
                /* } else {
                  //conversion failed so update linked notes dotb_flag to 5
                  foreach ($linked_notes_array as $key => $noteId) {
                  $sql = "Update notes SET dotb_flag = 5 WHERE id = '$noteId'";
                  $result = $db->query($sql);
                  }
                  foreach ($conversion_failed_notes as $key => $failedNoteId) {
                  $failed_notes_sql = "Update notes SET dotb_flag = 5 WHERE id = '$failedNoteId'";
                  $result = $db->query($failed_notes_sql);
                  }
                  } */

                // to remove files
                $converterObj->recursiveRemoveDirectoryPDFMergedThumbnails($converterObj->tobeProcessedDir . $parent_id);
                $converterObj->recursiveRemoveDirectoryPDFMergedThumbnails($converterObj->convertedPdfDir . $parent_id);
            }
        }
    }

    return true;
}

?>