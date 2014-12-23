Ext.onReady(function() {
    MODx.load({ xtype: 'learnmodx-page-home'});
});

LearnModx.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'learnmodx-panel-home'
            ,renderTo: 'learnmodx-panel-home-div'
        }]
    });
    LearnModx.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(LearnModx.page.Home,MODx.Component);
Ext.reg('learnmodx-page-home',LearnModx.page.Home);