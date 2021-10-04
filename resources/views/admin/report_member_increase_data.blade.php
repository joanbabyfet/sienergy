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
          <a><cite>會員增長數</cite></a>
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
                <select name="origin" lay-verify="">
                    <option value=""></option>
                    {!! make_options($origins, request("origin")) !!}
                </select>
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
                <a class="layui-btn layui-btn-sm" lay-event="export"><i class="layui-icon">&#xe67d;</i>匯出</a>
            </div>
        </script>
        <table id="list" lay-filter="list" style="display: none"
               lay-data="{height:'full-310', cellMinWidth: 80,toolbar: '#toolbar', defaultToolbar: ['filter'], limit: {{ $pages->perPage() }} }">
            <thead>
            <tr>
                <th lay-data="{field:'date', sort:true}">日期</th>
                <th lay-data="{field:'origin_dis', sort:true}">来源</th>
                <th lay-data="{field:'member_count', sort:true}">用戶數</th>
                <th lay-data="{field:'member_increase_count', sort:true}">用戶日增长</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $k => $v)
                <tr>
                    <td>{{ $v['date'] }}</td>
                    <td>{{ $v['origin_dis'] ?: '-' }}</td>
                    <td>{{ $v['member_count'] ?: '-' }}</td>
                    <td>{{ $v['member_increase_count'] ?: '-' }}</td>
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
                    ids.push(o.id);
                });
                switch(obj.event)
                {
                    //匯出
                    case 'export':
                        var search_str = $('#search-form').serialize();
                        var _data = {fields:[]};
                        _data.fields.push('date');
                        _data.fields.push('origin_dis');
                        _data.fields.push('member_count');
                        _data.fields.push('member_increase_count');
                        export_excel('{{ route('admin.report.export_list') }}', _data, search_str);
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
                $('select[name="origin"]').val("");
                $('#search-form').submit();
            });
        });
    </script>
@endsection
