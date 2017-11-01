<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once ('include/TimeDate.php');

class getRelatedDocument {

    protected static $fetchedRow = array();

    public function saveFetchedRow($bean, $event, $arguments) {
        self::$fetchedRow[$bean->id] = $bean->fetched_row;
    }

    function getRelatedDocumentStatus($bean, $event, $arguments) {
        global $app_list_strings;
        $categoryList = $app_list_strings['dotb_document_category_list'];
        $docsTrackList = array();
        $create_new_docs = array();

        //Document List on basis on category
        $rent_split = "rental_contract";

        $work_permit = array(
            'b_permit' => array("residence_permit"),
            'g_permit' => array("residence_permit"),
            'l_permit' => array("residence_permit")
        );

        $employment_type = array(
            'self_employed' => array("tax_assessment"),
            'permanent_contract' => array("pay_slips"), //,"pay_slips"
            'temporary_contract' => array("employment_contract", "pay_slips", "pay_slips"),
            'retirement' => array("account_credit_alimony_wages_rent_pension", "available_pensionskasse"),
            'disabled_gets_pension' => array("certificate_iv", "account_credit_alimony_wages_rent_pension", "available_pensionskasse")
        );


        $civil_status = array(
            'widowed' => array("certificate_widows_pension"),
            'divorced' => array("divorce_decree", "account_credit_alimony_wages_rent_pension"),
            //'separated' => array("divorce_decree", "account_credit_alimony_wages_rent_pension"),
            'married' => array("document_venture_partner"),
            'registered_partnership' => array("ID_passport_driving_license_partner", "document_venture_partner")
        );

        $single_parent = array("account_credit_alimony_wages_rent_pension");

        $premium_reduction = array("insurance_policy", "premium_reduction");
        $getfieldName = array(
            'ID_passport_driving_license' => 'dotb_iso_nationality_code_c',
            'rental_contract' => array('dotb_is_rent_split_c', 'dotb_housing_costs_rent_c'),
            'residence_permit' => array('dotb_iso_nationality_code_c', 'dotb_work_permit_type_id_c'),
            'tax_assessment' => 'dotb_employment_type_id_c',
            'pay_slips' => 'dotb_employment_type_id_c',
            'employment_contract' => 'dotb_employment_type_id_c',
            //'account_credit_alimony_wages_rent_pension' => 'dotb_employment_type_id_c',
            'certificate_iv' => 'dotb_employment_type_id_c',
            //'account_credit_alimony_wages_rent_pension' => 'dotb_employment_type_id_c',
            'available_pensionskasse' => 'dotb_employment_type_id_c',
            'certificate_widows_pension' => 'dotb_civil_status_id_c',
            //'account_credit_alimony_wages_rent_pension' => 'dotb_civil_status_id_c',
            'document_venture_partner' => 'dotb_civil_status_id_c',
            'ID_passport_driving_license_partner' => 'dotb_civil_status_id_c',
            'divorce_decree' => 'dotb_civil_status_id_c',
            'account_credit_alimony_wages_rent_pension' => array('dotb_employment_type_id_c', 'dotb_civil_status_id_c', 'dotb_rent_alimony_income_c', 'dotb_additional_income_desc_c'),
            'insurance_policy' => 'dotb_has_premium_reduction_c',
            'premium_reduction' => 'dotb_has_premium_reduction_c',
        );

        //if (isset(self::$fetchedRow[$bean->id]['id'])) {
        //Calculation duration on the basis of date
        $date_obj = new TimeDate();
        $current_date = date("Y-m-d");
        $work_permit_since_date = $bean->dotb_work_permit_since_c;

        $work_permit_since_days = floor((abs(strtotime($current_date) - strtotime($work_permit_since_date))) / 86400);
        $work_permit_since_months = floor($work_permit_since_days / 30);
        $work_permit_since_years = floor($work_permit_since_months / 12);

        $workPeriod = $work_permit_since_months;

        // $date_obj2 = new TimeDate();
        // $to_emp = date("Y-m-d");
        $employed_since_date = $bean->dotb_employed_since_c;

        $employed_since_days = floor((abs(strtotime($current_date) - strtotime($employed_since_date))) / 86400);
        $employed_since_months = floor($employed_since_days / 30);
        $employed_since_years = floor($employed_since_months / 12);

        $employedPeriod = $employed_since_months;

        $nationality = '';
        if (isset(self::$fetchedRow[$bean->id]['dotb_iso_nationality_code_c']))
            if (self::$fetchedRow[$bean->id]['dotb_iso_nationality_code_c'] != 'ch' && $bean->dotb_iso_nationality_code_c == "ch") {
                $nationality = "ID_passport_driving_license";
            }
        if ($bean->dotb_iso_nationality_code_c != "ch" && !empty($bean->dotb_iso_nationality_code_c)) {
            $nationality = "residence_permit";
        }

        if ($bean->load_relationship('leads_documents_1')) {
            $relatedDocumentsBeans = $bean->leads_documents_1->getBeans();

            //get category of all documents related to the lead in 'docsTrackList' array
            foreach ($relatedDocumentsBeans as $doc) {
                $doc->load_relationship('documents_dotb7_document_tracking_1');
                $relatedDocumentTrackBeans = $doc->documents_dotb7_document_tracking_1->getBeans();
                foreach ($relatedDocumentTrackBeans as $docTrack) {
                    $docsTrackList[] = $docTrack->category;
                }
            }

            //Check For Employment Type
            if (!empty($bean->dotb_employment_type_id_c)) {
                if (isset($employment_type[$bean->dotb_employment_type_id_c])) {
                    foreach ($employment_type[$bean->dotb_employment_type_id_c] as $key => $value) {
                        if ($bean->dotb_employment_type_id_c == "self_employed" && $employed_since_months > 24) {
                            $searchResult = array_search($value, $docsTrackList);
                            if (!empty($docsTrackList)) {
                                if (!(false !== $searchResult)) {
                                    //doc tracking not found add to creation list
                                    $create_new_docs[] = $value;
                                }
                            } else {
                                $create_new_docs[] = $value;
                            }
                        }

                        if ($bean->dotb_employment_type_id_c == "temporary_contract" && $employed_since_months > 12) {
                            $searchResult = array_search($value, $docsTrackList);
                            if (!empty($docsTrackList)) {
                                if (!(false !== $searchResult)) {
                                    //doc tracking not found add to creation list
                                    $create_new_docs[] = $value;
                                }
                            } else {
                                $create_new_docs[] = $value;
                            }
                        }

                        if ($bean->dotb_employment_type_id_c != "temporary_contract" && $bean->dotb_employment_type_id_c != "self_employed") {
                            $searchResult = array_search($value, $docsTrackList);
                            if (!empty($docsTrackList)) {
                                if (!(false !== $searchResult)) {
                                    //doc tracking not found add to creation list
                                    $create_new_docs[] = $value;
                                }
                            } else {
                                $create_new_docs[] = $value;
                            }
                        }
                    }
                }
            }


            //Check For Civil Status
            if (!empty($bean->dotb_civil_status_id_c)) {
                if ($bean->dotb_civil_status_id_c == "registered_partnership" || $bean->dotb_civil_status_id_c == "married") {
                    if ($bean->dotb_has_second_job_c == "yes") {
                        if (isset($civil_status[$bean->dotb_civil_status_id_c])) {
                            foreach ($civil_status[$bean->dotb_civil_status_id_c] as $key => $value) {
                                $searchResult = array_search($value, $docsTrackList);
                                if (!empty($docsTrackList)) {
                                    if (!(false !== $searchResult)) {
                                        //doc tracking not found add to creation list
                                        $create_new_docs[] = $value;
                                    }
                                } else {
                                    $create_new_docs[] = $value;
                                }
                            }
                        }
                    }
                } else {
                    if (isset($civil_status[$bean->dotb_civil_status_id_c])) {
                        foreach ($civil_status[$bean->dotb_civil_status_id_c] as $key => $value) {
                            $searchResult = array_search($value, $docsTrackList);
                            if (!empty($docsTrackList)) {
                                if (!(false !== $searchResult)) {
                                    //doc tracking not found add to creation list
                                    $create_new_docs[] = $value;
                                }
                            } else {
                                $create_new_docs[] = $value;
                            }
                        }
                    }
                }
            }
            //Check For Work Permit
            if (!empty($bean->dotb_work_permit_type_id_c)) {
                if (isset($work_permit[$bean->dotb_work_permit_type_id_c])) {
                    foreach ($work_permit[$bean->dotb_work_permit_type_id_c] as $key => $value) {
                        if ($bean->dotb_work_permit_type_id_c == "b_permit" && $work_permit_since_months > 6) {
                            $searchResult = array_search($value, $docsTrackList);
                            if (!empty($docsTrackList)) {
                                if (!(false !== $searchResult)) {
                                    //doc tracking not found add to creation list
                                    $create_new_docs[] = $value;
                                }
                            } else {
                                $create_new_docs[] = $value;
                            }
                        } else if ($bean->dotb_work_permit_type_id_c == "b_permit" && $work_permit_since_months < 6) {
                            $searchResult = array_search($value, $docsTrackList);
                            if (!empty($docsTrackList)) {
                                if (!(false !== $searchResult)) {
                                    $create_new_docs[] = $value;
                                }
                            } else {
                                $create_new_docs[] = $value;
                            }
                        }

                        if ($bean->dotb_work_permit_type_id_c == "g_permit" && $work_permit_since_years > 3) {
                            $searchResult = array_search($value, $docsTrackList);
                            if (!empty($docsTrackList)) {
                                if (!(false !== $searchResult)) {
                                    //doc tracking not found add to creation list
                                    $create_new_docs[] = $value;
                                }
                            } else {
                                $create_new_docs[] = $value;
                            }
                        } else if ($bean->dotb_work_permit_type_id_c == "g_permit" && $work_permit_since_years < 3) {
                            $searchResult = array_search($value, $docsTrackList);
                            if (!empty($docsTrackList)) {
                                if (!(false !== $searchResult)) {
                                    $create_new_docs[] = $value;
                                }
                            } else {
                                $create_new_docs[] = $value;
                            }
                        }

                        if ($bean->dotb_work_permit_type_id_c == "l_permit" && $work_permit_since_years < 1) {
                            $searchResult = array_search($value, $docsTrackList);
                            if (!empty($docsTrackList)) {
                                if (!(false !== $searchResult)) {
                                    //doc tracking not found add to creation list
                                    $create_new_docs[] = $value;
                                }
                            } else {
                                $create_new_docs[] = $value;
                            }
                        } else if ($bean->dotb_work_permit_type_id_c == "l_permit" && $work_permit_since_years > 1) {
                            $searchResult = array_search($value, $docsTrackList);
                            if (!empty($docsTrackList)) {
                                if (!(false !== $searchResult)) {
                                    $create_new_docs[] = $value;
                                }
                            } else {
                                $create_new_docs[] = $value;
                            }
                        }
                    }
                }
            }

            //Check For Housing Situation
            if ($bean->dotb_civil_status_id_c == "separated" && $bean->dotb_rent_alimony_income_c == "yes" && $bean->dotb_additional_income_desc_c == "alimony") {
                foreach ($single_parent as $key => $value) {
                    $searchResult = array_search($value, $docsTrackList);
                    if (!empty($docsTrackList)) {
                        if (!(false !== $searchResult)) {
                            //doc tracking not found add to creation list
                            $create_new_docs[] = $value;
                        }
                    } else {
                        $create_new_docs[] = $value;
                    }
                }
            }

            //Check For Nationality
            if (!empty($docsTrackList)) {
                $searchResult = array_search($nationality, $docsTrackList);
                if (!(false !== $searchResult)) {
                    $residence_permit_result = array_search($nationality, $create_new_docs);
                    if (!(false !== $residence_permit_result)) {
                        $create_new_docs[] = $nationality;
                    }
                }
            } else {
                // to check is residence_permit is already created by work permit checks
                $residence_permit_result = array_search($nationality, $create_new_docs);
                if (!(false !== $residence_permit_result)) {
                    $create_new_docs[] = $nationality;
                }
            }

            //Check For Rent Split
            if ($bean->dotb_is_rent_split_c == "yes" && $bean->dotb_housing_costs_rent_c < 1000  && !empty($bean->dotb_housing_costs_rent_c)) {
                $searchResult = array_search($rent_split, $docsTrackList);
                if (!empty($docsTrackList)) {
                    if (!(false !== $searchResult)) {
                        //doc tracking not found add to creation list
                        $create_new_docs[] = $rent_split;
                    }
                } else {
                    $create_new_docs[] = $rent_split;
                }
            }

            //Check For Premium Reduction
            if ($bean->dotb_has_premium_reduction_c == "yes") {
                foreach ($premium_reduction as $key => $value) {
                    $searchResult = array_search($value, $docsTrackList);
                    if (!empty($docsTrackList)) {
                        if (!(false !== $searchResult)) {
                            //doc tracking not found add to creation list
                            $create_new_docs[] = $value;
                        }
                    } else {
                        $create_new_docs[] = $value;
                    }
                }
            }
        }
        //}
        $create_new_docs = array_filter($create_new_docs);
        if (!empty($create_new_docs)) {
            foreach ($create_new_docs as $key => $val) {
                /*
                 * Checking if field is changed or not
                 *
                $create_doc = false;
                if (isset($getfieldName[$val])) {
                    $fieldName = $getfieldName[$val];
                    if (is_array($fieldName)) {
                        foreach ($fieldName as $field) {
                            if (self::$fetchedRow[$bean->id][$field] != $bean->$field)
                                $create_doc = true;
                        }
                    }else {
                        if (self::$fetchedRow[$bean->id][$fieldName] != $bean->$fieldName)
                            $create_doc = true;
                    }
                }

                if ($create_doc || empty($bean->credit_request_status_id_c)) {*/
                //By Seting to True the system will not check wether the field is changed or not
                if (true) {
                    $newDocument = BeanFactory::getBean("Documents");
                    $newDocument->document_name = $categoryList[$val];
                    $newDocument->save();

                    $documentTrack = BeanFactory::getBean("dotb7_document_tracking");
                    $documentTrack->category = $val;
                    $documentTrack->status = "fehlt";
                    $documentTrack->name = $newDocument->document_name;
                    $documentTrack->documents_checked = 0;
                    $documentTrack->documents_recieved = 0;
                    $documentTrack->save();

                    //Attaching documents to Lead
                    $newDocument->load_relationship('documents_dotb7_document_tracking_1');
                    $newDocument->documents_dotb7_document_tracking_1->add($documentTrack->id);
                    $bean->load_relationship('leads_documents_1');
                    $bean->leads_documents_1->add($newDocument->id);
                }
            }
        }
    }

}
