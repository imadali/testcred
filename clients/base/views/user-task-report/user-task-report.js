/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
({ //planned-activities
   // extendsFrom: 'TabbedDashletView',
    extendsFrom: 'PlannedActivitiesView',
    /*bitFlag: null,
    range: null,
    _defaultSettings: {limit: 10, visibility: 'user'},*/
    initialize: function (options) {
        selfz = this;
        options.meta = options.meta || {};
        options.meta.template = 'tabbed-dashlet';
        this.plugins = _.union(this.plugins, ['LinkedModel']);
        this.tbodyTag = 'ul[data-action="pagination-body"]';


        this._super('initialize', [options]);

    }, /*
    _initEvents: function () {
        this._super('_initEvents');
        this.on('active-tasks:close-task:fire', this.closeTask, this);
        this.on('render:rows', this._renderAvatars, this);
        return this;
    },*/
    refreshDashletData: function () {
        var  selfz = this;
        //console.log(selfz.settings.get('task_status_duration'));  
        this.range = selfz.settings.get('task_status_duration');
        if (this.bitFlag == null) {
            this._getFilters(this.range);
            this.loadData();
        }


    },
    _getFilters: function (index) {
        var  selfz = this;
        //Setting Custom Filters
        var curr = new Date; // get current date
        var first = curr.getDate() - curr.getDay(); // First day is the day of the month - the day of the week
        first = first + 1;
        var last = first + 4; // last day is the first day + 6

        var firstday = new Date(curr.setDate(first));
        var lastday = new Date(curr.setDate(last));

        var d1 = firstday.getFullYear() + "-" + (firstday.getMonth() + 1) + "-" + firstday.getDate();
        var d2 = lastday.getFullYear() + "-" + (lastday.getMonth() + 1) + "-" + lastday.getDate();

        var filters = [];
        this.range = selfz.settings.get('task_status_duration');

        if (this.range == "today") {
            filters.push({assigned_date_c: {$dateRange: "today"}});
        }
        if (this.range == "yesterday") {
            filters.push({assigned_date_c: {$dateRange: "yesterday"}});
        }
        if (this.range == "this_week") {
            filters.push({assigned_date_c: {$dateBetween: [d1, d2]}});
        }
        if (this.range == "last_week") {
            filters.push({assigned_date_c: {$dateRange: "last_7_days"}});
        }
        if (this.range == "this_month") {
            filters.push({assigned_date_c: {$dateRange: "this_month"}});
        }
        if (this.range == "last_month") {
            filters.push({assigned_date_c: {$dateRange: "last_30_days"}});
        }

        return filters;  },/*
    closeTask: function (model) {
        var self = this;
        var name = Handlebars.Utils.escapeExpression(app.utils.getRecordName(model)).trim();
        var context = app.lang.getModuleName(model.module).toLowerCase() + ' ' + name;
        app.alert.show('complete_task_confirmation:' + model.get('id'), {level: 'confirmation', messages: app.utils.formatString(app.lang.get('LBL_ACTIVE_TASKS_DASHLET_CONFIRM_CLOSE'), [context]), onConfirm: function () {
                model.save({status: 'closed'}, {showAlerts: true, success: self._getRemoveModelCompleteCallback()});
            }});
    },*/
    _initTabs: function () {
        this._super("_initTabs");
        var today = new Date();
        today.setHours(23, 59, 59);
        today.toISOString();
        _.each(_.pluck(_.pluck(this.tabs, 'filters'), 'date_due'), function (filter) {
            _.each(filter, function (value, operator) {
                if (value === 'today') {
                    filter[operator] = today;
                }
            });
        });
        selfz.settings.set({"task_status_duration": "today"});

   },/*
    createRecord: function (event, params) {
        if (this.module !== 'Home') {
            this.createRelatedRecord(params.module, params.link);
        } else {
            var self = this;
            app.drawer.open({layout: 'create-actions', context: {create: true, module: params.module}}, function (context, model) {
                if (!model) {
                    return;
                }
                self.context.resetLoadFlag();
                self.context.set('skipFetch', false);
                if (_.isFunction(self.loadData)) {
                    self.loadData();
                } else {
                    self.context.loadData();
                }
            });
        }
    }, bindCollectionAdd: function (model) {
        var pictureUrl = app.api.buildFileURL({module: 'Users', id: model.get('assigned_user_id'), field: 'picture'});
        model.set('picture_url', pictureUrl);
        this._super('bindCollectionAdd', [model]);
    },*/
    bindDataChange: function () {
        this._super('bindDataChange');
        console.log(this.settings);
        if (this.settings) {
            this.settings.on("change:task_status_duration", this.refreshDashletData, this);
        }
    },/*
    _renderHtml: function () {
        if (this.meta.config) {
            this._super('_renderHtml');
            return;
        }
        var tab = this.tabs[this.settings.get('activeTab')];
        if (tab.overdue_badge) {
            this.overdueBadge = tab.overdue_badge;
        }
        this._super('_renderHtml');

        this._renderAvatars();
    }, _renderAvatars: function () {
        this.$('img.avatar').load(function () {
            $(this).removeClass('hide');
        }).error(function () {
            $(this).parent().removeClass('avatar avatar-md').addClass('label label-module label-module-md label-Users');
            $(this).parent().find('span').removeClass('hide');
        });
        this.$('img.avatar').each(function () {
            var img = $(this);
            img.attr('src', img.data('src'));
        });
    }*/
    
})