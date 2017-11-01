({
    events: {
        'click [name=inline-save]': 'saveClicked',
        'click [name=inline-cancel]': 'cancelClicked'
    },
    extendsFrom: 'ButtonField',
    initialize: function(options) {
        this._super("initialize", [options]);
        if (this.name === 'inline-save') {
            this.model.off("change", null, this);
            this.model.on("change", function() {
                this.changed = true;
            }, this);
        }
    },
    _loadTemplate: function() {
        app.view.Field.prototype._loadTemplate.call(this);
        if (this.view.action === 'list' && _.indexOf(['edit', 'disabled'], this.action) >= 0) {
            this.template = app.template.getField('button', 'edit', this.module, 'edit');
        } else {
            this.template = app.template.empty;
        }
    },
    _validationComplete: function(isValid) {
        if (!isValid) {
            this.setDisabled(false);
            return;
        }
        if (!this.changed) {
            this.cancelEdit();
            return;
        }
        this._save();
    },
    _save: function() {
        var self = this,
            successCallback = function(model) {
                self.changed = false;
                self.view.toggleRow(model.id, false);
                self._refreshListView();
            },
            options = {
                success: successCallback,
                error: function(error) {
                    if (error.status === 409) {
                        app.utils.resolve409Conflict(error, self.model, function(model, isDatabaseData) {
                            if (model) {
                                if (isDatabaseData) {
                                    successCallback(model);
                                } else {
                                    self._save();
                                }
                            }
                        });
                    }
                },
                complete: function() {
                    if (self.model.get('_unlinked')) {
                        self.collection.remove(self.model, {
                            silent: true
                        });
                        self.collection.trigger('reset');
                        self.view.render();
                    } else {
                        self.setDisabled(false);
                    }
                },
                lastModified: self.model.get('date_modified'),
                showAlerts: {
                    'process': true,
                    'success': {
                        messages: app.lang.get('LBL_RECORD_SAVED', self.module)
                    }
                },
                relate: this.model.link ? true : false
            };
        options = _.extend({}, options, this.getCustomSaveOptions(options));
        this.model.save({}, options);
        //DOTBASE BEGIN 12032
        this.changed = null;	//Si on met this.changed = false, le test echoue (on teste si this.changed est vide)
        //C'est ici qu'on déclenche le refresh : quand une ligne du subpanel a ete mise a jour, on veut aussi rafraichir le subpanel Activities
		//Il y a un délai entre la sauvegarde de la CR et la création de la Tache par le Process associé
		var parentView = this.context.parent;
		var self = this;
		setTimeout(function(){ self.refreshActivitiesSubpanel(parentView); }, 3000);		
        //DOTBASE END 12032
    },
    getCustomSaveOptions: function(options) {
        return {};
    },
    saveModel: function() {
        this.setDisabled(true);
        var fieldsToValidate = this.view.getFields(this.module, this.model);
        this.model.doValidate(fieldsToValidate, _.bind(this._validationComplete, this));
    },
    cancelEdit: function() {
        if (this.isDisabled()) {
            this.setDisabled(false);
        }
        this.changed = false;
        this.model.revertAttributes();
        this.view.clearValidationErrors();
        this.view.toggleRow(this.model.id, false);
        if (this.context.parent) {
            this.context.parent.trigger('editablelist:cancel', this.model);
        }
    },
    saveClicked: function(evt) {
        if (!$(evt.currentTarget).hasClass('disabled')) {
            this.saveModel();
        }
    },
    cancelClicked: function(evt) {
        this.cancelEdit();
    },
    _refreshListView: function() {
        var filterPanelLayout = this.view;
        while (filterPanelLayout && filterPanelLayout.name !== 'filterpanel') {
            filterPanelLayout = filterPanelLayout.layout;
        }
        if (filterPanelLayout && !filterPanelLayout.disposed && this.collection) {
            filterPanelLayout.applyLastFilter(this.collection);
        }
    },
    
    //DOTBASE BEGIN 12032
    refreshActivitiesSubpanel: function(parentView) {
	if (parentView != null) {
            var subpanels = parentView.children;
            for (i = 0; i < subpanels.length; i++) {
                //Si nous sommes sur le subpanel Activities, on le recharge
                if (subpanels[i].attributes.module == 'dotb6_contact_activities') {
                    subpanels[i].reloadData(false);
                }
            }
        }
    }
    //DOTBASE END 12032
})