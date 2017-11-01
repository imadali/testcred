// plugin namespace
var K_EvalancheConnectorPluginContacts = {};

// AJAX settings
K_EvalancheConnectorPluginContacts.oAJAX = YAHOO.util.Connect;
K_EvalancheConnectorPluginContacts.oAJAX.url = 'index.php';
K_EvalancheConnectorPluginContacts.oAJAX.method = 'GET';
K_EvalancheConnectorPluginContacts.oAJAX.timeout = 300000;


K_EvalancheConnectorPluginContacts.addRow = function(sEVA, sSugar, bEVA, bSugar) {

	var oTable = document.getElementById('k_tbl_mapping'),
	    iRows = oTable.rows.length,
	    oRow = oTable.insertRow(iRows),
	    oSugarModuleFields = K_EvalancheConnectorPluginContacts.oSugarModuleFields,
	    oEvalancheFields = K_EvalancheConnectorPluginContacts.oEvalancheFields,
	    sOptionsSug = K_EvalancheConnectorPluginContacts.sSugarOptions,
	    sOptionsEva = K_EvalancheConnectorPluginContacts.sEvalancheOptions;

	for(var sProp in oSugarModuleFields) {

		if(sProp == sSugar) {
			sOptionsSug += '<option selected value="' + sProp + '">' + oSugarModuleFields[sProp] + '</option>';
		} else {
			sOptionsSug += '<option value="' + sProp + '">' + oSugarModuleFields[sProp] + '</option>';
		}
	}

	for(var sProp in oEvalancheFields) {

		if(sProp == sEVA) {
			sOptionsEva += '<option selected value="' + sProp + '">' + oEvalancheFields[sProp] + '</option>';
		} else {
			sOptionsEva += '<option value="' + sProp + '">' + oEvalancheFields[sProp] + '</option>';
		}
	}

	// column "number"
	var oCol = oRow.insertCell(0);
	oCol.innerHTML = '#' + iRows;
	oCol.style.cssText = 'vertical-align:middle;';

	// column "Sugar"        
	var oCol = oRow.insertCell(1);
	var elInput = document.createElement("select");
	elInput.size = 1;
	elInput.name = "kec_mapping_crm_name[" + (iRows - 1) + "]";
	elInput.style.width = '300px';
	oCol.appendChild(elInput);
	elInput.innerHTML = sOptionsSug;
	
	// column "EVALANCHE"        
	var oCol = oRow.insertCell(2);
	var elInput = document.createElement("select");
	elInput.size = 1;
	elInput.name = "kec_mapping_eva_name[" + (iRows - 1) + "]";
	elInput.style.width = '300px';
	oCol.appendChild(elInput);
	elInput.innerHTML = sOptionsEva;
	
	// column "Sync to EVALANCHE"        
	var oCol = oRow.insertCell(3);
	oCol.style.textAlign = "center";
	var elInput = document.createElement("input");
	elInput.type = "checkbox";
	elInput.name = "kec_mapping_eva_sync[" + (iRows - 1) + "]";
	elInput.value = '1';
	elInput.checked = (bEVA == 1) ? true : false;
	oCol.appendChild(elInput);
	
	// column "Sync to EVALANCHE"        
	var oCol = oRow.insertCell(4);
	oCol.style.textAlign = "center";
	var elInput = document.createElement("input");
	elInput.type = "checkbox";
	elInput.name = "kec_mapping_crm_sync[" + (iRows - 1) + "]";
	elInput.value = '1';
	elInput.checked = (bSugar == 1) ? true : false;
	oCol.appendChild(elInput);
	
	// column "remove"
	var oCol = oRow.insertCell(5);
	
	
	oCol.innerHTML = '<a href="javascript:void(0);" onClick="K_EvalancheConnectorPluginContacts.removeRow(this);">' + SUGAR.language.get('K_EvalancheConnector', 'LBL_FORM_MAPPING_REM_BUTTON') + '</a>';
	oCol.style.cssText = 'vertical-align:middle;';

}

K_EvalancheConnectorPluginContacts.removeRow = function(oHref) {

	var oTable = document.getElementById('k_tbl_mapping'),
	oRow = oHref.parentNode.parentNode,
	iIndex = oRow.rowIndex;

	if(oTable.rows[iIndex]) {
		oTable.deleteRow(iIndex);
	}

	// re-number all items + rename elements
	for(var i=1, s=oTable.rows.length; i < s; i++) {
	
		var oRow = oTable.rows[i];
		oRow.cells[0].innerHTML = '#' + i;
		oRow.cells[1].firstChild.name = "kec_mapping_crm_name[" + (i - 1) + "]";
		oRow.cells[2].firstChild.name = "kec_mapping_eva_name[" + (i - 1) + "]";
		oRow.cells[3].firstChild.name = "kec_mapping_crm_sync[" + (i - 1) + "]";
		oRow.cells[4].firstChild.name = "kec_mapping_eva_sync[" + (i - 1) + "]";
	
	}

}


K_EvalancheConnectorPluginContacts.submitForm = function() {

	ajaxStatus.showStatus('Speichervorgang gestartet');

	var oForm = document.forms['kinamuAdminSingleForm'];
	var oFormValues = {};
	
	for(var i=0, s=oForm.elements.length; i < s; i++) {
	
		var oElem = oForm.elements[i];
	
		if(oElem.type && ((oElem.type.toLowerCase() == "button") || (oElem.type.toLowerCase() == "submit"))) {
			continue;
		}
	
		if(oElem.type && (oElem.type.toLowerCase() == "checkbox")) {
			oFormValues[oElem.name] = (oElem.checked) ? 1 : 0;
		} else {
			oFormValues[oElem.name] = encodeURIComponent(oElem.value);
		}
	
	}

        /*
	K_EvalancheConnectorPluginContacts.oAJAX.asyncRequest(
		K_EvalancheConnectorPluginContacts.method, 
		K_EvalancheConnectorPluginContacts.url,
		{success: SUGAR.saveCallBack},
		SUGAR.util.paramsToUrl(oFormValues)
	); */
    
        $.ajax({
            type: K_EvalancheConnectorPluginContacts.oAJAX.method,
            url:  K_EvalancheConnectorPluginContacts.oAJAX.url,
            data: SUGAR.util.paramsToUrl(oFormValues)+'&task=config&todo=saveCFG'
        }).success(function(o) {
          SUGAR.saveCallBack(o);
        });    
	
	return true;

}

SUGAR.saveCallBack = function(o) {

	ajaxStatus.showStatus('Speichervorgang abgeschlossen');

	if (o.responseText == "true") {
            window.location.assign("index.php?module=Administration&action=index{$sAnchorToAdminPanel}");
	} else {
            // alert(o.responseText);
            SUGAR.MessageBox.show({msg:'o.responseText'});
	}
}