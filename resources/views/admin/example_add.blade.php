<div class="layui-card-body ">
    <form id="layer-form" class="layui-form" action="{{ route("admin.example.add") }}" method="POST">
        {{ csrf_field() }}
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">標題:</label>
            <div class="layui-input-block">
                <input type="text" name="title" placeholder="請輸入標題"
                       class="layui-input" required lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">狀態:</label>
            <div class="layui-input-block">
                <input type="checkbox" checked name="status" lay-skin="switch" lay-filter="status"
                       lay-text="啟用|禁用">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">內容</label>
            <div class="layui-input-block">
{{--                <textarea id="content" name="content" placeholder="請輸入內容"--}}
{{--                          class="layui-textarea" lay-verify="content"></textarea>--}}
                <textarea id="content" name="content" class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">封面圖片:</label>
            <div class="layui-input-block">
                <div class="layui-upload">
                    <button type="button" class="layui-btn layui-btn-sm layui-btn-primary upload"
                            id="img">
                        <i class="layui-icon">&#xe67c;</i>選擇圖片
                    </button>
                    <div class="layui-word-aux">圖片格式需為jpg、jpeg、png、bmp</div>
                    <div class="layui-upload-list uploader-list img"></div>
                </div>
            </div>
        </div>
{{--        <div class="layui-form-item">--}}
{{--            <label class="layui-form-label">附件上傳:</label>--}}
{{--            <div class="layui-input-block">--}}
{{--                <div class="layui-upload">--}}
{{--                    <button type="button" class="layui-btn layui-btn-sm layui-btn-primary upload"--}}
{{--                            id="file">--}}
{{--                        <i class="layui-icon">&#xe67c;</i>選擇圖片--}}
{{--                    </button>--}}
{{--                    <div class="layui-word-aux">圖片格式需為jpg、jpeg、png、bmp</div>--}}
{{--                    <div class="layui-upload-list uploader-list file"></div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-sm" lay-filter="save" lay-submit>提交</button>
            </div>
        </div>
    </form>
</div>
<script>
    var verify = { //自定義表單驗證規則
    };

    layui.use(['form', 'layedit', 'upload'], function(){
        var form = layui.form;
        var $ = layui.$;
        {{--var layedit = layui.layedit;--}}

        {{--layedit.set({ //要放在 layedit.build前--}}
        {{--    uploadImage: {--}}
        {{--        url: '{{ route('admin.upload.upload') }}', //接口url--}}
        {{--        type: 'post' //默认post--}}
        {{--    }--}}
        {{--});--}}
        {{--var content = layedit.build('content'); //初始化富文本id="content"--}}
        var upload = layui.upload;

        upload.render({ //实例化
            elem: '.upload', //绑定元素
            url: '{{ route('admin.upload.upload') }}',
            data: { //额外参数
                {{--thumb_w: {{ $img_thumb_with }},--}}
            },
            //field:'file', //文件域的字段名
            multiple: true,
            accept: 'images',
            exts: 'jpg|jpeg|png|bmp', //限定上传類型
            number: 3,//0为不限制上传数量
            size: 1024 * 3,//文件最大可允许上传的大小，單位 KB 0=不限制
            before: function(obj){
                layer.msg('上傳中...', {icon: 16, shade: 0.01, time: 0});
            },
            done: function(res, index, upload){
                var item = this.item;
                var field = item.attr('id');
                layer.close(layer.msg('上傳成功'));//关闭上传提示窗口
                //上传完毕
                $('.uploader-list.'+ field).append(
                    '<div id="upload-'+ index +'" class="file-item">' +
                    '   <div class="handle"><i class="layui-icon layui-icon-close"></i></div>' +
                    '   <img class="layui-upload-img" style="width: 100px;height: 100px;" src="'+ res.data.filelink +'">' +
                    '   <input type="hidden" name="'+ field +'[]" value="' + res.data.filename + '" />' +
                    '   <div class="info"></div>' +
                    '</div>'
                );
            },
            error: function(){
                //请求异常回调
                layer.msg('上傳失敗');
            }
        });

        //監聽鼠標事件
        $(document).on("mouseenter mouseleave", ".file-item", function(event){
            if(event.type === "mouseenter"){ //鼠标悬浮
                $(this).children(".info").fadeIn("fast");
                $(this).children(".handle").fadeIn("fast");
            }else if(event.type === "mouseleave") { //鼠标离开
                $(this).children(".info").hide();
                $(this).children(".handle").hide();
            }
        });
        $(document).on("click", ".file-item .handle", function(event){
            $(this).parent().remove();
        });

        form.verify({
            // content: function(value) { //數據异步提交未同步的原因，使用layedit.sync(index) 解决
            //     return layedit.sync(content);
            // }
        });
        form.on('submit(save)', function(data){
            //$.post() 和 $.get() 默认是异步请求
            $.post($('#layer-form').attr("action"), data.field, function(res) {
                if (res.code === 0) {
                    layui.layer.close(layer.index);//关闭弹出层
                    layui.layer.msg(res.msg, {time: 2000, icon: 6});
                    parent.location.reload();//刷新父頁面
                } else {
                    layui.layer.msg(res.msg, {time: 2000, icon: 5});
                }
            });
            return false; //阻止表單跳转
        });

        $('#content').redactor({
            imageWebUpload: '{{ route('admin.upload.upload') }}',
            imageUpload: '{{ route('admin.upload.upload') }}',
            imageDir: 'image',
            thumbWidth: 0,
            thumbHeight: 0,
            plugins: [''], //webuploadImage
            minHeight: '250px',
            maxHeight: '250px',
            lang: 'zh_cn'
        });
    });
</script>
