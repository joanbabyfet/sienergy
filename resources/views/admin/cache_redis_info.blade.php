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
          <a><cite>Redis服務器信息</cite></a>
        </span>
    </div>
@endsection

{{--內容--}}
@section('content')
    <div class="layui-card-body ">
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
        <table id="list" lay-filter="list" class="layui-table">
            <colgroup>
                <col width="200">
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th>鍵名</th>
                    <th>鍵值</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($list as $k => $v)
                <tr>
                    <td>{!! $k !!}</td>
                    <td>{{ $v }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="text-align: center">无数据</td>
                </tr>
                @endforelse
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

            //重置
            $('#reset').on('click', function (e) {
                $('input[name="keyword"]').val("");
                $('#search-form').submit();
            });
        });
    </script>
@endsection
