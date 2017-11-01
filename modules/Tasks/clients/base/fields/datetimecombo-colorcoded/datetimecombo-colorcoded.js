
({
    extendsFrom: 'BaseDatetimecomboColorcodedField',

    _render: function() {
        this._super('_render');
    },

    _getColorCodeClass: function() {
        var eventDate,
            today,
            nextDay;

        if (_.isEmpty(this.model.get(this.name))) {
            return null;
        }

        // Changing due_date e.g 12-01-2016 01:01:01 to 12-01-2016 23:59:59
        eventDate = app.date(this.model.get(this.name)).hours('23').minutes('59').seconds('59');

        today = app.date();
        nextDay = app.date().add(1, 'days');
        
        var status = this.model.get('status');
        if (eventDate.isBefore(today) && status != 'closed') {
            return this.colorCodeClasses.overdue;
        } else if (eventDate.isBefore(nextDay) && status != 'closed') {
            return this.colorCodeClasses.upcoming;
        } else {
            return null;
        }
    },

})
