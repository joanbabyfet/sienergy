<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('global.admin.app_title') }} - 登入</title>
    <link rel="stylesheet" href="{{ ADMIN_CSS }}/login.css" />
    <link rel="stylesheet" href="{{ PLUGINS }}/laravel-layui-admin/lib/layui/css/layui.css">
</head>
<body>
<div id="logincontainer">
    <div id="loginbox">
        <div id="loginheader">
            <img src="{{ ADMIN_IMG }}/hanboxLogoA.png" alt="Control Panel Login" />
{{--            <h2>{{ config('global.admin.app_title') }}</h2>--}}
        </div>
        <div id="innerlogin">
            <form class="layui-form" lay-filter="form-box" action="" method="POST">
                {{ csrf_field() }}
                @include('admin.common.msg')
                <div class="layui-form-item">
                    <label class="layui-form-label layui-form-mid" style="color: #FAFAFA">用戶名</label>
                    <div class="layui-input-block">
                        <input type="text" name="username" required  lay-verify="required" lay-verType=""
                               placeholder="請輸入用戶名" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label layui-form-mid" style="color: #FAFAFA">密　碼</label>
                    <div class="layui-input-block">
                        <input type="password" name="password" required lay-verify="required" lay-verType=""
                               placeholder="請輸入密碼" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <button type="submit" class="layui-btn" lay-submit lay-filter="login">立即登入</button>
                    <button type="button" class="layui-btn layui-btn-primary">忘記密碼</button>
                </div>
            </form>
        </div>
    </div>
    <img src="{{ ADMIN_IMG }}/login_fade.png" alt="Fade" />
</div>
<script src="{{ PLUGINS }}/laravel-layui-admin/lib/layui/layui.js"></script>
<script src="{{ PLUGINS }}/laravel-layui-admin/js/admin.js"></script>
<script>
    //JavaScript代碼区域
    var verify = {
    };

    layui.use('form', function(){
        var form = layui.form;
        var $ = layui.$;

        form.verify(verify);
        form.on('submit(login)',function(data) {
        });
    });
</script>
</body>
</html>
