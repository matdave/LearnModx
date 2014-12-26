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
                    ,id: 'learnmodx-content'
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
                    ,id: 'learnmodx-section'
                    ,anchor: '100%'
                    ,allowBlank: true
                    ,value: LearnModx.config.section
                    ,listeners: {
                        'render': {fn: function(cmp) {
                            this.fireEvent('load-content');
                        }}
                    }
                },{
                    xtype: 'panel'
                    ,html: '<hr />'
                },{
                    xtype: 'button'
                    ,text: _('learnmodx:setup')
                    ,style: 'display: block;color:#fff;background-color: #d9534f;border-color: #d43f3a;'
                },{
                    xtype: 'panel'
                    ,html: '<hr />'
                },{
                    xtype: 'button'
                    ,text: _('learnmodx:verify')
                    ,cls: 'primary-button'
                    ,style: 'display: block;'
                }]
            }]
        }]
    });
    LearnModx.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(LearnModx.panel.Home, MODx.Panel);
Ext.reg('learnmodx-panel-home', LearnModx.panel.Home);

LearnModx.combo.Chapter = function(config) {
    config = config || {};

    /**
     * Config
     */

    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/learnmodx/getChapters'
        },
        displayField: 'name',
        valueField: 'id',
        fields: ['id', 'name'],
        url: LearnModx.config.connectorUrl,
        typeAhead: true

    });
    LearnModx.combo.Chapter.superclass.constructor.call(this, config);

    /**
     * Event
     * On select, store and fetch
     */

    this.on('select', function (combo) {
        // Update this store
        this.getStore().baseParams['chapter'] = combo.value;
        this.getStore().baseParams['section'] = 0;

        // Set section store
        Ext.getCmp('learnmodx-section').getStore().baseParams['chapter'] = combo.value;
        Ext.getCmp('learnmodx-section').getStore().baseParams['section'] = 0;

        // Reload
        Ext.getCmp('learnmodx-section').getStore().removeAll();
        Ext.getCmp('learnmodx-section').getStore().load();
        Ext.getCmp('learnmodx-section').setValue('');
    });
};
Ext.extend(LearnModx.combo.Chapter,MODx.combo.ComboBox);
Ext.reg('learnmodx-combo-chapter',LearnModx.combo.Chapter);

LearnModx.combo.Section = function(config){
    config = config || {};

    /**
     * Config
     */

    Ext.applyIf(config,{
        baseParams:{
            action: 'mgr/learnmodx/getSections'
            ,chapter: LearnModx.config.chapter
            ,section: LearnModx.config.section
        }
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name']
        ,url: LearnModx.config.connectorUrl
        ,value: ''
        ,emptyText: _('learnmodx:selectsection')

    });
    LearnModx.combo.Section.superclass.constructor.call(this,config);

    /**
     * Loads content
     */

    this.getContent = function () {
        // Load
        MODx.Ajax.request({
            url: LearnModx.config.connectorUrl
            ,params: {
                action: 'mgr/learnmodx/get'
                ,chapter: this.getStore().baseParams['chapter']
                ,section: this.getStore().baseParams['section']
            }
            ,listeners: {
                'success':{fn:function(r) {
                    Ext.getCmp('learnmodx-content').update(r.content);
                },scope:this}
            }
        });
    };

    /**
     * Event
     * When components is loaded, load the content
     */

    this.on('load-content', function () {
        // Load content
        this.getContent();
    });

    /**
     * Event
     * On change
     */

    // On change
    this.on('select', function (combo) {
        // Store value
        this.getStore().baseParams['section'] = combo.value;

        // Load content
        this.getContent();
    });
};
Ext.extend(LearnModx.combo.Section,MODx.combo.ComboBox);
Ext.reg('learnmodx-combo-section',LearnModx.combo.Section);