<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

require_once 'clients/base/api/FileApi.php';

/**
 * API Class to handle temporary image (attachment) interactions with a field in
 * a bean that can be new, so no record id is associated yet.
 */
class CustomFileTempApi extends FileApi {
    /**
     * Dictionary registration method, called when the API definition is built
     *
     * @return array
     */
    public function registerApiRest() {
        return array(
            'saveTempImagePost' => array(
                'reqType' => 'POST',
                'path' => array('<module>', 'cstmtemp', 'file', '?'),
                'pathVars' => array('module', 'temp', '', 'field'),
                'method' => 'saveTempImagePost',
                'rawPostContents' => true,
                'shortHelp' => 'Saves an image to a temporary folder.',
                'longHelp' => 'include/api/help/module_temp_file_field_post_help.html',
                ),
            'saveFilePost' => array(
                'reqType' => 'POST',
                'path' => array('Documents', '?', 'file', '?'),
                'pathVars' => array('module', 'record', '', 'field'),
                'method' => 'saveFilePost',
                'rawPostContents' => true,
                'shortHelp' => 'Saves a file. The file can be a new file or a file override.s',
                'longHelp' => 'include/api/help/module_record_file_field_post_help.html',
                ),
            'removeFile' => array(
                'reqType' => 'DELETE',
                'path' => array('RemoveDocuments', '?'),
                'pathVars' => array('', 'record'),
                'method' => 'removeFile',
                'rawPostContents' => true,
                'shortHelp' => 'Removes a file from a field.',
                'longHelp' => 'include/api/help/module_record_file_field_delete_help.html',
            ),
            );
    }

    /**
     * Saves a temporary image to a module field using the POST method (but not attached to any model)
     *
     * @param ServiceBase $api The service base
     * @param array $args Arguments array built by the service base
     * @return array
     * @throws SugarApiExceptionError
     */
    public function saveTempImagePost($api, $args)
    {
        if (!isset($args['record'])) {
            $args['record'] = null;
        }
        $temp = false;
        return $this->saveFilePost($api, $args, $temp);
    }

