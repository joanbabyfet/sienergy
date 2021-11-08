<div class="layui-card-body ">
    <form id="layer-form" class="layui-form" action="{{ route("admin.member.import") }}" method="POST">
        {{ csrf_field() }}
        <div class="layui-form-item uploader-group"
             data-token="{{ csrf_token() }}"
             data-dir="doc"
             data-extensions="xls,xlsx"
             data-multiple="false"
             data-auto="true"
             data-size="20"
             data-thumb_w=""
             data-len="1"
             data-chunked='chunked'>
            <label class="layui-form-label label-required-next">Excel上傳:</label>
            <div class="layui-input-block">
                <!--用来存放文件信息-->
                <div class="uploader-list"></div>
                <!--data-type=image/file/video-->
                <a class="layui-btn layui-btn-sm layui-btn-primary uploader-picker" data-file="excel[]" data-type="file">
                    <i class="layui-icon">&#xe67c;</i> 选择文件</a>
                <div class="layui-row">
                    <div class="layui-col-md12">
                        <div class="layui-form-mid layui-word-aux">格式：xls、xlsx</div>
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

    layui.use(['form'], function(){
        var form = layui.form;
        var $ = layui.$;

        form.verify({
        });
        form.on('submit(save)', function(data){
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
    });
</script>
