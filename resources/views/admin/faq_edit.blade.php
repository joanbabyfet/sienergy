<div class="layui-card-body ">
    <form id="layer-form" class="layui-form" action="{{ route("admin.faq.edit") }}" method="POST">
        {{ csrf_field() }}
        <input type='hidden' name='id' value="{{ $row['id'] }}"/>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">分類:</label>
            <div class="layui-input-block">
                <select name="cat_id" lay-verify="required">
                    <option value=""></option>
                    {!! make_options($cats, $row['cat_id']) !!}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">問題:</label>
            <div class="layui-input-block">
                <textarea name="question" placeholder="請輸入問題" class="layui-textarea"
                          required lay-verify="required">{{ $row['question'] }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">狀態:</label>
            <div class="layui-input-block">
                <input type="checkbox" {!! ($row['status'] == 1) ? ' checked="checked"':'' !!} name="status" lay-skin="switch" lay-filter="status"
                       lay-text="啟用|禁用">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">回復:</label>
            <div class="layui-input-block">
                <textarea name="answer" placeholder="請輸入回復" class="layui-textarea"
                          required lay-verify="required">{{ $row['answer'] }}</textarea>
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