    public function saveFilePost($api, $args, $temporary = false) {
        //Needed by SugarFieldImage.php to know if we are saving a temporary image
        $args['temp'] = $temporary;

        // Get the field
        $field = $args['field'];

        // To support field prefixes like Sugar proper
        $prefix = empty($args['prefix']) ? '' : $args['prefix'];

        // Set the files array index (for type == file)
        $filesIndex = $prefix . $field;
        // Get the bean before we potentially delete if fails (e.g. see below if attachment too large, etc.)
        $bean = $this->loadBean($api, $args);

        $this->checkFileAccess($bean, $field, $args);

        // Simple validation
        // In the case of very large files that are too big for the request too handle AND
        // if the auth token was sent as part of the request body, you will get a no auth error
        // message on uploads. This check is in place specifically for file uploads that are too
        // big to be handled by checking for the presence of the $_FILES array and also if it is empty.
        if (empty($_FILES[$filesIndex])) {

            // If we get here, the attachment was > php.ini upload_max_filesize value so we need to
            // check if delete_if_fails optional parameter was set true, etc.
            $this->deleteIfFails($bean, $args);

            $this->checkPostRequestBody();

            // @TODO Localize this exception message
            throw new SugarApiExceptionMissingParameter("Incorrect field name for attachement: $filesIndex");
        }

        // Get the defs for this field
        $def = $bean->field_defs[$field];

        // Only work on file or image fields
        if (isset($def['type']) && ($def['type'] == 'image' || $def['type'] == 'file')) {
            // Get our tools to actually save the file|image
            require_once 'include/SugarFields/SugarFieldHandler.php';
            $sfh = new SugarFieldHandler();
            $sf = $sfh->getSugarField($def['type']);
            if ($sf) {
                // SugarFieldFile expects something different than SugarFieldImage
                if ($def['type'] == 'file') {
                    // docType setting is throwing errors if missing
                    if (!isset($def['docType'])) {
                        $def['docType'] = 'Sugar';
                    }

                    // Session error handler is throwing errors if not set
                    $_SESSION['user_error_message'] = array();

                    // Handle setting the files array to what SugarFieldFile is expecting
                    if (!empty($_FILES[$filesIndex]) && empty($_FILES[$filesIndex . '_file'])) {
                        $_FILES[$filesIndex . '_file'] = $_FILES[$filesIndex];
                        unset($_FILES[$filesIndex]);
                        $filesIndex .= '_file';
                    }
                }

                // Noticed for some reason that API FILE[type] was set to application/octet-stream
                // That breaks the uploader which is looking for very specific mime types
                // So rather than rely on what $_FILES thinks, set it with our own methodology
                require_once 'include/download_file.php';
                $dl = new DownloadFileApi($api);
                $mime = $dl->getMimeType($_FILES[$filesIndex]['tmp_name']);
                $_FILES[$filesIndex]['type'] = $mime;

                // Set the docType into args if its in the def
                // This addresses a need in the UploadFile object
                if (isset($def['docType']) && !isset($args[$prefix . $def['docType']])) {
                    $args[$prefix . $def['docType']] = $mime;
                }

                // This saves the attachment
                $sf->save($bean, $args, $field, $def, $prefix);

                // Handle errors
                if (!empty($sf->error)) {

                    // Note, that although the code earlier in this method (where attachment too large) handles
                    // if file > php.ini upload_maxsize, we still may have a file > sugarcrm maxsize
                    $this->deleteIfFails($bean, $args);
                    throw new SugarApiExceptionRequestTooLarge($sf->error);
                }

                // Prep our return
                $fileinfo = array();

                //In case we are returning a temporary file
                if ($temporary) {
                    $fileinfo['guid'] = $bean->$field;
                }
                else {
                    /**
                     *  Save the bean
                     *  If new Document then Document.php will create revision
                     *  If existing document then create revision manually  
                     */

                    if(empty($bean->id) || $bean->new_with_id){
                        $bean->save();
                    }else{
                        $this->saveBean($bean);
                    }

                    $fileinfo = $this->getFileInfo($bean, $field, $api);

                    // This isn't needed in this return
                    unset($fileinfo['path']);
                }

                // This is a good return
                return array(
                    $field => $fileinfo,
                    'record' => $this->formatBean($api, $args, $bean)
                    );
            }
        }

        // @TODO Localize this exception message
        throw new SugarApiExceptionError("Unexpected field type: ".$def['type']);
    }

    /**
     * (non-PHPdoc)
     * @see FileApi::saveBean()
     */
    protected function saveBean($bean)
    {
        // Recreate revision bean with correct data
        if($bean->document_revision_id) {
            ++$bean->revision;
        } else {
            $bean->revision = 1;
        }
        $revision = $bean->createRevisionBean();

        $bean->document_revision_id = $revision->id;

        // Save the bean
        $bean->save();
        // move the file to the revision's ID
        if(empty($bean->doc_type) || $bean->doc_type == 'Sugar') {
            rename("upload://{$bean->id}", "upload://{$revision->id}");
        }
        // Save the revision object
        $revision->save();
        // update the fields
        //$bean->fill_in_additional_detail_fields();
    }

    /**
     * Removes an attachment from a record field
     *
     * @param ServiceBase $api The service base
     * @param array $args The request args
     * @return array Listing of fields for a record
     * @throws SugarApiExceptionError|SugarApiExceptionNoMethod|SugarApiExceptionRequestMethodFailure
     */
    public function removeFile($api, $args) {
        $module = "DocumentRevision";
        /**
         * Remove Document Revision record
         */
        $sql = "UPDATE document_revisions SET deleted = 1 WHERE id = '".$args['record']."'";
        $GLOBALS['db']->query($sql);
        /**
        * Remove Document Revision record
        */
        require_once 'include/upload_file.php';
        $upload = new UploadFile();
        $upload->unlink_file($args['record']);
    }
}
