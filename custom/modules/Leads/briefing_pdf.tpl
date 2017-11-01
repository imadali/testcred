<p><span style="font-size:230px;text-align:center; ">Credaris Kreditantrag</span></p>
<p><b>Kundendaten</b></p>
<table border="1" cellpadding="10" nobr="true">
    <tbody>
        <tr>
            <td>Vorname</td>
            <td>{$leadBasicInfo.first_name}</td>
            <td>Name</td>
            <td>{$leadBasicInfo.last_name}</td>
        </tr>
        <tr>
            <td>Korrespondenzsprache</td>
            <td>{$leadBasicInfo.dotb_correspondence_language_c}</td>
            <td>E-Mail</td>
            <td>{$leadBasicInfo.email1}</td>
        </tr>
        <tr>
            <td>Telefon (mobil)</td>
            <td>{$leadBasicInfo.phone_mobile}</td>
            <td>Telefon (privat)</td>
            <td>{$leadBasicInfo.phone_other}</td>
        </tr>
        <tr>
            <td>Telefon (Geschäft)</td>
            <td></td>
            <td>Geburtsdatum</td>
            <td>{$leadBasicInfo.birthdate}</td>
        </tr>
        <tr>
            <td>Alter</td>
            <td>{$leadBasicInfo.dotb_age_c}</td>
            <td>Geschlecht</td>
            <td>{$leadBasicInfo.dotb_gender_id_c}</td>
        </tr>
        <tr>
            <!-- civil status --- Nationality -->
            <td>Zivilstand</td>
            <td>{$leadBasicInfo.dotb_civil_status_id_c}</td>
            <td>Nationalität</td>
            <td>{$leadBasicInfo.dotb_iso_nationality_code_c}</td>
        </tr>
        <tr>
            <!-- Residence permit --- Residence permit for -->
            <td>Aufenthaltsbewilligung</td>
            <td>{$leadBasicInfo.dotb_work_permit_type_id_c}</td>
            <td>Aufenthaltsbewilligung seit</td>
            <td>{$leadBasicInfo.dotb_work_permit_since_c}</td>
        </tr>
        <tr>
            <!-- Residence permit to ---  -->
            <td>Aufenthaltsbewilligung bis</td>
            <td>{$leadBasicInfo.dotb_work_permit_until_c}</td>
            <td>Adresstyp</td>
            <td>Wohnadresse</td>
        </tr>
        <tr>
            <!-- Address --- extra information -->
            <td>Adresse</td>
            <td>{$leadBasicInfo.primary_address_street}</td>
            <td>Zusatzinformation</td>
            <td>{$leadBasicInfo.address_c_o}</td>
        </tr>
        <tr>
            <!-- Postal Code --- City -->
            <td>PLZ</td>
            <td>{$leadBasicInfo.primary_address_postalcode}</td>
            <td>Ort</td>
            <td>{$leadBasicInfo.primary_address_city}</td>
        </tr>
        <tr>
            <!-- Land --- Resident since -->
            <td>Land</td>
            <td>{$leadBasicInfo.primary_address_country}</td>
            <td>Wohnhaft seit</td>
            <td>{$leadBasicInfo.dotb_resident_since_c}</td>
        </tr>
        <!-- Address -->
        {foreach from=$leadAddresses item="address"}
            <tr>
                <td>ehemalige Addresse</td>
                <td></td>
                <td>Adresse</td>
                <td>{$address.primary_address_street}</td>
            </tr>
            <tr>
                <td>Zusatzinformation</td>
                <td>{$address.address_c_o}</td>
                <td>PLZ</td>
                <td>{$address.postal_code}</td>
            </tr>
            <tr>
                <td>Ort</td>
                <td>{$address.city}</td>
                <td>Land</td>
                <td>{$address.land}</td>
            </tr>
            <tr>
                <td>Wohnhaft bis</td>
                <td>{$address.residence_to}</td>
                <td>Wohnhaft seit</td>
                <td>{$address.residence_since}</td>
            </tr>
        {/foreach}
        <tr>
            <!-- Bank ( Name Bank ) --- Bank (PLZ) -->
            <td>Bankverbindung (Name Bank)</td>
            <td>{$leadBasicInfo.dotb_bank_name_c}</td>
            <td>Bank (PLZ)</td>
            <td>{$leadBasicInfo.dotb_bank_city_name_c}</td>
        </tr>
        <tr>
            <!-- Bank (Location) --- IBAN -->
            <td>Bank (Ort)</td>
            <td>{$leadBasicInfo.dotb_bank_city_name_c}</td>
            <td>IBAN</td>
            <td>{$leadBasicInfo.dotb_iban_c}</td>
        </tr>
        <tr>
            <!-- Desired payment option -->
            <td>Gewünschte Auszahlungsvariante</td>
            <td>{$leadBasicInfo.dotb_payout_option_id_c}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </tbody>
