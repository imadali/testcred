<?php

require_once('include/api/SugarApi.php');
require_once('include/SugarQuery/SugarQuery.php');

class checkConnectionAPI extends SugarApi {

    public function registerApiRest() {
        return array(
            'checkConnection' => array(
                'reqType' => 'GET',
                'path' => array('Leads', 'checkConnection'),
                'pathVars' => array('', ''),
                'method' => 'checkConnection',
                'shortHelp' => '',
                'longHelp' => '',
            ),
        );
    }

    public function checkConnection($api, $args) {
        require_once 'custom/include/ftpConnection.php';
        $ftp_host = $GLOBALS['sugar_config']['ftp_host'];
        $ftp_user = $GLOBALS['sugar_config']['ftp_user'];
        $ftp_pass = $GLOBALS['sugar_config']['ftp_pass'];
        if (isset($GLOBALS['sugar_config']['ftp_host']) && isset($GLOBALS['sugar_config']['ftp_user']) && isset($GLOBALS['sugar_config']['ftp_pass'])) {
            $FTPConnection = new ftpConnection($ftp_host, $ftp_user, $ftp_pass);
            if ($FTPConnection->isConnectionEstablish()) {
                $return_upload = $FTPConnection->uploadFilesToFTP($args['pdf_info'], $args['leadId']);
                if (!$return_upload) {
                    return -1;
                }
                $GLOBALS['log']->debug(' ==== Document Uploaded With Success ==== ');
                return true;
            } else {
                $GLOBALS['log']->debug('Invalid Credentials :: ');
                //return translate('LBL_NOT_VALID', 'Invalid Credentials');
                return 'Invalid Credentials';
            }
        } else {
            $GLOBALS['log']->debug('FTP credentials are not congigured in config file');
            return 'FTP credentials are not congigured in config file';
        }
    }

}

?>