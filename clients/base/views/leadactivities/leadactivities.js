({
    plugins: ['Dashlet'],
    activities: null,
    previourId: null,
    showActivities: false,
    getActivities: true,
    offset: 0,
    noMore: false,
    firstRecord: null,
    initialize: function (options) {
        this._super('initialize', [options]);
         app.events.on('refreshActivitiesDashlet', _.bind(this.refreshClicked, this));
    },
    events: {
        'click .more-activities': 'moreActivities'
    },
    moreActivities: function (e) {
        var self = this;
        self.offset = self.offset + 20;
        self._render(self.offset);

    },
    _renderHtml: function () {
        this._super('_renderHtml');
        $(".leadActivities").parent().css("max-height", "600px");
    },
    refreshClicked: function () {
        app.alert.show('loading-activities', {
                        level: 'process',
                        title: 'Laoding Activities...'
        });
        this.activities = null;
        this.previourId = null;
        this.showActivities = false;
        this.getActivities = true;
        this.offset = 0;
        this.noMore = false;
        this.firstRecord = null;
        this.render();
        return;
    },
    _render: function (offset) {
        this._super('_render');
        var self = this;
        var module = self.model.get('_module');
        if (module == "Leads") {
            if (self.noMore) {
                $(".activitystream-footer").hide();
                self.offset = 0;
            } else {
                $(".activitystream-footer").show();
            }
            if (self.getActivities) {
                if (self.offset > 0) {
                    app.alert.show('loading-activities', {
                        level: 'process',
                        title: 'Laoding Activities...'
                    });
                }
                var id = self.model.get('id');
                if (typeof offset == 'undefined') {
                    var offset = 0;
                }
                if (self.previourId != id) {
                    self.activities = null;
                }
                self.previourId = id;
                var url = 'Leads/' + id + '/link/preview_activities/' + offset + '/date_entered:desc';
                App.api.call('get', App.api.buildURL(url), {id: id, activities: "yes"}, {
                    success: function (data) {
                        if (typeof data.records[0] != 'undefined') {
                            self.firstRecord = data.records[0]['id'];
                        }
                        if (data.records.length < 20) {
                            self.noMore = true;
                        } else {
                            self.noMore = false;
                        }
                        if (offset == 0) {
                            self.activities = data;
                        } else {
                            for (var i = 0; i < data.records.length; i++)
                                self.activities.records.push(data.records[i]);
                        }
                        self.getActivities = false;
                        self.showActivities = true;
                        app.alert.dismiss('loading-activities');
                        self.render();
                    },
                    error: function (e) {
                        throw e;
                    }
                });
            } else {
                self.getActivities = true;
                var li_id = '#activity_' + self.firstRecord;
                if($(li_id).position()){
                    $('.activitiesStreams').animate({scrollTop: $(li_id).position().top - 100}, 'fast');
                }
            }
        } else {
            if (self.showActivities) {
                self.showActivities = false;
                self.render();
            }
        }
    },
})