</table>
</br></br>
<p><b>Kreditanfrage</b></p>
<table border="1" cellpadding="10" nobr="true">
    <tr>
        <!-- loan amount --- Duration in months -->
        <td>Kreditsumme</td>
        <td>{$app.credit_amount_c}</td>
        <td>Laufzeit in Monaten</td>
        <td>{$app.credit_duration_c}</td>
    </tr>
    <tr>
        <!-- PPI --- Usage -->
        <td>PPI</td>
        <td>{$app.ppi_c}</td>
        <td>Verwendungszweck</td>
        <td></td>
    </tr>
    <tr>
        <!--  --- -->
        <td>Bank</td>
        <td>{$app.provider_id_c}</td>
        <td>Offener Betrag</td>
        <td>
            <table>
                {foreach from=$creditHistories item="history"}
                    <tr>
                        <td>{$history.credit_balance|string_format:"%.2f"}</td>
                    </tr>
                {/foreach}
            </table>
        </td>
    </tr>
    <tr>
        <!--  ---  -->
        <td>Monatliche Rate</td>
        <td>
            <table>
                {foreach from=$creditHistories item="history"}
                    <tr>
                        <td>{$history.monthly_credit_rate|string_format:"%.2f"}</td>
                    </tr>
                {/foreach}
            </table>
        </td>
        <td>Bestehen aktuell Betreibungen?</td>
        <td></td>
    </tr>
</table>

