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
          <a><cite>操作日志</cite></a>
        </span>
    </div>
@endsection

{{--內容--}}
@section('content')
    <div class="layui-card-body ">
        <form class="layui-form layui-col-space5" id="search-form" method="GET">
            <div class="layui-inline layui-show-xs-block">
                <input type="text" name="date1" id="date1" class="layui-input" value="{{ request("date1") }}" placeholder="開始日期">
            </div>
            <div class="layui-inline layui-show-xs-block">
                <input type="text" name="date2" id="date2" class="layui-input" value="{{ request("date2") }}" placeholder="結束日期">
            </div>
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
                <a class="layui-btn layui-btn-sm" lay-event="batch_del"><i class="layui-icon">&#xe640;</i>批量刪除</a>
                <a class="layui-btn layui-btn-sm" lay-event="export"><i class="layui-icon">&#xe67d;</i>匯出</a>
            </div>
        </script>
        <table id="list" lay-filter="list" style="display: none"
               lay-data="{height:'full-310', cellMinWidth: 80,toolbar: '#toolbar', defaultToolbar: ['filter'], limit: {{ $pages->perPage() }} }">
            <thead>
            <tr>
                <th lay-data="{field:'_id', type:'checkbox', fixed:'left'}"></th>
                <th lay-data="{field:'_id'}">ID</th>
                <th lay-data="{field:'username'}">用戶名</th>
                <th lay-data="{field:'op_ip'}">操作地址</th>
                <th lay-data="{field:'op_country'}">操作國家</th>
                <th lay-data="{field:'op_time_dis'}">操作時間</th>
                <th lay-data="{field:'msg'}">操作说明</th>
                <th lay-data="{field:'action', fixed: 'right', width:200, align:'center'}">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $k => $v)
                <tr>
                    <td>{{ $v['_id'] }}</td>
                    <td>{{ $v['_id'] }}</td>
                    <td>{{ $v['username'] ?: '-' }}</td>
                    <td>{{ $v['op_ip'] }}</td>
                    <td>{{ $v['op_country'] ?: '-' }}</td>
                    <td>{{ $v['op_time_dis'] }}</td>
                    <td>{{ $v['msg'] ?: '-' }}</td>
                    <td>
                        <a class="layui-btn layui-btn-xs layui-btn-danger"
                           onclick="admin.tableDataDelete('{{ route("admin.admin_user_oplog.delete").'?ids[]='.$v['_id'] }}', this)">刪除</a>
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

        layui.use(['form', 'table', 'laydate'], function () {
            var table = layui.table;
            var form = layui.form;
            var laydate = layui.laydate;

            laydate.render({ //開始日期
                elem: '#date1',
                format: 'yyyy/MM/dd'
            });
            laydate.render({ //結束日期
                elem: '#date2',
                format: 'yyyy/MM/dd'
            });

            form.verify(verify);
            table.init('list'); //初始化,靜態表格轉動態,獲取lay-filter值,獲取lay-filter值

            //头工具栏事件
            table.on('toolbar(list)', function(obj){
                var noRefresh = false;
                var checkStatus = table.checkStatus(obj.config.id);
                var ids = [];
                $(checkStatus.data).each(function (i, o) { //o即为表格中一行的數據
                    ids.push(o._id);
                });
                switch(obj.event)
                {
                    case 'batch_del': //批量刪除
                        layui.layer.confirm('你確認刪除嗎？', {btn: ['確定', '取消']}, function (){
                            $.ajax({
                                url: '{{ route('admin.admin_user_oplog.delete') }}',
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
                        _data.fields.push('_id');
                        _data.fields.push('username');
                        _data.fields.push('op_ip');
                        _data.fields.push('op_country');
                        _data.fields.push('op_time_dis');
                        _data.fields.push('msg');
                        export_excel('{{ route('admin.admin_user_oplog.export_list') }}', _data, search_str);
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
                $('input[name="date1"]').val("");
                $('input[name="date2"]').val("");
                $('input[name="username"]').val("");
                $('#search-form').submit();
            });
        });
    </script>
@endsection
