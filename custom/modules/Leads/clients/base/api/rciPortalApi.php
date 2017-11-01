<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class rciPortalApi extends SugarApi {

    /**
     *
     */
    public function registerApiRest() {
        return array(
            'populateRciPortal' => array(
                'reqType' => 'POST',
                'path' => array('Leads', 'rciPortal', '?'),
                'pathVars' => array('', '', 'id'),
                'method' => 'getFilledRciForm',
                'shortHelp' => 'This Api will fill the xml with leads values',
                'longHelp' => '',
            )
        );
    }

    public function formateDate($date) {
        global $timedate;
        if (empty($date)) {
            return '';
        } else {
            try {
                $date = str_replace('/', '-', $date);
                $date = new DateTime($date);
                $date = $timedate->asDbDate($date);
                $date = str_replace('-', '', $date);
                return $date;
            } catch (Exception $e) {
                $GLOBALS['log']->fatal("Error while parssing date for RCI XML");
                $GLOBALS['log']->fatal(print_r($e, true));
                return '';
            }
        }
    }

    public function rciCountry($country) {
        $country = strtoupper($country);
        $country = trim($country);
        if (empty($country)) {
            return '-1';
        } else if ($country == 'schweiz' || $country == 'suisse' || $country == 'svizzera') {
            return 'CH';
        } else {
            return 'LI';
        }
    }

    public function childrenCount($children_birth_years) {
        $birthYears = explode("-*#*-", $children_birth_years);
        $totalYears = count($birthYears);
        $currentYear = Date('Y');
        $no_of_children = array(
            'ls_6' => 0,
            'gt_6_ls_10' => 0,
            'gt_10_ls_12' => 0,
            'gt_12' => 0,
        );
        foreach ($birthYears as $year) {
            $age = ($currentYear - $year) + 1;
            if ($age <= 6)
                $no_of_children['ls_6'] = $no_of_children['ls_6'] + 1;
            else if ($age > 6 && $age <= 10)
                $no_of_children['gt_6_ls_10'] = $no_of_children['gt_6_ls_10'] + 1;
            else if ($age > 10 && $age <= 12)
                $no_of_children['gt_10_ls_12'] = $no_of_children['gt_10_ls_12'] + 1;
            else if ($age > 12)
                $no_of_children['gt_12'] = $no_of_children['gt_12'] + 1;
        }
        return $no_of_children;
    }

    public function getFilledRciForm($api, $args) {
        $rci_dom_mapping = array(
            'yes_no' => array('' => '', 'no' => '1', 'yes' => '2'),
            'gender' => array('' => '-1', 'f' => '1', 'm' => '3'),
            'lang' => array('' => '', 'en' => '3', 'fr' => '1', 'de' => '3', 'it' => '4'),
            'permit_type' => array('' => '', 'a_permit' => 'A', 'b_permit' => 'B', 'c_permit' => '', 'l_permit' => '', 'non_resident' => '', 'g_permit' => '', 'diplomate_permit' => ''),
            'housing_situation' => array('' => '',
                'alone' => '0',
                'flat_share' => '1',
                'married_couple' => '2',
                'single_parent' => '3',
                'by_parents' => '1',
            ),
            'civil_status' => array('' => '',
                'single' => '2',
                'married' => '1',
                'separated' => '11',
                'divorced' => '3',
                'registered_partnership' => '1',
                'widowed' => '4',
            ),
        );
        $rci_mapping = array();
        $rci_p_mapping = array();
        $leadObj = BeanFactory::getBean("Leads", $args['lead_id']);

        /*
         * Getting related latest granted application
         */
        $relatedApp = null;
        if ($leadObj->load_relationship('leads_opportunities_1')) {
            $relatedApps = $leadObj->leads_opportunities_1->getBeans();
            $count = 0;
            $date_entered = '';
            foreach ($relatedApps as $appObj) {
                if ($appObj->provider_status_id_c == "granted") {
                    if ($count == 0) {
                        $relatedApp = $appObj;
                        $date_entered = $appObj->date_entered;
                    } else if (strtotime($appObj->date_entered) > strtotime($date_entered)) {
                        $relatedApp = $appObj;
                        $date_entered = $appObj->date_entered;
                    }
                    $count++;
                }
            }
        }
        /*
         * Kunde - Zivilstand
         */
        $rci_mapping['title'] = $rci_dom_mapping['gender'][$leadObj->dotb_gender_id_c]; //Gender
        $rci_mapping['firstName'] = $leadObj->first_name; //First Name.........
        $rci_mapping['lastName'] = $leadObj->last_name; //Last Name
        $rci_mapping['birthDate'] = self::formateDate($leadObj->birthdate); //Date of Birth
        $rci_mapping['nationality'] = strtoupper($leadObj->dotb_iso_nationality_code_c); //Nationality
        $rci_mapping['languageCode'] = $rci_dom_mapping['lang'][$leadObj->dotb_correspondence_language_c]; //Correspondence Language
        $rci_mapping['datePermis'] = self::formateDate($leadObj->dotb_work_permit_since_c); //Work permit since
        $rci_mapping['permisType'] = $rci_dom_mapping['permit_type'][$leadObj->dotb_work_permit_type_id_c]; //Work permit type Dom
        $rci_mapping['datePermisConduire'] = ''; //Remains empty @RCI
        $rci_mapping['email'] = $leadObj->email1; //Email
        $rci_mapping['receiveRCICommunications'] = 0; //Remains empty @RCI
        /*
         * Kunde - Adresse
         */
        $rci_mapping['adress1'] = $leadObj->primary_address_street; //Primary Address
        $rci_mapping['adress2'] = ''; //Remains empty @RCI
        $rci_mapping['zipCode'] = $leadObj->primary_address_postalcode; //Postal Code
        $rci_mapping['city'] = $leadObj->primary_address_city; //Drop down based on PLZ (ZIP)
        $rci_mapping['country'] = self::rciCountry($leadObj->primary_address_country); //Country Dom
        $rci_mapping['cantonCode'] = ''; //
        $rci_mapping['housePhone'] = ''; //Remains empty @RCI
        $rci_mapping['mobilePhone'] = $leadObj->phone_mobile; //
        $rci_mapping['fax'] = ''; //Remains empty @RCI
        /*
         * Kunde - Wohnsituation
         */
        $no_of_children = self::childrenCount($leadObj->children_birth_years_c);
        $rci_mapping['housingType'] = $rci_dom_mapping['yes_no'][$leadObj->dotb_is_home_owner_c]; //Home owner = no = Mieter / = yes = Inhaber Dom
        $rci_mapping['housingMode'] = $rci_dom_mapping['housing_situation'][$leadObj->dotb_housing_situation_id_c]; //Housing situation Dom
        $rci_mapping['movingDate'] = self::formateDate($leadObj->dotb_resident_since_c); //Resident since
        $rci_mapping['children6Years'] = $no_of_children['ls_6']; //Children birth years  Dom
        $rci_mapping['children0710Years'] = $no_of_children['gt_6_ls_10']; //Children birth years  Dom
        $rci_mapping['children1012Years'] = $no_of_children['gt_10_ls_12']; //Children birth years  Dom
        $rci_mapping['children12Years'] = $no_of_children['gt_12']; //Children birth years  Dom
        /*
         * Kunde - Familiensituation
         */
        $rci_mapping['familySituation'] = $rci_dom_mapping['civil_status'][$leadObj->dotb_civil_status_id_c]; //Dom
        $rci_mapping['codeSocioPro'] = 48; //Value @RCI constantly "Arbeiter" Not found: Tätigkeitsbereich
        $rci_mapping['codeSocioPro'] = 48; //Value @RCI constantly "Arbeiter"
        $rci_mapping['employerName'] = $leadObj->dotb_employer_name_c; //Employer name
        $rci_mapping['workDate'] = self::formateDate($leadObj->dotb_employed_since_c); //Employed since
        $rci_mapping['employerAdress1'] = ''; //Remains empty @RCI
        $rci_mapping['employerAdress2'] = ''; //Remains empty @RCI
        $rci_mapping['employerZipCode'] = ''; //Remains empty @RCI
        $rci_mapping['employerCity'] = $leadObj->dotb_employer_town_c; //Employer town
        $rci_mapping['employerTelephone'] = ''; //Remains empty @RCI
        $rci_mapping['employerFax'] = ''; //Remains empty @RCI
        /*
         * Kunde - Freibetrag
         */
        $rci_mapping['salary'] = $leadObj->dotb_monthly_gross_income_c; //Monthly gross income
        $rci_mapping['salary1213'] = $rci_dom_mapping['yes_no'][$leadObj->dotb_has_thirteenth_salary_c]; //Thirteenth salary 
        $rci_mapping['anOther'] = $leadObj->dotb_second_job_gross_income_c; //Second job gross income
        $rci_mapping['anOther1213'] = $rci_dom_mapping['yes_no'][$leadObj->dotb_second_job_has_13th_c]; //Second job has 13th
        $rci_mapping['gratification'] = ''; //Remains empty @RCI
        $rci_mapping['rental'] = $leadObj->dotb_housing_costs_rent_c; //Housing costs (rent) or loyer
        $rci_mapping['allocationPaid'] = $rci_dom_mapping['yes_no'][$leadObj->dotb_has_alimony_payments_c]; //Aalimony payments Dom
        $rci_mapping['allocationReceived'] = $rci_dom_mapping['yes_no'][$leadObj->dotb_rent_alimony_income_c]; //Rent or Alimony income Dom
        $rci_mapping['healthInsurancePremium'] = $leadObj->dot_health_insurance_premium_c; //Health insurance premium
        $rci_mapping['transportMeans'] = 4; //Value @RCI constantly "Auto"
        $rci_mapping['montantTransport'] = '100.00'; //
        $rci_mapping['medicalInsuranceAmount'] = 100; //Required prameter
        $rci_mapping['creditCharges'] = ''; //Remains empty @RCI
        $rci_mapping['budgetLeasing'] = ''; //Remains empty @RCI
        $rci_mapping['budgetMontant1'] = ''; //Remains empty @RCI
        $rci_mapping['budgetDate1'] = ''; //Remains empty @RCI
        $rci_mapping['budgetMontant2'] = ''; //Remains empty @RCI
        $rci_mapping['budgetDate2'] = ''; //Remains empty @RCI
        $rci_mapping['budgetMontant3'] = ''; //Remains empty @RCI
        $rci_mapping['budgetDate3'] = ''; //Remains empty @RCI
        $rci_mapping['budgetMontant4'] = ''; //Remains empty @RCI
        $rci_mapping['budgetDate4'] = ''; //Remains empty @RCI
        $rci_mapping['debtReplacementAmount'] = ''; //Remains empty @RCI
        $rci_mapping['ecclesiasticalTax'] = 'true'; //Value @RCI constantly "Ja" or "Nein"
        $rci_mapping['withholdingTax'] = ''; //Remains empty @RCI
        $rci_mapping['prosecution'] = 'false'; //Value @RCI constantly "No"
        $rci_mapping['objFinancPurpose'] = $leadObj->credit_usage_type_id_c; //Credit usage Dom


        /*
         * Syncing Teams With Lead's Partner
         */
        if ($leadObj->load_relationship("leads_contacts_1")) {
            $partners = $leadObj->leads_contacts_1->getBeans();
            foreach ($partners as $partner) {
                /*
                 * Kunde - Partner - Zivilstand
                 */
                $rci_p_mapping['title'] = $rci_dom_mapping['gender'][$partner->dotb_gender_id]; //Gender
                $rci_p_mapping['firstName'] = $partner->first_name; //First Name.........
                $rci_p_mapping['lastName'] = $partner->last_name; //Last Name
                $rci_p_mapping['birthDate'] = self::formateDate($partner->birthdate); //Date of Birth
                $rci_p_mapping['nationality'] = strtoupper($partner->dotb_iso_nationality_code); //Nationality
                $rci_p_mapping['datePermis'] = self::formateDate($partner->dotb_work_permit_since); //Work permit since
                $rci_p_mapping['permisType'] = $rci_dom_mapping['permit_type'][$partner->dotb_work_permit_type_id]; //Work permit type Dom
                $rci_p_mapping['datePermisConduire'] = ''; //Remains empty @RCI
                $rci_p_mapping['receiveRCICommunications'] = 0; //Remains empty @RCI


                /*
                 * Kunde - Partner - Adresse
                 */
                $rci_p_mapping['adress1'] = $partner->primary_address_street; //Primary Address
                $rci_p_mapping['zipCode'] = $partner->primary_address_postalcode; //Postal Code
                $rci_p_mapping['city'] = $partner->primary_address_city; //Drop down based on PLZ (ZIP)
                $rci_p_mapping['country'] = self::rciCountry($partner->primary_address_country); //Country Dom
                $rci_p_mapping['mobilePhone'] = $partner->phone_mobile; //
                $rci_p_mapping['fax'] = '';
                /*
                 * Kunde - Partner - Wohnsituation
                 */
                $no_of_children = self::childrenCount($partner->children_birth_years_c);
                $rci_p_mapping['movingDate'] = self::formateDate($partner->dotb_resident_since_c); //Resident since
                $rci_p_mapping['children6Years'] = $no_of_children['ls_6']; //Children birth years  Dom
                $rci_p_mapping['children0710Years'] = $no_of_children['gt_6_ls_10']; //Children birth years  Dom
                $rci_p_mapping['children1012Years'] = $no_of_children['gt_10_ls_12']; //Children birth years  Dom
                $rci_p_mapping['children12Years'] = $no_of_children['gt_12']; //Children birth years  Dom

                /*
                 * Kunde - Partner - Familiensituation
                 */
                $rci_p_mapping['familySituation'] = $rci_dom_mapping['civil_status'][$partner->dotb_civil_status_id_c]; //Dom
                $rci_p_mapping['codeSocioPro'] = 48; //Value @RCI constantly "Arbeiter" Not found: Tätigkeitsbereich
                $rci_p_mapping['employerName'] = $partner->dotb_employer_name_c; //Employer name
                $rci_p_mapping['workDate'] = self::formateDate($partner->dotb_employed_since_c); //Employed since

                /*
                 * Kunde - Freibetrag
                 */
                $rci_p_mapping['salary'] = $partner->dotb_monthly_gross_income_c; //Monthly gross income
                $rci_p_mapping['salary1213'] = $rci_dom_mapping['yes_no'][$partner->dotb_has_thirteenth_salary_c]; //Thirteenth salary 
                $rci_p_mapping['anOther'] = $partner->dotb_second_job_gross_income_c; //Second job gross income
                $rci_p_mapping['anOther1213'] = $rci_dom_mapping['yes_no'][$partner->dotb_second_job_has_13th_c]; //Second job has 13th
                $rci_p_mapping['gratification'] = ''; //Remains empty @RCI
                $rci_p_mapping['allocationPaid'] = $rci_dom_mapping['yes_no'][$partner->dotb_has_alimony_payments_c]; //Aalimony payments Dom
                $rci_p_mapping['allocationReceived'] = $rci_dom_mapping['yes_no'][$partner->dotb_rent_alimony_income_c]; //Rent or Alimony income Dom
                $rci_p_mapping['transportMeans'] = 4; //Value @RCI constantly "Auto"
                $rci_p_mapping['montantTransport'] = '100.-'; //Not found on RCI form
                $rci_p_mapping['medicalInsuranceAmount'] = 100; //Required prameter
                $rci_p_mapping['creditCharges'] = ''; //Remains empty @RCI
                $rci_p_mapping['budgetLeasing'] = ''; //Remains empty @RCI
                $rci_p_mapping['budgetMontant1'] = ''; //Remains empty @RCI
                $rci_p_mapping['budgetDate1'] = ''; //Remains empty @RCI
                $rci_p_mapping['budgetMontant2'] = ''; //Remains empty @RCI
                $rci_p_mapping['budgetDate2'] = ''; //Remains empty @RCI
                $rci_p_mapping['budgetMontant3'] = ''; //Remains empty @RCI
                $rci_p_mapping['budgetDate3'] = ''; //Remains empty @RCI
                $rci_p_mapping['budgetMontant4'] = ''; //Remains empty @RCI
                $rci_p_mapping['budgetDate4'] = ''; //Remains empty @RCI

                break; //Because the business have only one partner
            }
        }
        $rci_xml = '<?xml version="1.0" encoding="UTF-8"?>
                    <PosteVendeur>  
                      <XMLPVTOFINANCE>yes</XMLPVTOFINANCE>
                      <applicationDisplayName>app CREDARIS</applicationDisplayName>
                      <callingSystemId>CREDARIS</callingSystemId>
                      <simulationId>6742-1922</simulationId>  
                      <language>fr</language>
                      <defaultCountry>CH</defaultCountry>

                      <dealerCode>352612000000</dealerCode>
                      <dealerName>Garage Mustermann</dealerName>

                      <salesCode>vendniss</salesCode>
                      <salesName>Salesman Nissan</salesName>';

        /*
         * Finanzierung
         */
        $rci_xml .= "\n<funding>";
        if ($relatedApp) {
            $rci_xml .= "\n<period> $relatedApp->credit_duration_c </period>"; //Credit duration on Application
            $rci_xml .= "\n<codeProduitCommercial> $relatedApp->interest_rate_c </codeProduitCommercial>"; //Interest Rate on Application
            $rci_xml .= "\n<operationAmountTtc> $relatedApp->credit_amount_c </operationAmountTtc>"; //Credit Amount on Application
        } else {
            $rci_xml .= "\n<period></period>"; //Credit duration on Application
            $rci_xml .= "\n<codeProduitCommercial></codeProduitCommercial>"; //Interest Rate on Application
            $rci_xml .= "\n<operationAmountTtc></operationAmountTtc>"; //Credit Amount on Application
        }
        $rci_xml .= "\n<productCode></productCode>"; //Remains empty @RCI
        $rci_xml .= "\n<startDate></startDate>"; //Remains empty @RCI
        $rci_xml .= "\n</funding>";

        /*
         * Preparing Main Customer XML
         */
        $rci_xml .= "\n<ClientElements>";
        $rci_xml .= "\n<clientType>1</clientType>";
        $rci_xml .= "\n<maritalStatus>6</maritalStatus>";
        $rci_xml .= "\n<drivingLicenceType></drivingLicenceType>";
        $rci_xml .= "\n<switzerlandSince></switzerlandSince>";
        $rci_xml .= "\n<drivingLicenceDate></drivingLicenceDate>";
        $rci_xml .= "\n<profession></profession>";
        $rci_xml .= "\n<medicalInsuranceAmount>100</medicalInsuranceAmount>";
        foreach ($rci_mapping as $xml_key => $xml_value) {
            $rci_xml .= "\n<$xml_key>$xml_value</$xml_key>"; //Credit duration on Application
        }
        $rci_xml .= "\n</ClientElements>";

        /*
         * Preparing Partner XML
         */
        $rci_xml .= "\n<PartnerElements>";
        foreach ($rci_p_mapping as $xml_p_key => $xml_p_value) {
            $rci_xml .= "\n<$xml_p_key>$xml_p_value</$xml_p_key>"; //Credit duration on Application
        }
        $rci_xml .= "\n</PartnerElements>";
        /*
         * Closing xml Element
         */
        $rci_xml .= "\n</PosteVendeur>";
        $rci_url = 'https://www.services.rcibanque.com/rci_psxch/site/configexternepv3.do';
        //$rci_url='https://www.services.rcibanque.com/rci_psxch/site/initFinancingLoan.do';
        $rci_form = '<form class="rci_portal_form" action="' . $rci_url . '" method="post" target="blank">
	<input type="submit" value="Soumettre la configuration">
        <textarea rows="30" cols="100" name="XMLPVTOFINANCE">' . $rci_xml . '</textarea>
        </form>';
        return $rci_form;
    }

}