</br></br>
<p><b>Arbeitgeber und Einkommen</b></p>
<table border="1" cellpadding="10" nobr="true">
    <tr>
        <!-- Employment type --- Pensioner -->
        <td>Berufliche Situation im Haupterwerb</td>
        <td>{$leadBasicInfo.dotb_employment_type_id_c}</td>
        <td>Rentner(in)</td>
        <td>{$leadBasicInfo.dotb_is_pensioner_c}</td>
    </tr>
    <tr>
        <!-- Partner Income? --- Employer name -->
        <td>Partnereinkommen?</td>
        <td>{$leadBasicInfo.dotb_partner_agreement_c}</td>
        <td>Arbeitgeber, Name</td>
        <td>{$leadBasicInfo.dotb_employer_name_c}</td>
    </tr>
    <tr>
        <!-- Employers NPA --- Employer town -->
        <td>Arbeitgeber, PLZ</td>
        <td>{$leadBasicInfo.dotb_employer_npa_c}</td>
        <td>Arbeitgeber, Ort</td>
        <td>{$leadBasicInfo.dotb_employer_town_c}</td>
    </tr>
    <tr>
        <!-- Probation period --- Monthly net income) -->
        <td>Ist in Probezeit</td>
        <td>{$leadBasicInfo.dotb_is_in_probation_period_c}</td>
        <td>Haupterwerb (netto, pro Monat)</td>
        <td>{$leadBasicInfo.dotb_monthly_net_income_c|string_format:"%.2f"}</td>
    </tr>
    <tr>
        <!-- Monthly gross income --- Thirteenth Salary -->
        <td>Haupterwerb (brutto, pro Monat)</td>
        <td>{$leadBasicInfo.dotb_monthly_gross_income_c}</td>
        <td>13. Monatslohn?</td>
        <td>{$leadBasicInfo.dotb_has_thirteenth_salary_c}</td>
    </tr>
    <tr>
        <!--  --- Direct withholding tax -->
        <td>Bonus – Gratifikation</td>
        <td>{$leadBasicInfo.dotb_bonus_gratuity_c}</td>
        <td>Direkte Quellensteuer</td>
        <td></td>
    </tr>
    <tr>
        <!-- Second Job --- Sideline , Second Job Description -->
        <td>Nebenerwerb</td>
        <td>{$leadBasicInfo.dotb_has_second_job_c}</td>
        <td>Nebenerwerb, Beschreibung</td>
        <td>{$leadBasicInfo.dotb_second_job_description_c}</td>
    </tr>
    <tr>
        <!-- Second Job employer name --- Sideline employer ZIP -->
        <td>Nebenerwerbs-Arbeitgeber, Name</td>
        <td>{$leadBasicInfo.dot_second_job_employer_name_c}</td>
        <td>Nebenerwerbs-Arbeitgeber, PLZ</td>
        <td>{$leadBasicInfo.dotb_second_job_employer_npa_c}</td>
    </tr>
    <tr>
        <!-- Sideline employer City --- Sideline ( net, per month ) -->
        <td>Nebenerwerbs-Arbeitgeber, Ort</td>
        <td>{$leadBasicInfo.dot_second_job_employer_town_c}</td>
        <td>Nebenerwerb (netto, pro Monat)</td>
        <td>{$leadBasicInfo.dotb_monthly_net_income_nb_c}</td>
    </tr>
    <tr>
        <!-- Sideline ( gross per month ) ---  -->
        <td>Nebenerwerb (brutto, pro Monat)</td>
        <td>{$leadBasicInfo.dotb_second_job_gross_income_c|string_format:"%.2f"}</td>
        <td>Nebenerwerb, 13. Monatslohn</td>
        <td>{$leadBasicInfo.dotb_second_job_has_13th_c}</td>
    </tr>
    <tr>
        <!-- Additional assistance ( for example, alimony , widow's pension , rental income ) --- Additional income , Description -->
        <td>Zusatzerwerb (z.B. Alimente, Witwenrente, Mieteinnahmen)</td>
        <td>{$leadBasicInfo.dotb_rent_alimony_income_c}</td>
        <td>Zusatzeinkommen, Beschreibung</td>
        <td>{$leadBasicInfo.dotb_additional_income_desc_c}</td>
    </tr>
    <tr>
        <!-- Additional income per month (gross ) -->
        <td>Zusatzeinkommen pro Monat (brutto)</td>
        <td>{$leadBasicInfo.dotb_rent_or_alimony_income_c|string_format:"%.2f"}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
</br></br>
<p><b>Informationen zum Partner</b></p>
<table border="1" cellpadding="10" nobr="true">
    <tr>
        <!-- First Name --- Last Name -->
        <td>Vorname</td>
        <td>{$partnerInfo.first_name}</td>
        <td>Name</td>
        <td>{$partnerInfo.last_name}</td>
    </tr>
    <tr>
        <!-- date of birth --- nationality -->
        <td>Geburtsdatum</td>
        <td>{$partnerInfo.birthdate}</td>
        <td>Nationalität</td>
        <td>{$partnerInfo.dotb_iso_nationality_code}</td>
    </tr>
    <tr>
        <!-- Work Permot type --- Pensioner -->
        <td>Aufenthaltsbewilligung</td>
        <td>{$partnerInfo.dotb_work_permit_type_id}</td>
        <td>Aufenthaltsbewilligung seit</td>
        <td>{$partnerInfo.dotb_work_permit_since}</td>
    </tr>
    <tr>
        <td>Aufenthaltsbewilligung bis</td>
        <td>{$partnerInfo.dotb_work_permit_until}</td>
        <td>Berufliche Situation im Haupterwerb</td>
        <td>{$partnerInfo.dotb_employment_type_id}</td>
    </tr>
    <tr>
        <!-- Pensioner --- Pensioner -->
        <td>Rentner(in)</td>
        <td>{$partnerInfo.dotb_is_pensioner}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Arbeitgeber, Name</td>
        <td>{$partnerInfo.dotb_employer_name}</td>
        <td>Arbeitgeber, PLZ</td>
        <td>{$partnerInfo.dotb_employer_npa}</td>
    </tr>
    <tr>
        <td>Arbeitgeber, Ort</td>
        <td>{$partnerInfo.dotb_employer_town}</td>
        <td>Ist in Probezeit</td>
        <td>{$partnerInfo.dotb_is_in_probation_period}</td>
    </tr>
    <tr>
        <td>Haupterwerb (netto, pro Monat)</td>
        <td>{$partnerInfo.dotb_monthly_net_income}</td>
        <td>Hauperwerb (brutto, pro Monat)</td>
        <td>{$partnerInfo.dotb_monthly_gross_income}</td>
    </tr>
    <tr>
        <td>13. Monatslohn?</td>
        <td>{$partnerInfo.dotb_has_thirteenth_salary}</td>
        <td>Nebenwerb</td>
        <td>{$partnerInfo.dotb_has_second_job}</td>
    </tr>
    <tr>
        <td>Nebenerwerb, Beschreibung</td>
        <td>{$partnerInfo.dotb_second_job_description}</td>
        <td>Nebenerwerbs-Arbeitgeber, Name</td>
        <td>{$partnerInfo.dotb_second_job_employer_name}</td>
    </tr>
    <tr>
        <td>Nebenerwerbs-Arbeitgeber, PLZ</td>
        <td>{$partnerInfo.dotb_second_job_employer_npa}</td>
        <td>Nebenerwerbs-Arbeitgeber, Ort</td>
        <td>{$partnerInfo.dotb_second_job_employer_town}</td>
    </tr>
    <tr>
        <td>Nebenerwerb (netto, pro Monat)</td>
        <td>{$partnerInfo.dotb_monthly_net_income_nb_c}</td>
        <td>Nebenerwerb (brutto, pro Monat)</td>
        <td>{$partnerInfo.dotb_second_job_gross_income_c}</td>
    </tr>
    <tr>
        <td>Nebenerwerb, 13. Monatslohn</td>
        <td>{$partnerInfo.dotb_second_job_has_13th}</td>
        <td>Bonus – Gratifikation</td>
        <td>{$partnerInfo.dotb_bonus_gratuity_c}</td>
    </tr>
    <tr>
        <td>Zusatzerwerb (z.B. Alimente, Witwenrente, Mieteinnahmen)</td>
        <td>{$partnerInfo.dotb_rent_alimony_income_c}</td>
        <td>Zusatzeinkommen, Beschreibung</td>
        <td>{$partnerInfo.dotb_additional_income_desc}</td>
    </tr>
    <tr>
        <td>Zusatzeinkommen pro Monat (brutto)</td>
        <td>{$partnerInfo.dotb_rent_or_alimony_income}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>

</br></br>
<p><b>Wohnen | Ausgaben | Kinder</b></p>
<table border="1" cellpadding="10" nobr="true">
    <tr>
        <td>Wohnverhältnis</td>
        <td>{$leadBasicInfo.dotb_housing_situation_id_c}</td>
        <td>Eigenheimbesitzer</td>
        <td>{$leadBasicInfo.dotb_is_home_owner_c}</td>
    </tr>
    <tr>
        <td>Hypothekarbetrag</td>
        <td></td>
        <td>Effektive Wohnkosten</td>
        <td>{$leadBasicInfo.dotb_housing_costs_rent_c}</td>
    </tr>
    <tr>
        <td>Mietsplitting</td>
        <td></td>
        <td>Krankenkassenprämie</td>
        <td>{$leadBasicInfo.dot_health_insurance_premium_c|string_format:"%.2f"}</td>
    </tr>
    <tr>
        <td>Prämienverbilligung</td>
        <td>{$leadBasicInfo.dotb_has_prem}</td>
        <td>Alimente / Unterhaltszahlungen</td>
        <td>{$leadBasicInfo.dotb_has_alimony_payments_c}</td>
    </tr>
    <tr>
        <td>Alimente / Unterhaltszahlungen (Betrag)</td>
        <td>{$leadBasicInfo.dotb_has_alimony_payments_c}</td>
        <td>Sonstige Ausgaben</td>
        <td>{$leadBasicInfo.dotb_additional_expenses_c|string_format:"%.2f"}</td>
    </tr>
    <tr>
        <td>Anzahl Kinder im gleichen Haushalt</td>
        <td>{$leadBasicInfo.no_of_dependent_children_c}</td>
        <td>Geburtsjahre Kinder</td>
        <td>{$leadBasicInfo.children_birth_years_c}</td>
    </tr>
</table>