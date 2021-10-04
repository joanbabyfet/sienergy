<div class="layui-card-body ">
    <form id="layer-form" class="layui-form" action="{{ route("admin.config.add") }}" method="POST">
        {{ csrf_field() }}
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">說明標題:</label>
            <div class="layui-input-block">
                <input type="text" name="title" placeholder="請輸入說明標題"
                       class="layui-input" required lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">分组:</label>
            <div class="layui-input-block">
                <select name="group" lay-verify="required">
                    <option value=""></option>
                    {!! make_options($groups) !!}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">變量名:</label>
            <div class="layui-input-block">
                <input type="text" name="name" placeholder="請輸入變量名"
                       class="layui-input" required lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">變量值:</label>
            <div class="layui-input-block">
                <textarea name="value" placeholder="請輸入變量值" class="layui-textarea"
                          required lay-verify="required"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">變量類型:</label>
            <div class="layui-input-block">
                <input type="radio" name="type" value="string" title="字符串" checked>
                <input type="radio" name="type" value="number" title="數字">
                <input type="radio" name="type" value="text" title="多行文本">
                <input type="radio" name="type" value="bool" title="Bool">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序:</label>
            <div class="layui-input-block">
                <input type="text" name="sort" value="0" placeholder="請輸入排序"
                       class="layui-input">
            </div>
        </div>
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

    layui.use('form', function(){
        var form = layui.form;
        var $ = layui.$;

        form.verify(verify);
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
