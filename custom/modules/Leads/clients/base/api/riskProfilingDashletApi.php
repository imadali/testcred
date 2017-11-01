<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once ('include/TimeDate.php');
require_once('include/SugarQuery/SugarQuery.php');

class riskProfilingDashletApi extends SugarApi {

    public $riskFactor;
    public $fieldDefs;
    public $leadBean;
    public $leadDefs;
    public $newFlag3;

    public function registerApiRest() {
        return array(
            'getRiskProfilingData' => array(
                'reqType' => 'GET',
                'path' => array('Leads', 'RiskProfile', '?'),
                'pathVars' => array('', '', 'id'),
                'method' => 'getRiskProfileData',
                'shortHelp' => 'This api will return the related risk profile for a Lead',
                'longHelp' => '',
            ),
        );
    }

    public function overallRiskCalculation() {
        //Getting Risk Profile record
        global $db;
        $temp = array();

        $sql = 'SELECT * 
            FROM dotb9_risk_profiling r1
            INNER JOIN dotb9_risk_profiling_cstm r2 ON r1.id = r2.id_c
            AND r1.deleted =0
            WHERE r1.name LIKE  "RiskFactor"
            AND r2.status_c = "Inactive"';

        $result = $db->query($sql);

        while ($row = $db->fetchByAssoc($result)) {
            $temp[] = $row;
        }


        //Removing Unwanted indexes
        unset($temp[0]['id']);
        unset($temp[0]['id_c']);
        unset($temp[0]['name']);
        unset($temp[0]['date_entered']);
        unset($temp[0]['date_modified']);
        unset($temp[0]['modified_user_id']);
        unset($temp[0]['created_by']);
        unset($temp[0]['description']);
        unset($temp[0]['deleted']);
        unset($temp[0]['team_id']);
        unset($temp[0]['team_set_id']);
        unset($temp[0]['assigned_user_id']);
        unset($temp[0]['has_intrum_response_c']);
        unset($temp[0]['intrum_score_c']);
        unset($temp[0]['intrum_request_id_c']);
        unset($temp[0]['deltavista_score_c']);
        unset($temp[0]['has_deltavista_response_c']);
        unset($temp[0]['deltavista_request_id_c']);
        unset($temp[0]['dotb_overall_risk_c']);
        unset($temp[0]['status_c']);


        $this->riskFactor = $temp[0];
    }

