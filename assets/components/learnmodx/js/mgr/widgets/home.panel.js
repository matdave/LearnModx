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
            layout: 'form'
            ,items: [{
                html: '<p>Her goes dummpy text</p>'
                ,bodyCssClass: 'panel-desc'
                ,border: false
            }]
        },{
            layout: 'column'
            ,border: false
            ,cls: 'main-wrapper'
            ,items: [{
                columnWidth: .7
                ,cls: 'left-col'
                ,border: false
                ,layout: 'anchor'
                ,items: [{
                    xtype: 'panel'
                    ,cls:'main-wrapper'
                    ,layout: 'form'
                    ,labelAlign: 'top'
                    ,html: '<h1>Loading...</h1>'
                }]
            },{
                columnWidth: .3
                ,layout: 'form'
                ,border: false
                ,autoHeight: true
                ,id: 'right-column'
                ,items: [{
                    xtype: 'learnmodx-combo-chapter'
                    ,fieldLabel: _('learnmodx:chapter')
                    ,name: 'chapter'
                    ,anchor: '100%'
                    ,allowBlank: true
                    ,value: LearnModx.config.chapter
                },{
                    xtype: 'learnmodx-combo-section'
                    ,fieldLabel: _('learnmodx:section')
                    ,name: 'section'
                    ,anchor: '100%'
                    ,allowBlank: true
                    ,value: LearnModx.config.section
                },{
                    xtype: 'panel'
                    ,style: 'text-align: right'
                    ,items: [{
                        xtype: 'button'
                        ,text: _('learnmodx:load')
                        ,cls: 'primary-button'
                    }]
                },{
                    xtype: 'panel'
                    ,html: '<hr />'
                },{
                    xtype: 'panel'
                    ,style: 'text-align: right'
                    ,items: [{
                        xtype: 'button'
                        ,text: _('learnmodx:load')
                        ,cls: 'primary-button'
                    }]
                },{
                    xtype: 'panel'
                    ,html: '<hr />'
                },{
                    xtype: 'panel'
                    ,style: 'text-align: right'
                    ,items: [{
                        xtype: 'button'
                        ,text: _('learnmodx:load')
                        ,cls: 'primary-button'
                    }]
                }]
            }]
        }]
    });
    LearnModx.panel.Home.superclass.constructor.call(this,config);

    (function(scope){
        console.log(scope);
        console.log(LearnModx.config);
    })(this);
};
Ext.extend(LearnModx.panel.Home, MODx.Panel);
Ext.reg('learnmodx-panel-home', LearnModx.panel.Home);

LearnModx.combo.Chapter = function(config){
    config = config || {};
    Ext.applyIf(config,{
        baseParams:{
            action: 'mgr/learnmodx/getChapters'
        }
        ,defaultValue: 0
        ,displayField: 'chapter'
        ,valueField: 'id'
        ,fields: ['id','chapter']
        ,url: LearnModx.config.connectorUrl
        ,typeAhead: true

    });
    LearnModx.combo.Chapter.superclass.constructor.call(this,config);

    this.on('load', function (e) {
        console.log('derp');
    });
};
Ext.extend(LearnModx.combo.Chapter,MODx.combo.ComboBox);
Ext.reg('learnmodx-combo-chapter',LearnModx.combo.Chapter);

LearnModx.combo.Section = function(config){
    config = config || {};
    Ext.applyIf(config,{
        baseParams:{
            action: 'mgr/learnmodx/getSections'
            ,chapter: LearnModx.config.chapter
        }
        ,defaultValue: 0
        ,displayField: 'section'
        ,valueField: 'id'
        ,fields: ['id','section']
        ,url: LearnModx.config.connectorUrl
        ,value: ''
    });
    LearnModx.combo.Section.superclass.constructor.call(this,config);
};
Ext.extend(LearnModx.combo.Section,MODx.combo.ComboBox);
Ext.reg('learnmodx-combo-section',LearnModx.combo.Section);