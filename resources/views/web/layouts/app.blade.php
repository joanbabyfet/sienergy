<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
{{--    <meta name="csrf-token" content="{{ csrf_token() }}">--}}
    <title>@yield('title')</title>
    <link href="{{ WEB_CSS.'/sienergy/style.css' }}" rel="stylesheet" type="text/css"/>
    <link href="{{ WEB_CSS.'/sienergy/form.css' }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ PLUGINS }}/laravel-layui-admin/lib/layui/css/layui.css">
    @section('style')
        {{-- 自定义css --}}
    @show
</head>

<body>
<div id="container">
    <div id="HeaderIdx">
        @include('web.common.header')
    </div>
    <div id="pageBanner"><img src="{{ WEB_IMG.'/visual/idxVisualB.jpg' }}" width="980" height="210"/></div>
    <div id="main">
        <div id="bannerSideIdx"></div>
        <div id="mainRegion">
            @yield('content')
        </div>
    </div>
    <div id="footer">
        @include('web.common.footer')
    </div>
</div>
<script src="{{ PLUGINS }}/laravel-layui-admin/lib/layui/layui.js"></script>
<script src="{{ WEB_JS }}/main.js"></script>
@section('script')
    {{-- 自定义js --}}
@show
@include('web.common.google_analytics')
</body>
