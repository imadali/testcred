({
    className: 'deep-link tcenter',
    _render: function(options) {
        this._super('_render', [options]);
        var url_vars = this.getUrlVars();
        var module = this.module;
        var action = "create";

        if(typeof url_vars.action !== 'undefined'){
            action = url_vars.action;
            delete url_vars.action;
        }

        var bean = app.data.createBean(module, url_vars);

        app.drawer.open({
            layout: action,
            context: {
                create: true,
                module: bean.module,
                model: bean
            }
        },function(){
            app.router.index();
        });

    },
    getUrlVars: function(){
        var query = {};
        location.search.substr(1).split("&").forEach(
            function(item) {
                query[item.split("=")[0]] = decodeURIComponent(item.split("=")[1]);
            }
        );
        return query;
    }
})