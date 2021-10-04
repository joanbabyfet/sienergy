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
          <a><cite>用戶管理</cite></a>
        </span>
    </div>
@endsection

{{--內容--}}
@section('content')
    <div class="layui-card-body ">
        <form class="layui-form layui-col-space5" id="search-form" method="GET">
            <div class="layui-inline layui-show-xs-block">
                <input type="text" name="username" placeholder="請輸入用戶名" value="{{ request("username") }}" autocomplete="off"
                       class="layui-input">
            </div>
            <div class="layui-inline layui-show-xs-block">
                <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                <button type="reset" id="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </form>
    </div>
    <div class="layui-card-body ">
        <script type="text/html" id="toolbar">
            <div class="layui-btn-container">
                <a class="layui-btn layui-btn-sm" onclick="admin.openLayerForm('{{ route("admin.admin_user.add") }}', '用戶添加', 'POST', '600px', '350px')"><i class="layui-icon"></i>新增</a>
                <a class="layui-btn layui-btn-sm" lay-event="batch_del"><i class="layui-icon">&#xe640;</i>批量刪除</a>
                <a class="layui-btn layui-btn-sm" lay-event="batch_enable"><i class="layui-icon">&#xe605;</i>啟用</a>
                <a class="layui-btn layui-btn-sm" lay-event="batch_disable"><i class="layui-icon">&#x1006;</i>禁用</a>
                <a class="layui-btn layui-btn-sm" lay-event="export"><i class="layui-icon">&#xe67d;</i>匯出</a>
            </div>
        </script>
        <table id="list" lay-filter="list" style="display: none"
               lay-data="{height:'full-310', cellMinWidth: 80,toolbar: '#toolbar', defaultToolbar: ['filter'], limit: {{ $pages->perPage() }} }">
            <thead>
            <tr>
                <th lay-data="{field:'id', type:'checkbox', fixed:'left'}"></th>
                <th lay-data="{field:'username'}">用戶名</th>
                <th lay-data="{field:'realname'}">真實姓名</th>
{{--                <th lay-data="{field:'email'}">郵箱</th>--}}
                <th lay-data="{field:'role_name'}">用戶組</th>
                <th lay-data="{field:'login_time_dis'}">上次登入</th>
                <th lay-data="{field:'status'}">狀態</th>
                <th lay-data="{field:'create_time_dis'}">添加時間</th>
                <th lay-data="{field:'action', fixed: 'right', width:200, align:'center'}">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $k => $v)
                <tr>
                    <td>{{ $v['id'] }}</td>
                    <td>{{ $v['username'] }}</td>
                    <td>{{ $v['realname'] }}</td>
{{--                    <td>{{ $v['email'] }}</td>--}}
                    <td>{{ $v['role_name'] ?: '-' }}</td>
                    <td>{{ $v['login_time_dis'] ?: '-' }}</td>
                    <td>{{ $v['status_dis'] }}</td>
                    <td>{{ $v['create_time_dis'] }}</td>
                    <td>
                        <a class="layui-btn layui-btn-xs"
                           onclick="admin.openLayerForm('{{ route("admin.admin_user.edit").'?id='.$v['id'] }}', '用戶編輯', 'POST', '600px', '350px')">編輯</a>
                        <a class="layui-btn layui-btn-xs"
                           onclick="admin.openLayerForm('{{ route("admin.admin_user.purview").'?id='.$v['id'] }}', '獨立權限設置', 'POST', '600px', '400px')">獨立權限</a>
                        <a class="layui-btn layui-btn-xs layui-btn-danger"
                           onclick="admin.tableDataDelete('{{ route("admin.admin_user.del_purview").'?id='.$v['id'] }}', this)">清除權限</a>
                        <a class="layui-btn layui-btn-xs layui-btn-danger"
                           onclick="admin.tableDataDelete('{{ route("admin.admin_user.delete").'?ids[]='.$v['id'] }}', this)">刪除</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div id="page"></div>
    </div>
    <!--匯出數據进度条-->
    @include('admin.common.pup_progress')
