@extends('web.layouts.app')

@section('title', config('global.web.app_title'))

{{--自定義css--}}
@section('style')
    @parent
    <style>
    </style>
@endsection

{{--內容--}}
@section('content')
    <div id="innerMainRegion">
        <div id="sidebar">
            <div id="innerSidebar">
                <div class="header"><img src="/images/visual/sideBar/sideBarHdNewServices.gif" alt="" width="218"
                                         height="20"/></div>
                <div class="blockA">
                    <div class="main menu">
                    </div>
                    <div class="footer"></div>
                </div>
            </div>
        </div>
        <div id="bodyMain">
            <div id="innerBodyMain">
                <div id="pagePath"><a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span>會員登入</div>
                <div class="mainTitle titleBrand">會員登入</div>
                <div id="mainContents">
                    <div class="layui-card">
                        <div class="layui-card-body ">
                            <form class="layui-form" lay-filter="form-box" action="" method="POST">
                                @include('web.common.msg')
                                {{ csrf_field() }}
                                <div class="layui-form-item">
                                    <label class="layui-form-label label-required-next">用戶名</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="username" required  lay-verify="required" lay-verType=""
                                               placeholder="請輸入用戶名" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label label-required-next">密碼</label>
                                    <div class="layui-input-block">
                                        <input type="password" name="password" required lay-verify="required" lay-verType=""
                                               placeholder="請輸入密碼" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label label-required-next">驗證碼</label>
                                    <div class="layui-input-block">
                                        <input maxlength="4" size="8" name="captcha" id="captcha" type="text"
                                               required  lay-verify="required" placeholder="請輸入驗證碼" autocomplete="off" class="layui-input" />
                                        <img src="{{ captcha_src('flat') }}" style="cursor: pointer" onclick="this.src='{{ captcha_src('flat') }}'+Math.random()">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button type="submit" class="layui-btn layui-btn-sm" lay-submit lay-filter="login">立即登入</button>
                                        <button type="button" id="forget_password" class="layui-btn layui-btn-sm layui-btn-primary">忘記密碼</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="mainFooterSide"></div>
    </div>
@endsection

{{--自定義js--}}
@section('script')
    @parent
    <script>
        layui.use('form', function(){
            var form = layui.form;
            var $ = layui.$;

            form.on('submit(save)', function(data){
            });

            //忘記密碼
            $('#forget_password').on('click', function (e) {
                window.location = '{{ route('web.password.request') }}';
            });
        });
    </script>
@endsection
