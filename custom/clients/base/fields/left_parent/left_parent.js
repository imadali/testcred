({
    minChars: 1,
    extendsFrom: 'RelateField',
    fieldTag: 'input.select2[name=left_parent_name]',
    typeFieldTag: 'select.select2[name=left_parent_type]',
    _render: function() {
        var result, self = this;
        this._super("_render");
        //SALE
        this.href = '#' + app.router.buildRoute(this.model.get("left_parent_type"), this._getRelateId());
        //END SALE
        if (this.tplName === 'edit') {
            this.checkAcl('access', this.model.get('left_parent_type'));
            var inList = (this.view.name === 'recordlist') ? true : false;
            this.$(this.typeFieldTag).select2({
                dropdownCssClass: inList ? 'select2-narrow' : '',
                containerCssClass: inList ? 'select2-narrow' : '',
                width: inList ? 'off' : '100%',
                minimumResultsForSearch: 5
            }).on("change", function(e) {
                var module = e.val;
                self.checkAcl.call(self, 'edit', module);
                self.setValue({
                    id: '',
                    value: '',
                    module: module
                });
                self.$(self.fieldTag).select2('val', '');
            });
            var plugin = this.$(this.typeFieldTag).data('select2');
            if (plugin) {
                plugin.focusser.on('focus', _.bind(_.debounce(this.handleFocus, 0), this));
            }
            var domParentTypeVal = this.$(this.typeFieldTag).val();
            if (this.model.get(this.def.type_name) !== domParentTypeVal) {
                this.model.set(this.def.type_name, domParentTypeVal, {
                    silent: true
                });
                this.model.setDefaultAttribute(this.def.type_name, domParentTypeVal);
            }
            if (app.acl.hasAccessToModel('edit', this.model, this.name) === false) {
                this.$(this.typeFieldTag).select2("disable");
            } else {
                this.$(this.typeFieldTag).select2("enable");
            }
        } else if (this.tplName === 'disabled') {
            this.$(this.typeFieldTag).select2('disable');
        }
        return result;
    },
    _getRelateId: function() {
        return this.model.get("left_parent_id");
    },
    format: function(value) {
        this.def.module = this.getSearchModule();
        var moduleString = app.lang.getAppListStrings('moduleListSingular'),
            module;
        if (this.def.module) {
            if (!moduleString[this.def.module]) {
                app.logger.error("Module '" + this.def.module + "' doesn't have singular translation.");
                module = this.def.module;
            } else {
                module = moduleString[this.def.module];
            }
        }
        this.context.set('record_label', {
            field: this.name,
            label: (this.tplName === 'detail') ? module : app.lang.get(this.def.label, this.module)
        });
        var parentCtx = this.context && this.context.parent,
            setFromCtx;
        setFromCtx = !value && parentCtx && this.view instanceof app.view.views.BaseCreateView && _.contains(_.keys(app.lang.getAppListStrings(this.def.parent_type)), parentCtx.get('module')) && this.module !== this.def.module;
        if (setFromCtx) {
            var model = parentCtx.get('model');
            var attributes = model.toJSON();
            attributes.silent = true;
            this.setValue(attributes);
            value = this.model.get(this.name);
        }
        return this._super('format', [value]);
    },
    checkAcl: function(action, module) {
        if (app.acl.hasAccess(action, module) === false) {
            this.$(this.typeFieldTag).select2("disable");
        } else {
            this.$(this.typeFieldTag).select2("enable");
        }
    },
    setValue: function(model) {
        if (!model) {
            return;
        }
        var silent = model.silent || false,
            module = model.module || model._module;
        if (app.acl.hasAccess(this.action, module, this.model.get('assigned_user_id'), this.name)) {
            if (module) {
                this.model.set('left_parent_type', module, {
                    silent: silent
                });
                this.model.removeDefaultAttribute('left_parent_type');
            }
            this.model.set('left_parent_id', model.id, {
                silent: silent
            });
            var value = model.value || model[this.def.rname || 'name'] || model['full_name'];
            this.model.set('left_parent_name', value, {
                silent: silent
            });
        }
    },
    isAvailableParentType: function(module) {
        var moduleFound = _.find(this.$(this.typeFieldTag).find('option'), function(dom) {
            return $(dom).val() === module;
        });
        return !!moduleFound;
    },
    getSearchModule: function() {
        return this.model.get('left_parent_type') || this.$(this.typeFieldTag).val();
    },
    getPlaceHolder: function() {
        return app.lang.get('LBL_SEARCH_SELECT', this.module);
    },
    unbindDom: function() {
        this.$(this.typeFieldTag).select2('destroy');
        this._super("unbindDom");
    },
    bindDataChange: function() {
        this._super('bindDataChange');
        if (this.model) {
            this.model.on('change:left_parent_type', function() {
                if (_.isEmpty(this.$(this.typeFieldTag).data('select2'))) {
                    this.render();
                } else {
                    this.$(this.typeFieldTag).select2('val', this.model.get('left_parent_type'));
                }
            }, this);
        }
    }
})