@endsection

{{--自定義js--}}
@section('script')
    <script>
        var verify = { //自定義表單驗證規則
        };

        layui.use(['form', 'table'], function () {
            var table = layui.table;
            var form = layui.form;
            form.verify(verify);
            table.init('list'); //初始化,靜態表格轉動態,獲取lay-filter值,獲取lay-filter值

            //头工具栏事件
            table.on('toolbar(list)', function(obj){
                var noRefresh = false;
                var checkStatus = table.checkStatus(obj.config.id);
                var ids = [];
                $(checkStatus.data).each(function (i, o) { //o即为表格中一行的數據
                    ids.push(o.id);
                });
                switch(obj.event)
                {
                    case 'batch_del': //批量刪除
                        layui.layer.confirm('你確認刪除嗎？', {btn: ['確定', '取消']}, function (){
                            $.ajax({
                                url: '{{ route('admin.admin_user.delete') }}',
                                type: 'POST',
                                data: { ids: ids }, //送到服务器數據
                                success: function(res) {
                                    if (res.code === 0) {
                                        layui.layer.msg(res.msg, {time: 2000, icon: 6});
                                        if (!noRefresh) {
                                            window.location = window.location.href
                                        }
                                    }
                                    else {
                                        layui.layer.msg(res.msg, {time: 2000, icon: 5});
                                    }
                                },
                                error: function () {
                                }
                            });
                        });
                        break;
                    case 'batch_enable': //批量啟用
                        layui.layer.confirm('你確認啟用嗎？', {btn: ['確定', '取消']}, function (){
                            $.ajax({
                                url: '{{ route('admin.admin_user.enable') }}',
                                type: 'POST',
                                data: { ids: ids }, //送到服务器數據
                                success: function(res) {
                                    if (res.code === 0) {
                                        layui.layer.msg(res.msg, {time: 2000, icon: 6});
                                        if (!noRefresh) {
                                            window.location = window.location.href
                                        }
                                    }
                                    else {
                                        layui.layer.msg(res.msg, {time: 2000, icon: 5});
                                    }
                                },
                                error: function () {
                                }
                            });
                        });
                        break;
                    case 'batch_disable': //批量禁用
                        layui.layer.confirm('你確認禁用嗎？', {btn: ['確定', '取消']}, function (){
                            $.ajax({
                                url: '{{ route('admin.admin_user.disable') }}',
                                type: 'POST',
                                data: { ids: ids }, //送到服务器數據
                                success: function(res) {
                                    if (res.code === 0) {
                                        layui.layer.msg(res.msg, {time: 2000, icon: 6});
                                        if (!noRefresh) {
                                            window.location = window.location.href
                                        }
                                    }
                                    else {
                                        layui.layer.msg(res.msg, {time: 2000, icon: 5});
                                    }
                                },
                                error: function () {
                                }
                            });
                        });
                        break;
                    //匯出
                    case 'export':
                        var search_str = $('#search-form').serialize();
                        var _data = {fields:[]};
                        _data.fields.push('username');
                        _data.fields.push('realname');
                        //_data.fields.push('email');
                        _data.fields.push('role_name');
                        _data.fields.push('login_time_dis');
                        export_excel('{{ route('admin.admin_user.export_list') }}', _data, search_str);
                        break;
                }
            });

            //监听行工具事件
            table.on("tool(list)", function (obj) {
                console.log(obj);
                switch (obj.event) {
                    case 'edit':
                        console.log(obj.data);
                        break;
                    case 'delete':
                        console.log(obj.data);
                        break;
                }
            });
            //分頁器
            admin.paginate("{{ $pages->total() }}", "{{ $pages->currentPage() }}", "{{ $pages->perPage() }}");

            //重置
            $('#reset').on('click', function (e) {
                $('input[name="username"]').val("");
                $('#search-form').submit();
            });
        });
    </script>
@endsection
