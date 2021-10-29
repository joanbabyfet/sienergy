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
                <div id="pagePath"><a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span>修改密碼</div>
                <div class="mainTitle titleBrand">修改密碼</div>
                <div id="mainContents">
                    <div class="layui-card">
                        <div class="layui-card-body ">
                            <form id="layer-form" class="layui-form" action="{{ route('web.change_pwd.edit') }}" method="POST">
                                @include('web.common.msg')
                                {{ csrf_field() }}
                                <input type='hidden' name='id' value="{{ $row['id'] }}"/>
                                <div class="layui-form-item">
                                    <label class="layui-form-label label-required-next">原密碼:</label>
                                    <div class="layui-input-inline">
                                        <input type="password" name="old_password" value="" placeholder="請輸入原密碼"
                                               class="layui-input" required lay-verify="required">
                                    </div>
                                    <div class="layui-form-mid layui-word-aux">必須大於6位，包含大小寫字母和數字</div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label label-required-next">新密碼:</label>
                                    <div class="layui-input-inline">
                                        <input type="password" name="password" value="" placeholder="請輸入新密碼"
                                               class="layui-input" required lay-verify="required">
                                    </div>
                                    <div class="layui-form-mid layui-word-aux">必須大於6位，包含大小寫字母和數字</div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label label-required-next">確認密碼:</label>
                                    <div class="layui-input-inline">
                                        <input type="password" name="password_confirmation" value="" placeholder="請輸入確認密碼"
                                               class="layui-input" required lay-verify="required|confirm_pass">
                                    </div>
                                    <div class="layui-form-mid layui-word-aux"></div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="layui-btn layui-btn-sm" lay-filter="save" lay-submit>提交</button>
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
                $.post($('#layer-form').attr("action"), data.field, function(res) {
                    if (res.code === 0) {
                        layui.layer.msg(res.msg, {time: 3000, icon: 6});
                        window.location = "{{ route("web.index.index") }}";
                    } else {
                        layui.layer.msg(res.msg, {time: 3000, icon: 5});
                    }
                });
                return false; //阻止表單跳转
            });
        });
    </script>
@endsection