    //Function for caculating the Risk Factor using flags set bythe Owner
    public function riskFactorCalculation() {

        $riskFactorFlag = array();
        $innerArray = array();
        $overAllRiskFlag = array();

        $riskFactorFlag['flag'] = "";
        //Credit Amount
        if (!empty($this->leadBean->credit_amount_c)) {

            if ($this->riskFactor['more_than_80000_c'] == "Green") {

                if ($this->leadBean->credit_amount_c > 80000) {
                    $innerArray[$this->fieldDefs["more_than_80000_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["more_than_80000_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['more_than_80000_c'] == "Yellow") {
                if ($this->leadBean->credit_amount_c > 80000) {
                    $innerArray[$this->fieldDefs["more_than_80000_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["more_than_80000_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['more_than_80000_c'] == "Red") {
                if ($this->leadBean->credit_amount_c > 80000) {
                    $innerArray[$this->fieldDefs["more_than_80000_c"]['vname']] = "NO";
                } else {
                    $innerArray[$this->fieldDefs["more_than_80000_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["more_than_80000_c"]['vname']] = 'Missing';
        }


        //Primary Address Country Postcode if Liechtenstein and not Swiss or citizen FL
        if (!empty($this->leadBean->dotb_iso_nationality_code_c)) {
            if (!empty($this->leadBean->primary_address_country) && $this->leadBean->primary_address_country == "Liechtenstein") {

                if ($this->riskFactor["postcode_if_liechtenstein_c"] == "Green") {
                    if ($this->leadBean->primary_address_country == "Liechtenstein" && $this->leadBean->dotb_iso_nationality_code_c != "ch") {
                        $innerArray[$this->fieldDefs["postcode_if_liechtenstein_c"]['vname']] = "YES";
                    } else {
                        $innerArray[$this->fieldDefs["postcode_if_liechtenstein_c"]['vname']] = "NO";
                    }
                } else if ($this->riskFactor['postcode_if_liechtenstein_c'] == "Yellow") {
                    if ($this->leadBean->primary_address_country == "Liechtenstein" && $this->leadBean->dotb_iso_nationality_code_c != "ch") {
                        $innerArray[$this->fieldDefs["postcode_if_liechtenstein_c"]['vname']] = "Caution";
                    } else {
                        $innerArray[$this->fieldDefs["postcode_if_liechtenstein_c"]['vname']] = "NO";
                    }
                } else if ($this->riskFactor['postcode_if_liechtenstein_c'] == "Red") {
                    if ($this->leadBean->primary_address_country == "Liechtenstein" && $this->leadBean->dotb_iso_nationality_code_c != "ch") {
                        $innerArray[$this->fieldDefs["postcode_if_liechtenstein_c"]['vname']] = "NO";
                    } else {
                        $innerArray[$this->fieldDefs["postcode_if_liechtenstein_c"]['vname']] = "YES";
                    }
                }
            }
        } else {
            $innerArray[$this->fieldDefs["postcode_if_liechtenstein_c"]['vname']] = "Missing";
        }

        // Liechtenstein and CH- or FL-Bürger
        // Implemented as: Liechtenstein or CH- or FL
        if (!empty($this->leadBean->dotb_iso_nationality_code_c)) {
            if (!empty($this->leadBean->primary_address_country) && $this->leadBean->primary_address_country == "Liechtenstein") {

                if ($this->riskFactor['zip_liechtenstein_and_swiss_c'] == "Green") {
                    if ($this->leadBean->primary_address_country == "Liechtenstein" && $this->leadBean->dotb_iso_nationality_code_c == "ch") {
                        $innerArray[$this->fieldDefs["zip_liechtenstein_and_swiss_c"]['vname']] = "YES";
                    } else {
                        $innerArray[$this->fieldDefs["zip_liechtenstein_and_swiss_c"]['vname']] = "NO";
                    }
                } else if ($this->riskFactor['zip_liechtenstein_and_swiss_c'] == "Yellow") {
                    if ($this->leadBean->primary_address_country == "Liechtenstein" && $this->leadBean->dotb_iso_nationality_code_c == "ch") {
                        $innerArray[$this->fieldDefs["zip_liechtenstein_and_swiss_c"]['vname']] = "Caution";
                    } else {
                        $innerArray[$this->fieldDefs["zip_liechtenstein_and_swiss_c"]['vname']] = "NO";
                    }
                } else if ($this->riskFactor['zip_liechtenstein_and_swiss_c'] == "Red") {
                    if ($this->leadBean->primary_address_country == "Liechtenstein" && $this->leadBean->dotb_iso_nationality_code_c == "ch") {
                        $innerArray[$this->fieldDefs["zip_liechtenstein_and_swiss_c"]['vname']] = "NO";
                    } else {
                        $innerArray[$this->fieldDefs["zip_liechtenstein_and_swiss_c"]['vname']] = "YES";
                    }
                }
            }
        } else {
            $innerArray[$this->fieldDefs["zip_liechtenstein_and_swiss_c"]['vname']] = "Missing";
        }
        //Credit Usage Type
        if (!empty($this->leadBean->credit_usage_type_id_c)) {

            if ($this->riskFactor['pay_bills_taxes_inv_real_est_c'] == "Green") {
                if ($this->leadBean->credit_usage_type_id_c == "taxes" || $this->leadBean->credit_usage_type_id_c == "invoices" || $this->leadBean->credit_usage_type_id_c == "investment" || $this->leadBean->credit_usage_type_id_c == "real_estate_abroad") {
                    $innerArray[$this->fieldDefs["pay_bills_taxes_inv_real_est_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["pay_bills_taxes_inv_real_est_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['pay_bills_taxes_inv_real_est_c'] == "Yellow") {
                if ($this->leadBean->credit_usage_type_id_c == "taxes" || $this->leadBean->credit_usage_type_id_c == "invoices" || $this->leadBean->credit_usage_type_id_c == "investment" || $this->leadBean->credit_usage_type_id_c == "real_estate_abroad") {
                    $innerArray[$this->fieldDefs["pay_bills_taxes_inv_real_est_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["pay_bills_taxes_inv_real_est_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['pay_bills_taxes_inv_real_est_c'] == "Red") {
                if ($this->leadBean->credit_usage_type_id_c == "taxes" || $this->leadBean->credit_usage_type_id_c == "invoices" || $this->leadBean->credit_usage_type_id_c == "investment" || $this->leadBean->credit_usage_type_id_c == "real_estate_abroad") {
                    $innerArray[$this->fieldDefs["pay_bills_taxes_inv_real_est_c"]['vname']] = "NO";
                } else {
                    $innerArray[$this->fieldDefs["pay_bills_taxes_inv_real_est_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["pay_bills_taxes_inv_real_est_c"]['vname']] = "Missing";
        }

        //Has Enforcements

        if (!empty($this->leadBean->dotb_has_enforcements_c)) {

            if ($this->riskFactor['currently_open_enforcements_c'] == "Green") {
                if ($this->leadBean->dotb_has_enforcements_c == "yes") {
                    $innerArray[$this->fieldDefs["currently_open_enforcements_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["currently_open_enforcements_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['currently_open_enforcements_c'] == "Yellow") {
                if ($this->leadBean->dotb_has_enforcements_c == "yes") {
                    $innerArray[$this->fieldDefs["currently_open_enforcements_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["currently_open_enforcements_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['currently_open_enforcements_c'] == "Red") {
                if ($this->leadBean->dotb_has_enforcements_c == "yes") {
                    $innerArray[$this->fieldDefs["currently_open_enforcements_c"]['vname']] = "NO";
                } else {
                    $innerArray[$this->fieldDefs["currently_open_enforcements_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["currently_open_enforcements_c"]['vname']] = "Missing";
        }

        //Past Enforcements
        if (!empty($this->leadBean->dotb_past_enforcements_c)) {

            if ($this->riskFactor['if_enforcements_in_the_past_c'] == "Green") {
                if ($this->leadBean->dotb_past_enforcements_c == "yes") {
                    $innerArray[$this->fieldDefs["if_enforcements_in_the_past_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_enforcements_in_the_past_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_enforcements_in_the_past_c'] == "Yellow") {
                if ($this->leadBean->dotb_past_enforcements_c == "yes") {
                    $innerArray[$this->fieldDefs["if_enforcements_in_the_past_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_enforcements_in_the_past_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_enforcements_in_the_past_c'] == "Red") {
                if ($this->leadBean->dotb_past_enforcements_c == "no") {
                    $innerArray[$this->fieldDefs["if_enforcements_in_the_past_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_enforcements_in_the_past_c"]['vname']] = "NO";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_enforcements_in_the_past_c"]['vname']] = "Missing";
        }

        //YES +NO + Caution
        if (in_array("NO", $innerArray) && in_array("YES", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["PANEL_BODY"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        }
        //YES + NO
        else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["PANEL_BODY"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        }
        //NO + Caution
        else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["PANEL_BODY"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        }
        //YES + Caution
        else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["PANEL_BODY"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        }
        //Caution
        else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["PANEL_BODY"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        }
        //NO + Missing
        else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["PANEL_BODY"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        }
        //NO
        else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["PANEL_BODY"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        }
        //Missing
        else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["PANEL_BODY"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        }
        //Caution + Missing
        else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["PANEL_BODY"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        }
        //YES + NO + Missing(Red)
        else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["PANEL_BODY"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        }
        //YES + Caution + Missing
        else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["PANEL_BODY"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        }
        //NO + Caution + Missing(Red)
        else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["PANEL_BODY"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        }
        //YEs + NO + Caution + Missing(Red)
        else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["PANEL_BODY"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        }
        //YES + Missing
        else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["PANEL_BODY"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        }
        //YES
        else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {

            $overAllRiskFlag[] = "YES";
        }
        //None
        else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            
        }

        $flag12 = '';
        $innerArray = array();
        //Age less than 18
        if (!empty($this->leadBean->birthdate)) {

            if ($this->riskFactor['if_younger_than_18_c'] == "Green") {
                if ($this->leadBean->dotb_age_c < 18) {
                    $innerArray[$this->fieldDefs["if_younger_than_18_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_younger_than_18_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_younger_than_18_c'] == "Yellow") {
                if ($this->leadBean->dotb_age_c < 18) {
                    $innerArray[$this->fieldDefs["if_younger_than_18_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_younger_than_18_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_younger_than_18_c'] == "Red") {
                if ($this->leadBean->dotb_age_c < 18) {
                    $innerArray[$this->fieldDefs["if_younger_than_18_c"]['vname']] = "NO";
                    $flag12 = true;
                } else {
                    $innerArray[$this->fieldDefs["if_younger_than_18_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_younger_than_18_c"]['vname']] = "Missing";
        }


        $flag13 = '';
        //Age less than 21
        if (!empty($this->leadBean->birthdate)) {

            if ($this->riskFactor['if_younger_than_21_c'] == "Green") {
                if ($this->leadBean->dotb_age_c < 21) {
                    $innerArray[$this->fieldDefs["if_younger_than_21_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_younger_than_21_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_younger_than_21_c'] == "Yellow") {
                if ($this->leadBean->dotb_age_c < 21) {
                    $innerArray[$this->fieldDefs["if_younger_than_21_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_younger_than_21_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_younger_than_21_c'] == "Red") {
                if ($this->leadBean->dotb_age_c < 21) {
                    if ($flag12 != true) {
                        $innerArray[$this->fieldDefs["if_younger_than_21_c"]['vname']] = "NO";
                        $flag13 = true;
                    }
                } else {
                    $innerArray[$this->fieldDefs["if_younger_than_21_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_younger_than_21_c"]['vname']] = "Missing";
        }


        //Age less than 21 and credit amount > 15000
        $newFlag = '';
        if (!empty($this->leadBean->birthdate) && !empty($this->leadBean->credit_amount_c)) {

            if ($this->riskFactor['if_young_21_credit_amount_15_c'] == "Green") {
                if ($this->leadBean->dotb_age_c < 21) {
                    if ($this->leadBean->credit_amount_c > 15000) {
                        $innerArray[$this->fieldDefs["if_young_21_credit_amount_15_c"]['vname']] = "YES";
                    } else {
                        $innerArray[$this->fieldDefs["if_young_21_credit_amount_15_c"]['vname']] = "NO";
                    }
                }
            } else if ($this->riskFactor['if_young_21_credit_amount_15_c'] == "Yellow") {
                if ($this->leadBean->dotb_age_c < 21) {
                    if ($this->leadBean->credit_amount_c > 15000) {
                        $innerArray[$this->fieldDefs["if_young_21_credit_amount_15_c"]['vname']] = "Caution";
                    } else {
                        $innerArray[$this->fieldDefs["if_young_21_credit_amount_15_c"]['vname']] = "YES"; //Caution
                    }
                }
            } else if ($this->riskFactor['if_young_21_credit_amount_15_c'] == "Red") {
                if ($this->leadBean->dotb_age_c > 21) {
                    if ($this->leadBean->credit_amount_c < 15000) {
                        //both condition result to false
                        $innerArray[$this->fieldDefs["if_young_21_credit_amount_15_c"]['vname']] = "YES";
                    } else {
                        if ($flag13 == true) {
                            $innerArray[$this->fieldDefs["if_young_21_credit_amount_15_c"]['vname']] = "NO"; //no    
                            $newFlag = true;
                        }
                    }
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_young_21_credit_amount_15_c"]['vname']] = "Missing";
        }

        $flag14 = '';
        //Age less than 25
        if (!empty($this->leadBean->birthdate)) {

            if ($this->riskFactor['if_younger_than_25_c'] == "Green") {
                if ($this->leadBean->dotb_age_c < 25) {
                    $innerArray[$this->fieldDefs["if_younger_than_25_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_younger_than_25_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_younger_than_25_c'] == "Yellow") {
                if ($this->leadBean->dotb_age_c < 25) {
                    $innerArray[$this->fieldDefs["if_younger_than_25_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_younger_than_25_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_younger_than_25_c'] == "Red") {
                if ($this->leadBean->dotb_age_c < 25) {
                    if ($flag13 == true) {
                        $innerArray[$this->fieldDefs["if_younger_than_25_c"]['vname']] = "NO";
                        $flag14 = true;
                    }
                } else {
                    $innerArray[$this->fieldDefs["if_younger_than_25_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_younger_than_25_c"]['vname']] = "Missing";
        }

        //Age less than 25 and credit amount > 25000
        $newFlag1 = '';
        if (!empty($this->leadBean->birthdate) && !empty($this->leadBean->credit_amount_c)) {

            if ($this->riskFactor['if_young_25_credit_amount_25_c'] == "Green") {
                if ($this->leadBean->dotb_age_c < 25) {
                    if ($this->leadBean->credit_amount_c > 25000) {
                        $innerArray[$this->fieldDefs["if_young_25_credit_amount_25_c"]['vname']] = "YES";
                    } else {
                        $innerArray[$this->fieldDefs["if_young_25_credit_amount_25_c"]['vname']] = "NO";
                    }
                }
            } else if ($this->riskFactor['if_young_25_credit_amount_25_c'] == "Yellow") {
                if ($this->leadBean->dotb_age_c < 25) {
                    if ($this->leadBean->credit_amount_c > 25000) {
                        $innerArray[$this->fieldDefs["if_young_25_credit_amount_25_c"]['vname']] = "Caution";
                    } else {
                        $innerArray[$this->fieldDefs["if_young_25_credit_amount_25_c"]['vname']] = "YES";
                    }
                }
            } else if ($this->riskFactor['if_young_25_credit_amount_25_c'] == "Red") {
                if ($this->leadBean->dotb_age_c < 25) {
                    if ($this->leadBean->credit_amount_c < 25000) {
                        $innerArray[$this->fieldDefs["if_young_25_credit_amount_25_c"]['vname']] = "YES";
                    } else {
                        if ($newFlag == true) {
                            $innerArray[$this->fieldDefs["if_young_25_credit_amount_25_c"]['vname']] = "NO";
                            $newFlag1 = true;
                        }
                    }
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_young_25_credit_amount_25_c"]['vname']] = "Missing";
        }
        //Age greater than 59
        $flag16 = '';
        if (!empty($this->leadBean->birthdate)) {

            if ($this->riskFactor['if_older_than_59_c'] == "Green") {

                if ($this->leadBean->dotb_age_c > 59) {
                    $innerArray[$this->fieldDefs["if_older_than_59_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_older_than_59_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_older_than_59_c'] == "Yellow") {
                if ($this->leadBean->dotb_age_c > 59) {
                    $innerArray[$this->fieldDefs["if_older_than_59_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_older_than_59_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_older_than_59_c'] == "Red") {
                if ($this->leadBean->dotb_age_c > 59) {

                    $innerArray[$this->fieldDefs["if_older_than_59_c"]['vname']] = "NO";
                    $flag16 = true;
                } else {
                    $innerArray[$this->fieldDefs["if_older_than_59_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_older_than_59_c"]['vname']] = "Missing";
        }

        //Age greater than 59 and credit amount > 50000
        $newFlag2 = '';
        if (!empty($this->leadBean->birthdate) && !empty($this->leadBean->credit_amount_c)) {

            if ($this->riskFactor['if_older_59_credit_amount_50_c'] == "Green") {
                if ($this->leadBean->dotb_age_c > 59) {
                    if ($this->leadBean->credit_amount_c > 50000) {
                        $innerArray[$this->fieldDefs["if_older_59_credit_amount_50_c"]['vname']] = "YES";
                    } else {
                        $innerArray[$this->fieldDefs["if_older_59_credit_amount_50_c"]['vname']] = "NO";
                    }
                }
            } else if ($this->riskFactor['if_older_59_credit_amount_50_c'] == "Yellow") {
                if ($this->leadBean->dotb_age_c > 59) {
                    if ($this->leadBean->credit_amount_c > 50000) {
                        $innerArray[$this->fieldDefs["if_older_59_credit_amount_50_c"]['vname']] = "Caution";
                    } else {
                        $innerArray[$this->fieldDefs["if_older_59_credit_amount_50_c"]['vname']] = "YES";
                    }
                }
            } else if ($this->riskFactor['if_older_59_credit_amount_50_c'] == "Red") {
                if ($this->leadBean->dotb_age_c > 59) {
                    if ($this->leadBean->credit_amount_c < 50000) {
                        $innerArray[$this->fieldDefs["if_older_59_credit_amount_50_c"]['vname']] = "YES";
                    } else {
                        if ($newFlag1 == true) {
                            $innerArray[$this->fieldDefs["if_older_59_credit_amount_50_c"]['vname']] = "NO";
                            $newFlag2 = true;
                        }
                    }
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_older_59_credit_amount_50_c"]['vname']] = "Missing";
        }

        //Age less than 64
        $flag15 = '';
        if (!empty($this->leadBean->birthdate)) {

            if ($this->riskFactor['if_younger_than_64_c'] == "Green") {
                if ($this->leadBean->dotb_age_c < 64) {
                    $innerArray[$this->fieldDefs["if_younger_than_64_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_younger_than_64_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_younger_than_64_c'] == "Yellow") {
                if ($this->leadBean->dotb_age_c < 64) {
                    $innerArray[$this->fieldDefs["if_younger_than_64_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_younger_than_64_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_younger_than_64_c'] == "Red") {
                if ($this->leadBean->dotb_age_c < 64) {
                    if ($flag14 == true) {
                        $innerArray[$this->fieldDefs["if_younger_than_64_c"]['vname']] = "NO";
                        $flag15 = true;
                    }
                } else {
                    $innerArray[$this->fieldDefs["if_younger_than_64_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_younger_than_64_c"]['vname']] = "Missing";
        }

        //Age less than 64 and credit amount > 50000.
        $this->newFlag3 = '';
        if (!empty($this->leadBean->birthdate) && !empty($this->leadBean->credit_amount_c)) {

            if ($this->riskFactor['if_younger_64_credit_50000_c'] == "Green") {
                if ($this->leadBean->dotb_age_c < 64) {
                    if ($this->leadBean->credit_amount_c > 50000) {
                        $innerArray[$this->fieldDefs["if_younger_64_credit_50000_c"]['vname']] = "YES";
                    } else {
                        $innerArray[$this->fieldDefs["if_younger_64_credit_50000_c"]['vname']] = "NO";
                    }
                }
            } else if ($this->riskFactor['if_younger_64_credit_50000_c'] == "Yellow") {
                if ($this->leadBean->dotb_age_c < 64) {
                    if ($this->leadBean->credit_amount_c > 50000) {
                        $innerArray[$this->fieldDefs["if_younger_64_credit_50000_c"]['vname']] = "Caution";
                    } else {
                        $innerArray[$this->fieldDefs["if_younger_64_credit_50000_c"]['vname']] = "YES";
                    }
                }
            } else if ($this->riskFactor['if_younger_64_credit_50000_c'] == "Red") {
                if ($this->leadBean->dotb_age_c < 64) {
                    if ($this->leadBean->credit_amount_c < 50000) {
                        $innerArray[$this->fieldDefs["if_younger_64_credit_50000_c"]['vname']] = "YES";
                    } else {
                        if ($flag15 == true) {
                            $innerArray[$this->fieldDefs["if_younger_64_credit_50000_c"]['vname']] = "NO";
                            $this->newFlag3 = true;
                        }
                    }
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_younger_64_credit_50000_c"]['vname']] = "Missing";
        }

        //Age greater than 65
        $flag17 = '';
        if (!empty($this->leadBean->birthdate)) {

            if ($this->riskFactor['if_older_than_65_c'] == "Green") {
                if ($this->leadBean->dotb_age_c > 65) {
                    $innerArray[$this->fieldDefs["if_older_than_65_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_older_than_65_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_older_than_65_c'] == "Yellow") {
                if ($this->leadBean->dotb_age_c > 65) {
                    $innerArray[$this->fieldDefs["if_older_than_65_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_older_than_65_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_older_than_65_c'] == "Red") {
                if ($this->leadBean->dotb_age_c > 65) {
                    if ($flag16 == true) {
                        $innerArray[$this->fieldDefs["if_older_than_65_c"]['vname']] = "NO";
                        $flag17 = true;
                    }
                } else {
                    $innerArray[$this->fieldDefs["if_older_than_65_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_older_than_65_c"]['vname']] = "Missing";
        }

        //Age greater than 70
        $flag18 = true;
        if (!empty($this->leadBean->birthdate)) {

            if ($this->riskFactor['if_older_than_70_c'] == "Green") {
                if ($this->leadBean->dotb_age_c > 70) {
                    $innerArray[$this->fieldDefs["if_older_than_70_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_older_than_70_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_older_than_70_c'] == "Yellow") {
                if ($this->leadBean->dotb_age_c > 70) {
                    $innerArray[$this->fieldDefs["if_older_than_70_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_older_than_70_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_older_than_70_c'] == "Red") {
                if ($this->leadBean->dotb_age_c > 70) {
                    if ($flag17 == true) {
                        $innerArray[$this->fieldDefs["if_older_than_70_c"]['vname']] = "NO";
                        $flag18 = true;
                    }
                } else {
                    $innerArray[$this->fieldDefs["if_older_than_70_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_older_than_70_c"]['vname']] = "Missing";
        }


        if (in_array("NO", $innerArray) && in_array("YES", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL1"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL1"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL1"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL1"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL1"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL1"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL1"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL1"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL1"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL1"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL1"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL1"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL1"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL1"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            //$riskFactorFlag["PANEL_BODY"] = $innerArray; 
            $overAllRiskFlag[] = "YES";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            //$riskFactorFlag["PANEL_BODY"] = $innerArray; 
            //$overAllRiskFlag[] = "YES";
        }

        $innerArray = array();
        //Nationality
        if (!empty($this->leadBean->dotb_iso_nationality_code_c)) {


            if ($this->riskFactor['if_iran_red_syr_mianmar_sud_c'] == "Green") {
                if ($this->leadBean->dotb_iso_nationality_code_c == "ir" || $this->leadBean->dotb_iso_nationality_code_c == "kp" || $this->leadBean->dotb_iso_nationality_code_c == "sy" || $this->leadBean->dotb_iso_nationality_code_c == "sd" || $this->leadBean->dotb_iso_nationality_code_c == "mm") {
                    $innerArray[$this->fieldDefs["if_iran_red_syr_mianmar_sud_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_iran_red_syr_mianmar_sud_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_iran_red_syr_mianmar_sud_c'] == "Yellow") {
                if ($this->leadBean->dotb_iso_nationality_code_c == "ir" || $this->leadBean->dotb_iso_nationality_code_c == "kp" || $this->leadBean->dotb_iso_nationality_code_c == "sy" || $this->leadBean->dotb_iso_nationality_code_c == "sd" || $this->leadBean->dotb_iso_nationality_code_c == "mm") {
                    $innerArray[$this->fieldDefs["if_iran_red_syr_mianmar_sud_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_iran_red_syr_mianmar_sud_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_iran_red_syr_mianmar_sud_c'] == "Red") {
                if ($this->leadBean->dotb_iso_nationality_code_c == "ir" || $this->leadBean->dotb_iso_nationality_code_c == "kp" || $this->leadBean->dotb_iso_nationality_code_c == "sy" || $this->leadBean->dotb_iso_nationality_code_c == "sd" || $this->leadBean->dotb_iso_nationality_code_c == "mm") {
                    $innerArray[$this->fieldDefs["if_iran_red_syr_mianmar_sud_c"]['vname']] = "NO";
                } else {
                    $innerArray[$this->fieldDefs["if_iran_red_syr_mianmar_sud_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_iran_red_syr_mianmar_sud_c"]['vname']] = "Missing";
        }

        //Nationality
        if (!empty($this->leadBean->dotb_iso_nationality_code_c)) {

            if ($this->riskFactor['if_iraq_zim_con_leb_yem_usa_c'] == "Green") {
                if ($this->leadBean->dotb_iso_nationality_code_c == "iq" || $this->leadBean->dotb_iso_nationality_code_c == "zw" || $this->leadBean->dotb_iso_nationality_code_c == "cd" || $this->leadBean->dotb_iso_nationality_code_c == "by" || $this->leadBean->dotb_iso_nationality_code_c == "lb" || $this->leadBean->dotb_iso_nationality_code_c == "so" || $this->leadBean->dotb_iso_nationality_code_c == "gn" || $this->leadBean->dotb_iso_nationality_code_c == "er" || $this->leadBean->dotb_iso_nationality_code_c == "ly" || $this->leadBean->dotb_iso_nationality_code_c == "gw" || $this->leadBean->dotb_iso_nationality_code_c == "cf" || $this->leadBean->dotb_iso_nationality_code_c == "ye" || $this->leadBean->dotb_iso_nationality_code_c == "us" || $this->leadBean->dotb_iso_nationality_code_c == "lr") {
                    $innerArray[$this->fieldDefs["if_iraq_zim_con_leb_yem_usa_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_iraq_zim_con_leb_yem_usa_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_iraq_zim_con_leb_yem_usa_c'] == "Yellow") {
                if ($this->leadBean->dotb_iso_nationality_code_c == "iq" || $this->leadBean->dotb_iso_nationality_code_c == "zw" || $this->leadBean->dotb_iso_nationality_code_c == "cd" || $this->leadBean->dotb_iso_nationality_code_c == "by" || $this->leadBean->dotb_iso_nationality_code_c == "lb" || $this->leadBean->dotb_iso_nationality_code_c == "so" || $this->leadBean->dotb_iso_nationality_code_c == "gn" || $this->leadBean->dotb_iso_nationality_code_c == "er" || $this->leadBean->dotb_iso_nationality_code_c == "ly" || $this->leadBean->dotb_iso_nationality_code_c == "gw" || $this->leadBean->dotb_iso_nationality_code_c == "cf" || $this->leadBean->dotb_iso_nationality_code_c == "ye" || $this->leadBean->dotb_iso_nationality_code_c == "us" || $this->leadBean->dotb_iso_nationality_code_c == "lr") {
                    $innerArray[$this->fieldDefs["if_iraq_zim_con_leb_yem_usa_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_iraq_zim_con_leb_yem_usa_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_iraq_zim_con_leb_yem_usa_c'] == "Red") {
                if ($this->leadBean->dotb_iso_nationality_code_c == "iq" || $this->leadBean->dotb_iso_nationality_code_c == "zw" || $this->leadBean->dotb_iso_nationality_code_c == "cd" || $this->leadBean->dotb_iso_nationality_code_c == "by" || $this->leadBean->dotb_iso_nationality_code_c == "lb" || $this->leadBean->dotb_iso_nationality_code_c == "so" || $this->leadBean->dotb_iso_nationality_code_c == "gn" || $this->leadBean->dotb_iso_nationality_code_c == "er" || $this->leadBean->dotb_iso_nationality_code_c == "ly" || $this->leadBean->dotb_iso_nationality_code_c == "gw" || $this->leadBean->dotb_iso_nationality_code_c == "cf" || $this->leadBean->dotb_iso_nationality_code_c == "ye" || $this->leadBean->dotb_iso_nationality_code_c == "us" || $this->leadBean->dotb_iso_nationality_code_c == "lr") {
                    $innerArray[$this->fieldDefs["if_iraq_zim_con_leb_yem_usa_c"]['vname']] = "NO";
                } else {
                    $innerArray[$this->fieldDefs["if_iraq_zim_con_leb_yem_usa_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_iraq_zim_con_leb_yem_usa_c"]['vname']] = "Missing";
        }


        //Nationality
        if (!empty($this->leadBean->dotb_iso_nationality_code_c)) {

            if ($this->riskFactor['if_redt_ger_aus_den_sweden_c'] == "Green") {
                if ($this->leadBean->dotb_iso_nationality_code_c == "ch" || $this->leadBean->dotb_iso_nationality_code_c == "de" || $this->leadBean->dotb_iso_nationality_code_c == "fr" || $this->leadBean->dotb_iso_nationality_code_c == "it" || $this->leadBean->dotb_iso_nationality_code_c == "lu" || $this->leadBean->dotb_iso_nationality_code_c == "be" || $this->leadBean->dotb_iso_nationality_code_c == "nl" || $this->leadBean->dotb_iso_nationality_code_c == "at" || $this->leadBean->dotb_iso_nationality_code_c == "ie" || $this->leadBean->dotb_iso_nationality_code_c == "dk" || $this->leadBean->dotb_iso_nationality_code_c == "gb" || $this->leadBean->dotb_iso_nationality_code_c == "es" || $this->leadBean->dotb_iso_nationality_code_c == "fi" || $this->leadBean->dotb_iso_nationality_code_c == "se" || $this->leadBean->dotb_iso_nationality_code_c == "no" || $this->leadBean->dotb_iso_nationality_code_c == "li") {
                    $innerArray[$this->fieldDefs["if_redt_ger_aus_den_sweden_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_redt_ger_aus_den_sweden_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_redt_ger_aus_den_sweden_c'] == "Yellow") {
                if ($this->leadBean->dotb_iso_nationality_code_c == "ch" || $this->leadBean->dotb_iso_nationality_code_c == "de" || $this->leadBean->dotb_iso_nationality_code_c == "fr" || $this->leadBean->dotb_iso_nationality_code_c == "it" || $this->leadBean->dotb_iso_nationality_code_c == "lu" || $this->leadBean->dotb_iso_nationality_code_c == "be" || $this->leadBean->dotb_iso_nationality_code_c == "nl" || $this->leadBean->dotb_iso_nationality_code_c == "at" || $this->leadBean->dotb_iso_nationality_code_c == "ie" || $this->leadBean->dotb_iso_nationality_code_c == "dk" || $this->leadBean->dotb_iso_nationality_code_c == "gb" || $this->leadBean->dotb_iso_nationality_code_c == "es" || $this->leadBean->dotb_iso_nationality_code_c == "fi" || $this->leadBean->dotb_iso_nationality_code_c == "se" || $this->leadBean->dotb_iso_nationality_code_c == "no" || $this->leadBean->dotb_iso_nationality_code_c == "li") {
                    $innerArray[$this->fieldDefs["if_redt_ger_aus_den_sweden_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_redt_ger_aus_den_sweden_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_redt_ger_aus_den_sweden_c'] == "Red") {
                if ($this->leadBean->dotb_iso_nationality_code_c == "ch" || $this->leadBean->dotb_iso_nationality_code_c == "de" || $this->leadBean->dotb_iso_nationality_code_c == "fr" || $this->leadBean->dotb_iso_nationality_code_c == "it" || $this->leadBean->dotb_iso_nationality_code_c == "lu" || $this->leadBean->dotb_iso_nationality_code_c == "be" || $this->leadBean->dotb_iso_nationality_code_c == "nl" || $this->leadBean->dotb_iso_nationality_code_c == "at" || $this->leadBean->dotb_iso_nationality_code_c == "ie" || $this->leadBean->dotb_iso_nationality_code_c == "dk" || $this->leadBean->dotb_iso_nationality_code_c == "gb" || $this->leadBean->dotb_iso_nationality_code_c == "es" || $this->leadBean->dotb_iso_nationality_code_c == "fi" || $this->leadBean->dotb_iso_nationality_code_c == "se" || $this->leadBean->dotb_iso_nationality_code_c == "no" || $this->leadBean->dotb_iso_nationality_code_c == "li") {
                    $innerArray[$this->fieldDefs["if_redt_ger_aus_den_sweden_c"]['vname']] = "NO";
                } else {
                    $innerArray[$this->fieldDefs["if_redt_ger_aus_den_sweden_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_redt_ger_aus_den_sweden_c"]['vname']] = "Missing";
        }


        if (in_array("NO", $innerArray) && in_array("YES", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL2"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL2"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL2"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL2"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL2"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL2"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL2"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL2"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL2"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL2"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL2"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL2"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL2"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL2"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {

            $overAllRiskFlag[] = "YES";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            
        }

        $innerArray = array();


        //Duration Calculation for comparison
        $date_obj = new TimeDate();
        $date_to = date("Y-m-d");

        $datefrom = $this->leadBean->dotb_work_permit_since_c;
        $from = $date_obj->to_db_date($this->leadBean->dotb_work_permit_since_c);

        $days = floor((abs(strtotime($date_to) - strtotime($from))) / 86400);
        $months = floor($days / 30);
        $years = floor($months / 12);

        //Residence Permit < 6 months
        if ($this->leadBean->dotb_iso_nationality_code_c != "ch") {
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                if ($this->riskFactor['if_b_permit_less_6_month_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {

                        if ($months < 6) {
                            $innerArray[$this->fieldDefs["if_b_permit_less_6_month_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_permit_less_6_month_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_permit_less_6_month_c'] == "Yellow") {

                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months < 6) {
                            $innerArray[$this->fieldDefs["if_b_permit_less_6_month_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_permit_less_6_month_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_permit_less_6_month_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months < 6) {
                            $innerArray[$this->fieldDefs["if_b_permit_less_6_month_c"]['vname']] = "NO";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_permit_less_6_month_c"]['vname']] = "YES";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_permit_less_6_month_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit between 6-12 months
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                if ($this->riskFactor['if_b_permit_btwn_6_12_month_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 6 && $months <= 12) {
                            $innerArray[$this->fieldDefs["if_b_permit_btwn_6_12_month_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_permit_btwn_6_12_month_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_permit_btwn_6_12_month_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 6 && $months <= 12) {
                            $innerArray[$this->fieldDefs["if_b_permit_btwn_6_12_month_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_permit_btwn_6_12_month_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_permit_btwn_6_12_month_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 6 && $months <= 12) {
                            $innerArray[$this->fieldDefs["if_b_permit_btwn_6_12_month_c"]['vname']] = "NO";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_permit_btwn_6_12_month_c"]['vname']] = "YES";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_permit_btwn_6_12_month_c"]['vname']] = "Missing";
                }
            }
            //Residence Permit between 6-12 months and monthly salary  < 4000
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                if ($this->riskFactor['if_b_btw_12_net_m_sal_l_4000_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 6 && $months <= 12 && $this->leadBean->dotb_monthly_net_income_c < 4000) {
                            $innerArray[$this->fieldDefs["if_b_btw_12_net_m_sal_l_4000_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_12_net_m_sal_l_4000_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_12_net_m_sal_l_4000_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 6 && $months <= 12 && $this->leadBean->dotb_monthly_net_income_c < 4000) {
                            $innerArray[$this->fieldDefs["if_b_btw_12_net_m_sal_l_4000_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_12_net_m_sal_l_4000_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_12_net_m_sal_l_4000_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months <= 6 || $months >= 12 && $this->leadBean->dotb_monthly_net_income_c > 4000) {
                            $innerArray[$this->fieldDefs["if_b_btw_12_net_m_sal_l_4000_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_12_net_m_sal_l_4000_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_btw_12_net_m_sal_l_4000_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit between 6-12 months and monthly salary between 4000 and 6000 and credit amount > 15000
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c)) {

                if ($this->riskFactor['if_b_6_12_sal_btw_4_6_amt_15_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 6 && $months <= 12 && $this->leadBean->dotb_monthly_net_income_c >= 4000 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->credit_amount_c > 15000) {
                            $innerArray[$this->fieldDefs["if_b_6_12_sal_btw_4_6_amt_15_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_6_12_sal_btw_4_6_amt_15_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_6_12_sal_btw_4_6_amt_15_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 6 && $months <= 12 && $this->leadBean->dotb_monthly_net_income_c >= 4000 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->credit_amount_c > 15000) {
                            $innerArray[$this->fieldDefs["if_b_6_12_sal_btw_4_6_amt_15_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_6_12_sal_btw_4_6_amt_15_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_6_12_sal_btw_4_6_amt_15_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months <= 6 || $months >= 12 && $this->leadBean->dotb_monthly_net_income_c <= 4000 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->credit_amount_c < 15000) {
                            $innerArray[$this->fieldDefs["if_b_6_12_sal_btw_4_6_amt_15_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_6_12_sal_btw_4_6_amt_15_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_6_12_sal_btw_4_6_amt_15_c"]['vname']] = "Missing";
                }
            }


            //Residence Permit between 6-12 months and monthly salary between 6000 and 8000 and credit amount > 20000
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c)) {

                if ($this->riskFactor['if_b_6_12_sal_btw_6_8_amt_20_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 6 && $months <= 12 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->dotb_monthly_net_income_c <= 8000 && $this->leadBean->credit_amount_c > 20000) {
                            $innerArray[$fieldDefs["if_b_6_12_sal_btw_6_8_amt_20_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_6_12_sal_btw_6_8_amt_20_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_6_12_sal_btw_6_8_amt_20_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 6 && $months <= 12 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->dotb_monthly_net_income_c <= 8000 && $this->leadBean->credit_amount_c > 20000) {
                            $innerArray[$this->fieldDefs["if_b_6_12_sal_btw_6_8_amt_20_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_6_12_sal_btw_6_8_amt_20_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_6_12_sal_btw_6_8_amt_20_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months <= 6 || $months >= 12 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->dotb_monthly_net_income_c >= 8000 && $this->leadBean->credit_amount_c < 20000) {
                            $innerArray[$this->fieldDefs["if_b_6_12_sal_btw_6_8_amt_20_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_6_12_sal_btw_6_8_amt_20_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_6_12_sal_btw_6_8_amt_20_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit between 6-12 months and monthly salary greater 8000 and credit amount > 30000
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c)) {

                if ($this->riskFactor['if_b_btw_6_12_m_sal_8_amt_30_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 6 && $months <= 12 && $this->leadBean->dotb_monthly_net_income_c > 8000 && $this->leadBean->credit_amount_c > 30000) {
                            $innerArray[$this->fieldDefs["if_b_btw_6_12_m_sal_8_amt_30_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_6_12_m_sal_8_amt_30_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_6_12_m_sal_8_amt_30_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 6 && $months <= 12 && $this->leadBean->dotb_monthly_net_income_c > 8000 && $this->leadBean->credit_amount_c > 30000) {
                            $innerArray[$this->fieldDefs["if_b_btw_6_12_m_sal_8_amt_30_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_6_12_m_sal_8_amt_30_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_6_12_m_sal_8_amt_30_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months <= 6 || $months >= 12 && $this->leadBean->dotb_monthly_net_income_c < 8000 && $this->leadBean->credit_amount_c < 30000) {
                            $innerArray[$this->fieldDefs["if_b_btw_6_12_m_sal_8_amt_30_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_6_12_m_sal_8_amt_30_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_btw_6_12_m_sal_8_amt_30_c"]['vname']] = "Missing";
                }
            }
            //Residence Permit between 12-24 months and monthly salary less 4000 and credit amount > 15000
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c)) {

                if ($this->riskFactor['if_b_btw_12_24__sal_4_amt_15_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 12 && $months <= 24 && $this->leadBean->dotb_monthly_net_income_c < 4000 && $this->leadBean->credit_amount_c > 15000) {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24__sal_4_amt_15_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24__sal_4_amt_15_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_12_24__sal_4_amt_15_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 12 && $months <= 24 && $this->leadBean->dotb_monthly_net_income_c < 4000 && $this->leadBean->credit_amount_c > 15000) {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24__sal_4_amt_15_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24__sal_4_amt_15_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_12_24__sal_4_amt_15_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months <= 12 || $months >= 24 && $this->leadBean->dotb_monthly_net_income_c > 4000 && $this->leadBean->credit_amount_c < 15000) {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24__sal_4_amt_15_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24__sal_4_amt_15_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_btw_12_24__sal_4_amt_15_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit between 12-24 months and monthly salary between 4000 and 6000 and credit amount > 25000
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c)) {

                if ($this->riskFactor['if_b_btw_12_24_sal_4_6_am_25_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 12 && $months <= 24 && $this->leadBean->dotb_monthly_net_income_c >= 4000 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->credit_amount_c > 25000) {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_4_6_am_25_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_4_6_am_25_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_12_24_sal_4_6_am_25_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 12 && $months <= 24 && $this->leadBean->dotb_monthly_net_income_c >= 4000 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->credit_amount_c > 25000) {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_4_6_am_25_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_4_6_am_25_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_12_24_sal_4_6_am_25_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months <= 12 || $months >= 24 && $this->leadBean->dotb_monthly_net_income_c <= 4000 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->credit_amount_c < 25000) {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_4_6_am_25_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_4_6_am_25_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_4_6_am_25_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit between 12-24 months and monthly salary between 6000 and 8000 and credit amount > 30000
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c)) {

                if ($this->riskFactor['if_b_btw_12_24_sal_6_8_am_30_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 12 && $months <= 24 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->dotb_monthly_net_income_c <= 8000 && $this->leadBean->credit_amount_c > 30000) {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_6_8_am_30_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_6_8_am_30_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_12_24_sal_6_8_am_30_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 12 && $months <= 24 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->dotb_monthly_net_income_c <= 8000 && $this->leadBean->credit_amount_c > 30000) {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_6_8_am_30_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_6_8_am_30_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_12_24_sal_6_8_am_30_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months <= 12 || $months >= 24 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->dotb_monthly_net_income_c >= 8000 && $this->leadBean->credit_amount_c < 30000) {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_6_8_am_30_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_6_8_am_30_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_6_8_am_30_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit between 12-24 months and monthly salary greater 8000 and credit amount > 40000
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c)) {

                if ($this->riskFactor['if_b_btw_12_24_sal_8_amnt_40_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 12 && $months <= 24 && $this->leadBean->dotb_monthly_net_income_c > 8000 && $this->leadBean->credit_amount_c > 40000) {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_8_amnt_40_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_8_amnt_40_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_12_24_sal_8_amnt_40_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 12 && $months <= 24 && $this->leadBean->dotb_monthly_net_income_c > 8000 && $this->leadBean->credit_amount_c > 40000) {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_8_amnt_40_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_8_amnt_40_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_12_24_sal_8_amnt_40_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months <= 12 || $months >= 24 && $this->leadBean->dotb_monthly_net_income_c < 8000 && $this->leadBean->credit_amount_c < 40000) {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_8_amnt_40_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_8_amnt_40_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_btw_12_24_sal_8_amnt_40_c"]['vname']] = "Missing";
                }
            }
            //Residence Permit between 24 - 36 months AND net monthly salary  less than 4000 AND credit-amount less than 25,000
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c)) {

                if ($this->riskFactor['if_b_btw_24_36_sal_4_amnt_25_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c < 4000 && $this->leadBean->credit_amount_c < 25000) {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_amnt_25_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_amnt_25_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_24_36_sal_4_amnt_25_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c < 4000 && $this->leadBean->credit_amount_c < 25000) {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_amnt_25_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_amnt_25_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_24_36_sal_4_amnt_25_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months <= 24 || $months >= 36 && $this->leadBean->dotb_monthly_net_income_c > 4000 && $this->leadBean->credit_amount_c > 25000) {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_amnt_25_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_amnt_25_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_amnt_25_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit  between 24 - 36 months AND net monthly salary less than 4000 AND credit-amount greater than  25,000
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c)) {

                if ($this->riskFactor['if_b_btw_24_36_sal_4_a_g_25_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c > 4000 && $this->leadBean->credit_amount_c > 25000) {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_a_g_25_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_a_g_25_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_24_36_sal_4_a_g_25_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c > 4000 && $this->leadBean->credit_amount_c > 25000) {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_a_g_25_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_a_g_25_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_24_36_sal_4_a_g_25_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months <= 24 || $months >= 36 && $this->leadBean->dotb_monthly_net_income_c < 4000 && $this->leadBean->credit_amount_c < 25000) {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_a_g_25_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_a_g_25_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_a_g_25_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit  between 24 - 36 months AND net monthly salary between 4,000 and 6,000 AND credit-amount greater than 35,000
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c)) {

                if ($this->riskFactor['if_b_btw_24_36_sal_4_6_am_35_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c >= 4000 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->credit_amount_c > 35000) {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_6_am_35_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_6_am_35_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_24_36_sal_4_6_am_35_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c >= 4000 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->credit_amount_c > 35000) {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_6_am_35_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_6_am_35_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_24_36_sal_4_6_am_35_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months <= 24 || $months >= 36 && $this->leadBean->dotb_monthly_net_income_c <= 4000 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->credit_amount_c < 35000) {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_6_am_35_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_6_am_35_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_4_6_am_35_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit  between 24 - 36 months AND net monthly salary between 6,000 and 8,000 AND credit-amount greater than  40,000
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c)) {

                if ($this->riskFactor['if_b_btw_24_36_sal_6_8_am_40_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                        if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->dotb_monthly_net_income_c <= 8000 && $this->leadBean->credit_amount_c > 40000) {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_6_8_am_40_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_6_8_am_40_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_24_36_sal_6_8_am_40_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->dotb_monthly_net_income_c <= 8000 && $this->leadBean->credit_amount_c > 40000) {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_6_8_am_40_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_6_8_am_40_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_24_36_sal_6_8_am_40_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months <= 24 || $months >= 36 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->dotb_monthly_net_income_c >= 8000 && $this->leadBean->credit_amount_c < 40000) {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_6_8_am_40_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_6_8_am_40_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_6_8_am_40_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit  between 24 - 36 months AND net monthly salary greater than  8,000 AND credit-amount greater than  50,000
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c)) {

                if ($this->riskFactor['if_b_btw_24_36_sal_8_amnt_50_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c > 8000 && $this->leadBean->credit_amount_c > 50000) {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_8_amnt_50_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_8_amnt_50_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_24_36_sal_8_amnt_50_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c > 8000 && $this->leadBean->credit_amount_c > 50000) {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_8_amnt_50_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_8_amnt_50_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_btw_24_36_sal_8_amnt_50_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months <= 24 || $months >= 36 && $this->leadBean->dotb_monthly_net_income_c < 8000 && $this->leadBean->credit_amount_c < 50000) {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_8_amnt_50_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_8_amnt_50_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_btw_24_36_sal_8_amnt_50_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit greater than  36 months AND net monthly salary  less than 4000 AND credit-amount greater than 35,000
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c)) {

                if ($this->riskFactor['if_b_36_sal_4_credit_amnt_35_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months > 36 && $this->leadBean->dotb_monthly_net_income_c < 4000 && $this->leadBean->credit_amount_c > 35000) {
                            $innerArray[$this->fieldDefs["if_b_36_sal_4_credit_amnt_35_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_36_sal_4_credit_amnt_35_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_36_sal_4_credit_amnt_35_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months > 36 && $this->leadBean->dotb_monthly_net_income_c < 4000 && $this->leadBean->credit_amount_c > 35000) {
                            $innerArray[$this->fieldDefs["if_b_36_sal_4_credit_amnt_35_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_36_sal_4_credit_amnt_35_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_36_sal_4_credit_amnt_35_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months < 36 && $this->leadBean->dotb_monthly_net_income_c > 4000 && $this->leadBean->credit_amount_c < 35000) {
                            $innerArray[$this->fieldDefs["if_b_36_sal_4_credit_amnt_35_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_36_sal_4_credit_amnt_35_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_36_sal_4_credit_amnt_35_c"]['vname']] = "Missing";
                }
            }


            //Residence Permit greater than  36 months AND net monthly salary between 4,000 and 6,000 AND credit-amount greater 45,000
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c)) {

                if ($this->riskFactor['if_b_36_sal_4_6_credit_am_45_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months > 36 && $this->leadBean->dotb_monthly_net_income_c >= 4000 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->credit_amount_c > 45000) {
                            $innerArray[$this->fieldDefs["if_b_36_sal_4_6_credit_am_45_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_36_sal_4_6_credit_am_45_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_36_sal_4_6_credit_am_45_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months > 36 && $this->leadBean->dotb_monthly_net_income_c >= 4000 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->credit_amount_c > 45000) {
                            $innerArray[$this->fieldDefs["if_b_36_sal_4_6_credit_am_45_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_36_sal_4_6_credit_am_45_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_36_sal_4_6_credit_am_45_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months < 36 && $this->leadBean->dotb_monthly_net_income_c <= 4000 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->credit_amount_c < 45000) {
                            $innerArray[$this->fieldDefs["if_b_36_sal_4_6_credit_am_45_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_36_sal_4_6_credit_am_45_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_36_sal_4_6_credit_am_45_c"]['vname']] = "Missing";
                }
            }


            //Residence Permit greater than  36  AND net monthly salary between 6,000 and 8,000 AND credit-amount greater than 50,000
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c)) {

                if ($this->riskFactor['if_b_36_sal_6_8_credit_am_50_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months > 36 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->dotb_monthly_net_income_c <= 8000 && $this->leadBean->credit_amount_c > 50000) {
                            $innerArray[$this->fieldDefs["if_b_36_sal_6_8_credit_am_50_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_36_sal_6_8_credit_am_50_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_36_sal_6_8_credit_am_50_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months > 36 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->dotb_monthly_net_income_c <= 8000 && $this->leadBean->credit_amount_c > 50000) {
                            $innerArray[$this->fieldDefs["if_b_36_sal_6_8_credit_am_50_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_36_sal_6_8_credit_am_50_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_36_sal_6_8_credit_am_50_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months < 36 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->dotb_monthly_net_income_c >= 8000 && $this->leadBean->credit_amount_c < 50000) {
                            $innerArray[$this->fieldDefs["if_b_36_sal_6_8_credit_am_50_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_36_sal_6_8_credit_am_50_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_36_sal_6_8_credit_am_50_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit greater than  36 months AND net monthly salary greater 8,000 AND credit-amount greater than 60,000
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c)) {

                if ($this->riskFactor['if_b_36_sal_8_credit_amnt_60_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months > 36 && $this->leadBean->dotb_monthly_net_income_c > 8000 && $this->leadBean->credit_amount_c > 60000) {
                            $innerArray[$this->fieldDefs["if_b_36_sal_8_credit_amnt_60_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_36_sal_8_credit_amnt_60_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_36_sal_8_credit_amnt_60_c'] == "Yellow") {


                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months > 36 && $this->leadBean->dotb_monthly_net_income_c > 8000 && $this->leadBean->credit_amount_c > 60000) {
                            $innerArray[$this->fieldDefs["if_b_36_sal_8_credit_amnt_60_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_36_sal_8_credit_amnt_60_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_36_sal_8_credit_amnt_60_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months < 36 && $this->leadBean->dotb_monthly_net_income_c < 8000 && $this->leadBean->credit_amount_c < 60000) {
                            $innerArray[$this->fieldDefs["if_b_36_sal_8_credit_amnt_60_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_36_sal_8_credit_amnt_60_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_36_sal_8_credit_amnt_60_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit  between 12-24 months
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                if ($this->riskFactor['if_b_permit_btw_12_24_months_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 12 && $months <= 24) {
                            $innerArray[$this->fieldDefs["if_b_permit_btw_12_24_months_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_permit_btw_12_24_months_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_permit_btw_12_24_months_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 12 && $months <= 24) {
                            $innerArray[$this->fieldDefs["if_b_permit_btw_12_24_months_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_permit_btw_12_24_months_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_permit_btw_12_24_months_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months <= 12 && $months >= 24) {
                            $innerArray[$this->fieldDefs["if_b_permit_btw_12_24_months_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_permit_btw_12_24_months_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_permit_btw_12_24_months_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit  between 24-36 months
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                if ($this->riskFactor['if_b_permit_btw_24_36_months_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 24 && $months <= 36) {
                            $innerArray[$this->fieldDefs["if_b_permit_btw_24_36_months_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_permit_btw_24_36_months_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_permit_btw_24_36_months_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months >= 24 && $months <= 36) {
                            $innerArray[$this->fieldDefs["if_b_permit_btw_24_36_months_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_permit_btw_24_36_months_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_permit_btw_24_36_months_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months <= 24 && $months >= 36) {
                            $innerArray[$this->fieldDefs["if_b_permit_btw_24_36_months_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_permit_btw_24_36_months_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_permit_btw_24_36_months_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit B-permit more than 12 months, "living alone and children" / single_parents
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_housing_situation_id_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                if ($this->riskFactor['if_b_12_alo_child_sin_parent_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months > 12 && $this->leadBean->dotb_housing_situation_id_c == "single_parent" || $this->leadBean->dotb_housing_situation_id_c == "alone") {
                            $innerArray[$this->fieldDefs["if_b_12_alo_child_sin_parent_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_12_alo_child_sin_parent_c"]['vname']] = "NO";
                        }
                    }
                } else if ($this->riskFactor['if_b_12_alo_child_sin_parent_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months > 12 && $this->leadBean->dotb_housing_situation_id_c == "single_parent" || $this->leadBean->dotb_housing_situation_id_c == "alone") {
                            $innerArray[$this->fieldDefs["if_b_12_alo_child_sin_parent_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_12_alo_child_sin_parent_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_b_12_alo_child_sin_parent_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                        if ($months < 12 && $this->leadBean->dotb_housing_situation_id_c != "single_parent" || $this->leadBean->dotb_housing_situation_id_c != "alone") {
                            $innerArray[$this->fieldDefs["if_b_12_alo_child_sin_parent_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_b_12_alo_child_sin_parent_c"]['vname']] = "NO";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_b_12_alo_child_sin_parent_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit  G-permit less than 3 years with employer
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                if ($this->riskFactor['if_g_less_3_years_employer_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "g_permit") {
                        if ($years < 3) {
                            $innerArray[$this->fieldDefs["if_g_less_3_years_employer_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_g_less_3_years_employer_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_g_less_3_years_employer_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "g_permit") {
                        if ($years < 3) {
                            $innerArray[$this->fieldDefs["if_g_less_3_years_employer_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_g_less_3_years_employer_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_g_less_3_years_employer_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "g_permit") {
                        if ($years < 3) {
                            $innerArray[$this->fieldDefs["if_g_less_3_years_employer_c"]['vname']] = "NO";
                        } else {
                            $innerArray[$this->fieldDefs["if_g_less_3_years_employer_c"]['vname']] = "YES";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "g_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_g_less_3_years_employer_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit  G-permit more than 3 years
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                if ($this->riskFactor['if_g_permit_more_3_years_c'] == "Green") {

                    if ($this->leadBean->dotb_work_permit_type_id_c == "g_permit") {
                        if ($years > 3) {
                            $innerArray[$this->fieldDefs["if_g_permit_more_3_years_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_g_permit_more_3_years_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_g_permit_more_3_years_c'] == "Yellow") {

                    if ($this->leadBean->dotb_work_permit_type_id_c == "g_permit") {
                        if ($years > 3) {
                            $innerArray[$this->fieldDefs["if_g_permit_more_3_years_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_g_permit_more_3_years_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_g_permit_more_3_years_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "g_permit") {
                        if ($years > 3) {
                            $innerArray[$this->fieldDefs["if_g_permit_more_3_years_c"]['vname']] = "NO";
                        } else {
                            $innerArray[$this->fieldDefs["if_g_permit_more_3_years_c"]['vname']] = "YES";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "g_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_g_permit_more_3_years_c"]['vname']] = "Missing";
                }
            }


            //Residence Permit if L-permit less than 1 year
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                if ($this->riskFactor['if_l_permit_less_1_year_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "l_permit") {
                        if ($months < 12) {
                            $innerArray[$this->fieldDefs["if_l_permit_less_1_year_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_l_permit_less_1_year_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_l_permit_less_1_year_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "l_permit") {
                        if ($months < 12) {
                            $innerArray[$this->fieldDefs["if_l_permit_less_1_year_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_l_permit_less_1_year_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_l_permit_less_1_year_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "l_permit") {
                        if ($months < 12) {
                            $innerArray[$this->fieldDefs["if_l_permit_less_1_year_c"]['vname']] = "NO";
                        } else {
                            $innerArray[$this->fieldDefs["if_l_permit_less_1_year_c"]['vname']] = "YES";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "l_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_l_permit_less_1_year_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit if L-permit more than 1 year
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                if ($this->riskFactor['if_l_permit_more_than_1_year_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "l_permit") {
                        if ($months > 12) {
                            $innerArray[$this->fieldDefs["if_l_permit_more_than_1_year_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_l_permit_more_than_1_year_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_l_permit_more_than_1_year_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "l_permit") {
                        if ($months > 12) {
                            $innerArray[$this->fieldDefs["if_l_permit_more_than_1_year_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_l_permit_more_than_1_year_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_l_permit_more_than_1_year_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "l_permit") {
                        if ($months > 12) {
                            $innerArray[$this->fieldDefs["if_l_permit_more_than_1_year_c"]['vname']] = "NO";
                        } else {
                            $innerArray[$this->fieldDefs["if_l_permit_more_than_1_year_c"]['vname']] = "YES";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "l_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_l_permit_more_than_1_year_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit if Diplomat less than 3 years
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                if ($this->riskFactor['if_diplomat_less_3_years_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "diplomate_permit") {
                        if ($months < 36) {
                            $innerArray[$this->fieldDefs["if_diplomat_less_3_years_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_diplomat_less_3_years_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_diplomat_less_3_years_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "diplomate_permit") {
                        if ($months < 36) {
                            $innerArray[$this->fieldDefs["if_diplomat_less_3_years_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_diplomat_less_3_years_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_diplomat_less_3_years_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "diplomate_permit") {
                        if ($months < 36) {
                            $innerArray[$this->fieldDefs["if_diplomat_less_3_years_c"]['vname']] = "NO";
                        } else {
                            $innerArray[$this->fieldDefs["if_diplomat_less_3_years_c"]['vname']] = "YES";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "diplomate_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_diplomat_less_3_years_c"]['vname']] = "Missing";
                }
            }

            //Residence Permit if Diplomat more than 3 years
            if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c) && $this->leadBean->dotb_work_permit_type_id_c == "diplomate_permit") {

                if ($this->riskFactor['if_diplomat_more_3_years_c'] == "Green") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "diplomate_permit") {
                        if ($months > 36) {
                            $innerArray[$this->fieldDefs["if_diplomat_more_3_years_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_diplomat_more_3_years_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_diplomat_more_3_years_c'] == "Yellow") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "diplomate_permit") {
                        if ($months > 36) {
                            $innerArray[$this->fieldDefs["if_diplomat_more_3_years_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_diplomat_more_3_years_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_diplomat_more_3_years_c'] == "Red") {
                    if ($this->leadBean->dotb_work_permit_type_id_c == "diplomate_permit") {
                        if ($months > 36) {
                            $innerArray[$this->fieldDefs["if_diplomat_more_3_years_c"]['vname']] = "NO";
                        } else {
                            $innerArray[$this->fieldDefs["if_diplomat_more_3_years_c"]['vname']] = "YES";
                        }
                    }
                }
            } else {
                if ($this->leadBean->dotb_work_permit_type_id_c == "diplomate_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                    $innerArray[$this->fieldDefs["if_diplomat_more_3_years_c"]['vname']] = "Missing";
                }
            }
        }



        if (in_array("NO", $innerArray) && in_array("YES", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL3"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL3"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL3"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL3"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL3"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL3"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL3"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL3"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL3"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL3"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL3"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL3"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL3"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL3"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {

            $overAllRiskFlag[] = "YES";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            
        }

        $innerArray = array();

        //Duration Calculation for comparison
        $date_obj2 = new TimeDate();
        $dateto = date("Y-m-d");

        $datefrom = $this->leadBean->dotb_employed_since_c;
        $from_emp = $date_obj2->to_db_date($this->leadBean->dotb_employed_since_c);

        $days2 = floor((abs(strtotime($dateto) - strtotime($from_emp))) / 86400);
        $months2 = floor($days2 / 30);
        $years2 = floor($months2 / 12);



        //Employment Type if "self" / self_employed and employed for less than 2 years
        if ($this->leadBean->dotb_employment_type_id_c == "self_employed" || empty($this->leadBean->dotb_employment_type_id_c)) {
            if (!empty($this->leadBean->dotb_employment_type_id_c) && !empty($this->leadBean->dotb_employed_since_c)) {

                if ($this->riskFactor['if_self_emp_less_2_years_c'] == "Green") {
                    if ($this->leadBean->dotb_employment_type_id_c == "self_employed") {
                        if ($months2 < 24) {
                            $innerArray[$this->fieldDefs["if_self_emp_less_2_years_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_self_emp_less_2_years_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_self_emp_less_2_years_c'] == "Yellow") {
                    if ($this->leadBean->dotb_employment_type_id_c == "self_employed") {
                        if ($months2 < 24) {
                            $innerArray[$this->fieldDefs["if_self_emp_less_2_years_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_self_emp_less_2_years_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_self_emp_less_2_years_c'] == "Red") {
                    if ($this->leadBean->dotb_employment_type_id_c == "self_employed") {
                        if ($months2 < 24) {
                            $innerArray[$this->fieldDefs["if_self_emp_less_2_years_c"]['vname']] = "NO";
                        } else {
                            $innerArray[$this->fieldDefs["if_self_emp_less_2_years_c"]['vname']] = "YES";
                        }
                    }
                }
            } else {
                $innerArray[$this->fieldDefs["if_self_emp_less_2_years_c"]['vname']] = "Missing";
            }

            //Employment Type if "self" more than 2 years
            if (!empty($this->leadBean->dotb_employment_type_id_c) && !empty($this->leadBean->dotb_employed_since_c)) {

                if ($this->riskFactor['if_self_more_2_years_c'] == "Green") {
                    if ($this->leadBean->dotb_employment_type_id_c == "self_employed") {
                        if ($months2 > 24) {
                            $innerArray[$this->fieldDefs["if_self_more_2_years_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_self_more_2_years_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_self_more_2_years_c'] == "Yellow") {
                    if ($this->leadBean->dotb_employment_type_id_c == "self_employed") {
                        if ($months2 > 24) {
                            $innerArray[$this->fieldDefs["if_self_more_2_years_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_self_more_2_years_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_self_more_2_years_c'] == "Red") {
                    if ($this->leadBean->dotb_employment_type_id_c == "self_employed") {
                        if ($months2 > 24) {
                            $innerArray[$this->fieldDefs["if_self_more_2_years_c"]['vname']] = "NO";
                        } else {
                            $innerArray[$this->fieldDefs["if_self_more_2_years_c"]['vname']] = "YES";
                        }
                    }
                }
            } else {
                $innerArray[$this->fieldDefs["if_self_more_2_years_c"]['vname']] = "Missing";
            }
        }

        //Duration Calculation for comparison
        $date_obj2 = new TimeDate();
        $dateto2 = $this->leadBean->dotb_employed_since_c;

        $datefrom2 = date("Y-m-d");

        $days4 = floor((abs(strtotime($dateto2) - strtotime($datefrom2))) / 86400);
        $months4 = floor($days4 / 30);
        $years4 = floor($months4 / 12);


        //Employment Type if "unemployed" / not-working
        if ($this->leadBean->dotb_employment_type_id_c == "not_working" || empty($this->leadBean->dotb_employment_type_id_c)) {
            if (!empty($this->leadBean->dotb_employment_type_id_c)) {

                if ($this->riskFactor['if_unemp_not_working_c'] == "Green") {
                    if ($this->leadBean->dotb_employment_type_id_c == "not_working") {
                        $innerArray[$this->fieldDefs["if_unemp_not_working_c"]['vname']] = "YES";
                    } else {
                        $innerArray[$this->fieldDefs["if_unemp_not_working_c"]['vname']] = "YES";
                    }
                } else if ($this->riskFactor['if_unemp_not_working_c'] == "Yellow") {
                    if ($this->leadBean->dotb_employment_type_id_c == "not_working") {
                        $innerArray[$this->fieldDefs["if_unemp_not_working_c"]['vname']] = "Caution";
                    } else {
                        $innerArray[$this->fieldDefs["if_unemp_not_working_c"]['vname']] = "YES";
                    }
                } else if ($this->riskFactor['if_unemp_not_working_c'] == "Red") {
                    if ($this->leadBean->dotb_employment_type_id_c == "not_working") {
                        $innerArray[$this->fieldDefs["if_unemp_not_working_c"]['vname']] = "NO";
                    } else {
                        $innerArray[$this->fieldDefs["if_unemp_not_working_c"]['vname']] = "YES";
                    }
                }
            } else {
                $innerArray[$this->fieldDefs["if_unemp_not_working_c"]['vname']] = "Missing";
            }
        }

        //Employment Type if "temporarily" / temporary_contract and employed for less than 6 months
        if ($this->leadBean->dotb_employment_type_id_c == "temporary_contract" || empty($this->leadBean->dotb_employment_type_id_c)) {
            if (!empty($this->leadBean->dotb_employment_type_id_c) && !empty($this->leadBean->dotb_employed_since_c)) {

                if ($this->riskFactor['if_temp_cont_6_months_c'] == "Green") {
                    if ($this->leadBean->dotb_employment_type_id_c == "temporary_contract") {
                        if ($months2 < 6) {
                            $innerArray[$this->fieldDefs["if_temp_cont_6_months_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_temp_cont_6_months_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_temp_cont_6_months_c'] == "Yellow") {
                    if ($this->leadBean->dotb_employment_type_id_c == "temporary_contract") {
                        if ($months2 < 6) {
                            $innerArray[$this->fieldDefs["if_temp_cont_6_months_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_temp_cont_6_months_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_temp_cont_6_months_c'] == "Red") {
                    if ($this->leadBean->dotb_employment_type_id_c == "temporary_contract") {
                        if ($months2 < 6) {
                            $innerArray[$this->fieldDefs["if_temp_cont_6_months_c"]['vname']] = "NO";
                        } else {
                            $innerArray[$this->fieldDefs["if_temp_cont_6_months_c"]['vname']] = "YES";
                        }
                    }
                }
            } else {
                $innerArray[$this->fieldDefs["if_temp_cont_6_months_c"]['vname']] = "Missing";
            }


            //Employment Type if "temporarily" / temporary_contract 6-12 months
            if (!empty($this->leadBean->dotb_employment_type_id_c) && !empty($this->leadBean->dotb_employed_since_c)) {

                if ($this->riskFactor['if_temp_cont_6_12_mon_c'] == "Green") {
                    if ($this->leadBean->dotb_employment_type_id_c == "temporary_contract") {
                        if ($months2 >= 6 && $months2 <= 12) {
                            $innerArray[$this->fieldDefs["if_temp_cont_6_12_mon_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_temp_cont_6_12_mon_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_temp_cont_6_12_mon_c'] == "Yellow") {
                    if ($this->leadBean->dotb_employment_type_id_c == "temporary_contract") {
                        if ($months2 >= 6 && $months2 <= 12) {
                            $innerArray[$this->fieldDefs["if_temp_cont_6_12_mon_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_temp_cont_6_12_mon_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_temp_cont_6_12_mon_c'] == "Red") {
                    if ($this->leadBean->dotb_employment_type_id_c == "temporary_contract") {
                        if ($months2 >= 6 && $months2 <= 12) {
                            $innerArray[$this->fieldDefs["if_temp_cont_6_12_mon_c"]['vname']] = "NO";
                        } else {
                            $innerArray[$this->fieldDefs["if_temp_cont_6_12_mon_c"]['vname']] = "YES";
                        }
                    }
                }
            } else {
                $innerArray[$this->fieldDefs["if_temp_cont_6_12_mon_c"]['vname']] = "Missing";
            }


            //Employment Type if "temporarily" / temporary_contract more than 12 months
            if (!empty($this->leadBean->dotb_employment_type_id_c) && !empty($this->leadBean->dotb_employed_since_c)) {

                if ($this->riskFactor['if_temp_cont_12_month_c'] == "Green") {

                    if ($this->leadBean->dotb_employment_type_id_c == "temporary_contract") {
                        if ($months2 > 12) {
                            $innerArray[$this->fieldDefs["if_temp_cont_12_month_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_temp_cont_12_month_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_temp_cont_12_month_c'] == "Yellow") {
                    if ($this->leadBean->dotb_employment_type_id_c == "temporary_contract") {
                        if ($months2 > 12) {
                            $innerArray[$this->fieldDefs["if_temp_cont_12_month_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_temp_cont_12_month_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_temp_cont_12_month_c'] == "Red") {
                    if ($this->leadBean->dotb_employment_type_id_c == "temporary_contract") {
                        if ($months2 > 12) {
                            $innerArray[$this->fieldDefs["if_temp_cont_12_month_c"]['vname']] = "NO";
                        } else {
                            $innerArray[$this->fieldDefs["if_temp_cont_12_month_c"]['vname']] = "YES";
                        }
                    }
                }
            } else {
                $innerArray[$this->fieldDefs["if_temp_cont_12_month_c"]['vname']] = "Missing";
            }
        }
        //Employment Type if "disability pension" / retirement
        if ($this->leadBean->dotb_employment_type_id_c == "disabled_gets_pension" || $this->leadBean->dotb_employment_type_id_c == "retirement" || empty($this->leadBean->dotb_employment_type_id_c)) {
            if (!empty($this->leadBean->dotb_employment_type_id_c)) {

                if ($this->riskFactor['if_disable_pension_ret_c'] == "Green") {
                    if ($this->leadBean->dotb_employment_type_id_c == "disabled_gets_pension" || $this->leadBean->dotb_employment_type_id_c == "retirement") {
                        $innerArray[$this->fieldDefs["if_disable_pension_ret_c"]['vname']] = "YES";
                    } else {
                        $innerArray[$this->fieldDefs["if_disable_pension_ret_c"]['vname']] = "YES";
                    }
                } else if ($this->riskFactor['if_disable_pension_ret_c'] == "Yellow") {
                    if ($this->leadBean->dotb_employment_type_id_c == "disabled_gets_pension" || $this->leadBean->dotb_employment_type_id_c == "retirement") {
                        $innerArray[$this->fieldDefs["if_disable_pension_ret_c"]['vname']] = "Caution";
                    } else {
                        $innerArray[$this->fieldDefs["if_disable_pension_ret_c"]['vname']] = "YES";
                    }
                } else if ($this->riskFactor['if_disable_pension_ret_c'] == "Red") {
                    if ($this->leadBean->dotb_employment_type_id_c == "disabled_gets_pension" || $this->leadBean->dotb_employment_type_id_c == "retirement") {
                        $innerArray[$this->fieldDefs["if_disable_pension_ret_c"]['vname']] = "NO";
                    } else {
                        $innerArray[$this->fieldDefs["if_disable_pension_ret_c"]['vname']] = "YES";
                    }
                }
            } else {
                $innerArray[$this->fieldDefs["if_disable_pension_ret_c"]['vname']] = "Missing";
            }
        }


        if (in_array("NO", $innerArray) && in_array("YES", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL4"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL4"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL4"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL4"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL4"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL4"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL4"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL4"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL4"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL4"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL4"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL4"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL4"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL4"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {

            $overAllRiskFlag[] = "YES";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            
        }

        $innerArray = array();

        if ($this->leadBean->dotb_employment_type_id_c == "not_working" || $this->leadBean->dotb_employment_type_id_c == "permanent_contract" || $this->leadBean->dotb_employment_type_id_c == "temporary_contract" || $this->leadBean->dotb_employment_type_id_c == "fixed_term_contract") {
            //Employment if less than 3 months
            if (!empty($this->leadBean->dotb_employment_type_id_c) && !empty($this->leadBean->dotb_employed_since_c)) {

                if ($this->riskFactor['if_less_than_3_months_c'] == "Green") {
                    if (!empty($this->leadBean->dotb_employment_type_id_c) && $this->leadBean->dotb_employment_type_id_c != "not_working") {
                        if ($months4 < 3) {
                            $innerArray[$this->fieldDefs["if_less_than_3_months_c"]['vname']] = "YES";
                        } else {
                            $innerArray[$this->fieldDefs["if_less_than_3_months_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_less_than_3_months_c'] == "Yellow") {
                    if (!empty($this->leadBean->dotb_employment_type_id_c) && $this->leadBean->dotb_employment_type_id_c != "not_working") {
                        if ($months4 < 3) {
                            $innerArray[$this->fieldDefs["if_less_than_3_months_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_less_than_3_months_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_less_than_3_months_c'] == "Red") {
                    if (!empty($this->leadBean->dotb_employment_type_id_c) && $this->leadBean->dotb_employment_type_id_c != "not_working") {
                        if ($months4 < 3) {
                            $innerArray[$this->fieldDefs["if_less_than_3_months_c"]['vname']] = "NO";
                        } else {
                            $innerArray[$this->fieldDefs["if_less_than_3_months_c"]['vname']] = "YES";
                        }
                    }
                }
            } else {
                $innerArray[$this->fieldDefs["if_less_than_3_months_c"]['vname']] = "Missing";
            }

            //Employment if less than 12 months
            if (!empty($this->leadBean->dotb_employment_type_id_c) && !empty($this->leadBean->dotb_employed_since_c)) {

                if ($this->riskFactor['if_less_than_12_month_c'] == "Green") {
                    if (!empty($this->leadBean->dotb_employment_type_id_c) && $this->leadBean->dotb_employment_type_id_c != "not_working") {

                        if ($months4 < 12) {
                            $newArray[$this->fieldDefs["if_less_than_12_month_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_less_than_12_month_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_less_than_12_month_c'] == "Yellow") {

                    if (!empty($this->leadBean->dotb_employment_type_id_c) && $this->leadBean->dotb_employment_type_id_c != "not_working") {
                        if ($months4 < 12) {
                            $innerArray[$this->fieldDefs["if_less_than_12_month_c"]['vname']] = "Caution";
                        } else {
                            $innerArray[$this->fieldDefs["if_less_than_12_month_c"]['vname']] = "YES";
                        }
                    }
                } else if ($this->riskFactor['if_less_than_12_month_c'] == "Red") {
                    if (!empty($this->leadBean->dotb_employment_type_id_c) && $this->leadBean->dotb_employment_type_id_c != "not_working") {
                        if ($months4 < 12) {
                            $innerArray[$this->fieldDefs["if_less_than_12_month_c"]['vname']] = "NO";
                        } else {
                            $innerArray[$this->fieldDefs["if_less_than_12_month_c"]['vname']] = "YES";
                        }
                    }
                }
            } else {
                $innerArray[$this->fieldDefs["if_less_than_12_month_c"]['vname']] = "Missing";
            }
        }


        if (in_array("NO", $innerArray) && in_array("YES", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL5"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL5"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL5"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL5"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL5"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL5"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL5"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL5"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL5"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL5"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL5"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL5"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL5"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL5"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {

            $overAllRiskFlag[] = "YES";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            
        }

        $innerArray = array();


        //Residence  if "with parents" - by_parents 
        if (!empty($this->leadBean->dotb_housing_situation_id_c)) {

            if ($this->riskFactor['if_with_par_by_parent_c'] == "Green") {
                if ($this->leadBean->dotb_housing_situation_id_c == "by_parents") {
                    $innerArray[$this->fieldDefs["if_with_par_by_parent_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_with_par_by_parent_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_with_par_by_parent_c'] == "Yellow") {
                if ($this->leadBean->dotb_housing_situation_id_c == "by_parents") {
                    $innerArray[$this->fieldDefs["if_with_par_by_parent_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_with_par_by_parent_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_with_par_by_parent_c'] == "Red") {
                if ($this->leadBean->dotb_housing_situation_id_c == "by_parents") {
                    $innerArray[$this->fieldDefs["if_with_par_by_parent_c"]['vname']] = "NO";
                } else {
                    $innerArray[$this->fieldDefs["if_with_par_by_parent_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_with_par_by_parent_c"]['vname']] = "Missing";
        }
        //Residence if "Urbanization" - flat_share
        if (!empty($this->leadBean->dotb_housing_situation_id_c)) {

            if ($this->riskFactor['if_urbanize_flat_share_c'] == "Green") {
                if ($this->leadBean->dotb_housing_situation_id_c == "flat_share") {
                    $innerArray[$this->fieldDefs["if_urbanize_flat_share_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_urbanize_flat_share_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_urbanize_flat_share_c'] == "Yellow") {
                if ($this->leadBean->dotb_housing_situation_id_c == "flat_share") {
                    $innerArray[$this->fieldDefs["if_urbanize_flat_share_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_urbanize_flat_share_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_urbanize_flat_share_c'] == "Red") {
                if ($this->leadBean->dotb_housing_situation_id_c == "flat_share") {
                    $innerArray[$this->fieldDefs["if_urbanize_flat_share_c"]['vname']] = "NO";
                } else {
                    $innerArray[$this->fieldDefs["if_urbanize_flat_share_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_urbanize_flat_share_c"]['vname']] = "Missing";
        }


        if (in_array("NO", $innerArray) && in_array("YES", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL6"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL6"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL6"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL6"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL6"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL6"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL6"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL6"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL6"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL6"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL6"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL6"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL6"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL6"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {

            $overAllRiskFlag[] = "YES";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            
        }

        $innerArray = array();

        //Duration Calculation for comparison
        $from_res = "";
        $date_obj3 = new TimeDate();
        $date_to = date("Y-m-d");
        $to_res = $date_obj3->to_db_date($date_to);
        $date_from = $this->leadBean->dotb_resident_since_c;
        $from_res = $date_obj3->to_db_date($this->leadBean->dotb_resident_since_c);


        $days3 = floor((abs(strtotime($date_to) - strtotime($from_res))) / 86400);
        $months3 = floor($days3 / 30);
        $years3 = floor($months3 / 12);


        //Residence if less than 12 months
        if (!empty($this->leadBean->dotb_resident_since_c)) {

            if ($this->riskFactor['if_less_than_12_months_c'] == "Green") {
                if ($months3 < 12) {
                    $innerArray[$this->fieldDefs["if_less_than_12_months_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_less_than_12_months_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_less_than_12_months_c'] == "Yellow") {
                if ($months3 < 12) {
                    $innerArray[$this->fieldDefs["if_less_than_12_months_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_less_than_12_months_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_less_than_12_months_c'] == "Red") {
                if ($months3 < 12) {
                    $innerArray[$this->fieldDefs["if_less_than_12_months_c"]['vname']] = "NO";
                } else {
                    $innerArray[$this->fieldDefs["if_less_than_12_months_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_less_than_12_months_c"]['vname']] = "Missing";
        }

        //Residence if less than 24 months
        if (!empty($this->leadBean->dotb_resident_since_c)) {

            if ($this->riskFactor['if_less_than_24_months_c'] == "Green") {
                if ($months3 < 24) {
                    $innerArray[$this->fieldDefs["if_less_than_24_months_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_less_than_24_months_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_less_than_24_months_c'] == "Yellow") {
                if ($months3 < 24) {
                    $innerArray[$this->fieldDefs["if_less_than_24_months_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_less_than_24_months_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_less_than_24_months_c'] == "Red") {
                if ($months3 < 24) {
                    $innerArray[$this->fieldDefs["if_less_than_24_months_c"]['vname']] = "NO";
                } else {
                    $innerArray[$this->fieldDefs["if_less_than_24_months_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_less_than_24_months_c"]['vname']] = "Missing";
        }



        if (in_array("NO", $innerArray) && in_array("YES", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL7"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL7"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL7"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL7"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL7"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL7"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL7"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL7"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL7"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL7"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL7"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL7"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL7"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL7"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {

            $overAllRiskFlag[] = "YES";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            
        }


        $innerArray = array();


        //Home Owner if "no"

        if ($this->riskFactor['if_no_c'] == "Green") {
            if ($this->leadBean->dotb_is_home_owner_c == "no") {
                $innerArray[$this->fieldDefs["if_no_c"]['vname']] = "YES";
            } else {
                $innerArray[$this->fieldDefs["if_no_c"]['vname']] = "YES";
            }
        } else if ($this->riskFactor['if_no_c'] == "Yellow") {
            if ($this->leadBean->dotb_is_home_owner_c == "no") {
                $innerArray[$this->fieldDefs["if_no_c"]['vname']] = "Caution";
            } else {
                $innerArray[$this->fieldDefs["if_no_c"]['vname']] = "YES";
            }
        } else if ($this->riskFactor['if_no_c'] == "Red") {
            if ($this->leadBean->dotb_is_home_owner_c == "no") {
                $innerArray[$this->fieldDefs["if_no_c"]['vname']] = "NO";
            } else {
                $innerArray[$this->fieldDefs["if_no_c"]['vname']] = "YES";
            }
        }




        //Home Owner if "yes" and less than 2 years
        if ($this->riskFactor['if_yes_and_less_than_2_years_c'] == "Green") {
            if ($this->leadBean->dotb_is_home_owner_c == "yes") {
                if ($months3 < 24) {
                    $innerArray[$this->fieldDefs["if_yes_and_less_than_2_years_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_yes_and_less_than_2_years_c"]['vname']] = "YES";
                }
            }
        } else if ($this->riskFactor['if_yes_and_less_than_2_years_c'] == "Yellow") {
            if ($this->leadBean->dotb_is_home_owner_c == "yes") {
                if ($months3 < 24) {
                    $innerArray[$this->fieldDefs["if_yes_and_less_than_2_years_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_yes_and_less_than_2_years_c"]['vname']] = "YES";
                }
            }
        } else if ($this->riskFactor['if_yes_and_less_than_2_years_c'] == "Red") {
            if ($this->leadBean->dotb_is_home_owner_c == "yes") {
                if ($months3 < 24) {
                    $innerArray[$this->fieldDefs["if_yes_and_less_than_2_years_c"]['vname']] = "NO";
                } else {
                    $innerArray[$this->fieldDefs["if_yes_and_less_than_2_years_c"]['vname']] = "YES";
                }
            }
        }




        //Home Owner if "yes" and longer than 2 years at current adress
        if ($this->riskFactor['if_yes_long_2_current_adress_c'] == "Green") {
            if ($this->leadBean->dotb_is_home_owner_c == "yes") {
                if ($months3 > 24) {
                    $innerArray[$this->fieldDefs["if_yes_long_2_current_adress_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_yes_long_2_current_adress_c"]['vname']] = "YES";
                }
            }
        } else if ($this->riskFactor['if_yes_long_2_current_adress_c'] == "Yellow") {
            if ($this->leadBean->dotb_is_home_owner_c == "yes") {
                if ($months3 > 24) {
                    $innerArray[$this->fieldDefs["if_yes_long_2_current_adress_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_yes_long_2_current_adress_c"]['vname']] = "YES";
                }
            }
        } else if ($this->riskFactor['if_yes_long_2_current_adress_c'] == "Red") {
            if ($this->leadBean->dotb_is_home_owner_c == "yes") {
                if ($months3 > 24) {
                    $innerArray[$this->fieldDefs["if_yes_long_2_current_adress_c"]['vname']] = "NO";
                } else {
                    $innerArray[$this->fieldDefs["if_yes_long_2_current_adress_c"]['vname']] = "YES";
                }
            }
        }


        if (in_array("NO", $innerArray) && in_array("YES", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL8"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL8"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL8"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL8"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL8"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL8"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL8"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL8"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL8"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL8"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL8"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL8"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL8"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL8"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {

            $overAllRiskFlag[] = "YES";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            
        }



        $innerArray = array();

        //Health Premium Reduction

        if ($this->riskFactor['if_yes_c'] == "Green") {
            if (!empty($this->leadBean->dot_health_insurance_premium_c) && $this->leadBean->dot_health_insurance_premium_c != 0.00) {
                $innerArray[$this->fieldDefs["if_yes_c"]['vname']] = "YES";
            } else {
                $innerArray[$this->fieldDefs["if_yes_c"]['vname']] = "YES";
            }
        } else if ($this->riskFactor['if_yes_c'] == "Yellow") {
            if (empty($this->leadBean->dot_health_insurance_premium_c) || $this->leadBean->dot_health_insurance_premium_c == 0.00) {
                $innerArray[$this->fieldDefs["if_yes_c"]['vname']] = "YES";
            } else {
                $innerArray[$this->fieldDefs["if_yes_c"]['vname']] = "Caution";
            }
        } else if ($this->riskFactor['if_yes_c'] == "Red") {
            if (empty($this->leadBean->dot_health_insurance_premium_c) || $this->leadBean->dot_health_insurance_premium_c == 0.00) {
                $innerArray[$this->fieldDefs["if_yes_c"]['vname']] = "YES";
            } else {
                $innerArray[$this->fieldDefs["if_yes_c"]['vname']] = "NO";
            }
        }




        if (in_array("NO", $innerArray) && in_array("YES", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL9"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL9"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL9"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL9"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL9"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL9"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL9"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL9"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL9"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL9"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL9"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL9"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL9"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL9"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {

            $overAllRiskFlag[] = "YES";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            
        }

        $innerArray = array();

        //Alimony if customer Receives alimony       
        if (!empty($this->leadBean->dotb_aliments_c)) {

            if ($this->riskFactor['if_customer_receives_alimony_c'] == "Green") {
                if (!empty($this->leadBean->dotb_aliments_c) && $this->leadBean->dotb_aliments_c != 0.00) {
                    $innerArray[$this->fieldDefs["if_customer_receives_alimony_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_customer_receives_alimony_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_customer_receives_alimony_c'] == "Yellow") {
                if (!empty($this->leadBean->dotb_aliments_c) && $this->leadBean->dotb_aliments_c != 0.00) {
                    $innerArray[$this->fieldDefs["if_customer_receives_alimony_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_customer_receives_alimony_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_customer_receives_alimony_c'] == "Red") {
                if (!empty($this->leadBean->dotb_aliments_c) && $this->leadBean->dotb_aliments_c != 0.00) {
                    $innerArray[$this->fieldDefs["if_customer_receives_alimony_c"]['vname']] = "NO";
                } else {
                    $innerArray[$this->fieldDefs["if_customer_receives_alimony_c"]['vname']] = "YES";
                }
            }
        }

        //Alimony if customer has to pay alimony
        if ($this->riskFactor['if_customer_has_pay_alimony_c'] == "Green") {
            if ($this->leadBean->dotb_has_alimony_payments_c == "yes") {
                $innerArray[$this->fieldDefs["if_customer_has_pay_alimony_c"]['vname']] = "YES";
            } else {
                $innerArray[$this->fieldDefs["if_customer_has_pay_alimony_c"]['vname']] = "YES";
            }
        } else if ($this->riskFactor['if_customer_has_pay_alimony_c'] == "Yellow") {
            if ($this->leadBean->dotb_has_alimony_payments_c == "yes") {
                $innerArray[$this->fieldDefs["if_customer_has_pay_alimony_c"]['vname']] = "Caution";
            } else {
                $innerArray[$this->fieldDefs["if_customer_has_pay_alimony_c"]['vname']] = "YES";
            }
        } else if ($this->riskFactor['if_customer_has_pay_alimony_c'] == "Red") {
            if ($this->leadBean->dotb_has_alimony_payments_c == "yes") {
                $innerArray[$this->fieldDefs["if_customer_has_pay_alimony_c"]['vname']] = "NO";
            } else {
                $innerArray[$this->fieldDefs["if_customer_has_pay_alimony_c"]['vname']] = "YES";
            }
        }


        if (in_array("NO", $innerArray) && in_array("YES", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL10"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL10"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL10"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL10"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL10"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL10"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL10"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL10"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL10"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL10"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL10"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL10"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL10"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL10"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {

            $overAllRiskFlag[] = "YES";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            
        }
        $innerArray = array();

        //Civil Status if "divorced" or "judicially separated" / divorced or separated
        if (!empty($this->leadBean->dotb_civil_status_id_c)) {

            if ($this->riskFactor['if_div_judicial_sep_div_sep_c'] == "Green") {
                if ($this->leadBean->dotb_civil_status_id_c == "divorced" || $this->leadBean->dotb_civil_status_id_c == "separated") {
                    $innerArray[$this->fieldDefs["if_div_judicial_sep_div_sep_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_div_judicial_sep_div_sep_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_div_judicial_sep_div_sep_c'] == "Yellow") {
                if ($this->leadBean->dotb_civil_status_id_c == "divorced" || $this->leadBean->dotb_civil_status_id_c == "separated") {
                    $innerArray[$this->fieldDefs["if_div_judicial_sep_div_sep_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_div_judicial_sep_div_sep_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_div_judicial_sep_div_sep_c'] == "Red") {
                if ($this->leadBean->dotb_civil_status_id_c == "divorced" || $this->leadBean->dotb_civil_status_id_c == "separated") {
                    $innerArray[$this->fieldDefs["if_div_judicial_sep_div_sep_c"]['vname']] = "NO";
                } else {
                    $innerArray[$this->fieldDefs["if_div_judicial_sep_div_sep_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_div_judicial_sep_div_sep_c"]['vname']] = "Missing";
        }


        //Civil Status if "married" / married
        if (!empty($this->leadBean->dotb_civil_status_id_c)) {

            if ($this->riskFactor['if_married_c'] == "Green") {
                if ($this->leadBean->dotb_civil_status_id_c == "married") {
                    $innerArray[$this->fieldDefs["if_married_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_married_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_married_c'] == "Yellow") {
                if ($this->leadBean->dotb_civil_status_id_c == "married") {
                    $innerArray[$this->fieldDefs["if_married_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_married_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_married_c'] == "Red") {
                if ($this->leadBean->dotb_civil_status_id_c == "married") {
                    $innerArray[$this->fieldDefs["if_married_c"]['vname']] = "NO";
                } else {
                    $innerArray[$this->fieldDefs["if_married_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_married_c"]['vname']] = "Missing";
        }


        if (in_array("NO", $innerArray) && in_array("YES", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL11"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL11"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL11"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL11"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL11"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL11"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL11"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL11"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL11"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL11"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL11"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL11"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL11"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL11"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {

            $overAllRiskFlag[] = "YES";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            
        }

        $innerArray = array();

        //Deltavista code no code and can not be found
        /* if($this->riskFactor['no_code_and_cannot_be_found_c'] == "Green"){
          if($this->leadBean->deltavista_request_id_c == "No Code and cannot be found" ){
          $innerArray[$this->fieldDefs["no_code_and_cannot_be_found_c"]['vname']] = "YES";
          }
          else{
          $innerArray[$this->fieldDefs["no_code_and_cannot_be_found_c"]['vname']] = "YES";
          }
          }
          else if($this->riskFactor['no_code_and_cannot_be_found_c'] == "Yellow"){
          if($this->leadBean->deltavista_request_id_c == "No Code and cannot be found" ){
          $innerArray[$this->fieldDefs["no_code_and_cannot_be_found_c"]['vname']] = "Caution";
          }
          else{
          $innerArray[$this->fieldDefs["no_code_and_cannot_be_found_c"]['vname']] = "YES";
          }
          }
          else if($this->riskFactor['no_code_and_cannot_be_found_c'] == "Red"){
          if($this->leadBean->deltavista_request_id_c == "No Code and cannot be found" ){
          $innerArray[$this->fieldDefs["no_code_and_cannot_be_found_c"]['vname']] = "NO";
          }
          else{
          $innerArray[$this->fieldDefs["no_code_and_cannot_be_found_c"]['vname']] = "YES";
          }

          }


          //Deltavista Code code 1
          if( $this->riskFactor['code_1_c'] == "Green"){
          if($this->leadBean->deltavista_request_id_c == "Code 1"){
          $innerArray[$this->fieldDefs["code_1_c"]['vname']] = "YES";
          }
          else{
          $innerArray[$this->fieldDefs["code_1_c"]['vname']] = "YES";
          }
          }
          else if( $this->riskFactor['code_1_c'] == "Yellow"){
          if($this->leadBean->deltavista_request_id_c == "Code 1"){
          $innerArray[$this->fieldDefs["code_1_c"]['vname']] = "Caution";
          }
          else{
          $innerArray[$this->fieldDefs["code_1_c"]['vname']] = "YES";
          }
          }
          else if( $this->riskFactor['code_1_c'] == "Red"){
          if($this->leadBean->deltavista_request_id_c == "Code 1"){
          $innerArray[$this->fieldDefs["code_1_c"]['vname']] = "NO";
          }
          else{
          $innerArray[$this->fieldDefs["code_1_c"]['vname']] = "YES";
          }
          }




          //Deltavista Code code 2
          if($this->riskFactor['code_2_c'] == "Green"){
          if($this->leadBean->deltavista_request_id_c == "Code 2"){
          $innerArray[$this->fieldDefs["code_2_c"]['vname']] = "YES";
          }
          else{
          $innerArray[$this->fieldDefs["code_2_c"]['vname']] = "YES";
          }
          }
          else if($this->riskFactor['code_2_c'] == "Yellow"){
          if($this->leadBean->deltavista_request_id_c == "Code 2"){
          $innerArray[$this->fieldDefs["code_2_c"]['vname']] = "Caution";
          }
          else{
          $innerArray[$this->fieldDefs["code_2_c"]['vname']] = "YES";
          }
          }
          else if($this->riskFactor['code_2_c'] == "Red"){
          if($this->leadBean->deltavista_request_id_c == "Code 2"){
          $innerArray[$this->fieldDefs["code_2_c"]['vname']] = "NO";
          }
          else{
          $innerArray[$this->fieldDefs["code_2_c"]['vname']] = "YES";
          }
          }



          //Deltavista Code code 3
          if($this->riskFactor['code_3_c'] == "Green"){
          if($this->leadBean->deltavista_request_id_c == "Code 3"){
          $innerArray[$this->fieldDefs["code_3_c"]['vname']] = "YES";
          }
          else{
          $innerArray[$this->fieldDefs["code_3_c"]['vname']] = "YES";
          }
          }
          else if($this->riskFactor['code_3_c'] == "Yellow"){
          if($this->leadBean->deltavista_request_id_c == "Code 3"){
          $innerArray[$this->fieldDefs["code_3_c"]['vname']] = "Caution";
          }
          else{
          $innerArray[$this->fieldDefs["code_3_c"]['vname']] = "YES";
          }
          }
          else if($this->riskFactor['code_3_c'] == "Red"){
          if($this->leadBean->deltavista_request_id_c == "Code 3"){
          $innerArray[$this->fieldDefs["code_3_c"]['vname']] = "NO";
          }
          else{
          $innerArray[$this->fieldDefs["code_3_c"]['vname']] = "YES";
          }
          }



          //Deltavista Code code 4
          if($this->riskFactor['code_4_c'] == "Green"){
          if($this->leadBean->deltavista_request_id_c == "Code 4"){
          $innerArray[$this->fieldDefs["code_4_c"]['vname']] = "YES";
          }
          else{
          $innerArray[$this->fieldDefs["code_4_c"]['vname']] = "YES";
          }
          }
          else if($this->riskFactor['code_4_c'] == "Green"){
          if($this->leadBean->deltavista_request_id_c == "Code 4"){
          $innerArray[$this->fieldDefs["code_4_c"]['vname']] = "Caution";
          }
          else{
          $innerArray[$this->fieldDefs["code_4_c"]['vname']] = "YES";
          }
          }
          else if($this->riskFactor['code_4_c'] == "Green"){
          if($this->leadBean->deltavista_request_id_c == "Code 4"){
          $innerArray[$this->fieldDefs["code_4_c"]['vname']] = "NO";
          }
          else{
          $innerArray[$this->fieldDefs["code_4_c"]['vname']] = "YES";
          }

          } */

        //Deltavista Code if DV-score <440
        if (!empty($this->leadBean->deltavista_score_c)) {
            if ($this->riskFactor['if_dv_score_440_c'] == "Green") {
                if ($this->leadBean->deltavista_score_c < 440) {
                    $innerArray[$this->fieldDefs["if_dv_score_440_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_dv_score_440_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_dv_score_440_c'] == "Yellow") {
                if ($this->leadBean->deltavista_score_c < 440) {
                    $innerArray[$this->fieldDefs["if_dv_score_440_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_dv_score_440_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_dv_score_440_c'] == "Green") {
                if ($this->leadBean->deltavista_score_c < 440) {
                    $innerArray[$this->fieldDefs["if_dv_score_440_c"]['vname']] = "NO";
                } else {
                    $innerArray[$this->fieldDefs["if_dv_score_440_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_dv_score_440_c"]['vname']] = "Missing";
        }



        if (in_array("NO", $innerArray) && in_array("YES", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL12"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL12"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL12"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL12"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL12"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL12"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL12"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL12"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL12"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL12"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL12"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL12"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL12"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL12"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {

            $overAllRiskFlag[] = "YES";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            
        }

        $innerArray = array();

        $flagincome1 = '';
        if ($this->leadBean->dotb_employment_type_id_c == "permanent_contract") {
            //Income total income less than 2500
            if (!empty($this->leadBean->dotb_monthly_gross_income_c)) {

                if ($this->riskFactor['total_income_less_than_2500_c'] == "Green") {
                    if ($this->leadBean->dotb_monthly_gross_income_c < 2500) {
                        $innerArray[$this->fieldDefs["total_income_less_than_2500_c"]['vname']] = "YES";
                    } else {
                        $innerArray[$this->fieldDefs["total_income_less_than_2500_c"]['vname']] = "YES";
                    }
                } else if ($this->riskFactor['total_income_less_than_2500_c'] == "Yellow") {
                    if ($this->leadBean->dotb_monthly_gross_income_c < 2500) {
                        $innerArray[$this->fieldDefs["total_income_less_than_2500_c"]['vname']] = "Caution";
                    } else {
                        $innerArray[$this->fieldDefs["total_income_less_than_2500_c"]['vname']] = "YES";
                    }
                } else if ($this->riskFactor['total_income_less_than_2500_c'] == "Red") {
                    if ($this->leadBean->dotb_monthly_gross_income_c < 2500) {
                        $innerArray[$this->fieldDefs["total_income_less_than_2500_c"]['vname']] = "NO";
                        $flagincome1 = true;
                    } else {
                        $innerArray[$this->fieldDefs["total_income_less_than_2500_c"]['vname']] = "YES";
                    }
                }
            } else {
                $innerArray[$this->fieldDefs["total_income_less_than_2500_c"]['vname']] = "Missing";
            }


            $flagincome2 = '';
            //Income total income less than 3000
            if (!empty($this->leadBean->dotb_monthly_gross_income_c)) {

                if ($this->riskFactor['total_income_less_than_3000_c'] == "Green") {
                    if ($this->leadBean->dotb_monthly_gross_income_c < 3000) {
                        $innerArray[$this->fieldDefs["total_income_less_than_3000_c"]['vname']] = "YES";
                    } else {
                        $innerArray[$this->fieldDefs["total_income_less_than_3000_c"]['vname']] = "YES";
                    }
                } else if ($this->riskFactor['total_income_less_than_3000_c'] == "Yellow") {
                    if ($this->leadBean->dotb_monthly_gross_income_c < 3000) {
                        $innerArray[$this->fieldDefs["total_income_less_than_3000_c"]['vname']] = "Caution";
                    } else {
                        $innerArray[$this->fieldDefs["total_income_less_than_3000_c"]['vname']] = "YES";
                    }
                } else if ($this->riskFactor['total_income_less_than_3000_c'] == "Red") {
                    if ($this->leadBean->dotb_monthly_gross_income_c < 3000) {
                        if ($flagincome2 != true) {
                            $innerArray[$this->fieldDefs["total_income_less_than_3000_c"]['vname']] = "NO";
                            $flagincome2 = true;
                        }
                    } else {
                        $innerArray[$this->fieldDefs["total_income_less_than_3000_c"]['vname']] = "YES";
                    }
                }
            } else {
                $innerArray[$this->fieldDefs["total_income_less_than_3000_c"]['vname']] = "Missing";
            }

            $flagincome3 = '';
            //Income total income less than 4000
            if (!empty($this->leadBean->dotb_monthly_gross_income_c)) {

                if ($this->riskFactor['total_income_less_than_4000_c'] == "Green") {
                    if ($this->leadBean->dotb_monthly_gross_income_c < 4000) {
                        $innerArray[$this->fieldDefs["total_income_less_than_4000_c"]['vname']] = "YES";
                    } else {
                        $innerArray[$this->fieldDefs["total_income_less_than_4000_c"]['vname']] = "YES";
                    }
                } else if ($this->riskFactor['total_income_less_than_4000_c'] == "Yellow") {
                    if ($this->leadBean->dotb_monthly_gross_income_c < 4000) {
                        $innerArray[$this->fieldDefs["total_income_less_than_4000_c"]['vname']] = "Caution";
                    } else {
                        $innerArray[$this->fieldDefs["total_income_less_than_4000_c"]['vname']] = "YES";
                    }
                } else if ($this->riskFactor['total_income_less_than_4000_c'] == "Red") {
                    if ($this->leadBean->dotb_monthly_gross_income_c < 4000) {
                        if ($flagincome3 != true) {
                            $innerArray[$this->fieldDefs["total_income_less_than_4000_c"]['vname']] = "NO";
                            $flagincome3 = true;
                        }
                    } else {
                        $innerArray[$this->fieldDefs["total_income_less_than_4000_c"]['vname']] = "YES";
                    }
                }
            } else {
                $innerArray[$this->fieldDefs["total_income_less_than_4000_c"]['vname']] = "Missing";
            }
        }



        if (in_array("NO", $innerArray) && in_array("YES", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL13"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL13"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL13"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL13"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL13"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL13"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL13"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL13"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL13"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL13"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL13"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL13"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL13"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL13"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {

            $overAllRiskFlag[] = "YES";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            
        }


        $innerArray = array();

        //PPI if "credit amount"> 80,000
        if (!empty($this->leadBean->credit_amount_c)) {

            if ($this->riskFactor['if_credit_amount_80000_c'] == "Green") {
                if ($this->leadBean->credit_amount_c > 80000) {
                    $innerArray[$this->fieldDefs["if_credit_amount_80000_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_credit_amount_80000_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_credit_amount_80000_c'] == "Yellow") {
                if ($this->leadBean->credit_amount_c > 80000) {
                    $innerArray[$this->fieldDefs["if_credit_amount_80000_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_credit_amount_80000_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_credit_amount_80000_c'] == "Red") {
                if ($this->leadBean->credit_amount_c > 80000) {
                    $innerArray[$this->fieldDefs["if_credit_amount_80000_c"]['vname']] = "NO";
                } else {
                    $innerArray[$this->fieldDefs["if_credit_amount_80000_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_credit_amount_80000_c"]['vname']] = "Missing";
        }


        //PPI if "credit duration"> = 60 months
        if (!empty($this->leadBean->credit_duration_c)) {
            if ($this->riskFactor['if_credit_duration_60_months_c'] == "Green") {
                if ($this->leadBean->credit_duration_c >= 60) {
                    $innerArray[$this->fieldDefs["if_credit_duration_60_months_c"]['vname']] = "YES";
                } else {
                    $innerArray[$this->fieldDefs["if_credit_duration_60_months_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_credit_duration_60_months_c'] == "Yellow") {
                if ($this->leadBean->credit_duration_c >= 60) {
                    $innerArray[$this->fieldDefs["if_credit_duration_60_months_c"]['vname']] = "Caution";
                } else {
                    $innerArray[$this->fieldDefs["if_credit_duration_60_months_c"]['vname']] = "YES";
                }
            } else if ($this->riskFactor['if_credit_duration_60_months_c'] == "Red") {
                if ($this->leadBean->credit_duration_c >= 60) {
                    $innerArray[$this->fieldDefs["if_credit_duration_60_months_c"]['vname']] = "NO";
                } else {
                    $innerArray[$this->fieldDefs["if_credit_duration_60_months_c"]['vname']] = "YES";
                }
            }
        } else {
            $innerArray[$this->fieldDefs["if_credit_duration_60_months_c"]['vname']] = "Missing";
        }



        if (in_array("NO", $innerArray) && in_array("YES", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL14"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL14"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL14"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL14"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL14"] = $innerArray;
            $overAllRiskFlag[] = "Caution";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL14"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL14"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL14"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL14"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL14"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL14"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (!in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL14"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && in_array("NO", $innerArray) && in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL14"] = $innerArray;
            $overAllRiskFlag[] = "NO";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && in_array("Missing", $innerArray)) {
            $riskFactorFlag["LBL_RECORDVIEW_PANEL14"] = $innerArray;
            $overAllRiskFlag[] = "Missing";
        } else if (in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {

            $overAllRiskFlag[] = "YES";
        } else if (!in_array("YES", $innerArray) && !in_array("NO", $innerArray) && !in_array("Caution", $innerArray) && !in_array("Missing", $innerArray)) {
            
        }


        $innerArray = array();

        /** Overall Risk Flag * */
        $temp_val = array_values(array_unique($overAllRiskFlag));


        // YES 
        if (in_array("YES", $overAllRiskFlag) && !in_array("Caution", $overAllRiskFlag) && !in_array("NO", $overAllRiskFlag) && !in_array("Missing", $overAllRiskFlag)) {
            $riskFactorFlag['flag'] = "Red";
        }
        // NO 
        if (!in_array("YES", $overAllRiskFlag) && !in_array("Caution", $overAllRiskFlag) && in_array("NO", $overAllRiskFlag) && !in_array("Missing", $overAllRiskFlag)) {
            $riskFactorFlag['flag'] = "Red";
        }
        // Caution 
        if (!in_array("YES", $overAllRiskFlag) && in_array("Caution", $overAllRiskFlag) && !in_array("NO", $overAllRiskFlag) && !in_array("Missing", $overAllRiskFlag)) {
            $riskFactorFlag['flag'] = "Red";
        }
        // Missing
        if (!in_array("YES", $overAllRiskFlag) && !in_array("Caution", $overAllRiskFlag) && !in_array("NO", $overAllRiskFlag) && in_array("Missing", $overAllRiskFlag)) {
            $riskFactorFlag['flag'] = "Red";
        }
        // YES + NO
        if (in_array("YES", $overAllRiskFlag) && !in_array("Caution", $overAllRiskFlag) && in_array("NO", $overAllRiskFlag) && !in_array("Missing", $overAllRiskFlag)) {
            $riskFactorFlag['flag'] = "Red";
        }
        //YES + Caution
        if (in_array("YES", $overAllRiskFlag) && in_array("Caution", $overAllRiskFlag) && !in_array("NO", $overAllRiskFlag) && !in_array("Missing", $overAllRiskFlag)) {
            $riskFactorFlag['flag'] = "Yellow";
        }
        //YES + Missing
        if (in_array("YES", $overAllRiskFlag) && !in_array("Caution", $overAllRiskFlag) && !in_array("NO", $overAllRiskFlag) && in_array("Missing", $overAllRiskFlag)) {
            $riskFactorFlag['flag'] = "Grey";
        }
        // Caution + NO
        if (!in_array("YES", $overAllRiskFlag) && in_array("Caution", $overAllRiskFlag) && in_array("NO", $overAllRiskFlag) && !in_array("Missing", $overAllRiskFlag)) {
            $riskFactorFlag['flag'] = "Red";
        }
        //  NO + Missing(Red)
        if (!in_array("YES", $overAllRiskFlag) && !in_array("Caution", $overAllRiskFlag) && in_array("NO", $overAllRiskFlag) && in_array("Missing", $overAllRiskFlag)) {
            $riskFactorFlag['flag'] = "Red";
        }
        // Caution + Missing
        if (!in_array("YES", $overAllRiskFlag) && in_array("Caution", $overAllRiskFlag) && !in_array("NO", $overAllRiskFlag) && in_array("Missing", $overAllRiskFlag)) {
            $riskFactorFlag['flag'] = "Grey";
        }
        //YES + Caution + Missing
        if (in_array("YES", $overAllRiskFlag) && in_array("Caution", $overAllRiskFlag) && !in_array("NO", $overAllRiskFlag) && in_array("Missing", $overAllRiskFlag)) {
            $riskFactorFlag['flag'] = "Grey";
        }
        // YES + NO + Missing(Red)
        if (in_array("YES", $overAllRiskFlag) && !in_array("Caution", $overAllRiskFlag) && in_array("NO", $overAllRiskFlag) && !in_array("Missing", $overAllRiskFlag)) {
            $riskFactorFlag['flag'] = "Red";
        }
        // YES + NO + Caution
        if (in_array("YES", $overAllRiskFlag) && in_array("Caution", $overAllRiskFlag) && in_array("NO", $overAllRiskFlag) && !in_array("Missing", $overAllRiskFlag)) {
            $riskFactorFlag['flag'] = "Red";
        }
        //YES + Caution + Missing
        if (in_array("YES", $overAllRiskFlag) && in_array("Caution", $overAllRiskFlag) && !in_array("NO", $overAllRiskFlag) && in_array("Missing", $overAllRiskFlag)) {
            $riskFactorFlag['flag'] = "Grey";
        }
        // Caution + NO + Missing(Red)-
        if (!in_array("YES", $overAllRiskFlag) && in_array("Caution", $overAllRiskFlag) && in_array("NO", $overAllRiskFlag) && in_array("Missing", $overAllRiskFlag)) {
            $riskFactorFlag['flag'] = "Red";
        }
        //YES + Caution + NO + Missing(Red)-
        if (in_array("YES", $overAllRiskFlag) && in_array("Caution", $overAllRiskFlag) && in_array("NO", $overAllRiskFlag) && in_array("Missing", $overAllRiskFlag)) {
            $riskFactorFlag['flag'] = "Red";
        }
        //None
        if (!in_array("YES", $overAllRiskFlag) && !in_array("Caution", $overAllRiskFlag) && !in_array("NO", $overAllRiskFlag) && !in_array("Missing", $overAllRiskFlag)) {
            
        }
        if (in_array("NO", $overAllRiskFlag)) {
            $riskFactorFlag['flag'] = "Red";
        }

        return $riskFactorFlag;
    }

    public function getRiskProfileData(ServiceBase $api, array $args) {
        $leadId = $args['id'];
        $this->leadBean = BeanFactory::getBean("Leads", $leadId);
        $riskBean = BeanFactory::getBean("dotb9_risk_profiling");
        $this->fieldDefs = $riskBean->getFieldDefinitions();
        $this->leadDefs = $this->leadBean->getFieldDefinitions();


        $risk_profile = array();
        $risk_profile["overallrisk"] = "";

        $seed = BeanFactory::newBean('Accounts');
        $q = new SugarQuery();

        $q->from($seed);
        $q->select('accounts.id');
        $q->select('accounts.name');

        $sql = "JOIN accounts_cstm"
                . " ON accounts.id = accounts_cstm.id_c ";

        $q->orderByRaw('accounts_cstm.bank_order_c', 'ASC');

        $recordz = $q->execute();


        $res = $this->overallRiskCalculation();

        $riskFactorData = $this->riskFactorCalculation();
        $risk_profile["Risk Factor"] = $riskFactorData;

        //Risk Factor Calculation for Each Bank
        foreach ($recordz as $item) {

            $outerArray = array();
            $records = array();
            $flagArray = array();

            $records[] = $item['name'];

            $account = BeanFactory::getBean("Accounts", $item['id']);
            $risk_profiling = $account->get_linked_beans('accounts_dotb9_risk_profiling_1', 'dotb9_risk_profiling');

            foreach ($risk_profiling as $rp) {

                $days = "";
                $months = "";
                $years = "";

                $newArray = array();

                //Credit Amount

                $outerArray["flag"] = "";

                if (!empty($this->leadBean->credit_amount_c)) {

                    if ($rp->more_than_80000_c == "yes") {
                        if ($this->leadBean->credit_amount_c > 80000) {
                            $newArray[$this->fieldDefs["more_than_80000_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["more_than_80000_c"]['vname']] = "YES";
                        }
                    } else if ($rp->more_than_80000_c == "no") {
                        if ($this->leadBean->credit_amount_c > 80000) {
                            $newArray[$this->fieldDefs["more_than_80000_c"]['vname']] = "NO";
                        } else {
                            $newArray[$this->fieldDefs["more_than_80000_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->more_than_80000_c != "not_relevant") {
                        $newArray[$this->fieldDefs["more_than_80000_c"]['vname']] = "Missing";
                    }
                }

                //Primary Address Country Postcode if Liechtenstein and not Swiss or citizen FL
                if (!empty($this->leadBean->dotb_iso_nationality_code_c)) {
                    if (!empty($this->leadBean->primary_address_country) && $this->leadBean->primary_address_country == "Liechtenstein") {

                        if ($rp->postcode_if_liechtenstein_c == "yes") {
                            if ($this->leadBean->primary_address_country == "Liechtenstein" && $this->leadBean->dotb_iso_nationality_code_c != "ch") {
                                $newArray[$this->fieldDefs["postcode_if_liechtenstein_c"]['vname']] = "YES";
                            } else {
                                $newArray[$this->fieldDefs["postcode_if_liechtenstein_c"]['vname']] = "NO";
                            }
                        } else if ($rp->postcode_if_liechtenstein_c == "no") {
                            if ($this->leadBean->primary_address_country == "Liechtenstein" && $this->leadBean->dotb_iso_nationality_code_c != "ch") {
                                $newArray[$this->fieldDefs["postcode_if_liechtenstein_c"]['vname']] = "NO";
                            } else {
                                $newArray[$this->fieldDefs["postcode_if_liechtenstein_c"]['vname']] = "YES";
                            }
                        }
                    }
                } else {
                    if ($rp->postcode_if_liechtenstein_c != "not_relevant") {
                        $newArray[$this->fieldDefs["postcode_if_liechtenstein_c"]['vname']] = "Missing";
                    }
                }

                if (!empty($this->leadBean->dotb_iso_nationality_code_c)) {
                    if (!empty($this->leadBean->primary_address_country) && $this->leadBean->primary_address_country == "Liechtenstein") {

                        if ($rp->zip_liechtenstein_and_swiss_c == "yes") {
                            if ($this->leadBean->primary_address_country == "Liechtenstein" && $this->leadBean->dotb_iso_nationality_code_c == "ch") {
                                $newArray[$this->fieldDefs["zip_liechtenstein_and_swiss_c"]['vname']] = "YES";
                            } else {
                                $newArray[$this->fieldDefs["zip_liechtenstein_and_swiss_c"]['vname']] = "NO";
                            }
                        } else if ($rp->zip_liechtenstein_and_swiss_c == "no") {
                            if ($this->leadBean->primary_address_country == "Liechtenstein" && $this->leadBean->dotb_iso_nationality_code_c == "ch") {
                                $newArray[$this->fieldDefs["zip_liechtenstein_and_swiss_c"]['vname']] = "NO";
                            } else {
                                $newArray[$this->fieldDefs["zip_liechtenstein_and_swiss_c"]['vname']] = "YES";
                            }
                        }
                    }
                } else {
                    if ($rp->zip_liechtenstein_and_swiss_c != "not_relevant") {
                        $newArray[$this->fieldDefs["zip_liechtenstein_and_swiss_c"]['vname']] = "Missing";
                    }
                }

                //Credit Usage Type
                if (!empty($this->leadBean->credit_usage_type_id_c)) {

                    if ($rp->pay_bills_taxes_inv_real_est_c == "yes") {
                        if ($this->leadBean->credit_usage_type_id_c == "taxes" || $this->leadBean->credit_usage_type_id_c == "invoices" || $this->leadBean->credit_usage_type_id_c == "investment" || $this->leadBean->credit_usage_type_id_c == "real_estate_abroad") {
                            $newArray[$this->fieldDefs["pay_bills_taxes_inv_real_est_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["pay_bills_taxes_inv_real_est_c"]['vname']] = "YES";
                        }
                    } else if ($rp->pay_bills_taxes_inv_real_est_c == "no") {
                        if ($this->leadBean->credit_usage_type_id_c == "taxes" || $this->leadBean->credit_usage_type_id_c == "invoices" || $this->leadBean->credit_usage_type_id_c == "investment" || $this->leadBean->credit_usage_type_id_c == "real_estate_abroad") {
                            $newArray[$this->fieldDefs["pay_bills_taxes_inv_real_est_c"]['vname']] = "NO";
                        } else {
                            $newArray[$this->fieldDefs["pay_bills_taxes_inv_real_est_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->pay_bills_taxes_inv_real_est_c != "not_relevant") {
                        $newArray[$this->fieldDefs["pay_bills_taxes_inv_real_est_c"]['vname']] = "Missing";
                    }
                }


                //Has Enforcements
                if (!empty($this->leadBean->dotb_has_enforcements_c)) {

                    if ($rp->currently_open_enforcements_c == "yes") {

                        if ($this->leadBean->dotb_has_enforcements_c != "no") {
                            $newArray[$this->fieldDefs["currently_open_enforcements_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["currently_open_enforcements_c"]['vname']] = "YES";
                        }
                    } else if ($rp->currently_open_enforcements_c == "no") {

                        if ($this->leadBean->dotb_has_enforcements_c != "no") {
                            $newArray[$this->fieldDefs["currently_open_enforcements_c"]['vname']] = "NO";
                        } else {
                            $newArray[$this->fieldDefs["currently_open_enforcements_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->currently_open_enforcements_c != "not_relevant") {
                        $newArray[$this->fieldDefs["currently_open_enforcements_c"]['vname']] = "Missing";
                    }
                }


                //Past Enforcements
                if (!empty($this->leadBean->dotb_past_enforcements_c)) {

                    if ($rp->if_enforcements_in_the_past_c == "yes") {
                        if ($this->leadBean->dotb_past_enforcements_c == "yes") {
                            $newArray[$this->fieldDefs["if_enforcements_in_the_past_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_enforcements_in_the_past_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_enforcements_in_the_past_c == "no") {
                        if ($this->leadBean->dotb_past_enforcements_c == "no") {
                            $newArray[$this->fieldDefs["if_enforcements_in_the_past_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_enforcements_in_the_past_c"]['vname']] = "NO";
                        }
                    }
                } else {
                    if ($rp->if_enforcements_in_the_past_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_enforcements_in_the_past_c"]['vname']] = "Missing";
                    }
                }

                if (in_array("NO", $newArray) && in_array("YES", $newArray)) {
                    $outerArray["PANEL_BODY"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("NO", $newArray) && !in_array("YES", $newArray)) {
                    $outerArray["PANEL_BODY"] = $newArray;
                    $flagArray[] = "NO";
                }
                if (in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["PANEL_BODY"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["PANEL_BODY"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (!in_array("YES", $newArray) && !in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["PANEL_BODY"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["PANEL_BODY"] = $newArray;
                    $flagArray[] = "Missing";
                } else {

                    $flagArray[] = "YES";
                }

                $newArray = array();

                $flag1 = '';
                //Age less than 18
                if (!empty($this->leadBean->birthdate)) {

                    if ($rp->if_younger_than_18_c == "yes") {
                        if ($this->leadBean->dotb_age_c < 18) {
                            $newArray[$this->fieldDefs["if_younger_than_18_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_younger_than_18_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_younger_than_18_c == "no") {
                        if ($this->leadBean->dotb_age_c < 18) {
                            $newArray[$this->fieldDefs["if_younger_than_18_c"]['vname']] = "NO";
                            $flag1 = true;
                        } else {
                            $newArray[$this->fieldDefs["if_younger_than_18_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_younger_than_18_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_younger_than_18_c"]['vname']] = "Missing";
                    }
                }

                $flag2 = '';
                //Age less than 21
                if (!empty($this->leadBean->birthdate)) {

                    if ($rp->if_younger_than_21_c == "yes") {
                        if ($this->leadBean->dotb_age_c < 21) {
                            $newArray[$this->fieldDefs["if_younger_than_21_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_younger_than_21_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_younger_than_21_c == "no") {
                        if ($this->leadBean->dotb_age_c < 21) {
                            if ($flag1 == true) {
                                $newArray[$this->fieldDefs["if_younger_than_21_c"]['vname']] = "NO";
                                $flag2 = true;
                            }
                        } else {
                            $newArray[$this->fieldDefs["if_younger_than_21_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_younger_than_21_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_younger_than_21_c"]['vname']] = "Missing";
                    }
                }

                //Age less than 21 and credit amount > 15000
                $newFlag5 = '';
                if (!empty($this->leadBean->birthdate) && !empty($this->leadBean->credit_amount_c)) {

                    if ($rp->if_young_21_credit_amount_15_c == "yes") {
                        if ($this->leadBean->dotb_age_c < 21) {

                            if ($this->leadBean->credit_amount_c > 15000) {
                                $newArray[$this->fieldDefs["if_young_21_credit_amount_15_c"]['vname']] = "YES";
                            } else {
                                if ($flag1 == true) {
                                    $newArray[$this->fieldDefs["if_young_21_credit_amount_15_c"]['vname']] = "NO";
                                    $newFlag5 = true;
                                }
                            }
                        }
                    } else if ($rp->if_young_21_credit_amount_15_c == "no") {
                        if ($this->leadBean->dotb_age_c < 21) {
                            if ($this->leadBean->dotb_age_c < 21 && $this->leadBean->credit_amount_c > 15000) {
                                if ($flag1 == true) {
                                    $newArray[$this->fieldDefs["if_young_21_credit_amount_15_c"]['vname']] = "NO";
                                    $newFlag5 = true;
                                }
                            } else {

                                $newArray[$this->fieldDefs["if_young_21_credit_amount_15_c"]['vname']] = "YES";
                            }
                        }
                    }
                } else {
                    if ($rp->if_young_21_credit_amount_15_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_young_21_credit_amount_15_c"]['vname']] = "Missing";
                    }
                }

                $flag3 = '';
                //Age less than 25
                if (!empty($this->leadBean->birthdate)) {

                    if ($rp->if_younger_than_25_c == "yes") {
                        if ($this->leadBean->dotb_age_c < 25) {
                            $newArray[$this->fieldDefs["if_younger_than_25_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_younger_than_25_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_younger_than_25_c == "no") {
                        if ($this->leadBean->dotb_age_c < 25) {
                            if ($flag2 == true) {
                                $newArray[$this->fieldDefs["if_younger_than_25_c"]['vname']] = "NO";
                                $flag3 = true;
                            }
                        } else {
                            $newArray[$this->fieldDefs["if_younger_than_25_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_younger_than_25_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_younger_than_25_c"]['vname']] = "Missing";
                    }
                }

                //Age less than 25 and credit amount > 25000
                $newFlag6 = '';
                if (!empty($this->leadBean->birthdate) && !empty($this->leadBean->credit_amount_c)) {

                    if ($rp->if_young_25_credit_amount_25_c == "yes") {
                        if ($this->leadBean->dotb_age_c < 25) {
                            if ($this->leadBean->credit_amount_c > 25000) {
                                $newArray[$this->fieldDefs["if_young_25_credit_amount_25_c"]['vname']] = "YES";
                            } else {
                                if ($this->newFlag3 == true) {
                                    $newArray[$this->fieldDefs["if_young_25_credit_amount_25_c"]['vname']] = "NO";
                                    $newFlag6 = true;
                                }
                            }
                        }
                    } else if ($rp->if_young_25_credit_amount_25_c == "no") {
                        if ($this->leadBean->dotb_age_c < 25) {

                            if ($this->leadBean->dotb_age_c < 25 && $this->leadBean->credit_amount_c > 25000) {
                                if ($this->newFlag3 == true) {
                                    $newArray[$this->fieldDefs["if_young_25_credit_amount_25_c"]['vname']] = "NO";
                                    $newFlag6 = true;
                                }
                            } else {
                                $newArray[$this->fieldDefs["if_young_25_credit_amount_25_c"]['vname']] = "YES";
                            }
                        }
                    }
                } else {
                    if ($rp->if_young_25_credit_amount_25_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_young_25_credit_amount_25_c"]['vname']] = "YES";
                    }
                }

                //Age greater than 59
                if (!empty($this->leadBean->birthdate)) {

                    if ($rp->if_older_than_59_c == "yes") {
                        if ($this->leadBean->dotb_age_c > 59) {
                            $newArray[$this->fieldDefs["if_older_than_59_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_older_than_59_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_older_than_59_c == "no") {
                        if ($this->leadBean->dotb_age_c > 59) {
                            $newArray[$this->fieldDefs["if_older_than_59_c"]['vname']] = "NO";
                            $flagzero = true;
                        } else {
                            $newArray[$this->fieldDefs["if_older_than_59_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_older_than_59_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_older_than_59_c"]['vname']] = "Missing";
                    }
                }


                //Age greater than 59 and credit amount > 50000
                $newFlag7 = '';
                if (!empty($this->leadBean->birthdate) && !empty($this->leadBean->credit_amount_c)) {

                    if ($rp->if_older_59_credit_amount_50_c == "yes") {
                        if ($this->leadBean->dotb_age_c > 59) {
                            if ($this->leadBean->credit_amount_c > 50000) {
                                $newArray[$this->fieldDefs["if_older_59_credit_amount_50_c"]['vname']] = "YES";
                            } else {
                                if ($flagzero == true) {
                                    $newArray[$this->fieldDefs["if_older_59_credit_amount_50_c"]['vname']] = "NO";
                                    $newFlag7 = true;
                                }
                            }
                        }
                    } else if ($rp->if_older_59_credit_amount_50_c == "no") {
                        if ($this->leadBean->dotb_age_c > 59) {
                            if ($this->leadBean->dotb_age_c > 59 && $this->leadBean->credit_amount_c > 50000) {
                                if ($newFlag6 == true) {
                                    $newArray[$this->fieldDefs["if_older_59_credit_amount_50_c"]['vname']] = "NO";
                                    $newFlag7 = true;
                                }
                            } else {
                                $newArray[$this->fieldDefs["if_older_59_credit_amount_50_c"]['vname']] = "YES";
                            }
                        }
                    }
                } else {
                    if ($rp->if_older_59_credit_amount_50_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_older_59_credit_amount_50_c"]['vname']] = "Missing";
                    }
                }

                $flag4 = '';
                //Age less than 64
                if (!empty($this->leadBean->birthdate)) {

                    if ($rp->if_younger_than_64_c == "yes") {
                        if ($this->leadBean->dotb_age_c < 64) {
                            $newArray[$this->fieldDefs["if_younger_than_64_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_younger_than_64_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_younger_than_64_c == "no") {
                        if ($this->leadBean->dotb_age_c < 64) {
                            if ($flag3 == true) {
                                $newArray[$this->fieldDefs["if_younger_than_64_c"]['vname']] = "NO";
                                $flag4 = true;
                            }
                        } else {
                            $newArray[$this->fieldDefs["if_younger_than_64_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_younger_than_64_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_younger_than_64_c"]['vname']] = "Missing";
                    }
                }


                //Age less than 64 and credit amount > 50000
                $tempFlag = '';
                if (!empty($this->leadBean->birthdate) && !empty($this->leadBean->credit_amount_c)) {

                    if ($rp->if_younger_64_credit_50000_c == "yes") {
                        if ($this->leadBean->dotb_age_c < 64) {
                            if ($this->leadBean->credit_amount_c > 50000) {
                                $newArray[$this->fieldDefs["if_younger_64_credit_50000_c"]['vname']] = "YES";
                            } else {
                                if ($flag4 == true) {
                                    $newArray[$this->fieldDefs["if_younger_64_credit_50000_c"]['vname']] = "NO";
                                    $tempFlag = true;
                                }
                            }
                        }
                    } else if ($rp->if_younger_64_credit_50000_c == "no") {
                        if ($this->leadBean->dotb_age_c < 64) {
                            if ($this->leadBean->credit_amount_c > 50000) {
                                if ($flag4 == true) {
                                    $newArray[$this->fieldDefs["if_younger_64_credit_50000_c"]['vname']] = "NO";
                                    $tempFlag = true;
                                }
                            } else {
                                $newArray[$this->fieldDefs["if_younger_64_credit_50000_c"]['vname']] = "YES";
                            }
                        }
                    }
                } else {
                    if ($rp->if_younger_64_credit_50000_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_younger_64_credit_50000_c"]['vname']] = "Missing";
                    }
                }

                $flag6 = '';
                //Age greater than 65
                if (!empty($this->leadBean->birthdate)) {

                    if ($rp->if_older_than_65_c == "yes") {
                        if ($this->leadBean->dotb_age_c > 65) {
                            $newArray[$this->fieldDefs["if_older_than_65_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_older_than_65_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_older_than_65_c == "no") {
                        if ($this->leadBean->dotb_age_c > 65) {
                            $newArray[$this->fieldDefs["if_older_than_65_c"]['vname']] = "NO";
                            $flag6 = true;
                        } else {
                            $newArray[$this->fieldDefs["if_older_than_65_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_older_than_65_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_older_than_65_c"]['vname']] = "Missing";
                    }
                }

                $flag7 = '';
                //Age greater than 70
                if (!empty($this->leadBean->birthdate)) {

                    if ($rp->if_older_than_70_c == "yes") {
                        if ($this->leadBean->dotb_age_c > 70) {
                            $newArray[$this->fieldDefs["if_older_than_70_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_older_than_70_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_older_than_70_c == "no") {
                        if ($this->leadBean->dotb_age_c > 70) {
                            if ($flag6 == true) {
                                $newArray[$this->fieldDefs["if_older_than_70_c"]['vname']] = "NO";
                            } else {
                                $flag7 = true;
                            }
                        } else {
                            $newArray[$this->fieldDefs["if_older_than_70_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_older_than_70_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_older_than_70_c"]['vname']] = "Missing";
                    }
                }


                if (in_array("NO", $newArray) && in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL1"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("NO", $newArray) && !in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL1"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL1"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL1"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (!in_array("YES", $newArray) && !in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL1"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL1"] = $newArray;
                    $flagArray[] = "Missing";
                } else {

                    $flagArray[] = "YES";
                }


                $newArray = array();


                //Nationality
                if (!empty($this->leadBean->dotb_iso_nationality_code_c)) {

                    if ($rp->if_iran_red_syr_mianmar_sud_c == "yes") {
                        if ($this->leadBean->dotb_iso_nationality_code_c == "ir" || $this->leadBean->dotb_iso_nationality_code_c == "kp" || $this->leadBean->dotb_iso_nationality_code_c == "sy" || $this->leadBean->dotb_iso_nationality_code_c == "sd" || $this->leadBean->dotb_iso_nationality_code_c == "mm") {
                            $newArray[$this->fieldDefs["if_iran_red_syr_mianmar_sud_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_iran_red_syr_mianmar_sud_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_iran_red_syr_mianmar_sud_c == "no") {
                        if ($this->leadBean->dotb_iso_nationality_code_c == "ir" || $this->leadBean->dotb_iso_nationality_code_c == "kp" || $this->leadBean->dotb_iso_nationality_code_c == "sy" || $this->leadBean->dotb_iso_nationality_code_c == "sd" || $this->leadBean->dotb_iso_nationality_code_c == "mm") {
                            $newArray[$this->fieldDefs["if_iran_red_syr_mianmar_sud_c"]['vname']] = "NO";
                        } else {
                            $newArray[$this->fieldDefs["if_iran_red_syr_mianmar_sud_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_iran_red_syr_mianmar_sud_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_iran_red_syr_mianmar_sud_c"]['vname']] = "Missing";
                    }
                }

                //Nationality
                if (!empty($this->leadBean->dotb_iso_nationality_code_c)) {

                    if ($rp->if_iraq_zim_con_leb_yem_usa_c == "yes") {
                        if ($this->leadBean->dotb_iso_nationality_code_c == "iq" || $this->leadBean->dotb_iso_nationality_code_c == "zw" || $this->leadBean->dotb_iso_nationality_code_c == "cd" || $this->leadBean->dotb_iso_nationality_code_c == "by" || $this->leadBean->dotb_iso_nationality_code_c == "lb" || $this->leadBean->dotb_iso_nationality_code_c == "so" || $this->leadBean->dotb_iso_nationality_code_c == "gn" || $this->leadBean->dotb_iso_nationality_code_c == "er" || $this->leadBean->dotb_iso_nationality_code_c == "ly" || $this->leadBean->dotb_iso_nationality_code_c == "gw" || $this->leadBean->dotb_iso_nationality_code_c == "cf" || $this->leadBean->dotb_iso_nationality_code_c == "ye" || $this->leadBean->dotb_iso_nationality_code_c == "us" || $this->leadBean->dotb_iso_nationality_code_c == "lr") {
                            $newArray[$this->fieldDefs["if_iraq_zim_con_leb_yem_usa_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_iraq_zim_con_leb_yem_usa_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_iraq_zim_con_leb_yem_usa_c == "no") {
                        if ($this->leadBean->dotb_iso_nationality_code_c == "iq" || $this->leadBean->dotb_iso_nationality_code_c == "zw" || $this->leadBean->dotb_iso_nationality_code_c == "cd" || $this->leadBean->dotb_iso_nationality_code_c == "by" || $this->leadBean->dotb_iso_nationality_code_c == "lb" || $this->leadBean->dotb_iso_nationality_code_c == "so" || $this->leadBean->dotb_iso_nationality_code_c == "gn" || $this->leadBean->dotb_iso_nationality_code_c == "er" || $this->leadBean->dotb_iso_nationality_code_c == "ly" || $this->leadBean->dotb_iso_nationality_code_c == "gw" || $this->leadBean->dotb_iso_nationality_code_c == "cf" || $this->leadBean->dotb_iso_nationality_code_c == "ye" || $this->leadBean->dotb_iso_nationality_code_c == "us" || $this->leadBean->dotb_iso_nationality_code_c == "lr") {
                            $newArray[$this->fieldDefs["if_iraq_zim_con_leb_yem_usa_c"]['vname']] = "NO";
                        } else {
                            $newArray[$this->fieldDefs["if_iraq_zim_con_leb_yem_usa_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_iraq_zim_con_leb_yem_usa_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_iraq_zim_con_leb_yem_usa_c"]['vname']] = "Missing";
                    }
                }


                //Nationality
                if (!empty($this->leadBean->dotb_iso_nationality_code_c)) {

                    if ($rp->if_redt_ger_aus_den_sweden_c == "yes") {
                        if ($this->leadBean->dotb_iso_nationality_code_c == "ch" || $this->leadBean->dotb_iso_nationality_code_c == "de" || $this->leadBean->dotb_iso_nationality_code_c == "fr" || $this->leadBean->dotb_iso_nationality_code_c == "it" || $this->leadBean->dotb_iso_nationality_code_c == "lu" || $this->leadBean->dotb_iso_nationality_code_c == "be" || $this->leadBean->dotb_iso_nationality_code_c == "nl" || $this->leadBean->dotb_iso_nationality_code_c == "at" || $this->leadBean->dotb_iso_nationality_code_c == "ie" || $this->leadBean->dotb_iso_nationality_code_c == "dk" || $this->leadBean->dotb_iso_nationality_code_c == "gb" || $this->leadBean->dotb_iso_nationality_code_c == "es" || $this->leadBean->dotb_iso_nationality_code_c == "fi" || $this->leadBean->dotb_iso_nationality_code_c == "se" || $this->leadBean->dotb_iso_nationality_code_c == "no" || $this->leadBean->dotb_iso_nationality_code_c == "li") {
                            $newArray[$this->fieldDefs["if_redt_ger_aus_den_sweden_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_redt_ger_aus_den_sweden_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_redt_ger_aus_den_sweden_c == "no") {
                        if ($this->leadBean->dotb_iso_nationality_code_c == "ch" || $this->leadBean->dotb_iso_nationality_code_c == "de" || $this->leadBean->dotb_iso_nationality_code_c == "fr" || $this->leadBean->dotb_iso_nationality_code_c == "it" || $this->leadBean->dotb_iso_nationality_code_c == "lu" || $this->leadBean->dotb_iso_nationality_code_c == "be" || $this->leadBean->dotb_iso_nationality_code_c == "nl" || $this->leadBean->dotb_iso_nationality_code_c == "at" || $this->leadBean->dotb_iso_nationality_code_c == "ie" || $this->leadBean->dotb_iso_nationality_code_c == "dk" || $this->leadBean->dotb_iso_nationality_code_c == "gb" || $this->leadBean->dotb_iso_nationality_code_c == "es" || $this->leadBean->dotb_iso_nationality_code_c == "fi" || $this->leadBean->dotb_iso_nationality_code_c == "se" || $this->leadBean->dotb_iso_nationality_code_c == "no" || $this->leadBean->dotb_iso_nationality_code_c == "li") {
                            $newArray[$this->fieldDefs["if_redt_ger_aus_den_sweden_c"]['vname']] = "NO";
                        } else {
                            $newArray[$this->fieldDefs["if_redt_ger_aus_den_sweden_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_redt_ger_aus_den_sweden_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_redt_ger_aus_den_sweden_c"]['vname']] = "Missing";
                    }
                }


                if (in_array("NO", $newArray) && in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL2"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("NO", $newArray) && !in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL2"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL2"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL2"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (!in_array("YES", $newArray) && !in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL2"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL2"] = $newArray;
                    $flagArray[] = "Missing";
                } else {
                    $flagArray[] = "YES";
                }

                $newArray = array();

                //Residence Permit < 6 months

                $date_obj = new TimeDate();
                $dateto = date("Y-m-d");
                //$to = $date_obj->to_db_date($this->leadBean->dotb_work_permit_until_c);
                $datefrom = $this->leadBean->dotb_work_permit_since_c;
                $from = $date_obj->to_db_date($this->leadBean->dotb_work_permit_since_c);

                $days = floor((abs(strtotime($dateto) - strtotime($from))) / 86400);
                $months = floor($days / 30);
                $years = floor($months / 12);

                if ($this->leadBean->dotb_iso_nationality_code_c != "ch") {
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_b_permit_less_6_month_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {

                                if ($months < 6) {
                                    $newArray[$this->fieldDefs["if_b_permit_less_6_month_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_permit_less_6_month_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_permit_less_6_month_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months < 6) {
                                    $newArray[$this->fieldDefs["if_b_permit_less_6_month_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_permit_less_6_month_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_permit_less_6_month_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_permit_less_6_month_c"]['vname']] = "Missing";
                            }
                        }
                    }


                    //Residence Permit between 6-12 months

                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_b_permit_btwn_6_12_month_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 6 && $months <= 12) {
                                    $newArray[$this->fieldDefs["if_b_permit_btwn_6_12_month_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_permit_btwn_6_12_month_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_permit_btwn_6_12_month_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 6 && $months <= 12) {
                                    $newArray[$this->fieldDefs["if_b_permit_btwn_6_12_month_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_permit_btwn_6_12_month_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_permit_btwn_6_12_month_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_permit_btwn_6_12_month_c"]['vname']] = "Missing";
                            }
                        }
                    }

                    //Residence Permit between 6-12 months and monthly salary  < 4000
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_b_btw_12_net_m_sal_l_4000_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 6 && $months <= 12 && $this->leadBean->dotb_monthly_net_income_c < 4000) {
                                    $newArray[$this->fieldDefs["if_b_btw_12_net_m_sal_l_4000_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_12_net_m_sal_l_4000_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_btw_12_net_m_sal_l_4000_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 6 && $months <= 12 && $this->leadBean->dotb_monthly_net_income_c < 4000) {
                                    $newArray[$this->fieldDefs["if_b_btw_12_net_m_sal_l_4000_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_12_net_m_sal_l_4000_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_btw_12_net_m_sal_l_4000_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_btw_12_net_m_sal_l_4000_c"]['vname']] = "Missing";
                            }
                        }
                    }


                    //Residence Permit between 6-12 months and monthly salary between 4000 and 6000 and credit amount > 15000
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_b_6_12_sal_btw_4_6_amt_15_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 6 && $months <= 12 && $this->leadBean->dotb_monthly_net_income_c >= 4000 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->credit_amount_c > 15000) {
                                    $newArray[$this->fieldDefs["if_b_6_12_sal_btw_4_6_amt_15_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_6_12_sal_btw_4_6_amt_15_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_6_12_sal_btw_4_6_amt_15_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 6 && $months <= 12 && $this->leadBean->dotb_monthly_net_income_c >= 4000 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->credit_amount_c > 15000) {
                                    $newArray[$this->fieldDefs["if_b_6_12_sal_btw_4_6_amt_15_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_6_12_sal_btw_4_6_amt_15_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_6_12_sal_btw_4_6_amt_15_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_6_12_sal_btw_4_6_amt_15_c"]['vname']] = "Missing";
                            }
                        }
                    }



                    //Residence Permit between 6-12 months and monthly salary between 6000 and 8000 and credit amount > 20000
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_b_6_12_sal_btw_6_8_amt_20_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 6 && $months <= 12 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->dotb_monthly_net_income_c <= 8000 && $this->leadBean->credit_amount_c > 20000) {
                                    $newArray[$fieldDefs["if_b_6_12_sal_btw_6_8_amt_20_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_6_12_sal_btw_6_8_amt_20_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_6_12_sal_btw_6_8_amt_20_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 6 && $months <= 12 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->dotb_monthly_net_income_c <= 8000 && $this->leadBean->credit_amount_c > 20000) {
                                    $newArray[$this->fieldDefs["if_b_6_12_sal_btw_6_8_amt_20_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_6_12_sal_btw_6_8_amt_20_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_6_12_sal_btw_6_8_amt_20_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_6_12_sal_btw_6_8_amt_20_c"]['vname']] = "Missing";
                            }
                        }
                    }


                    //Residence Permit between 6-12 months and monthly salary greater 8000 and credit amount > 30000
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_b_btw_6_12_m_sal_8_amt_30_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 6 && $months <= 12 && $this->leadBean->dotb_monthly_net_income_c > 8000 && $this->leadBean->credit_amount_c > 30000) {
                                    $newArray[$this->fieldDefs["if_b_btw_6_12_m_sal_8_amt_30_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_6_12_m_sal_8_amt_30_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_btw_6_12_m_sal_8_amt_30_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 6 && $months <= 12 && $this->leadBean->dotb_monthly_net_income_c > 8000 && $this->leadBean->credit_amount_c > 30000) {
                                    $newArray[$this->fieldDefs["if_b_btw_6_12_m_sal_8_amt_30_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_6_12_m_sal_8_amt_30_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_btw_6_12_m_sal_8_amt_30_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_btw_6_12_m_sal_8_amt_30_c"]['vname']] = "Missing";
                            }
                        }
                    }

                    //Residence Permit between 12-24 months and monthly salary less 4000 and credit amount > 15000
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {


                        if ($rp->if_b_btw_12_24__sal_4_amt_15_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 12 && $months <= 24 && $this->leadBean->dotb_monthly_net_income_c < 4000 && $this->leadBean->credit_amount_c > 15000) {
                                    $newArray[$this->fieldDefs["if_b_btw_12_24__sal_4_amt_15_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_12_24__sal_4_amt_15_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_btw_12_24__sal_4_amt_15_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 12 && $months <= 24 && $this->leadBean->dotb_monthly_net_income_c < 4000 && $this->leadBean->credit_amount_c > 15000) {
                                    $newArray[$this->fieldDefs["if_b_btw_12_24__sal_4_amt_15_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_12_24__sal_4_amt_15_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_btw_12_24__sal_4_amt_15_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_btw_12_24__sal_4_amt_15_c"]['vname']] = "Missing";
                            }
                        }
                    }


                    //Residence Permit between 12-24 months and monthly salary between 4000 and 6000 and credit amount > 25000
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_b_btw_12_24_sal_4_6_am_25_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 12 && $months <= 24 && $this->leadBean->dotb_monthly_net_income_c >= 4000 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->credit_amount_c > 25000) {
                                    $newArray[$this->fieldDefs["if_b_btw_12_24_sal_4_6_am_25_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_12_24_sal_4_6_am_25_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_btw_12_24_sal_4_6_am_25_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 12 && $months <= 24 && $this->leadBean->dotb_monthly_net_income_c >= 4000 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->credit_amount_c > 25000) {
                                    $newArray[$this->fieldDefs["if_b_btw_12_24_sal_4_6_am_25_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_12_24_sal_4_6_am_25_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_btw_12_24_sal_4_6_am_25_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_btw_12_24_sal_4_6_am_25_c"]['vname']] = "Missing";
                            }
                        }
                    }


                    //Residence Permit between 12-24 months and monthly salary between 6000 and 8000 and credit amount > 30000

                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_b_btw_12_24_sal_6_8_am_30_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 12 && $months <= 24 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->dotb_monthly_net_income_c <= 8000 && $this->leadBean->credit_amount_c > 30000) {
                                    $newArray[$this->fieldDefs["if_b_btw_12_24_sal_6_8_am_30_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_12_24_sal_6_8_am_30_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_btw_12_24_sal_6_8_am_30_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 12 && $months <= 24 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->dotb_monthly_net_income_c <= 8000 && $this->leadBean->credit_amount_c > 30000) {
                                    $newArray[$this->fieldDefs["if_b_btw_12_24_sal_6_8_am_30_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_12_24_sal_6_8_am_30_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_btw_12_24_sal_6_8_am_30_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_btw_12_24_sal_6_8_am_30_c"]['vname']] = "Missing";
                            }
                        }
                    }

                    //Residence Permit between 12-24 months and monthly salary greater 8000 and credit amount > 40000
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_b_btw_12_24_sal_8_amnt_40_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 12 && $months <= 24 && $this->leadBean->dotb_monthly_net_income_c > 8000 && $this->leadBean->credit_amount_c > 40000) {
                                    $newArray[$this->fieldDefs["if_b_btw_12_24_sal_8_amnt_40_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_12_24_sal_8_amnt_40_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_btw_12_24_sal_8_amnt_40_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 12 && $months <= 24 && $this->leadBean->dotb_monthly_net_income_c > 8000 && $this->leadBean->credit_amount_c > 40000) {
                                    $newArray[$this->fieldDefs["if_b_btw_12_24_sal_8_amnt_40_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_12_24_sal_8_amnt_40_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_btw_12_24_sal_8_amnt_40_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_btw_12_24_sal_8_amnt_40_c"]['vname']] = "Missing";
                            }
                        }
                    }

                    //Residence Permit between 24 - 36 months AND net monthly salary  less than 4000 AND credit-amount less than 25,000
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {


                        if ($rp->if_b_btw_24_36_sal_4_amnt_25_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c < 4000 && $this->leadBean->credit_amount_c < 25000) {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_4_amnt_25_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_4_amnt_25_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_btw_24_36_sal_4_amnt_25_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c < 4000 && $this->leadBean->credit_amount_c < 25000) {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_4_amnt_25_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_4_amnt_25_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_btw_24_36_sal_4_amnt_25_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_btw_24_36_sal_4_amnt_25_c"]['vname']] = "Missing";
                            }
                        }
                    }

                    //Residence Permit  between 24 - 36 months AND net monthly salary less than 4000 AND credit-amount greater than  25,000
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_b_btw_24_36_sal_4_a_g_25_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c > 4000 && $this->leadBean->credit_amount_c > 25000) {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_4_a_g_25_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_4_a_g_25_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_btw_24_36_sal_4_a_g_25_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c > 4000 && $this->leadBean->credit_amount_c > 25000) {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_4_a_g_25_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_4_a_g_25_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_btw_24_36_sal_4_a_g_25_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_btw_24_36_sal_4_a_g_25_c"]['vname']] = "Missing";
                            }
                        }
                    }


                    //Residence Permit  between 24 - 36 months AND net monthly salary between 4,000 and 6,000 AND credit-amount greater than 35,000
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_b_btw_24_36_sal_4_6_am_35_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c >= 4000 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->credit_amount_c > 35000) {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_4_6_am_35_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_4_6_am_35_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_btw_24_36_sal_4_6_am_35_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c >= 4000 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->credit_amount_c > 35000) {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_4_6_am_35_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_4_6_am_35_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_btw_24_36_sal_4_6_am_35_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_btw_24_36_sal_4_6_am_35_c"]['vname']] = "Missing";
                            }
                        }
                    }


                    //Residence Permit  between 24 - 36 months AND net monthly salary between 6,000 and 8,000 AND credit-amount greater than  40,000
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_b_btw_24_36_sal_6_8_am_40_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->dotb_monthly_net_income_c <= 8000 && $this->leadBean->credit_amount_c > 40000) {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_6_8_am_40_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_6_8_am_40_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_btw_24_36_sal_6_8_am_40_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->dotb_monthly_net_income_c <= 8000 && $this->leadBean->credit_amount_c > 40000) {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_6_8_am_40_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_6_8_am_40_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_btw_24_36_sal_6_8_am_40_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_btw_24_36_sal_6_8_am_40_c"]['vname']] = "Missing";
                            }
                        }
                    }



                    //Residence Permit  between 24 - 36 months AND net monthly salary greater than  8,000 AND credit-amount greater than  50,000
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_b_btw_24_36_sal_8_amnt_50_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c > 8000 && $this->leadBean->credit_amount_c > 50000) {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_8_amnt_50_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_8_amnt_50_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_btw_24_36_sal_8_amnt_50_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 24 && $months <= 36 && $this->leadBean->dotb_monthly_net_income_c > 8000 && $this->leadBean->credit_amount_c > 50000) {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_8_amnt_50_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_btw_24_36_sal_8_amnt_50_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_btw_24_36_sal_8_amnt_50_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_btw_24_36_sal_8_amnt_50_c"]['vname']] = "Missing";
                            }
                        }
                    }


                    //Residence Permit greater than  36 months AND net monthly salary  less than 4000 AND credit-amount greater than 35,000
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {


                        if ($rp->if_b_36_sal_4_credit_amnt_35_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months > 36 && $this->leadBean->dotb_monthly_net_income_c < 4000 && $this->leadBean->credit_amount_c > 35000) {
                                    $newArray[$this->fieldDefs["if_b_36_sal_4_credit_amnt_35_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_36_sal_4_credit_amnt_35_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_36_sal_4_credit_amnt_35_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months > 36 && $this->leadBean->dotb_monthly_net_income_c < 4000 && $this->leadBean->credit_amount_c > 35000) {
                                    $newArray[$this->fieldDefs["if_b_36_sal_4_credit_amnt_35_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_36_sal_4_credit_amnt_35_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_36_sal_4_credit_amnt_35_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_36_sal_4_credit_amnt_35_c"]['vname']] = "Missing";
                            }
                        }
                    }



                    //Residence Permit greater than  36 months AND net monthly salary between 4,000 and 6,000 AND credit-amount greater 45,000
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {


                        if ($rp->if_b_36_sal_4_6_credit_am_45_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months > 36 && $this->leadBean->dotb_monthly_net_income_c >= 4000 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->credit_amount_c > 45000) {
                                    $newArray[$this->fieldDefs["if_b_36_sal_4_6_credit_am_45_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_36_sal_4_6_credit_am_45_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_36_sal_4_6_credit_am_45_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months > 36 && $this->leadBean->dotb_monthly_net_income_c >= 4000 && $this->leadBean->dotb_monthly_net_income_c <= 6000 && $this->leadBean->credit_amount_c > 45000) {
                                    $newArray[$this->fieldDefs["if_b_36_sal_4_6_credit_am_45_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_36_sal_4_6_credit_am_45_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_36_sal_4_6_credit_am_45_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_36_sal_4_6_credit_am_45_c"]['vname']] = "Missing";
                            }
                        }
                    }



                    //Residence Permit greater than  36  AND net monthly salary between 6,000 and 8,000 AND credit-amount greater than 50,000
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_b_36_sal_6_8_credit_am_50_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months > 36 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->dotb_monthly_net_income_c <= 8000 && $this->leadBean->credit_amount_c > 50000) {
                                    $newArray[$this->fieldDefs["if_b_36_sal_6_8_credit_am_50_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_36_sal_6_8_credit_am_50_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_36_sal_6_8_credit_am_50_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months > 36 && $this->leadBean->dotb_monthly_net_income_c >= 6000 && $this->leadBean->dotb_monthly_net_income_c <= 8000 && $this->leadBean->credit_amount_c > 50000) {
                                    $newArray[$this->fieldDefs["if_b_36_sal_6_8_credit_am_50_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_36_sal_6_8_credit_am_50_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_36_sal_6_8_credit_am_50_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c) || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_36_sal_6_8_credit_am_50_c"]['vname']] = "Missing";
                            }
                        }
                    }


                    //Residence Permit greater than  36 months AND net monthly salary greater 8,000 AND credit-amount greater than 60,000
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_monthly_net_income_c) && !empty($this->leadBean->credit_amount_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_b_36_sal_8_credit_amnt_60_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months > 36 && $this->leadBean->dotb_monthly_net_income_c > 8000 && $this->leadBean->credit_amount_c > 60000) {
                                    $newArray[$this->fieldDefs["if_b_36_sal_8_credit_amnt_60_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_36_sal_8_credit_amnt_60_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_36_sal_8_credit_amnt_60_c == "no") {

                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months > 36 && $this->leadBean->dotb_monthly_net_income_c > 8000 && $this->leadBean->credit_amount_c > 60000) {
                                    $newArray[$this->fieldDefs["if_b_36_sal_8_credit_amnt_60_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_36_sal_8_credit_amnt_60_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_36_sal_8_credit_amnt_60_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_36_sal_8_credit_amnt_60_c"]['vname']] = "Missing";
                            }
                        }
                    }


                    //Residence Permit  between 12-24 months
                    if (!empty($this->leadBean->dotb_work_permit_since_c)) {


                        if ($rp->if_b_permit_btw_12_24_months_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 12 && $months <= 24) {
                                    $newArray[$this->fieldDefs["if_b_permit_btw_12_24_months_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_permit_btw_12_24_months_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_permit_btw_12_24_months_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 12 && $months <= 24) {
                                    $newArray[$this->fieldDefs["if_b_permit_btw_12_24_months_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_permit_btw_12_24_months_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_permit_btw_12_24_months_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_permit_btw_12_24_months_c"]['vname']] = "Missing";
                            }
                        }
                    }


                    //Residence Permit  between 24-36 months
                    if (!empty($this->leadBean->dotb_work_permit_since_c)) {

                        if ($rp->if_b_permit_btw_24_36_months_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 24 && $months <= 36) {
                                    $newArray[$this->fieldDefs["if_b_permit_btw_24_36_months_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_permit_btw_24_36_months_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_permit_btw_24_36_months_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months >= 24 && $months <= 36) {
                                    $newArray[$this->fieldDefs["if_b_permit_btw_24_36_months_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_permit_btw_24_36_months_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_permit_btw_24_36_months_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_permit_btw_24_36_months_c"]['vname']] = "Missing";
                            }
                        }
                    }

                    //Residence Permit B-permit more than 12 months, "living alone and children" / single_parents
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_housing_situation_id_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_b_12_alo_child_sin_parent_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months > 12 && $this->leadBean->dotb_housing_situation_id_c == "single_parent" || $this->leadBean->dotb_housing_situation_id_c == "alone") {
                                    $newArray[$this->fieldDefs["if_b_12_alo_child_sin_parent_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_12_alo_child_sin_parent_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_b_12_alo_child_sin_parent_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit") {
                                if ($months > 12 && $this->leadBean->dotb_housing_situation_id_c == "single_parent" || $this->leadBean->dotb_housing_situation_id_c == "alone") {
                                    $newArray[$this->fieldDefs["if_b_12_alo_child_sin_parent_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_b_12_alo_child_sin_parent_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_b_12_alo_child_sin_parent_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "b_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_b_12_alo_child_sin_parent_c"]['vname']] = "Missing";
                            }
                        }
                    }


                    //Residence Permit  G-permit less than 3 years with employer
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {


                        if ($rp->if_g_less_3_years_employer_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "g_permit") {
                                if ($years < 3) {
                                    $newArray[$this->fieldDefs["if_g_less_3_years_employer_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_g_less_3_years_employer_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_g_less_3_years_employer_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "g_permit") {
                                if ($years < 3) {
                                    $newArray[$this->fieldDefs["if_g_less_3_years_employer_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_g_less_3_years_employer_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_g_less_3_years_employer_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "g_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_g_less_3_years_employer_c"]['vname']] = "Missing";
                            }
                        }
                    }


                    //Residence Permit  G-permit more than 3 years
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_g_permit_more_3_years_c == "yes") {

                            if ($this->leadBean->dotb_work_permit_type_id_c == "g_permit") {
                                if ($years > 3) {
                                    $newArray[$this->fieldDefs["if_g_permit_more_3_years_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_g_permit_more_3_years_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_g_permit_more_3_years_c == "no") {

                            if ($this->leadBean->dotb_work_permit_type_id_c == "g_permit") {
                                if ($years > 3) {
                                    $newArray[$this->fieldDefs["if_g_permit_more_3_years_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_g_permit_more_3_years_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_g_permit_more_3_years_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "g_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_g_permit_more_3_years_c"]['vname']] = "Missing";
                            }
                        }
                    }


                    //Residence Permit if L-permit less than 1 year
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_l_permit_less_1_year_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "l_permit") {
                                if ($months < 12) {
                                    $newArray[$this->fieldDefs["if_l_permit_less_1_year_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_l_permit_less_1_year_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_l_permit_less_1_year_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "l_permit") {
                                if ($months < 12) {
                                    $newArray[$this->fieldDefs["if_l_permit_less_1_year_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_l_permit_less_1_year_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_l_permit_less_1_year_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "l_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_l_permit_less_1_year_c"]['vname']] = "Missing";
                            }
                        }
                    }


                    //Residence Permit if L-permit more than 1 year
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_l_permit_more_than_1_year_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "l_permit") {
                                if ($months > 12) {
                                    $newArray[$this->fieldDefs["if_l_permit_more_than_1_year_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_l_permit_more_than_1_year_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_l_permit_more_than_1_year_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "l_permit") {
                                if ($months > 12) {
                                    $newArray[$this->fieldDefs["if_l_permit_more_than_1_year_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_l_permit_more_than_1_year_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_l_permit_more_than_1_year_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "l_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_l_permit_more_than_1_year_c"]['vname']] = "Missing";
                            }
                        }
                    }


                    //Residence Permit if Diplomat less than 3 years
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_diplomat_less_3_years_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "diplomate_permit") {
                                if ($months < 36) {
                                    $newArray[$this->fieldDefs["if_diplomat_less_3_years_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_diplomat_less_3_years_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_diplomat_less_3_years_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "diplomate_permit") {
                                if ($months < 36) {
                                    $newArray[$this->fieldDefs["if_diplomat_less_3_years_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_diplomat_less_3_years_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_diplomat_less_3_years_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "diplomate_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_diplomat_less_3_years_c"]['vname']] = "Missing";
                            }
                        }
                    }


                    //Residence Permit if Diplomat more than 3 years
                    if (!empty($this->leadBean->dotb_work_permit_since_c) && !empty($this->leadBean->dotb_work_permit_type_id_c)) {

                        if ($rp->if_diplomat_more_3_years_c == "yes") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "diplomate_permit") {
                                if ($months > 36) {
                                    $newArray[$this->fieldDefs["if_diplomat_more_3_years_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_diplomat_more_3_years_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_diplomat_more_3_years_c == "no") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "diplomate_permit") {
                                if ($months > 36) {
                                    $newArray[$this->fieldDefs["if_diplomat_more_3_years_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_diplomat_more_3_years_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_diplomat_more_3_years_c != "not_relevant") {
                            if ($this->leadBean->dotb_work_permit_type_id_c == "diplomate_permit" || empty($this->leadBean->dotb_work_permit_type_id_c)) {
                                $newArray[$this->fieldDefs["if_diplomat_more_3_years_c"]['vname']] = "Missing";
                            }
                        }
                    }
                }


                if (in_array("NO", $newArray) && in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL3"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("NO", $newArray) && !in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL3"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL3"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL3"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (!in_array("YES", $newArray) && !in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL3"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL3"] = $newArray;
                    $flagArray[] = "Missing";
                } else {
                    $flagArray[] = "YES";
                }


                $newArray = array();

                //Duration Calculation for comparison
                $date_obj2 = new TimeDate();
                $to_emp = date("Y-m-d");

                $datefrom = $this->leadBean->dotb_employed_since_c;
                $from_emp = $date_obj2->to_db_date($this->leadBean->dotb_employed_since_c);

                $days2 = floor((abs(strtotime($to_emp) - strtotime($from_emp))) / 86400);
                $months2 = floor($days2 / 30);
                $years2 = floor($months2 / 12);


                //Employment Type if "self" / self_employed and employed for less than 2 years
                if ($this->leadBean->dotb_employment_type_id_c == "self_employed" || empty($this->leadBean->dotb_employment_type_id_c)) {
                    if (!empty($this->leadBean->dotb_employment_type_id_c) && !empty($this->leadBean->dotb_employed_since_c)) {


                        if ($rp->if_self_emp_less_2_years_c == "yes") {
                            if ($this->leadBean->dotb_employment_type_id_c == "self_employed") {
                                if ($months2 < 24) {
                                    $newArray[$this->fieldDefs["if_self_emp_less_2_years_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_self_emp_less_2_years_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_self_emp_less_2_years_c == "no") {
                            if ($this->leadBean->dotb_employment_type_id_c == "self_employed") {
                                if ($months2 < 24) {
                                    $newArray[$this->fieldDefs["if_self_emp_less_2_years_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_self_emp_less_2_years_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_self_emp_less_2_years_c != "not_relevant") {
                            $newArray[$this->fieldDefs["if_self_emp_less_2_years_c"]['vname']] = "Missing";
                        }
                    }


                    //Employment Type if "self" more than 2 years
                    if (!empty($this->leadBean->dotb_employment_type_id_c) && !empty($this->leadBean->dotb_employed_since_c)) {


                        if ($rp->if_self_more_2_years_c == "yes") {
                            if ($this->leadBean->dotb_employment_type_id_c == "self_employed") {
                                if ($months2 > 24) {
                                    $newArray[$this->fieldDefs["if_self_more_2_years_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_self_more_2_years_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_self_more_2_years_c == "no") {

                            if ($this->leadBean->dotb_employment_type_id_c == "self_employed") {
                                if ($months2 > 24) {
                                    $newArray[$this->fieldDefs["if_self_more_2_years_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_self_more_2_years_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_self_more_2_years_c != "not_relevant") {
                            $newArray[$this->fieldDefs["if_self_more_2_years_c"]['vname']] = "Missing";
                        }
                    }
                }

                //Duration Calculation for comparison
                $date_obj2 = new TimeDate();
                $dateto2 = $this->leadBean->dotb_employed_since_c;
                $datefrom2 = date("Y-m-d");

                $days4 = floor((abs(strtotime($dateto2) - strtotime($datefrom2))) / 86400);
                $months4 = floor($days4 / 30);
                $years4 = floor($months4 / 12);


                //Employment Type if "unemployed" / not-working
                if ($this->leadBean->dotb_employment_type_id_c == "not_working" || empty($this->leadBean->dotb_employment_type_id_c)) {
                    if (!empty($this->leadBean->dotb_employment_type_id_c)) {

                        if ($rp->if_unemp_not_working_c == "yes") {
                            if ($this->leadBean->dotb_employment_type_id_c == "not_working") {
                                $newArray[$this->fieldDefs["if_unemp_not_working_c"]['vname']] = "YES";
                            } else {
                                $newArray[$this->fieldDefs["if_unemp_not_working_c"]['vname']] = "YES";
                            }
                        } else if ($rp->if_unemp_not_working_c == "no") {
                            if ($this->leadBean->dotb_employment_type_id_c == "not_working") {
                                $newArray[$this->fieldDefs["if_unemp_not_working_c"]['vname']] = "NO";
                            } else {
                                $newArray[$this->fieldDefs["if_unemp_not_working_c"]['vname']] = "YES";
                            }
                        }
                    } else {
                        if ($rp->if_unemp_not_working_c != "not_relevant") {
                            $newArray[$this->fieldDefs["if_unemp_not_working_c"]['vname']] = "Missing";
                        }
                    }
                }

                //Employment Type if "temporarily" / temporary_contract and employed for less than 6 months
                if ($this->leadBean->dotb_employment_type_id_c == "temporary_contract" || empty($this->leadBean->dotb_employment_type_id_c)) {
                    if (!empty($this->leadBean->dotb_employment_type_id_c) && !empty($this->leadBean->dotb_employed_since_c)) {

                        if ($rp->if_temp_cont_6_months_c == "yes") {
                            if ($this->leadBean->dotb_employment_type_id_c == "temporary_contract") {
                                if ($months2 < 6) {
                                    $newArray[$this->fieldDefs["if_temp_cont_6_months_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_temp_cont_6_months_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_temp_cont_6_months_c == "no") {

                            if ($this->leadBean->dotb_employment_type_id_c == "temporary_contract") {
                                if ($months2 < 6) {
                                    $newArray[$this->fieldDefs["if_temp_cont_6_months_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_temp_cont_6_months_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_temp_cont_6_months_c != "not_relevant") {
                            $newArray[$this->fieldDefs["if_temp_cont_6_months_c"]['vname']] = "Missing";
                        }
                    }



                    //Employment Type if "temporarily" / temporary_contract 6-12 months
                    if (!empty($this->leadBean->dotb_employment_type_id_c) && !empty($this->leadBean->dotb_employed_since_c)) {

                        if ($rp->if_temp_cont_6_12_mon_c == "yes") {
                            if ($this->leadBean->dotb_employment_type_id_c == "temporary_contract") {
                                if ($months2 >= 6 && $months2 <= 12) {
                                    $newArray[$this->fieldDefs["if_temp_cont_6_12_mon_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_temp_cont_6_12_mon_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_temp_cont_6_12_mon_c == "no") {
                            if ($this->leadBean->dotb_employment_type_id_c == "temporary_contract") {
                                if ($months2 >= 6 && $months2 <= 12) {
                                    $newArray[$this->fieldDefs["if_temp_cont_6_12_mon_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_temp_cont_6_12_mon_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_temp_cont_6_12_mon_c != "not_relevant") {
                            $newArray[$this->fieldDefs["if_temp_cont_6_12_mon_c"]['vname']] = "Missing";
                        }
                    }


                    //Employment Type if "temporarily" / temporary_contract more than 12 months
                    if (!empty($this->leadBean->dotb_employment_type_id_c) && !empty($this->leadBean->dotb_employed_since_c)) {

                        if ($rp->if_temp_cont_12_month_c == "yes") {

                            if ($this->leadBean->dotb_employment_type_id_c == "temporary_contract") {
                                if ($months2 > 12) {
                                    $newArray[$this->fieldDefs["if_temp_cont_12_month_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_temp_cont_12_month_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_temp_cont_12_month_c == "no") {
                            if ($this->leadBean->dotb_employment_type_id_c == "temporary_contract") {
                                if ($months2 > 12) {
                                    $newArray[$this->fieldDefs["if_temp_cont_12_month_c"]['vname']] = "NO";
                                } else {
                                    $newArray[$this->fieldDefs["if_temp_cont_12_month_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_temp_cont_12_month_c != "not_relevant") {
                            $newArray[$this->fieldDefs["if_temp_cont_12_month_c"]['vname']] = "Missing";
                        }
                    }
                }


                //Employment Type if "disability pension" / retirement
                if ($this->leadBean->dotb_employment_type_id_c == "disabled_gets_pension" || $this->leadBean->dotb_employment_type_id_c == "retirement" || empty($this->leadBean->dotb_employment_type_id_c)) {
                    if (!empty($this->leadBean->dotb_employment_type_id_c)) {

                        if ($rp->if_disable_pension_ret_c == "yes") {
                            if ($this->leadBean->dotb_employment_type_id_c == "disabled_gets_pension" || $this->leadBean->dotb_employment_type_id_c == "retirement") {
                                $newArray[$this->fieldDefs["if_disable_pension_ret_c"]['vname']] = "YES";
                            } else {
                                $newArray[$this->fieldDefs["if_disable_pension_ret_c"]['vname']] = "YES";
                            }
                        } else if ($rp->if_disable_pension_ret_c == "no") {
                            if ($this->leadBean->dotb_employment_type_id_c == "disabled_gets_pension" || $this->leadBean->dotb_employment_type_id_c == "retirement") {
                                $newArray[$this->fieldDefs["if_disable_pension_ret_c"]['vname']] = "NO";
                            } else {
                                $newArray[$this->fieldDefs["if_disable_pension_ret_c"]['vname']] = "YES";
                            }
                        }
                    } else {
                        if ($rp->if_disable_pension_ret_c != "not_relevant") {
                            $newArray[$this->fieldDefs["if_disable_pension_ret_c"]['vname']] = "Missing";
                        }
                    }
                }


                if (in_array("NO", $newArray) && in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL4"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("NO", $newArray) && !in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL4"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL4"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL4"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (!in_array("YES", $newArray) && !in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL4"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL4"] = $newArray;
                    $flagArray[] = "Missing";
                } else {
                    $flagArray[] = "YES";
                }

                $newArray = array();

                $flag8 = '';
                if ($this->leadBean->dotb_employment_type_id_c == "self_employed" || $this->leadBean->dotb_employment_type_id_c == "permanent_contract" || $this->leadBean->dotb_employment_type_id_c == "temporary_contract" || $this->leadBean->dotb_employment_type_id_c == "fixed_term_contract") {
                    //Employment if less than 3 months
                    if (!empty($this->leadBean->dotb_employment_type_id_c) && !empty($this->leadBean->dotb_employed_since_c)) {

                        if ($rp->if_less_than_3_months_c == "yes") {
                            if (!empty($this->leadBean->dotb_employment_type_id_c) && $this->leadBean->dotb_employment_type_id_c != "not_working") {
                                if ($months4 < 3) {
                                    $newArray[$this->fieldDefs["if_less_than_3_months_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_less_than_3_months_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_less_than_3_months_c == "no") {
                            if (!empty($this->leadBean->dotb_employment_type_id_c) && $this->leadBean->dotb_employment_type_id_c != "not_working") {
                                if ($months4 < 3) {
                                    $newArray[$this->fieldDefs["if_less_than_3_months_c"]['vname']] = "NO";
                                    $flag8 = true;
                                } else {
                                    $newArray[$this->fieldDefs["if_less_than_3_months_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_less_than_3_months_c != "not_relevant") {
                            $newArray[$this->fieldDefs["if_less_than_3_months_c"]['vname']] = "Missing";
                        }
                    }


                    $flag9 = '';
                    //Employment if less than 12 months
                    if (!empty($this->leadBean->dotb_employment_type_id_c) && !empty($this->leadBean->dotb_employed_since_c)) {

                        if ($rp->if_less_than_12_month_c == "yes") {
                            if (!empty($this->leadBean->dotb_employment_type_id_c) && $this->leadBean->dotb_employment_type_id_c != "not_working") {

                                if ($months4 < 12) {
                                    $newArray[$this->fieldDefs["if_less_than_12_month_c"]['vname']] = "YES";
                                } else {
                                    $newArray[$this->fieldDefs["if_less_than_12_month_c"]['vname']] = "YES";
                                }
                            }
                        } else if ($rp->if_less_than_12_month_c == "no") {

                            if (!empty($this->leadBean->dotb_employment_type_id_c) && $this->leadBean->dotb_employment_type_id_c != "not_working") {
                                if ($months4 < 12) {
                                    if ($flag8 == true) {
                                        $newArray[$this->fieldDefs["if_less_than_12_month_c"]['vname']] = "NO";
                                        $flag9 = true;
                                    }
                                } else {
                                    $newArray[$this->fieldDefs["if_less_than_12_month_c"]['vname']] = "YES";
                                }
                            }
                        }
                    } else {
                        if ($rp->if_less_than_12_month_c != "not_relevant") {
                            $newArray[$this->fieldDefs["if_less_than_12_month_c"]['vname']] = "Missing";
                        }
                    }
                }

                if (in_array("NO", $newArray) && in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL5"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("NO", $newArray) && !in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL5"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL5"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL5"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (!in_array("YES", $newArray) && !in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL5"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL5"] = $newArray;
                    $flagArray[] = "Missing";
                } else {

                    $flagArray[] = "YES";
                }

                $newArray = array();


                //Residence  if "with parents" - by_parents
                if (!empty($this->leadBean->dotb_housing_situation_id_c)) {

                    if ($rp->if_with_par_by_parent_c == "yes") {
                        if ($this->leadBean->dotb_housing_situation_id_c == "by_parents") {
                            $newArray[$this->fieldDefs["if_with_par_by_parent_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_with_par_by_parent_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_with_par_by_parent_c == "no") {
                        if ($this->leadBean->dotb_housing_situation_id_c == "by_parents") {
                            $newArray[$this->fieldDefs["if_with_par_by_parent_c"]['vname']] = "NO";
                        } else {
                            $newArray[$this->fieldDefs["if_with_par_by_parent_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_with_par_by_parent_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_with_par_by_parent_c"]['vname']] = "Missing";
                    }
                }


                //Residence if "Urbanization" - flat_share
                if (!empty($this->leadBean->dotb_housing_situation_id_c)) {


                    if ($rp->if_urbanize_flat_share_c == "yes") {
                        if ($this->leadBean->dotb_housing_situation_id_c == "flat_share") {
                            $newArray[$this->fieldDefs["if_urbanize_flat_share_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_urbanize_flat_share_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_urbanize_flat_share_c == "no") {
                        if ($this->leadBean->dotb_housing_situation_id_c == "flat_share") {
                            $newArray[$this->fieldDefs["if_urbanize_flat_share_c"]['vname']] = "NO";
                        } else {
                            $newArray[$this->fieldDefs["if_urbanize_flat_share_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_urbanize_flat_share_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_urbanize_flat_share_c"]['vname']] = "Missing";
                    }
                }

                if (in_array("NO", $newArray) && in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL6"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("NO", $newArray) && !in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL6"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL6"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL6"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (!in_array("YES", $newArray) && !in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL6"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL6"] = $newArray;
                    $flagArray[] = "Missing";
                } else {
                    $flagArray[] = "YES";
                }

                $newArray = array();

                $from_res = "";
                $date_obj3 = new TimeDate();
                $date_to = date("Y-m-d");
                $to_res = $date_obj3->to_db_date($date_to);
                $date_from = $this->leadBean->dotb_resident_since_c;
                $from_res = $date_obj3->to_db_date($this->leadBean->dotb_resident_since_c);

                $days3 = floor((abs(strtotime($date_to) - strtotime($from_res))) / 86400);
                $months3 = floor($days3 / 30);
                $years3 = floor($months3 / 12);

                //Residence if less than 12 months
                if (!empty($this->leadBean->dotb_resident_since_c)) {

                    if ($rp->if_less_than_12_months_c == "yes") {
                        if ($months3 < 12) {
                            $newArray[$this->fieldDefs["if_less_than_12_months_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_less_than_12_months_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_less_than_12_months_c == "no") {
                        if ($months3 < 12) {
                            $newArray[$this->fieldDefs["if_less_than_12_months_c"]['vname']] = "NO";
                        } else {
                            $newArray[$this->fieldDefs["if_less_than_12_months_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_less_than_12_months_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_less_than_12_months_c"]['vname']] = "Missing";
                    }
                }

                //Residence if less than 24 months
                if (!empty($this->leadBean->dotb_resident_since_c)) {

                    if ($rp->if_less_than_24_months_c == "yes") {
                        if ($months3 < 24) {
                            $newArray[$this->fieldDefs["if_less_than_24_months_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_less_than_24_months_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_less_than_24_months_c == "no") {
                        if ($months3 < 24) {
                            $newArray[$this->fieldDefs["if_less_than_24_months_c"]['vname']] = "NO";
                        } else {
                            $newArray[$this->fieldDefs["if_less_than_24_months_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_less_than_24_months_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_less_than_24_months_c"]['vname']] = "Missing";
                    }
                }


                if (in_array("NO", $newArray) && in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL7"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("NO", $newArray) && !in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL7"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL7"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL7"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (!in_array("YES", $newArray) && !in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL7"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL7"] = $newArray;
                    $flagArray[] = "Missing";
                } else {
                    $flagArray[] = "YES";
                }


                $newArray = array();


                //Home Owner if "no"

                if (!empty($this->leadBean->dotb_is_home_owner_c)) {
                    if ($rp->if_no_c == "yes") {
                        if ($this->leadBean->dotb_is_home_owner_c == "no") {
                            $newArray[$this->fieldDefs["if_no_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_no_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_no_c == "no") {
                        if ($this->leadBean->dotb_is_home_owner_c == "no") {
                            $newArray[$this->fieldDefs["if_no_c"]['vname']] = "NO";
                        } else {
                            $newArray[$this->fieldDefs["if_no_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_no_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_no_c"]['vname']] = "Missing";
                    }
                }




                //Home Owner if "yes" and less than 2 years
                if (!empty($this->leadBean->dotb_is_home_owner_c)) {
                    if ($rp->if_yes_and_less_than_2_years_c == "yes") {
                        if ($this->leadBean->dotb_is_home_owner_c == "yes") {
                            if ($months3 < 24) {
                                $newArray[$this->fieldDefs["if_yes_and_less_than_2_years_c"]['vname']] = "YES";
                            } else {
                                $newArray[$this->fieldDefs["if_yes_and_less_than_2_years_c"]['vname']] = "YES";
                            }
                        }
                    } else if ($rp->if_yes_and_less_than_2_years_c == "no") {
                        if ($this->leadBean->dotb_is_home_owner_c == "yes") {
                            if ($months3 < 24) {
                                $newArray[$this->fieldDefs["if_yes_and_less_than_2_years_c"]['vname']] = "NO";
                            } else {
                                $newArray[$this->fieldDefs["if_yes_and_less_than_2_years_c"]['vname']] = "YES";
                            }
                        }
                    }
                } else {
                    if ($rp->if_yes_and_less_than_2_years_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_yes_and_less_than_2_years_c"]['vname']] = "Missing";
                    }
                }





                //Home Owner if "yes" and longer than 2 years at current adress
                if (!empty($this->leadBean->dotb_is_home_owner_c)) {
                    if ($rp->if_yes_long_2_current_adress_c == "yes") {
                        if ($this->leadBean->dotb_is_home_owner_c == "yes") {
                            if ($months3 > 24) {
                                $newArray[$this->fieldDefs["if_yes_long_2_current_adress_c"]['vname']] = "YES";
                            } else {
                                $newArray[$this->fieldDefs["if_yes_long_2_current_adress_c"]['vname']] = "YES";
                            }
                        }
                    } else if ($rp->if_yes_long_2_current_adress_c == "no") {
                        if ($this->leadBean->dotb_is_home_owner_c == "yes") {
                            if ($months3 > 24) {
                                $newArray[$this->fieldDefs["if_yes_long_2_current_adress_c"]['vname']] = "NO";
                            } else {
                                $newArray[$this->fieldDefs["if_yes_long_2_current_adress_c"]['vname']] = "YES";
                            }
                        }
                    }
                } else {
                    if ($rp->if_yes_long_2_current_adress_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_yes_long_2_current_adress_c"]['vname']] = "Missing";
                    }
                }


                if (in_array("NO", $newArray) && in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL8"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("NO", $newArray) && !in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL8"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL8"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL8"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (!in_array("YES", $newArray) && !in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL8"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL8"] = $newArray;
                    $flagArray[] = "Missing";
                } else {

                    $flagArray[] = "YES";
                }


                $newArray = array();

                //Health Premium Reduction
                if($rp->if_yes_c != "not_relevant"){
                    if ($rp->if_yes_c == "yes") {
                        if (!empty($this->leadBean->dot_health_insurance_premium_c) && $this->leadBean->dot_health_insurance_premium_c != 0.00) {
                            $newArray[$this->fieldDefs["if_yes_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_yes_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_yes_c == "no") {
                        if (empty($this->leadBean->dot_health_insurance_premium_c) || $this->leadBean->dot_health_insurance_premium_c == 0.00) {
                            $newArray[$this->fieldDefs["if_yes_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_yes_c"]['vname']] = "NO";
                        }
                    }
                }
                



                if (in_array("NO", $newArray) && in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL9"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("NO", $newArray) && !in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL9"] = $newArray;
                    $flagArray[] = "NO";
                } else {

                    $flagArray[] = "YES";
                }

                $newArray = array();


                //Alimony if customer Receives alimony
                if ($rp->if_customer_receives_alimony_c != "not_relevant") {
                    if ($this->leadBean->dotb_civil_status_id_c == "divorced" || $this->leadBean->dotb_civil_status_id_c == "separated" || ($this->leadBean->dotb_civil_status_id_c == "married" && $this->leadBean->dotb_housing_situation_id_c == "alone" )) {
                        if ($rp->if_customer_receives_alimony_c == "yes") {
                            if (!empty($this->leadBean->dotb_aliments_c) && $this->leadBean->dotb_aliments_c != 0.00) {
                                $newArray[$this->fieldDefs["if_customer_receives_alimony_c"]['vname']] = "YES";
                            } else {
                                $newArray[$this->fieldDefs["if_customer_receives_alimony_c"]['vname']] = "YES";
                            }
                        } else if ($rp->if_customer_receives_alimony_c == "no") {
                            if (!empty($this->leadBean->dotb_aliments_c) && $this->leadBean->dotb_aliments_c != 0.00) {
                                $newArray[$this->fieldDefs["if_customer_receives_alimony_c"]['vname']] = "NO";
                            } else {
                                $newArray[$this->fieldDefs["if_customer_receives_alimony_c"]['vname']] = "YES";
                            }
                        }
                    }
                }

                //Alimony if customer has to pay alimony
                if (!empty($this->leadBean->dotb_has_alimony_payments_c)) {
                    if ($rp->if_customer_has_pay_alimony_c == "yes") {
                        if ($this->leadBean->dotb_has_alimony_payments_c == "yes") {
                            $newArray[$this->fieldDefs["if_customer_has_pay_alimony_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_customer_has_pay_alimony_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_customer_has_pay_alimony_c == "no") {
                        if ($this->leadBean->dotb_has_alimony_payments_c == "yes") {
                            $newArray[$this->fieldDefs["if_customer_has_pay_alimony_c"]['vname']] = "NO";
                        } else {
                            $newArray[$this->fieldDefs["if_customer_has_pay_alimony_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_customer_has_pay_alimony_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_customer_has_pay_alimony_c"]['vname']] = "Missing";
                    }
                }


                if (in_array("NO", $newArray) && in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL10"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("NO", $newArray) && !in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL10"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL10"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL10"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (!in_array("YES", $newArray) && !in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL10"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL10"] = $newArray;
                    $flagArray[] = "Missing";
                } else {

                    $flagArray[] = "YES";
                }


                $newArray = array();

                //Civil Status if "divorced" or "judicially separated" / divorced or separated
                if (!empty($this->leadBean->dotb_civil_status_id_c)) {

                    if ($rp->if_div_judicial_sep_div_sep_c == "yes") {
                        if ($this->leadBean->dotb_civil_status_id_c == "divorced" || $this->leadBean->dotb_civil_status_id_c == "separated") {
                            $newArray[$this->fieldDefs["if_div_judicial_sep_div_sep_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_div_judicial_sep_div_sep_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_div_judicial_sep_div_sep_c == "no") {
                        if ($this->leadBean->dotb_civil_status_id_c == "divorced" || $this->leadBean->dotb_civil_status_id_c == "separated") {
                            $newArray[$this->fieldDefs["if_div_judicial_sep_div_sep_c"]['vname']] = "NO";
                        } else {
                            $newArray[$this->fieldDefs["if_div_judicial_sep_div_sep_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_div_judicial_sep_div_sep_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_div_judicial_sep_div_sep_c"]['vname']] = "Missing";
                    }
                }



                //Civil Status if "married" / married
                if (!empty($this->leadBean->dotb_civil_status_id_c)) {

                    if ($rp->if_married_c == "yes") {
                        if ($this->leadBean->dotb_civil_status_id_c == "married") {
                            $newArray[$this->fieldDefs["if_married_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_married_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_married_c == "yes") {
                        if ($this->leadBean->dotb_civil_status_id_c == "married") {
                            $newArray[$this->fieldDefs["if_married_c"]['vname']] = "NO";
                        } else {
                            $newArray[$this->fieldDefs["if_married_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_married_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_married_c"]['vname']] = "Missing";
                    }
                }


                if (in_array("NO", $newArray) && in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL11"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("NO", $newArray) && !in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL11"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL11"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL11"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (!in_array("YES", $newArray) && !in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL11"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL11"] = $newArray;
                    $flagArray[] = "Missing";
                } else {

                    $flagArray[] = "YES";
                }


                $newArray = array();

                //Deltavista code no code and can not be found
                /* if($rp->no_code_and_cannot_be_found_c == "yes"){
                  if($this->leadBean->deltavista_request_id_c == "No Code and cannot be found" ){
                  $newArray[$this->fieldDefs["no_code_and_cannot_be_found_c"]['vname']] = "YES";
                  }
                  else{
                  $newArray[$this->fieldDefs["no_code_and_cannot_be_found_c"]['vname']] = "YES";
                  }
                  }
                  else{
                  if($this->leadBean->deltavista_request_id_c == "No Code and cannot be found" ){
                  $newArray[$this->fieldDefs["no_code_and_cannot_be_found_c"]['vname']] = "NO";
                  }
                  else{
                  $newArray[$this->fieldDefs["no_code_and_cannot_be_found_c"]['vname']] = "YES";
                  }
                  }



                  //Deltavista Code code 1
                  if( $rp->code_1_c == "yes"){
                  if($this->leadBean->deltavista_request_id_c == "Code 1"){
                  $newArray[$this->fieldDefs["code_1_c"]['vname']] = "YES";
                  }
                  else{
                  $newArray[$this->fieldDefs["code_1_c"]['vname']] = "YES";
                  }
                  }
                  else{
                  if($this->leadBean->deltavista_request_id_c == "Code 1"){
                  $newArray[$this->fieldDefs["code_1_c"]['vname']] = "YES";
                  }
                  else{
                  $newArray[$this->fieldDefs["code_1_c"]['vname']] = "NO";
                  }
                  }




                  //Deltavista Code code 2
                  if($rp->code_2_c == "yes"){
                  if($this->leadBean->deltavista_request_id_c == "Code 2"){
                  $newArray[$this->fieldDefs["code_2_c"]['vname']] = "YES";
                  }
                  else{
                  $newArray[$this->fieldDefs["code_2_c"]['vname']] = "YES";
                  }
                  }
                  else{
                  if($this->leadBean->deltavista_request_id_c == "Code 2"){
                  $newArray[$this->fieldDefs["code_2_c"]['vname']] = "NO";
                  }
                  else{
                  $newArray[$this->fieldDefs["code_2_c"]['vname']] = "YES";
                  }
                  }




                  //Deltavista Code code 3
                  if($rp->code_3_c == "yes"){
                  if($this->leadBean->deltavista_request_id_c == "Code 3"){
                  $newArray[$this->fieldDefs["code_3_c"]['vname']] = "YES";
                  }
                  else{
                  $newArray[$this->fieldDefs["code_3_c"]['vname']] = "YES";
                  }
                  }
                  else{
                  if($this->leadBean->deltavista_request_id_c == "Code 3"){
                  $newArray[$this->fieldDefs["code_3_c"]['vname']] = "NO";
                  }
                  else{
                  $newArray[$this->fieldDefs["code_3_c"]['vname']] = "YES";
                  }
                  }




                  //Deltavista Code code 4
                  if($rp->code_4_c == "yes"){
                  if($this->leadBean->deltavista_request_id_c == "Code 4"){
                  $newArray[$this->fieldDefs["code_4_c"]['vname']] = "YES";
                  }
                  else{
                  $newArray[$this->fieldDefs["code_4_c"]['vname']] = "YES";
                  }
                  }
                  else{
                  if($this->leadBean->deltavista_request_id_c == "Code 4"){
                  $newArray[$this->fieldDefs["code_4_c"]['vname']] = "NO";
                  }
                  else{
                  $newArray[$this->fieldDefs["code_4_c"]['vname']] = "YES";
                  }
                  } */


                //Deltavista Code if DV-score <440
                if (!empty($this->leadBean->deltavista_score_c)) {
                    if ($rp->if_dv_score_440_c == "yes") {
                        if ($this->leadBean->deltavista_score_c < 440) {
                            $newArray[$this->fieldDefs["if_dv_score_440_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_dv_score_440_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_dv_score_440_c == "no") {
                        if ($this->leadBean->deltavista_score_c < 440) {
                            $newArray[$this->fieldDefs["if_dv_score_440_c"]['vname']] = "NO";
                        } else {
                            $newArray[$this->fieldDefs["if_dv_score_440_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_dv_score_440_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_dv_score_440_c"]['vname']] = "Missing";
                    }
                }

                if (in_array("NO", $newArray) && in_array("YES", $newArray)) {
                    $flagArray[] = "NO";
                    $outerArray["LBL_RECORDVIEW_PANEL12"] = $newArray;
                } else if (in_array("NO", $newArray) && !in_array("YES", $newArray)) {
                    $flagArray[] = "NO";
                    $outerArray["LBL_RECORDVIEW_PANEL12"] = $newArray;
                } else if (in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL12"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL12"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (!in_array("YES", $newArray) && !in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL12"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL12"] = $newArray;
                    $flagArray[] = "Missing";
                } else {

                    $flagArray[] = "YES";
                }

                $newArray = array();

                $flag10 = '';

                if ($this->leadBean->dotb_employment_type_id_c == "permanent_contract") {

                    //Income total income less than 2500
                    if (!empty($this->leadBean->dotb_monthly_gross_income_c)) {

                        if ($rp->total_income_less_than_2500_c == "yes") {
                            if ($this->leadBean->dotb_monthly_gross_income_c < 2500) {
                                $newArray[$this->fieldDefs["total_income_less_than_2500_c"]['vname']] = "YES";
                            } else {
                                $newArray[$this->fieldDefs["total_income_less_than_2500_c"]['vname']] = "YES";
                            }
                        } else if ($rp->total_income_less_than_2500_c == "no") {
                            if ($this->leadBean->dotb_monthly_gross_income_c < 2500) {
                                $newArray[$this->fieldDefs["total_income_less_than_2500_c"]['vname']] = "NO";
                                $flag10 = true;
                            } else {
                                $newArray[$this->fieldDefs["total_income_less_than_2500_c"]['vname']] = "YES";
                            }
                        }
                    } else {
                        if ($rp->total_income_less_than_2500_c != "not_relevant") {
                            $newArray[$this->fieldDefs["total_income_less_than_2500_c"]['vname']] = "Missing";
                        }
                    }


                    $flag11 = '';
                    //Income total income less than 3000
                    if (!empty($this->leadBean->dotb_monthly_gross_income_c)) {

                        if ($rp->total_income_less_than_3000_c == "yes") {
                            if ($this->leadBean->dotb_monthly_gross_income_c < 3000) {
                                $newArray[$this->fieldDefs["total_income_less_than_3000_c"]['vname']] = "YES";
                            } else {
                                $newArray[$this->fieldDefs["total_income_less_than_3000_c"]['vname']] = "YES";
                            }
                        } else if ($rp->total_income_less_than_3000_c == "no") {
                            if ($this->leadBean->dotb_monthly_gross_income_c < 3000) {
                                if ($flag10 != true) {
                                    $newArray[$this->fieldDefs["total_income_less_than_3000_c"]['vname']] = "NO";
                                    $flag11 = true;
                                }
                            } else {
                                $newArray[$this->fieldDefs["total_income_less_than_3000_c"]['vname']] = "YES";
                            }
                        }
                    } else {
                        if ($rp->total_income_less_than_3000_c != "not_relevant") {
                            $newArray[$this->fieldDefs["total_income_less_than_3000_c"]['vname']] = "Missing";
                        }
                    }

                    $flag12 = '';
                    //Income total income less than 4000
                    if (!empty($this->leadBean->dotb_monthly_gross_income_c)) {

                        if ($rp->total_income_less_than_4000_c == "yes") {
                            if ($this->leadBean->dotb_monthly_gross_income_c < 4000) {
                                $newArray[$this->fieldDefs["total_income_less_than_4000_c"]['vname']] = "YES";
                            } else {
                                $newArray[$this->fieldDefs["total_income_less_than_4000_c"]['vname']] = "YES";
                            }
                        } else if ($rp->total_income_less_than_4000_c == "no") {
                            if ($this->leadBean->dotb_monthly_gross_income_c < 4000) {
                                if ($flag11 != true) {
                                    $newArray[$this->fieldDefs["total_income_less_than_4000_c"]['vname']] = "NO";
                                    $flag12 = true;
                                }
                            } else {
                                $newArray[$this->fieldDefs["total_income_less_than_4000_c"]['vname']] = "YES";
                            }
                        }
                    } else {
                        if ($rp->total_income_less_than_4000_c != "not_relevant") {
                            $newArray[$this->fieldDefs["total_income_less_than_4000_c"]['vname']] = "Missing";
                        }
                    }
                }

                if (in_array("NO", $newArray) && in_array("YES", $newArray)) {
                    $flagArray[] = "NO";
                    $outerArray["LBL_RECORDVIEW_PANEL13"] = $newArray;
                } else if (in_array("NO", $newArray) && !in_array("YES", $newArray)) {
                    $flagArray[] = "NO";
                    $outerArray["LBL_RECORDVIEW_PANEL13"] = $newArray;
                } else if (in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL13"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL13"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (!in_array("YES", $newArray) && !in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL13"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL13"] = $newArray;
                    $flagArray[] = "Missing";
                } else {

                    $flagArray[] = "YES";
                }


                $newArray = array();

                //PPI if "credit amount"> 80,000
                if (!empty($this->leadBean->credit_amount_c)) {

                    if ($rp->if_credit_amount_80000_c == "yes") {
                        if ($this->leadBean->credit_amount_c > 80000) {
                            $newArray[$this->fieldDefs["if_credit_amount_80000_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_credit_amount_80000_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_credit_amount_80000_c == "no") {
                        if ($this->leadBean->credit_amount_c > 80000) {
                            $newArray[$this->fieldDefs["if_credit_amount_80000_c"]['vname']] = "NO";
                        } else {
                            $newArray[$this->fieldDefs["if_credit_amount_80000_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_credit_amount_80000_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_credit_amount_80000_c"]['vname']] = "Missing";
                    }
                }


                //PPI if "credit duration"> = 60 months
                if (!empty($this->leadBean->credit_duration_c)) {
                    if ($rp->if_credit_duration_60_months_c == "yes") {
                        if ($this->leadBean->credit_duration_c >= 60) {
                            $newArray[$this->fieldDefs["if_credit_duration_60_months_c"]['vname']] = "YES";
                        } else {
                            $newArray[$this->fieldDefs["if_credit_duration_60_months_c"]['vname']] = "YES";
                        }
                    } else if ($rp->if_credit_duration_60_months_c == "no") {
                        if ($this->leadBean->credit_duration_c >= 60) {
                            $newArray[$this->fieldDefs["if_credit_duration_60_months_c"]['vname']] = "NO";
                        } else {
                            $newArray[$this->fieldDefs["if_credit_duration_60_months_c"]['vname']] = "YES";
                        }
                    }
                } else {
                    if ($rp->if_credit_duration_60_months_c != "not_relevant") {
                        $newArray[$this->fieldDefs["if_credit_duration_60_months_c"]['vname']] = "Missing";
                    }
                }


                if (in_array("NO", $newArray) && in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL14"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("NO", $newArray) && !in_array("YES", $newArray) && !in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL14"] = $newArray;
                    $flagArray[] = "NO";
                } else if (in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL14"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL14"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("Missing", $newArray) && in_array("YES", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL14"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (!in_array("YES", $newArray) && !in_array("NO", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL14"] = $newArray;
                    $flagArray[] = "Missing";
                } else if (in_array("NO", $newArray) && in_array("YES", $newArray) && in_array("Missing", $newArray)) {
                    $outerArray["LBL_RECORDVIEW_PANEL14"] = $newArray;
                    $flagArray[] = "Missing";
                } else {
                    //$outerArray["LBL_RECORDVIEW_PANEL14"] = $newArray;  
                    $flagArray[] = "YES";
                }

                $newArray = array();
            }



            //Flag for each bank

            if (!in_array("NO", $flagArray) && !in_array("Missing", $flagArray) && in_array("YES", $flagArray)) {
                $outerArray['flag'] = "Green";
            }
            if (!in_array("NO", $flagArray) && in_array("Missing", $flagArray) && !in_array("YES", $flagArray)) {
                $outerArray['flag'] = "Grey";
            }
            if (in_array("NO", $flagArray) && !in_array("Missing", $flagArray) && !in_array("YES", $flagArray)) {
                $outerArray['flag'] = "Red";
            }
            //
            if (in_array("YES", $flagArray) && in_array("NO", $flagArray) && !in_array("Missing", $flagArray)) {
                $outerArray['flag'] = "Red";
            }
            if (in_array("YES", $flagArray) && in_array("Missing", $flagArray) && !in_array("NO", $flagArray)) {
                $outerArray['flag'] = "Grey";
            }

            //No + Missing(Red)
            if (in_array("NO", $flagArray) && in_array("Missing", $flagArray) && !in_array("YES", $flagArray)) {
                $outerArray['flag'] = "Red";
            }
            //No + Missing + Yes(Red)
            if (in_array("YES", $flagArray) && in_array("NO", $flagArray) && in_array("Missing", $flagArray)) {
                $outerArray['flag'] = "Red";
            }
            if (!in_array("YES", $flagArray) && !in_array("NO", $flagArray) && !in_array("Missing", $flagArray)) {
                //$outerArray['flag'] = "Green";
            }


            $risk_profile[$item['name']] = $outerArray;
        }

        return $risk_profile;
    }

}

?>