LearnModx.grid.LearnModx = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'learnmodx-grid-learnmodx'
        ,url: SmartCache.config.connectorUrl
        ,baseParams: {
            action: 'mgr/learnmodx/getList'
            ,active: 1
        }
        ,fields: [
            'id'
            ,'resource'
            ,'resource_pretty'
            ,'children'
            ,'constraints'
            ,'clear_cache'
            ,'on_create'
            ,'on_update'
            ,'on_sort'
            ,'on_delete'
        ]
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,anchor: '97%'
        ,autoExpandColumn: 'comment'
        ,columns: [{
            dataIndex: 'id'
            ,header: _('learnmodx.id')
            ,sortable: true
            ,width: 10
        },{
            dataIndex: 'resource_pretty'
            ,header: _('learnmodx.resource_pretty')
            ,sortable: true
            ,width: 100
        },{
            dataIndex: 'constraints'
            ,header: _('learnmodx.constraints')
            ,sortable: false
            ,width: 30
        },{
            dataIndex: 'clear_cache'
            ,header: _('learnmodx.clear_cache')
            ,sortable: false
            ,width: 80
        }]
        ,tbar: [{
            text: _('learnmodx.new_rule')
            ,handler: {
                xtype: 'learnmodx-window-learnmodx-create'
                ,blankValues: true
            }
        }]
    });
    LearnModx.grid.LearnModx.superclass.constructor.call(this,config)
};
Ext.extend(SmartCache.grid.LearnModx,MODx.grid.Grid,{
    getMenu: function() {
        return [{
            text: _('learnmodx.rule_update')
            ,handler: this.updateRule
        },'-',{
            text: _('learnmodx.rule_remove')
            ,handler: this.removeRule
        }];
    }
    ,updateRule: function(btn,e) {
        if (!this.updateRuleWindow) {
            this.updateRuleWindow = MODx.load({
                xtype: 'learnmodx-window-learnmodx-update'
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        }
        this.updateRuleWindow.setValues(this.menu.record);
        this.updateRuleWindow.show(e.target);
    }
    ,removeRule: function() {
        MODx.msg.confirm({
            title: _('learnmodx.rule_remove')
            ,text: _('learnmodx.rule_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/learnmodx/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
});
Ext.reg('learnmodx-grid-learnmodx',LearnModx.grid.LearnModx);

LearnModx.window.CreateRule = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('learnmodx.rule_new')
        ,url: LearnModx.config.connectorUrl
        ,baseParams: {
            action: 'mgr/learnmodx/create'
        }
        ,fields: [{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: 1
                ,items: [{
                    allowBlank: false
                    ,anchor: '100%'
                    ,fieldLabel: _('learnmodx.resource')
                    ,name: 'resource'
                    ,xtype: 'learnmodx-combo-resources'
                },{
                    xtype: 'checkbox'
                    ,boxLabel: _('learnmodx.children')
                    ,labelStyle: 'font-weight: bold; color: #777777;'
                    ,name: 'children'
                    ,checked: true
                }]
            }]
        },{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: 1
                ,items: [{
                    html: '<div style="height: 1px; border-top: 1px solid #C0C0C0; width: 100%; display: block; margin-top: 10px; margin-bottom: 10px;"></div>'
                }]
            }]
        },{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: .25
                ,items: [{
                    xtype: 'checkbox'
                    ,anchor: '25%'
                    ,name: 'on_create'
                    ,fieldLabel: _('learnmodx.create')
                    ,checked: true
                }]
            },{
                columnWidth: .25
                ,items: [{
                    xtype: 'checkbox'
                    ,anchor: '25%'
                    ,name: 'on_update'
                    ,fieldLabel: _('learnmodx.update')
                    ,checked: true
                }]
            },{
                columnWidth: .25
                ,items: [{
                    xtype: 'checkbox'
                    ,anchor: '25%'
                    ,name: 'on_sort'
                    ,fieldLabel: _('learnmodx.sort')
                    ,checked: true
                }]
            },{
                columnWidth: .25
                ,items: [{
                    xtype: 'checkbox'
                    ,anchor: '25%'
                    ,name: 'on_delete'
                    ,fieldLabel: _('learnmodx.delete')
                    ,checked: true
                }]
            }]
        },{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: 1
                ,items: [{
                    html: '<div style="height: 1px; border-top: 1px solid #C0C0C0; width: 100%; display: block; margin-bottom: 10px;"></div>'
                }]
            }]
        },{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: 1
                ,items: [{
                    allowBlank: false
                    ,anchor: '100%'
                    ,fieldLabel: _('learnmodx.clear_cache')
                    ,name: 'clear_cache'
                    ,xtype: 'textfield'
                },{
                    html: '<p style="text-align: justify">Enter ids for resources that should have their cache removed here. Separate each resource with a comma.</p><br /><p>Adding c after the id indicates that the direct children also should have their cache deleted. A small s is a reference to self and will delete the cache for that resource.</p><br /><p>Content could be: <b>s,1,22,98c</b></p>'
                }]
            }]
        }]
    });
    LearnModx.window.CreateRule.superclass.constructor.call(this,config);
};
Ext.extend(LearnModx.window.CreateRule,MODx.Window);
Ext.reg('learnmodx-window-learnmodx-create',LearnModx.window.CreateRule);

