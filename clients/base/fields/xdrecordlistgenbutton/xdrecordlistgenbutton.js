/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
({
    extendsFrom: 'RowactionField',
    
    initialize: function (options){
        this._super('initialize', [options]);
        this.type = 'rowaction';
    },
    
    /**
     * Event to trigger xperidpo_generate_document
     */
    rowActionSelect: function(){

					this.xperido_list_generate_button();

    },
	
	xperido_build_filter: function() {
		var filter = "",
			length = 0;
		
		var mass_collection = this.context.get('mass_collection');
		if (mass_collection) {
			length = mass_collection.length;
			if (!mass_collection.entire) {
				filter = mass_collection.models.map(function(m) { return m.id; })
					.reduce(
						function(acc, val, index) { 
							return acc + "filter[0][$or][" +index + "][id]=" + val +"&"; 
						}, "");
			}
		} else {
			var TRs = $('input[checked="checked"]').closest('tr');
			if(TRs != null){
				for (var index = 0; index < TRs.length; index++) {
					if($(TRs[index]).hasClass('single'))
					{
						if ($(TRs[index]).attr('name').indexOf(module) == 0) {
							//IDs += $(TRs[index]).attr('name').substr(module.length + 1) + ',';
							filter += "filter[0][$or][" + index +"][id]=" + $(TRs[index]).attr('name').substr(module.length + 1) + "&"; 
						}
					}
				}
				length = TRs.length;
			}
		}
		
		return { 'url': filter, 'length': length };
	},
    
    xperido_list_generate_button: function() {
        //example of getting field data from current record
        
        //debugger;
        var url = null,
            body = null;
        
        var language = app.user.attributes.preferences.language;
		switch (language.toLowerCase())
		{
			case 'en_us':
			case 'nl_nl':
			case 'fr_fr':
			case 'de_de':
				break;
			default:
				language = 'en_us';
				break;
		}
        var module = this.module; //window.location['hash'].substr(1);
        var userid = app.user.id;
		var isAdmin = false;
		if(app.user.attributes.type.toLowerCase() == "admin")
			isAdmin = true;

        var IDs='';
        var filter = this.xperido_build_filter();
        
		url = app.api.buildURL(this.module +'?' + filter.url +'fields=id,name&offset=0&max_num='+ filter.length);
		app.api.call('read', url, body, {
			success: function(data){
				IDs = "";
				$.each(data.records, function(index, record){
					IDs += record.id + "|" + record.name + "_XDSPLIT_";                        
				});
	
				url = app.api.buildURL('XperiDo/has_xperidorole?currentUser=' + app.user.id);

				app.api.call('read', url, body, {
					success: function(data){
						if(isAdmin || data)
						{
								url = app.api.buildURL('XperiDo/active_con');

								app.api.call('read', url, body, {
									success: function(data){
										if(data == null || data == "undefined" || data == "")
										{
											var msgDefault = "No active connection set, please set an active connection in the XperiDo_Connections module";
											var msgNL = "Geen actieve verbinding ingesteld , stel een actieve verbinding in de XperiDo_Connections module";
											var msgDE = "Keine aktive Verbindung gesetzt, setzen Sie bitte eine aktive Verbindung im XperiDo_Connections Modul";
											var msgFR = "Aucun jeu de connexion active , s'il vous plaît mis une connexion active dans le module XperiDo_Connections";
											switch (language.toLowerCase())
											{
												case 'en_us':
												alert(msgDefault);
												break;
												case 'nl_nl':
												alert(msgNL);
												break;
												case 'fr_fr':
												alert(msgFR);
												break;
												case 'de_de':
												alert(msgNL);
												break;	
												default:
												alert(msgDefault);
												break;							
											}
										   
										}
										else
										{
											var json = data;
											$.getScript("./custom/xperido/invenso_xperido.js",function()
											{
												_invenso_xperido_openXperiDoPage(json[0].id,'Document',module,'',IDs,'','',language,'_blank',json[0].xperidoconfigurationurl,json[0].xperidoserviceurl,userid);
											});
										}
									}
								});
						}
						else
						{
							var msgDefault = 'Only users with the XperiDo security role are allowed to generate a document!';
							var msgNL = 'Alleen gebruikers met de XperiDo security rol mogen een document te genereren!';
							var msgDE = 'Nur Benutzer mit der XperiDo Sicherheitsrolle sind erlaubt , um ein Dokument zu generieren!';
							var msgFR = 'Seuls les utilisateurs ayant le rôle de la sécurité XperiDo sont autorisés à générer un document!';
							switch (SUGAR.App.user.attributes.preferences.language.toLowerCase())
							{
								case 'en_us':
									alert(msgDefault);
									break;
								case 'nl_nl':
									alert(msgNL);
									break;
								case 'fr_fr':
									alert(msgFR);
									break;
								case 'de_de':
									alert(msgNL);
									break;	
								default:
									alert(msgDefault);
									break;							
							}
							
						}
					}
				});
				
			}
		})    
    },
})


