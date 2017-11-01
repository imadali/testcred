<?php

class ftpConnection {

    private $connectionId;
    private $loginResult;
    private $host;                                                              // only Host Name
    private $sourceLocation;                                                    // complete source Location
    private $username;
    private $password;
    private $error;
    private $sourceType;

    public function __construct($host = '', $username = '', $password = '', $sourceType = 'File') {
        if (!extension_loaded('ftp')) {
            $this->error = 'PHP extension FTP is not loaded.';
            //$GLOBALS['log']->fatal($this->error);
        } else {
            //$this->host = $this->sourceLocation = $host;
            $this->host = $host;
            $this->username = $username;
            $this->password = $password;
            $this->sourceType = $sourceType;
            $this->connect();
        }
    }

    private function createMergedPDFForFTPUpload($pdfInfo){
        require_once 'custom/include/PDFConverter/PDFConverter.php';
        $pdfConvertor = new PDFConverter();
        return $pdfConvertor->createPDFForFTPUpload($pdfInfo);
    }
    
    public function uploadFilesToFTP($pdf_info, $leadId) {
        $download_location = '';
        $create_dir=true;
        if(!empty($pdf_info)){
            $download_location = $this->createMergedPDFForFTPUpload($pdf_info);
        }
        
        $GLOBALS['log']->debug('Download Location :: '.print_r($download_location,1));
        if(!empty($download_location)){
            //$file_contents = file_get_contents($download_location);
            if (!is_dir('custom/include/ftp')) {
                mkdir("custom/include/ftp", 0775);
            }
            if($create_dir)
            if (!ftp_chdir($this->connectionId, "Export")) {
                ftp_mkdir($this->connectionId, "Export");
                ftp_chdir($this->connectionId, "Export");
            }
            $leadBean = BeanFactory::getBean("Leads", $leadId);
            if (empty($leadBean->credit_request_number_c)) {
                $folder_name = $leadBean->first_name . "_" . $leadBean->last_name;
            } else {
                $folder_name = $leadBean->first_name . "_" . $leadBean->last_name . "_" . $leadBean->credit_request_number_c;
            }
            $folder_name = str_replace(" ", "_", $folder_name);
            if($create_dir)
            if (!ftp_chdir($this->connectionId, $folder_name)) {
                ftp_mkdir($this->connectionId, $folder_name);
                ftp_chdir($this->connectionId, $folder_name);
            }
            
            $ftp_file_name= $download_location['fileId'].'.pdf';
            /*$file_name = "custom/include/ftp/" . $ftp_file_name;
            $myfile = fopen($file_name, "w");
            fwrite($myfile, $file_contents);
            */
            if (filesize($download_location['fileName']) && file_exists($download_location['fileName'])) {
                if (ftp_put($this->connectionId, $ftp_file_name, $download_location['fileName'], FTP_BINARY)) {
                    unlink($download_location['fileName']);
                } else {}
                
                //fclose($myfile);
            } else {}
            
            $create_dir=false;
            // close the connection
            $this->closeConnection();
            return true;
        }
        
        else{
            return false;
        }
    }
public function ftpDeleteDirectory($directory){
    //$GLOBALS['log']->fatal("Removding Dir....: $directory");
    if(empty($directory))//Validate that a directory was sent, otherwise will delete ALL files/folders
        return json_encode(false);
    else{
        # here we attempt to delete the file/directory
        if( !(ftp_rmdir($this->connectionId,$directory) || ftp_delete($this->connectionId,$directory)) )
        {
            # if the attempt to delete fails, get the file listing
            $filelist = ftp_nlist($this->connectionId, $directory);
            # loop through the file list and recursively delete the FILE in the list
            foreach($filelist as $file)
            {
            //  return json_encode($filelist);
                $this->ftpDeleteDirectory($directory.'/'.$file);/***THIS IS WHERE I MUST RESEND ABSOLUTE PATH TO FILE***/
            }

            #if the file list is empty, delete the DIRECTORY we passed
            $this->ftpDeleteDirectory($directory);
        }
    }
    return json_encode(true);
}
    public function uploadFilesToFTP2($doc_ids, $leadId) {
        $doc_ids = explode('_', $doc_ids);
        foreach ($doc_ids as $key => $doc_id) {
            $doc = new Document();
            $doc->retrieve($doc_id);
            $download_location = "upload://{$doc->document_revision_id}";
            $file_contents = file_get_contents($download_location);
            if (!is_dir('custom/include/Export')) {
                mkdir("custom/include/Export", 0775);
            }

            $leadBean = BeanFactory::getBean("Leads", $leadId);
            if (empty($leadBean->credit_request_number_c)) {
                $folder_name = $leadBean->first_name . "_" . $leadBean->last_name;
            } else {
                $folder_name = $leadBean->first_name . "_" . $leadBean->last_name . "_" . $leadBean->credit_request_number_c;
            }

            $folder_name = str_replace(" ", "_", $folder_name);

            if (!is_dir('custom/include/Export/' . $folder_name)) {
                mkdir("custom/include/Export/" . $folder_name, 0775);
            }

            $file_name = "custom/include/Export/" . $folder_name . "/" . $doc->rev_file_name;

            $myfile = fopen($file_name, "w");
            fwrite($myfile, $file_contents);
            fclose($myfile);
        }
        return true;
        // close the connection
        //  ftp_close($conn_id);
    }

    public function closeConnection() {
        ftp_close($this->connectionId);
        //$GLOBALS['log']->fatal("FTP connections were closed");
    }

    public function isConnectionEstablish() {
        return $this->loginResult;
    }

    public function getConnectionErrors() {
        return $this->error;
    }

    private function connect() {
        if (!empty($this->host) && !empty($this->username) && !empty($this->password) && !empty($this->sourceType)) {
            if($GLOBALS['sugar_config']['ftp_connection_type'] == 'Staging'){
                $this->connectionId = ftp_ssl_connect($this->host);
            }
            else{
                $this->connectionId = ftp_connect($this->host);
            }
            $this->loginResult = ftp_login($this->connectionId, $this->username, $this->password);
            if ($this->loginResult) {
                ftp_pasv($this->connectionId, TRUE);
                $this->error = "Login Attempt : Success";
            } else {
                $this->error = "Login Attempt : Failed";
            }
            //$GLOBALS['log']->fatal('loginResult');
            //$GLOBALS['log']->fatal($this->loginResult);
        } else {
            $this->error = 'Missing Source Location, UserName, password or Source Type.';
        }
        //$GLOBALS['log']->fatal($this->error);
    }

}