LearnModx.window.UpdateRule = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('learnmodx.rule_update')
        ,url: LearnModx.config.connectorUrl
        ,baseParams: {
            action: 'mgr/learnmodx/update'
        }
        ,fields: [{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: 1
                ,items: [{
                    allowBlank: false
                    ,anchor: '100%'
                    ,fieldLabel: _('learnmodx.resource')
                    ,name: 'resource'
                    ,xtype: 'learnmodx-combo-resources'
                },{
                    xtype: 'checkbox'
                    ,boxLabel: _('learnmodx.children')
                    ,labelStyle: 'font-weight: bold; color: #777777;'
                    ,name: 'children'
                },{
                    allowBlank: false
                    ,anchor: '100%'
                    ,fieldLabel: _('learnmodx.id')
                    ,name: 'id'
                    ,xtype: 'textfield'
                    ,hidden: true
                }]
            }]  
        },{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: 1
                ,items: [{
                    html: '<div style="height: 1px; border-top: 1px solid #C0C0C0; width: 100%; display: block; margin-top: 10px; margin-bottom: 10px;"></div>'
                }]
            }]
        },{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: .25
                ,items: [{
                    xtype: 'checkbox'
                    ,anchor: '25%'
                    ,name: 'on_create'
                    ,fieldLabel: _('learnmodx.create')
                }]
            },{
                columnWidth: .25
                ,items: [{
                    xtype: 'checkbox'
                    ,anchor: '25%'
                    ,name: 'on_update'
                    ,fieldLabel: _('learnmodx.update')
                }]
            },{
                columnWidth: .25
                ,items: [{
                    xtype: 'checkbox'
                    ,anchor: '25%'
                    ,name: 'on_sort'
                    ,fieldLabel: _('learnmodx.sort')
                }]
            },{
                columnWidth: .25
                ,items: [{
                    xtype: 'checkbox'
                    ,anchor: '25%'
                    ,name: 'on_delete'
                    ,fieldLabel: _('learnmodx.delete')
                }]
            }]
        },{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: 1
                ,items: [{
                    html: '<div style="height: 1px; border-top: 1px solid #C0C0C0; width: 100%; display: block; margin-bottom: 10px;"></div>'
                }]
            }]
        },{
            border: false
            ,layout: 'column'
            ,defaults: {
                anchor: '100%'
                ,labelAlign: 'top'
                ,layout: 'form'
                ,border: false
            }
            ,items: [{
                columnWidth: 1
                ,items: [{
                    allowBlank: false
                    ,anchor: '100%'
                    ,fieldLabel: _('learnmodx.clear_cache')
                    ,name: 'clear_cache'
                    ,xtype: 'textfield'
                },{
                    html: '<p style="text-align: justify">Enter ids for resources that should have their cache removed here. Separate each resource with a comma.</p><br /><p>Adding c after the id indicates that the direct children also should have their cache deleted. A small s is a reference to self and will delete the cache for that resource.</p><br /><p>Content could be: <b>s,1,22,98c</b></p>'
                }]
            }]
        }]
    });
    LearnModx.window.UpdateRule.superclass.constructor.call(this,config);
};
Ext.extend(LearnModx.window.UpdateRule,MODx.Window);
Ext.reg('learnmodx-window-learnmodx-update',LearnModx.window.UpdateRule);

LearnModx.combo.Resources = function(config){
    config = config || {};
    Ext.applyIf(config,{
        baseParams:{
            action: 'mgr/learnmodx/getTree'
        }
        ,defaultValue: 0
        ,displayField: 'pagetitle'
        ,valueField: 'id'
        ,fields: ['id','pagetitle']
        ,url: LearnModx.config.connectorUrl
    });
    config.hiddenName = config.name || '';
    config.baseParams.parent = config.parent || 0;
    LearnModx.combo.Resources.superclass.constructor.call(this,config);
};
Ext.extend(LearnModx.combo.Resources,MODx.combo.ComboBox);
Ext.reg('learnmodx-combo-resources',LearnModx.combo.Resources);