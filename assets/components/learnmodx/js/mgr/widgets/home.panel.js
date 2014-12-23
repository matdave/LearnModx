LearnModx.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,items: [{
            html: '<h2>'+_('learnmodx')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,items: [{
                title: _('learnmodx')
                ,defaults: { autoHeight: true }
                ,items: [{
                    html: '<p>'+_('learnmodx.long_desc')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype: 'learnmodx-grid-learnmodx'
                    ,cls: 'main-wrapper'
                    ,preventRender: true
                }]
            }]
        }]
    });
    LearnModx.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(LearnModx.panel.Home,MODx.Panel);
Ext.reg('learnmodx-panel-home',LearnModx.panel.Home);