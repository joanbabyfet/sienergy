(function($R)
{
    $R.add('module', 'input', {
        init: function(app)
        {
            this.app = app;
            this.opts = app.opts;
            this.utils = app.utils;
            this.editor = app.editor;
            this.keycodes = app.keycodes;
            this.element = app.element;
            this.selection = app.selection;
            this.insertion = app.insertion;
            this.inspector = app.inspector;
            this.autoparser = app.autoparser;

            // local
            this.lastShiftKey = false;
            this.startTotal(app.rootElement,app);
        },

        onkeydown: function(e)
        {
            var total = e.target.innerText.replace(/\s/g,"").length;
            $(e.target).parents(".total-wrap").find(".total").html(total);
        },
        onpaste:function(e){
            var total = e.target.innerText.replace(/\s/g,"").length;
            $(e.target).parents(".total-wrap").find(".total").html(total);
        },
        onkeyup:function(e){
            var total = e.target.innerText.replace(/\s/g,"").length;
            $(e.target).parents(".total-wrap").find(".total").html(total);
        },
        startTotal:function(e,app){
            var str = '<span class="total" style="bottom:1px;top:inherit;position:absolute;padding:4px 10px;border-top:1px solid #f1f1f1;border-left:1px solid #f1f1f1 !important;right:1px">0</span>';
            $(e).parents(".total-wrap").append(str);

            setTimeout(function(){
                var len = 0;
                if($(e).val().length!=0){
                    var wrapDom = document.getElementsByClassName("redactor-in")[0];
                    $(wrapDom).children().each(function(){
                        len += $(this).context.innerText.replace(/\s/g,"").length;
                    })
                }
                $(e).parents(".total-wrap").find(".total").html(len);
            },400)


        }


    });
})(Redactor);