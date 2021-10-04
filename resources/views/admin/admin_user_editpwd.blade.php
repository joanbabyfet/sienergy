@extends('admin.layouts.app')

@section('title', config('global.admin.app_title'))

{{--自定義css--}}
@section('style')

@endsection

{{--導航--}}
@section('breadcrumb')
    <div class="admin-breadcrumb">
        <span class="layui-breadcrumb">
          <a href="{{ route('admin.index.index') }}">首頁</a>
          <a><cite>修改密碼</cite></a>
        </span>
    </div>
@endsection

{{--內容--}}
@section('content')
    <div class="layui-card-body ">
        <form id="layer-form" class="layui-form" action="" method="POST">
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
                           class="layui-input" required lay-verify="required">
                </div>
                <div class="layui-form-mid layui-word-aux">必須大於6位，包含大小寫字母和數字</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label label-required-next">確認密碼:</label>
                <div class="layui-input-inline">
                    <input type="password" name="passwordok" value="" placeholder="請輸入確認密碼"
                           class="layui-input" required lay-verify="required|confirm_pass">
                </div>
                <div class="layui-form-mid layui-word-aux"></div>
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
                           class="layui-input" lay-verify="email">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">用戶組:</label>
                <div class="layui-input-block">
                    <p class="form-control-static">{{ $row['role_name'] ?: '-' }}</p>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">上次登入時間:</label>
                <div class="layui-input-block">
                    <p class="form-control-static">{{ $row['login_time_dis'] ?: '-' }}</p>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">上次登入地址:</label>
                <div class="layui-input-block">
                    <p class="form-control-static">{{ $row['login_ip'] ?: '-' }}</p>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
{{--                    提交钮設置 lay-submit 属性，才会触发layui的表單验证规则--}}
                    <button class="layui-btn layui-btn-sm" lay-filter="save" lay-submit>提交</button>
                </div>
            </div>
        </form>
    </div>
@endsection

{{--自定義js--}}
@section('script')
    <script>
        var verify = { //自定義表單驗證規則
            confirm_pass:function(value){
                if($('input[name=password]').val() !== value)
                    return '兩次密碼輸入不一致';
            }
        };

        layui.use('form', function(){
            var form = layui.form;
            var $ = layui.$;

            form.verify(verify);
            form.on('submit(save)', function(data){
                $.post("{{ route("admin.admin_user.editpwd") }}", data.field, function(res) {
                    if (res.code === 0) {
                        layui.layer.msg(res.msg, {time: 2000, icon: 6});
                        window.location = "{{ route("admin.admin_user.editpwd") }}";
                    } else {
                        layui.layer.msg(res.msg, {time: 2000, icon: 5});
                    }
                });
                return false; //阻止表單跳转
            });
        });
    </script>
@endsection
