<div class="layui-card-body ">
    <form id="layer-form" class="layui-form" action="{{ route("admin.member.edit") }}" method="POST">
        {{ csrf_field() }}
        <input type='hidden' name='id' value="{{ $row['id'] }}"/>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">用戶名:</label>
            <div class="layui-input-block">
                <p class="form-control-static">{{ $row['username'] }}</p>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">用戶密碼:</label>
            <div class="layui-input-inline">
                <input type="password" name="password" value="" placeholder="請輸入用戶密碼"
                       class="layui-input" lay-verify="">
            </div>
            <div class="layui-form-mid layui-word-aux">必須大於6位，包含大小寫字母和數字</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">真實姓名:</label>
            <div class="layui-input-block">
                <input type="text" name="realname" value="{{ $row['realname'] }}" placeholder="請輸入真實姓名"
                       class="layui-input" required lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">郵箱:</label>
            <div class="layui-input-block">
                <input type="text" name="email" value="{{ $row['email'] }}" placeholder="請輸入郵箱"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">手機號:</label>
            <div class="layui-input-inline" style="width: 100px;">
                <select name="phone_code" lay-verify="required">
                    <option value=""></option>
                    {!! make_options($mobile_prefix_options, $row['phone_code']) !!}
                </select>
            </div>
            <div class="layui-input-inline">
                <input type="text" name="phone" value="{{ $row['phone'] }}" placeholder="請輸入聯絡電話"
                       class="layui-input" required lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">會員等級:</label>
            <div class="layui-input-block">
{{--                @foreach($roles as $role)--}}
{{--                    <div class="layui-col-sm4">--}}
{{--                        <input type="checkbox" name="roles[]" lay-skin="primary" value="{{ $role['id'] }}"--}}
{{--                               title="{{ $role['name'] }}"{!! in_array($role['id'], $groups) ? ' checked':'' !!}>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
                <select name="role_id" lay-verify="required">
                    <option value=""></option>
                    {!! make_options($roles, $row['role_id']) !!}
                </select>
            </div>
            {{--            <div class="layui-form-mid layui-word-aux">用戶權限 = 所屬組權限 + 獨立權限</div>--}}
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
