<?php
$viewdefs['Contracts'] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'javascript' => '<script type="text/javascript" language="javascript">
	function setvalue(source)  {ldelim} 
		src= new String(source.value);
		target=new String(source.form.name.value);

		if (target.length == 0)  {ldelim} 
			lastindex=src.lastIndexOf("\\"");
			if (lastindex == -1)  {ldelim} 
				lastindex=src.lastIndexOf("\\\\\\"");
			 {rdelim}  
			if (lastindex == -1)  {ldelim} 
				source.form.name.value=src;
				source.form.escaped_name.value = src;
			 {rdelim}  else  {ldelim} 
				source.form.name.value=src.substr(++lastindex, src.length);
				source.form.escaped_name.value = src.substr(lastindex, src.length);
			 {rdelim} 	
		 {rdelim} 			
	 {rdelim} 

	function set_expiration_notice_values(form)  {ldelim} 
		if (form.expiration_notice_flag.checked)  {ldelim} 
			form.expiration_notice_flag.value = "on";
			form.expiration_notice_date.value = "";
			form.expiration_notice_time.value = "";
			form.expiration_notice_date.readonly = true;
			form.expiration_notice_time.readonly = true;
			if(typeof(form.due_meridiem) != \'undefined\')  {ldelim} 
				form.due_meridiem.disabled = true;
			 {rdelim} 
			
		 {rdelim}  else  {ldelim} 
			form.expiration_notice_flag.value="off";
			form.expiration_notice_date.readOnly = false;
			form.expiration_notice_time.readOnly = false;
			
			if(typeof(form.due_meridiem) != \'undefined\')  {ldelim} 
				form.due_meridiem.disabled = false;
			 {rdelim} 
			
		 {rdelim} 
	 {rdelim} 
</script>',
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_CONTRACT_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'lbl_contract_information' => 
      array (
        0 => 
        array (
          0 => 'name',
          1 => 
          array (
            'name' => 'date_entered',
            'comment' => 'Date record created',
            'studio' => 
            array (
              'portaleditview' => false,
            ),
            'readonly' => true,
            'label' => 'LBL_DATE_ENTERED',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'date_modified',
            'comment' => 'Date record last modified',
            'studio' => 
            array (
              'portaleditview' => false,
            ),
            'readonly' => true,
            'label' => 'LBL_DATE_MODIFIED',
          ),
          1 => 
          array (
            'name' => 'provider_id_c',
            'label' => 'LBL_PROVIDER_ID',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'credit_amount_c',
            'label' => 'LBL_CREDIT_AMOUNT',
          ),
          1 => 
          array (
            'name' => 'interest_rate_c',
            'label' => 'LBL_INTEREST_RATE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'credit_duration_c',
            'label' => 'LBL_CREDIT_DURATION',
          ),
          1 => 
          array (
            'name' => 'contract_date_c',
            'label' => 'LBL_CONTRACT_DATE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'provision_c',
            'label' => 'LBL_PROVISION',
          ),
          1 => 
          array (
            'name' => 'bestand_bonus_c',
            'label' => 'LBL_BESTAND_BONUS',
          ),
        ),
        5 => 
        array (
          0 => '',
          1 => 
          array (
            'name' => 'provider_contract_no',
            'label' => 'LBL_PROVIDER_CONTRACT_NUMBER',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'promo_bonus_c',
            'label' => 'LBL_PROMO_BONUS',
          ),
          1 => 
          array (
            'name' => 'ppi_c',
            'label' => 'LBL_PPI',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'ppi_provision_c',
            'label' => 'LBL_PPI_PROVISION',
          ),
          1 => 'assigned_user_name',
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'contract_complete_c',
            'label' => 'LBL_CONTRACT_COMPLETE',
          ),
          1 => 
          array (
            'name' => 'paying_date_c',
            'label' => 'LBL_PAYING_DATE',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'provision_confirmed_c',
            'label' => 'LBL_PROVISION_CONFIRMED',
          ),
          1 => 
          array (
            'name' => 'storno_c',
            'label' => 'LBL_STORNO',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'storno_date_c',
            'label' => 'LBL_STORNO_DATE',
          ),
          1 => 
          array (
            'name' => 'payment_option_id_c',
            'label' => 'LBL_PAYMENT_OPTION_ID',
          ),
        ),
        11 => 
        array (
          0 => 'opportunity_name',
          1 => 
          array (
            'name' => 'contracts_leads_1_name',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'dotb_soko_c',
          ),
          1 => 
          array (
            'name' => 'contacts_contracts_1_name',
            'label' => 'LBL_CONTACTS_CONTRACTS_1_FROM_CONTACTS_TITLE',
          ),
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'team_name',
            'displayParams' => 
            array (
              'required' => true,
            ),
          ),
          1 => 
          array (
            'name' => 'lead_first_name',
            'label' => 'LBL_LEAD_NAME',
          ),
        ),
        14 => 
        array (
          0 => 
          array (
            'related_fields' => 
            array (
              0 => 'currency_id',
              1 => 'base_rate',
            ),
            'name' => 'basic_commission_agent',
            'label' => 'LBL_BASIC_COMMISSION_AGENT',
          ),
          1 => 
          array (
            'name' => 'basic_payout_date',
            'label' => 'LBL_BASIC_PAYOUT_DATE',
            'comment' => 'Date Field Comment',
          ),
        ),
        15 => 
        array (
          0 => 
          array (
            'related_fields' => 
            array (
              0 => 'currency_id',
              1 => 'base_rate',
            ),
            'name' => 'volume_commission_agent',
            'label' => 'LBL_VOLUME_COMMISSION_AGENT',
          ),
          1 => 
          array (
            'name' => 'volume_payout_date',
            'label' => 'LBL_VOLUME_PAYOUT_DATE',
            'comment' => 'Date Field Comment',
          ),
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'related_fields' => 
            array (
              0 => 'currency_id',
              1 => 'base_rate',
            ),
            'name' => 'customer_credit_amount_c',
            'label' => 'LBL_CUSTOMER_CREDIT_AMOUNT',
          ),
          1 => 
          array (
            'name' => 'customer_credit_duration_c',
            'label' => 'LBL_CREDIT_DURATION',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'customer_interest_rate_c',
            'label' => 'LBL_INTEREST_RATE',
          ),
          1 => 
          array (
            'name' => 'customer_ppi_c',
            'label' => 'LBL_PPI',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'contract_ppi_plus',
            'label' => 'LBL_CONTRACT_PPI_PLUS',
          ),
          1 => 
          array (
            'name' => 'credit_card_commission',
            'label' => 'LBL_CREDDIT_CARD_COMMISSION',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'contract_transfer_fee',
            'label' => 'LBL_CONTRACT_TRANSFER_FEE',
          ),
        ),
      ),
    ),
  ),
);
