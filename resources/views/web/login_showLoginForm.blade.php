<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('global.web.app_title') }} - 會員登入</title>
    <link rel="stylesheet" href="{{ asset('/vendor/laravel-layui-admin/lib/layui/css/layui.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/laravel-layui-admin/css/admin.css') }}">
</head>
<body class="layui-layout-body" style="background: #4e97d9;">
<canvas class="background" ></canvas>
<div id="admin-login">
    <form class="layui-form" lay-filter="form-box" action="" method="POST">
        @include('web.common.msg')
        {{ csrf_field() }}
        <h2>{{ config('global.web.app_title') }}</h2>
        <div class="layui-form-item">
            <label class="layui-form-label">用戶名</label>
            <div class="layui-input-block">
                <input type="text" name="username" required  lay-verify="required" lay-verType=""
                       placeholder="請輸入用戶名" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">密碼</label>
            <div class="layui-input-block">
                <input type="password" name="password" required lay-verify="required" lay-verType=""
                       placeholder="請輸入密碼" autocomplete="off" class="layui-input">
            </div>
        </div>
{{--        <div class="layui-form-item">--}}
{{--            <label class="layui-form-label">驗證碼</label>--}}
{{--            <div class="layui-input-block">--}}
{{--                <input maxlength="4" size="8" name="captcha" id="captcha" type="text"--}}
{{--                       required  lay-verify="required" placeholder="請輸入驗證碼" autocomplete="off" class="layui-input" />--}}
{{--                <img src="{{ captcha_src('flat') }}" style="cursor: pointer" onclick="this.src='{{ captcha_src('flat') }}'+Math.random()">--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button type="submit" class="layui-btn" lay-submit lay-filter="login">立即登入</button>
                <button type="button" class="layui-btn layui-btn-primary">忘記密碼</button>
            </div>
        </div>
    </form>
</div>
<script src="{{ asset('/vendor/laravel-layui-admin/lib/layui/layui.js') }}"></script>
<script src="{{ asset('/vendor/laravel-layui-admin/js/admin.js') }}"></script>
<script src="{{ asset('/vendor/laravel-layui-admin/js/particles.min.js') }}"></script>
<script>
    window.onload = function() {
        Particles.init({
            selector: '.background',
            color: '#ffffff',
            maxParticles: 80,
            connectParticles: true,
            responsive: [
                {
                    breakpoint: 768,
                    options: {
                        maxParticles: 80
                    }
                }, {
                    breakpoint: 375,
                    options: {
                        maxParticles: 50
                    }
                }
            ]
        });
    };
    //JavaScript代码区域
    var verify = {
    };

    layui.use('form', function(){
        var form = layui.form;
        var $ = layui.$;

        form.verify(verify);
        form.on('submit(login)',function(data) {
            //console.log(data.field);
            //return false; //阻止表单跳转
            // 用于提交成功初始化表单
            // form.val("form-box",{
            //     'username':''
            // });
        });
    });
</script>
</body>
</html>
