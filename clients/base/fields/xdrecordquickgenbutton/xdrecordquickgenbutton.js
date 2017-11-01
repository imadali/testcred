/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
({
    extendsFrom: 'RowactionField',
    
    initialize: function(options){
        this._super('initialize', [options]);
        this.type = 'rowaction';
    },
    
    /**
     * Event to trigger xperidpo_generate_document
     */
    rowActionSelect: function(){

					this.xperido_quickgenerate_button();

    },
    
    xperido_quickgenerate_button: function() {
        //debugger;
        var url = null,
			body = null,
            urlGenDoc = null;
        
        var quickGenerate = $('a[name ="xperido_quickgenerate_button"]').parent();
           
        var ID = this.model.get('id');
        var nameFromModel = "";
        try{
            nameFromModel = this.model.get('name');
            ID += "|" + nameFromModel;
        }
        catch(err){             
        }
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
        var module = this.module;
        var userid = app.user.id;
		var isAdmin = false;
		if(app.user.attributes.type.toLowerCase() == "admin")
			isAdmin = true;
		url = app.api.buildURL('XperiDo/has_xperidorole?currentUser=' + app.user.id);

        app.api.call('read', url, body, {
            success: function(data){
                if(isAdmin || data)
				{
					if($("#quickGenerateMenu").length != 0)
					{
						$("#quickGenerateMenu").remove();
					}

					$(quickGenerate).closest($('.actions.btn-group.detail')).addClass('open'); 
					var objDiv = $('<div id="quickGenerateMenu" style="display:block;" ></div>');
					var objUl = $('<ul class="dropdown-inset" style="display:block;"></ul>');

					url = app.api.buildURL('XperiDo/get_Templates');

					app.api.call('read', url, body, {
						success: function(data){
							var json = data;
																						   
								for(var index = 0; index < json.length ; index++)
								{
									var invensoValue = JSON.parse(json[index].invenso_value);
									if(invensoValue.Entity.toUpperCase() != module.toUpperCase())
										continue;
									if(invensoValue.ExcludeFromXperidoFlyoutMenu)
										continue;
									
									// Check if current user is in the correct Team
									var authTeams = invensoValue.AuthorizedTeams;
									var userInTeams = SUGAR.App.user.attributes.my_teams;
									var isUserInTeams = false;
									var hasNoTeamsPresent = false;
									if (authTeams != null) {
									  for (var indexAuthTeams = 0; indexAuthTeams < authTeams.$values.length; indexAuthTeams++) {
										for (var indexUserInTeams = 0; indexUserInTeams < userInTeams.length; indexUserInTeams++) {
										  if (authTeams.$values[indexAuthTeams].toLowerCase() == userInTeams[indexUserInTeams].id) {
											isUserInTeams = true;
											break;
										  }
										}
										if (isUserInTeams) {
										  break;
										}
									  }
									} else {
									  hasNoTeamsPresent = true;
									}						
									
									if (isUserInTeams || hasNoTeamsPresent) {						
										if(invensoValue.IsDynamicFieldsActive && invensoValue.IsRequiredAndHasNoDefaultValue)
											continue;
										var objLi = $('<li></li>');
										var objA = $('<a></a>');
										
										objA.attr('tabindex','-1');   
										objA.attr('id', 'QuickGen_' + invensoValue.TemplateName);
										objA.attr('href','javascript:void(0)');
										objA.attr('fullTemplateName',invensoValue.TemplateFullName)
										if(invensoValue.Document == null || invensoValue.Document == "undefined")
											objA.text(invensoValue.TemplateName);
										else	
											objA.text(invensoValue.Document);

										objLi.append(objA);
										objUl.append(objLi);
									}
									else
									{
										continue;
									}
									
								}
								objDiv.append(objUl);
								$(quickGenerate).append(objDiv);
											  
								$(quickGenerate).closest($('.actions.btn-group.detail')).addClass('open');
								
								urlGenDoc = app.api.buildURL('XperiDo/active_con');

								var xperidocrminstance;
								var xperidoconfigurationurl;
								var xperidoserviceurl;
								app.api.call('read', urlGenDoc, body, {
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
                                            var jsonConn = data;
                                            xperidocrminstance =jsonConn[0].id;
                                            xperidoconfigurationurl =jsonConn[0].xperidoconfigurationurl;
                                            xperidoserviceurl =jsonConn[0].xperidoserviceurl;

                                            $.getScript("./custom/xperido/invenso_xperido.js",function()
                                            {
                                                $('a[id^="QuickGen_"]').click(function(){
                                                    _invenso_xperido_openXperiDoPage_Template(xperidocrminstance,'Document',module,'',ID,'','',language,'_blank',xperidoconfigurationurl,xperidoserviceurl,userid,$(this).attr('fullTemplateName'));
                                                });
                                            });
                                        }
									}
								});
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
    },
})


