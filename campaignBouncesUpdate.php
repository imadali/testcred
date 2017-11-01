<?php

/**
 * CRED-728 : Updating Lead with Bounce Details
 */
if (!is_dir('./bounce-details')) {
    echo "<b>Directory bounce-details not found.</b>";
} else {
    $fileList = array();
    $path = '';
    $dir = new DirectoryIterator('./bounce-details');
    foreach ($dir as $fileinfo) {
        if (!$fileinfo->isDot()) {
            $fileList[] = $fileinfo->getRealPath();
            $path = $fileinfo->getPath();
        }
    }

    if (!empty($fileList)) {
        $file_url = $path . '/CRED-728.sql';
        $heading = true;
        $count = 0;

        foreach ($fileList as $files) {
            $file = fopen($files, "r");
            while (!feof($file)) {
                $lead = fgetcsv($file);
                if (!$heading) {
                    if ($lead[sizeof($lead) - 1] == 'hard') {
                        $query = "SELECT email.id FROM email_addresses email JOIN email_addr_bean_rel bean"
                                . " ON email.id = bean.email_address_id "
                                . " WHERE email.deleted = 0 AND bean.deleted = 0 "
                                . " AND bean.bean_id = '" . $lead[3] . "' AND bean.bean_module = 'Leads'"
                                . " AND email.email_address = '" . $lead[0] . "';";

                        $result = $GLOBALS['db']->query($query);
                        $row = $GLOBALS['db']->fetchByAssoc($result);

                        if (isset($row['id'])) {
                            $update = "UPDATE email_addresses SET invalid_email = 1 WHERE id = '" . $row['id'] . "';\n";
                            $count++;
                            file_put_contents($file_url, $update, FILE_APPEND);
                        }
                    }
                }
                $heading = false;
            }
        }

        fclose($fp);

        if ($count != 0) {
            echo "<b>$count Related Leads were found in the System.</b><br><br>";
            echo "<b>A SQL file containing queries to be executed has been placed in $file_url</b>";
        } else {
            echo "<b>No Related Lead was found in the System.</b>";
        }
    } else {
        echo "<b>Directory bounce-details is empty.</b>";
    }
}
?>