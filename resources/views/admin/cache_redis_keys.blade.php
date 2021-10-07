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
          <a><cite>Redis鍵值管理</cite></a>
        </span>
    </div>
@endsection

{{--內容--}}
@section('content')
    <div class="layui-card-body ">
        <blockquote class="layui-elem-quote">
            注意要按照redis的key规则，例如：<font color="red">keyword*</font> 或 <font color="red">*keyword*</font>
        </blockquote>
        <form class="layui-form layui-col-space5" id="search-form" method="GET">
            <div class="layui-inline layui-show-xs-block">
                <input type="text" name="keyword" placeholder="請輸入鍵名關鍵字" value="{{ request("keyword") }}" autocomplete="off"
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
            </div>
        </script>
        <table id="list" lay-filter="list" class="layui-table" lay-data="{toolbar: '#toolbar', defaultToolbar: ['filter'], page: false}">
            <thead>
            <tr>
                <th lay-data="{field:'key', type:'checkbox', fixed:'left'}"></th>
                <th lay-data="{field:'sn', width:100}">ID</th>
                <th lay-data="{field:'key'}">鍵名</th>
                <th lay-data="{field:'type'}">類型</th>
                <th lay-data="{field:'len'}">列表長度</th>
                <th lay-data="{field:'ttl'}">剩餘有效時間(秒)</th>
                <th lay-data="{field:'action', fixed: 'right', width:200, align:'center'}">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $k => $v)
            <tr>
                <td>{{ $v['key'] }}</td>
                <td>{{ $k+1 }}</td>
                <td>{{ $v['key'] }}</td>
                <td>{{ $v['type'] }}</td>
                <td>{{ $v['len'] ?: '-' }}</td>
                <td>
                    @if($v['ttl'] > 0)
                        {{ $v['ttl'] }}
                    @elseif($v['ttl'] == -1)
                        永不超時
                    @else
                        不存在
                    @endif
                </td>
                <td>
                    <a class="layui-btn layui-btn-xs"
                       onclick="admin.openLayerForm('{{ route("admin.cache.detail").'?key='.$v['key'] }}', '键值查看', 'POST', '500px', '350px')">查看</a>
                    <a class="layui-btn layui-btn-xs layui-btn-danger"
                       onclick="admin.tableDataDelete('{{ route("admin.cache.delete").'?keys[]='.$v['key'] }}', this)">刪除</a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
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
                var keys = [];
                var flag = 0;
                $(checkStatus.data).each(function (i, o) { //o即为表格中一行的數據
                    keys.push(o.key);
                    flag++;
                });
                switch(obj.event)
                {
                    case 'batch_del': //批量刪除
                        if(flag !== 0){
                            layui.layer.confirm('你確認刪除嗎？', {icon: 3, title: "提示", btn: ['確定', '取消']}, function (){
                                $.ajax({
                                    url: '{{ route('admin.cache.delete') }}',
                                    type: 'POST',
                                    data: { keys: keys }, //送到服务器數據
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
                        }
                        else{
                            layui.layer.msg('請先選擇數據', {time: 2000, icon: 5});
                        }
                        break;
                }
            });

            //重置
            $('#reset').on('click', function (e) {
                $('input[name="keyword"]').val("");
                $('#search-form').submit();
            });
        });
    </script>
@endsection
