<div class="layui-card-body ">
    <form id="layer-form" class="layui-form" action="{{ route("admin.navigation.add") }}" method="POST">
        {{ csrf_field() }}
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">名稱:</label>
            <div class="layui-input-block">
                <input type="text" name="name" placeholder="請輸入名稱"
                       class="layui-input" required lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">上級ID:</label>
            <div class="layui-input-block">
                <input type="text" name="parent_id" value="0" placeholder="請輸入上級ID"
                       class="layui-input" required lay-verify="required">
            </div>
        </div>
{{--        <div class="layui-form-item">--}}
{{--            <label class="layui-form-label label-required-next">Guard:</label>--}}
{{--            <div class="layui-input-block">--}}
{{--                <select name="guard_name" lay-verify="required">--}}
{{--                    <option value=""></option>--}}
{{--                    {!! make_options($guards) !!}--}}
{{--                </select>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="layui-form-item">
            <label class="layui-form-label">URL:</label>
            <div class="layui-input-block">
                <input type="text" name="uri" placeholder="請輸入URL"
                       class="layui-input">
            </div>
        </div>
{{--        <div class="layui-form-item">--}}
{{--            <label class="layui-form-label label-required-next">菜單類型:</label>--}}
{{--            <div class="layui-input-block">--}}
{{--                <input type="text" name="type" value="admin" placeholder="請輸入菜單類型"--}}
{{--                       class="layui-input" required lay-verify="required">--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="layui-form-item">
            <label class="layui-form-label">關聯權限:</label>
            <div class="layui-input-block">
                <input type="text" name="permission_name" value="" placeholder="請輸入關聯權限"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">icon:</label>
            <div class="layui-input-block">
                <input type="text" name="icon" value="" placeholder="請輸入icon"
                       class="layui-input">
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
