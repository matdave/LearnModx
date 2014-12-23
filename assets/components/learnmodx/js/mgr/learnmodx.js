var LearnModx = function(config) {
    config = config || {};
    LearnModx.superclass.constructor.call(this,config);
};
Ext.extend(LearnModx,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {}
});
Ext.reg('learnmodx',LearnModx);

LearnModx = new LearnModx();