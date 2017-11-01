<?php

    ini_set('max_execution_time', 1000);
    ini_set('memory_limit', '512M'); 
    
    $filename = 'custom/';
    
    $accept_file_name = array('credaris_closed_tasks1.csv','credaris_closed_tasks2.csv');
    
    if(isset($_REQUEST['file_name']) && in_array( $_REQUEST['file_name'], $accept_file_name)){
        $field_mapping = array(
            'Subject' => 'name',
            'ID' => 'id',
            'Description' => 'description',
            'Status' => 'status',
            'Start Date' => 'date_start', // two types of start date
            'Due Date' => 'date_due', // two type of due date
            'Priority' => 'priority',
            'Assigned User ID' => 'assigned_user_id',
            'Team ID' => 'team_id',
            'Team Set ID' => 'team_set_id',
            'Date Created' => 'date_entered',
            'Date Modified' => 'date_modified',
            'Created By ID' => 'created_by',
            'Modified By ID' => 'modified_user_id',
            'Assigned Date' => 'assigned_date_c',
            'Category' => 'category_c',
            'Lead Surname' => 'surname_c',
            'Lead Customer Contact' => 'assigned_user_id',
            'Lead Email' => 'email_c',
            'Lead Telephone' => 'phone_work_c',
            'Provider application no' => 'provider_application_no_c',
            'Base Rate' => 'base_rate',
            'Lead Birth Date' => 'birthdate_c',
            'Currency ID' => 'currency_id',
            'Lead Correspondence Language' => 'dotb_correspondence_language_c',
            'Lead Credit Amount' => 'lead_amount_c',
            'Lead Date Entered' => 'lead_date_entered_c',
            'Lead Status' => 'lead_status_c',
            'Bank' => 'bank_c',
            'Kreditsumme' => 'amount_c',
            'status_lead_c' => 'status_lead_c',
            'Lead Input process' => 'input_process_type_id',
        );

        $filename.= trim($_REQUEST['file_name']);
        
        $import_records_total = 10000;
        
        if(file_exists($filename))
        {
            $file = fopen($filename,"r") or die("can't open file");
            $i = 0;
            $count = 0;
            $lead_application_no = array();
            $foramted_id = '';
            $lead_id = array();
            
            // For getting credaris-Antragsnummer number to assoicte it with Leads
            while($row=fgets($file))
            {
                if($i == 0){
                    $i++;
                    continue;
                }
                
                $rowData = str_getcsv( $row , ',');
                
                $tdata = trim($rowData[41]);
                if(!empty($tdata)){
                    $lead_application_no[] = trim($rowData[41]);
                }
                
                // Fetching records in Chunk in order to avoid mysqli gone away error.
                if($count == $import_records_total && !empty($lead_application_no) ){
                    $foramted_id = implode(',', array_map('add_quotes', $lead_application_no));

                    $sql_leads = ' SELECT leads_cstm.credit_request_number_c, leads_cstm.id_c FROM leads_cstm INNER JOIN leads ON leads_cstm.id_c = leads.id AND leads.deleted = 0'
                            . ' WHERE leads_cstm.credit_request_number_c IN ( '.$foramted_id.' )';
                    $results = $GLOBALS['db']->query($sql_leads);

                    while($row = $GLOBALS['db']->fetchByAssoc($results)) {
                        $lead_id[$row['credit_request_number_c']] = $row['id_c'];
                    }// end of while loop
                    
                    $count = 0;
                    unset($lead_application_no);
                }//end of if
                
                $count++;
                $i++;
            }//end of while

            $task_empty_lead_id = array();
            
            if(!empty($lead_application_no)){
                $foramted_id = implode(',', array_map('add_quotes', $lead_application_no));

                $sql_leads = ' SELECT leads_cstm.credit_request_number_c, leads_cstm.id_c FROM leads_cstm INNER JOIN leads ON leads_cstm.id_c = leads.id AND leads.deleted = 0'
                        . ' WHERE leads_cstm.credit_request_number_c IN ( '.$foramted_id.' )';
                $results = $GLOBALS['db']->query($sql_leads);

                while($row = $GLOBALS['db']->fetchByAssoc($results)) {
                    $lead_id[$row['credit_request_number_c']] = $row['id_c'];
                }// end of while loop

                unset($lead_application_no);
            }//end of
            
           // $GLOBALS['log']->fatal('leads ID :: '.print_r($lead_id,1));
            
            $total_records_imported = 0;
            // To check either leads exist in System or not
            if(!empty($lead_id)){
                
                //Moving file pointer to Start of file again
                fseek($file,0);

                $insert_task= 'INSERT INTO tasks ( id, name, date_entered, date_modified, modified_user_id, '
                                            . 'created_by, description, assigned_user_id, team_id, team_set_id'
                                            . ' , status, date_due, date_start, parent_type, parent_id, priority, close_task '
                                            . '   ) VALUES ';

                $insert_task_cstm = ' INSERT INTO tasks_cstm ( id_c, category_c, status_lead_c, assigned_date_c, lead_status_c'
                                                     . ' , bank_c, amount_c, lead_amount_c, surname_c, birthdate_c,'
                                                    . ' dotb_correspondence_language_c, email_c, lead_date_entered_c, phone_work_c, provider_application_no_c  '
                                                    . ' , base_rate, currency_id ) VALUES ';

                $i = 0;
                $j = 0;
                $values_task = '';
                $values_task_cstm = '';
            
                $parent_module = '';
                $limit_records = 100;

                $taskBean = BeanFactory::newBean('Tasks');
              
                while($row=fgets($file)) {

                    $rowData = str_getcsv( $row , ',');
                    $parent_module = 'Leads';
                    $j++;

                    if($i == 0){
                        $i++;

                        $header_mapping = $rowData;
                        continue;
                    }
                    
                    $associated_lead_id = '';
                    if(isset($lead_id[trim($rowData[41])]))
                    {
                        $associated_lead_id = $lead_id[trim($rowData[41])];
                    }
                    
                    $task_id = create_guid();

                    if(empty($associated_lead_id)){
                        $task_empty_lead_id[$task_id] = trim($rowData[41]); 
                        $parent_module = '';
                    }

                    // For handling dropdown fields
                    $status = trim($rowData[3]);
                    $priority = trim($rowData[6]);
                    $category =  trim($rowData[22]);
                    $lead_correspondance_lang = trim($rowData[32]);
                    $lead_status = trim($rowData[35]);
                    $status_lead = trim($rowData[38]);
                    $lead_input_process = trim($rowData[40]);

                    // For handling date fields
                    $start_date = convert_date_to_db_date($rowData[4]);
                    $due_date = convert_date_to_db_date($rowData[5]);
                    $date_created = convert_date_to_db_date($rowData[15]);
                    $date_modified = convert_date_to_db_date($rowData[16]);
                    $assigned_date = convert_date_to_db_date($rowData[20]);
                    $lead_date_entered = convert_date_to_db_date($rowData[34]);
                    
                    $tdata1 = trim($rowData[14]);
                    $team_set_id = !empty($tdata1)? $rowData[14]: '1';

                    if($j>2){
                        $values_task.=' , ';
                        $values_task_cstm.= ' , ';
                    }

                    $values_task.=' ( "'.$task_id.'", "'.addslashes(trim($rowData[0])).'", "'.$date_created.'", "'.$date_modified.'", 
                                        "'.trim($rowData[18]).'", "'.trim($rowData[17]).'", "'.addslashes(trim($rowData[2])).'",
                                        "'.trim($rowData[11]).'",  "'.trim($rowData[13]).'", "'.$team_set_id.'",
                                        "'.$status.'",  "'.$due_date.'", "'.$start_date.'",  "'.$parent_module.'", "'.$associated_lead_id.'",
                                        "'.$priority.'", "1"     

                                    ) ';

                    $values_task_cstm.='( "'.$task_id.'", "'.$category.'", "'.$status_lead.'", "'.$assigned_date.'", "'.$lead_status.'",
                                          "'.trim($rowData[36]).'", "'.trim($rowData[37]).'", "'.trim($rowData[33]).'", "'.trim($rowData[24]).'",
                                          "'.trim($rowData[30]).'", "'.trim($rowData[32]).'", "'.trim($rowData[26]).'", "'.$lead_date_entered.'",
                                          "'.trim($rowData[27]).'", "'.trim($rowData[28]).'", "'.trim($rowData[29]).'", "'.trim($rowData[31]).'"
                                        ) ';

                    $total_records_imported++;

                    if( $j == $limit_records ){
                        
                        $GLOBALS['db']->query($insert_task.' '.$values_task);
                        $GLOBALS['db']->query($insert_task_cstm.' '.$values_task_cstm);

                        $values_task = '';
                        $values_task_cstm = '';

                        $j = 1;
                    }
                    $i++;
                }//end of while loop


                if(!empty($values_task)){
                    $GLOBALS['db']->query($insert_task.' '.$values_task);
                    $GLOBALS['db']->query($insert_task_cstm.' '.$values_task_cstm);
                }

            }//end of if

            fclose($file);
            echo '<br/><br/><b>Total Records Imported</b> :: '.$total_records_imported;
            
             // Write appilication number which doesn't exist in System
            $file_id = create_guid();
            $file_text = "custom/Application_Number_Not_Found_".$file_id.".txt";
            
            if(!empty($task_empty_lead_id)){
                write_errors_in_file($task_empty_lead_id, $file_text);
            }
           
        }//end of outer If
    }//End of Request IF condition for filename checkings
    
     
    function convert_date_to_db_date($old_date){
        $new_date = '';
        if(!empty($old_date)){
            $new_date = date("Y-m-d H:i:s",strtotime(str_replace('/','-',$old_date)));
        }
        return $new_date;
    }
    
    function write_errors_in_file($task_empty_lead_id, $file_text){
        $text = 'Task ID , Application Number '."\n";
        foreach($task_empty_lead_id as $key => $value)
        {
            $text.= $key." , ".$value."\n";
        }
        $myfile = fopen($file_text, "w") or die("Unable to open file!");
        fwrite($myfile, $text) or die("Could not write file!");
        fclose($myfile);
        
        echo '<br/><br/><font color="red">Either Lead Record is deleted or application number doesnot exist </font>'.' <br/>File Path : '.$file_text;
    }
