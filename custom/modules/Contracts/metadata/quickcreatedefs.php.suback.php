<?php
$viewdefs['Contracts'] = 
array (
  'QuickCreate' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'SAVE',
          1 => 'CANCEL',
        ),
      ),
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
        'LBL_PANEL_ASSIGNMENT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'lbl_contract_information' => 
      array (
        0 => 
        array (
          0 => 'name',
        ),
        1 => 
        array (
          0 => 'reference_code',
          1 => 
          array (
            'name' => 'start_date',
            'displayParams' => 
            array (
              'showFormats' => true,
            ),
          ),
        ),
        2 => 
        array (
          0 => 'account_name',
          1 => 
          array (
            'name' => 'end_date',
            'displayParams' => 
            array (
              'showFormats' => true,
            ),
          ),
        ),
        3 => 
        array (
          0 => 'opportunity_name',
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'provider_id_c',
            'label' => 'LBL_PROVIDER_ID',
          ),
          1 => 
          array (
            'name' => 'credit_amount_c',
            'label' => 'LBL_CREDIT_AMOUNT',
          ),
        ),
        5 => 
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
        6 => 
        array (
          0 => 
          array (
            'name' => 'promo_bonus_c',
            'label' => 'LBL_PROMO_BONUS',
          ),
          1 => 
          array (
            'name' => 'contract_complete_c',
            'label' => 'LBL_CONTRACT_COMPLETE',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'paying_date_c',
            'label' => 'LBL_PAYING_DATE',
          ),
          1 => 
          array (
            'name' => 'provision_confirmed_c',
            'label' => 'LBL_PROVISION_CONFIRMED',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'interest_rate_c',
            'label' => 'LBL_INTEREST_RATE',
          ),
          1 => 
          array (
            'name' => 'storno_c',
            'label' => 'LBL_STORNO',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'storno_date_c',
            'label' => 'LBL_STORNO_DATE',
          ),
          1 => 
          array (
            'name' => 'ppi_provision_c',
            'label' => 'LBL_PPI_PROVISION',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'ppi_c',
            'label' => 'LBL_PPI',
          ),
          1 => 
          array (
            'name' => 'payment_option_id_c',
            'label' => 'LBL_PAYMENT_OPTION_ID',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'description',
          ),
        ),
      ),
      'LBL_PANEL_ASSIGNMENT' => 
      array (
        0 => 
        array (
          0 => 'assigned_user_name',
          1 => 
          array (
            'name' => 'team_name',
            'displayParams' => 
            array (
              'required' => true,
            ),
          ),
        ),
      ),
    ),
  ),
);
