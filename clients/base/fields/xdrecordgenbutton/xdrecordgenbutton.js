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
		this.xperido_generate_button(); 
    },
    
    xperido_generate_button: function() {
        //example of getting field data from current record
        //debugger;
		var url = null,
            body = null;
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
                                    _invenso_xperido_openXperiDoPage(json[0].id,'Document',module,'',ID,'','',language,'_blank',json[0].xperidoconfigurationurl,json[0].xperidoserviceurl,userid);
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
            }
        });	

    },   
})


