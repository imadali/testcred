({
    plugins: ['Dashlet'],
    baseURL: null,
    mydata: null,
    initialize: function (options) {
        this._super('initialize', [options]);
        this.mydata = [];

    }, events: {
        'click .record-panel-header': 'togglePanel',
        'click .sortThis': 'sortThis'
    },
    loadData: function (options) {
        this._super('loadData', [options]);
        options = options || {};
        if (_.isFunction(options.complete))
        {
            options.complete();
        }

        var self = this;
        self.mydata = [];
        var apiCall = app.api.call('GET', app.api.buildURL('getRelatedTasks'));
        self.order_status = "";
        self.order_status_in = "";
        self.order_assignto = "";
        self.order_assignto_in = "";
        self.order_date = "";
        self.order_date_in = "";
        apiCall.xhr.success(function (data) {

            if (data.status_rsponse.order_by == 'status') {
                self.order_status = true;
                if (data.status_rsponse.order_in == 'asc') {
                    self.order_status_in = true;
                }
                else {
                    self.order_status_in = false;
                }
            }
            else {
                self.order_status = false;
                self.order_status_in = false;
            }
            if (data.status_rsponse.order_by == 'assignTo') {
                self.order_assignto = true;
                if (data.status_rsponse.order_in == 'asc') {
                    self.order_assignto_in = true;
                }
                else {
                    self.order_assignto_in = false;
                }
            }
            else {
                self.order_assignto = false;
                self.order_assignto_in = false;
            }
            if (data.status_rsponse.order_by == 'date') {
                self.order_date = true;
                if (data.status_rsponse.order_in == 'asc') {
                    self.order_date_in = true;
                }
                else {
                    self.order_date_in = false;
                }
            }
            else {
                self.order_date = false;
                self.order_date_in = false;
            }
            var count = 0;


            _.each(data.leads_response, function (value) {

                var record = [];
                record.lead_id = value.lead_id;
                record.lead_first = value.lead_name;
                record.lead_status = value.lead_status;
                record.lead_assign_to_name = value.lead_assign_to_name;
                record.last_open_task = value.last_open_task;
                record.related_tasks = value.related_tasks;
                self.mydata.push(record);


            });

            self.render();
        });
    },
    togglePanel: function (e) {

        var $panelHeader = this.$(e.currentTarget);
        if ($panelHeader && $panelHeader.next()) {
            $panelHeader.next().toggle();
            $panelHeader.toggleClass('panel-inactive panel-active');
        }
        if ($panelHeader && $panelHeader.find('i')) {
            $panelHeader.find('i').toggleClass('fa-chevron-up fa-chevron-down');
        }
        var panelName = this.$(e.currentTarget).parent().data('panelname');
        var state = 'collapsed';
        if (this.$(e.currentTarget).next().is(":visible")) {
            state = 'expanded';
        }
        //console.log(panelName);
        this.savePanelState(panelName, state);
    },
    savePanelState: function (panelID, state) {
        if (this.createMode) {
            return;
        }
        var panelKey = app.user.lastState.key(panelID + ':tabState', this);
        app.user.lastState.set(panelKey, state);
    },
    sortThis: function (e) {
        var $colum = this.$(e.currentTarget);
        var currentId = $colum.attr('id');
        var sortedAs = $colum.attr('sort');
        //console.log(sortedAs);
        if (sortedAs == 'asc') {
            sortedAs = 'desc'
        }

        else {
            sortedAs = 'asc'
        }

        var self = this;
        self.mydata = new Array();
        self.order_status = "";
        self.order_status_in = "";
        self.order_assignto = "";
        self.order_assignto_in = "";
        self.order_date = "";
        self.order_date_in = "";
        var apiCall = app.api.call('GET', app.api.buildURL('getRelatedTasks?sort=' + currentId + '&order=' + sortedAs));
        apiCall.xhr.success(function (data) {
            if (data.status_rsponse.order_by == 'status') {
                self.order_status = true;
                if (data.status_rsponse.order_in == 'asc') {
                    self.order_status_in = true;
                }
                else {
                    self.order_status_in = false;
                }
            }
            else {
                self.order_status = false;
                self.order_status_in = false;
            }
            if (data.status_rsponse.order_by == 'assignTo') {
                self.order_assignto = true;
                if (data.status_rsponse.order_in == 'asc') {
                    self.order_assignto_in = true;
                }
                else {
                    self.order_assignto_in = false;
                }
            }
            else {
                self.order_assignto = false;
                self.order_assignto_in = false;
            }
            if (data.status_rsponse.order_by == 'date') {
                self.order_date = true;
                if (data.status_rsponse.order_in == 'asc') {
                    self.order_date_in = true;
                }
                else {
                    self.order_date_in = false;
                }
            }
            else {
                self.order_date = false;
                self.order_date_in = false;
            }
            var count = 0;
            _.each(data.leads_response, function (value, index) {

                var record = new Array();
                record.lead_id = value.lead_id;
                record.lead_first = value.lead_name;
                record.lead_status = value.lead_status;
                record.lead_assign_to_name = value.lead_assign_to_name;
                record.last_open_task = value.last_open_task;
                record.related_tasks = value.related_tasks;
                self.mydata.push(record);

                self.render();
            });

        });
    }



})