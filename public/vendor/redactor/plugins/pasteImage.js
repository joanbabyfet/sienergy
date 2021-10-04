(function($R) {
    $R.add('plugin', 'pasteImage', {
        //初始化
        init: function(app) {

            this.app = app;
            this.opts = app.opts;
            this.lang = app.lang;
            this.toolbar = app.toolbar;
            this.button = app.button;
            this.insertion = app.insertion;
            this.selection = app.selection;
            this.module = app.module;
        },

        start: function() {
            var _this = this;
            _this.pasteFn();
        },

        //复制图片
        pasteFn:function(){
            var _this = this;
            document.querySelector('.redactor-box').addEventListener('paste',function(e){
                var cbd = e.clipboardData;
                var ua = window.navigator.userAgent;
                // 如果是 Safari 直接 return
                if ( !(e.clipboardData && e.clipboardData.items) ) {
                    return ;
                }
                // Mac平台下Chrome49版本以下 复制Finder中的文件的Bug Hack掉
                if(cbd.items && cbd.items.length === 2 && cbd.items[0].kind === "string" && cbd.items[1].kind === "file" &&
                    cbd.types && cbd.types.length === 2 && cbd.types[0] === "text/plain" && cbd.types[1] === "Files" &&
                    ua.match(/Macintosh/i) && Number(ua.match(/Chrome\/(\d{2})/i)[1]) < 49){
                    return;
                }

                for(var i = 0; i < cbd.items.length; i++) {
                    console.log(i)
                    var item = cbd.items[i];
                    if(item.kind == "file"){
                        var blob = item.getAsFile();
                        var name = blob.name;
                        console.log(name)
                        if (blob.size === 0) {
                            return;
                        }
                        $R.modules.image.prototype.ondropimage(e, blob, false,"parse",_this);
                    }
                }
            }, false);
        },


});
})(Redactor);
