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
                <textarea id="content" name="content" class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item uploader-group uploader-group-img"
             data-token="{{ csrf_token() }}"
             data-dir="image"
             data-extensions="jpg,jpeg,png,bmp"
             data-multiple="true"
             data-auto="true"
             data-size="20"
             data-thumb_w=""
             data-len="1"
             data-chunked='chunked'>
            <label class="layui-form-label">封面圖片:</label>
            <div class="layui-input-block">
                <!--用来存放文件信息-->
                <div class="uploader-list"></div>
                <a class="layui-btn layui-btn-sm layui-btn-primary uploader-picker" data-file="img[]" data-type="image">
                    <i class="layui-icon">&#xe67c;</i> 选择文件</a>
                <div class="layui-row">
                    <div class="layui-col-md12">
                        <div class="layui-form-mid layui-word-aux">圖片格式需為 jpg、jpeg、png、bmp</div>
                    </div>
                </div>
                <input type="hidden" class="form-control file" datatype="file" nullmsg="至少上传一张" errmsg="至少上传一张">
            </div>
        </div>
        <div class="layui-form-item uploader-group"
             data-token="{{ csrf_token() }}"
             data-dir="doc"
             data-extensions="pdf,doc,docx,xls,xlsx"
             data-multiple="false"
             data-auto="true"
             data-size="20"
             data-thumb_w=""
             data-len="1"
             data-chunked='chunked'>
            <label class="layui-form-label">附件上傳:</label>
            <div class="layui-input-block">
                <!--用来存放文件信息-->
                <div class="uploader-list"></div>
                <!--data-type=image/file/video-->
                <a class="layui-btn layui-btn-sm layui-btn-primary uploader-picker" data-file="file[]" data-type="file">
                    <i class="layui-icon">&#xe67c;</i> 选择文件</a>
                <div class="layui-row">
                    <div class="layui-col-md12">
                        <div class="layui-form-mid layui-word-aux">文件格式需為 pdf、doc、docx、xls、xlsx</div>
                    </div>
                </div>
                <input type="hidden" class="form-control file" datatype="file" nullmsg="至少上传一张" errmsg="至少上传一张">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-sm" lay-filter="save" lay-submit>提交</button>
            </div>
        </div>
    </form>
</div>
<script src="{{ ADMIN_JS }}/webuploader.own.js"></script>
<script>
    var verify = { //自定義表單驗證規則
    };

    layui.use(['form', 'layedit'], function(){
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
            plugins: ['webuploadImage'], //webuploadImage
            minHeight: '250px',
            maxHeight: '250px',
            lang: 'zh_cn'
        });
    });
</script>
