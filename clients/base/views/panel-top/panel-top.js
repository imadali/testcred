({
    extendsFrom : 'PanelTopView',
    initialize : function(options) {
        this._super('initialize', [ options ]);
    },

	openCreateDrawer: function(module, link) {
     
        link = link || this.context.get('link');
        //FIXME: `this.context` should always be used - SC-2550
        var context = (this.context.get('name') === 'tabbed-dashlet') ?
            this.context : (this.context.parent || this.context);
        var parentModel = context.get('model') || context.parent.get('model'),
            model = this.createLinkModel(parentModel, link),
            self = this;
        app.drawer.open({
            layout: 'create',
            context: {
                create: true,
                module: model.module,
                model: model
            }
        }, function(context, model) {
            if (!model) {
                return;
            }

            self.context.resetLoadFlag();
            self.context.set('skipFetch', false);
            // All the views have this method, but since this plugin
            // can officially be attached to a field, we need this
            // safe check.
            if (_.isFunction(self.loadData)) {
                self.loadData();
            }
            //DOTBASE BEGIN 12032
            //C'est ici qu'on declenche le refresh : a la fermeture du drawer, on veut rafraichir le subpanel Activities
            var parentView = self.context.parent;
			var currentClass = self;
			setTimeout(function(){ currentClass.refreshActivitiesSubpanel(parentView); }, 3000);
            //DOTBASE END 12032
        });
    },
    
    //DOTBASE BEGIN 12032
    refreshActivitiesSubpanel: function(parentView) {
		var subpanels = parentView.children;
        for(i=0;i<subpanels.length;i++) {
        	//Si nous sommes sur le subpanel Activities, on le recharge
        	if (subpanels[i].attributes.module == 'dotb6_contact_activities') {
        		subpanels[i].reloadData(false); 
			}
        }			
	},
    //DOTBASE END 12032
})