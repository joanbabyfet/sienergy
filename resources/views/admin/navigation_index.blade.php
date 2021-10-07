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
          <a><cite>菜單管理</cite></a>
        </span>
    </div>
@endsection

{{--內容--}}
@section('content')
    <div class="layui-card-body ">
        <form class="layui-form layui-col-space5" id="search-form" method="GET">
            <div class="layui-inline layui-show-xs-block">
                <input type="text" name="name" placeholder="請輸入名稱" value="{{ request("name") }}" autocomplete="off"
                       class="layui-input">
            </div>
{{--            <div class="layui-inline layui-show-xs-block">--}}
{{--                <select name="guard_name" lay-verify="">--}}
{{--                    <option value=""></option>--}}
{{--                    {!! make_options($guards, request("guard_name")) !!}--}}
{{--                </select>--}}
{{--            </div>--}}
            <div class="layui-inline layui-show-xs-block">
                <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                <button type="reset" id="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </form>
    </div>
    <div class="layui-card-body ">
        <div class="layui-btn-container">
            <a class="layui-btn layui-btn-sm" onclick="admin.openLayerForm('{{ route("admin.navigation.add") }}', '菜單添加', 'POST', '500px', '350px')"><i class="layui-icon"></i>新增</a>
{{--            <a class="layui-btn layui-btn-sm layui-btn-primary" lay-event="batch_del"><i class="layui-icon">&#xe640;</i>批量刪除</a>--}}
        </div>
        <table class="layui-table layui-form" id="tree-table" lay-size="sm"></table>
    </div>
    <!--匯出數據进度条-->
    @include('admin.common.pup_progress')
@endsection

{{--自定義js--}}
@section('script')
    <script>
        var verify = { //自定義表單驗證規則
        };

        layui.use(['form', 'table', 'treeTable'], function () {
            var table = layui.table;
            var form = layui.form;
            form.verify(verify);
            //table.init("table-hide"); //初始化,靜態表格轉動態,獲取lay-filter值,獲取lay-filter值

            var treeTable = layui.treeTable;
            treeTable.render({  //執行方法渲染
                elem: '#tree-table',
                data: {!! $list !!},
                //is_checkbox: true, //顯示複選框
                icon_key: 'name', //必須
                primary_key: 'id',
                parent_key: "parent_id", //父級id
                end: function(e){
                    form.render();
                },
                cols: [
                    { key: 'id', title: 'ID' },
                    { key: 'name', title: '名稱',
                        template: function(item){
                            if(item.level == 0){
                                return '<span style="color:red;">'+item.name+'</span>';
                            }else if(item.level == 1){
                                return '<span style="color:green;">'+item.name+'</span>';
                            }else if(item.level == 2){
                                return '<span style="color:#aaa;">'+item.name+'</span>';
                            }
                        }
                    },
                    { key: 'parent_id', title: '上級ID' },
                    { key: 'uri', title: 'URI',
                        template: function (item) {
                            return item.uri ? item.uri : '-';
                        }
                    },
                    { key: 'permission_name', title: '關聯權限',
                        template: function (item) {
                            return item.permission_name ? item.permission_name : '-';
                        }
                    },
                    //{ key: 'type', title: '菜單類型', align: 'center' },
                    //{ key: 'guard_dis', title: 'Guard', align: 'center' },
                    { key: 'sort', title: '排序', align: 'center' },
                    { title: '操作', align: 'center',
                        template: function(item){
                            return '<a class="layui-btn layui-btn-xs" lay-filter="edit">編輯</a>' +
                                '<a class="layui-btn layui-btn-xs layui-btn-danger" lay-filter="delete">刪除</a>';
                        }
                    }
                ]
            });

            //监听樹事件
            treeTable.on('tree(delete)', function (data) {
                admin.tableDataDelete('{{ route("admin.navigation.delete").'?ids[]=' }}' + data.item.id, this, true);
            });

            treeTable.on('tree(edit)', function (data) {
                admin.openLayerForm('{{ route("admin.navigation.edit").'?id=' }}' + data.item.id, "菜單編輯", 'POST', '500px', '350px');
            });

            //重置
            $('#reset').on('click', function (e) {
                // $('input[name="type"]').val("");
                // $('select[name="guard_name"]').val("");
                $('input[name="name"]').val("");
                $('#search-form').submit();
            });
        });
    </script>
@endsection
