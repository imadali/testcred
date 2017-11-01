({
    extendsFrom: 'TextField',
    
    buildHref: function () {
        var defRoute = this.def.route ? this.def.route : {},
        module = this.model.get('moduleName');
        return '#' + app.router.buildRoute(module, this.model.get('id'), defRoute.action, this.def.bwcLink);
    },
})
