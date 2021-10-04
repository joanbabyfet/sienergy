@extends('admin.layouts.app')

@section('title', config('global.admin.app_title'))

{{--自定義css--}}
@section('style')

@endsection

{{--導航--}}
@section('breadcrumb')
    <div class="admin-breadcrumb">
        <span class="layui-breadcrumb">
          <a><cite>首頁</cite></a>
        </span>
    </div>
@endsection

{{--內容--}}
@section('content')

@endsection

{{--自定義js--}}
@section('script')

@endsection
