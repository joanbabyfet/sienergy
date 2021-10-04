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
                <div class="header"><img src="/images/visual/sideBar/sideBarHdContact.gif" alt="" width="218"
                                         height="20"/></div>
                <div class="blockA">
                    <div class="main menu">
                        <ul>
                            <li><a href="{{ route('web.contact.index') }}"{!! Request::is('contact') ? ' class="current"':'' !!}>公司資訊</a></li>
                            <li><a href="{{ route('web.contact.feedback') }}"{!! Request::is('feedback') ? ' class="current"':'' !!}>問題諮詢</a></li>
                        </ul>
                    </div>
                    <div class="footer"></div>
                </div>
            </div>
        </div>
        <div id="bodyMain">
            <div id="innerBodyMain">
                <div id="pagePath"><a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span>聯絡鑫盈</div>
                <div class="mainTitle titleBrand">問題諮詢</div>
                <div id="mainContents">
                    <form id="Form" class="layui-form" action="" method="POST">
                        {{ csrf_field() }}
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="formTable">
                        <tr>
                            <th width="100">公司名稱：</th>
                            <td><input name="company_name" type="text" class="text width160"
                                       id="company_name" maxlength="30"/></td>
                        </tr>
                        <tr>
                            <th><span class="required">姓名：</span></th>
                            <td><input name="name" type="text" class="text width160"
                                       id="name" required  lay-verify="required" maxlength="20"/></td>
                        </tr>
                        <tr>
                            <th><span class="required">性別：</span></th>
                            <td><input type="radio" name="sex" id="sex" value="1" title="男" checked/>
                                <input type="radio" name="sex" id="sex" value="0" title="女"/>
                            </td>
                        </tr>
                        <tr>
                            <th><span class="required">電子郵件：</span></th>
                            <td><input name="email" type="text" class="text width300" id="email"
                                       required  lay-verify="required" maxlength="50"/>
                                <br/></td>
                        </tr>
                        <tr>
                            <th>聯絡電話：</th>
                            <td><input name="phone" type="text" class="text width160"
                                       id="phone" maxlength="10"/></td>
                        </tr>
                        <tr>
                            <th><span class="required">驗證碼：</span></th>
                            <td>
                                <img src="{{ captcha_src('flat') }}" style="cursor: pointer" onclick="this.src='{{ captcha_src('flat') }}'+Math.random()">
                                <br/>
                                <input maxlength="4" size="8" name="captcha" id="captcha" type="text"
                                       required  lay-verify="required" value=""/></td>
                        </tr>
                        <tr>
                            <th><span class="required">您的意見：</span></th>
                            <td><textarea name="content" cols="45" rows="5" class="form-textarea"
                                          id="content" required  lay-verify="required"></textarea></td>
                        </tr>
                        <tr>
                            <th></th>
                            <td>
                                <input type="image" name="submit" alt="Submit" lay-submit lay-filter="save" src="/images/icon-sent.png" style="border-width:0px;"/>
                            </td>
                        </tr>
                    </table>
                    </form>
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
                $.post("{{ route("web.contact.feedback") }}", data.field, function(response) {
                    if (response.code === 0) {
                        layui.layer.msg(response.msg, {time: 2000, icon: 6});
                        window.location = "{{ route("web.contact.feedback") }}";
                    } else {
                        layui.layer.msg(response.msg, {time: 3000, icon: 5});
                    }
                });
                return false;
            });
        });
    </script>
@endsection
