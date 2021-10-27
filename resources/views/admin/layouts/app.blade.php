<!DOCTYPE html>
<html id="admin">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ PLUGINS }}/laravel-layui-admin/lib/layui/css/layui.css">
    <link rel="stylesheet" href="{{ PLUGINS }}/laravel-layui-admin/css/admin.css">
    <link rel="stylesheet" href="{{ PLUGINS }}/redactor/css/redactor.css" />
    <link rel="stylesheet" href="{{ ADMIN_CSS }}/style.css" />
    <link rel="stylesheet" href="{{ ADMIN_CSS }}/font-awesome.min93e3.css" />
    @yield('style')
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">

    @include('admin.common.header')

    @include('admin.common.sidebar')

    <div class="layui-body">
        @yield('breadcrumb')
        <div class="layui-fluid" style="margin-top: 10px;">
            <div class="layui-row">
                <div class="layui-col-md12">
                    @include('admin.common.msg')
                    <div class="layui-card">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.common.footer')
</div>
<script src="{{ PLUGINS }}/laravel-layui-admin/lib/layui/layui.js"></script>
<script src="{{ PLUGINS }}/laravel-layui-admin/js/admin.js"></script>
<!-- REDACTOR Start -->
<script src="{{ PLUGINS }}/redactor/js/jquery-2.0.3.min.js"></script>
<script src="{{ PLUGINS }}/redactor/js/redactor.js"></script>
<script src="{{ PLUGINS }}/redactor/plugins/webuploadImage.js"></script>
<script src="{{ PLUGINS }}/redactor/plugins/webuploadVideo.js"></script>
<script src="{{ PLUGINS }}/redactor/plugins/fontcolor.min.js"></script>
<script src="{{ PLUGINS }}/redactor/plugins/fonttotal.js"></script>
<script src="{{ PLUGINS }}/redactor/js/zh_cn.js"></script>
<!-- REDACTOR End -->
<script src="{{ PLUGINS }}/webuploader/webuploader.min.js"></script>
@yield('script')
<script>
    //JavaScript代碼区域
    layui.use(['element', 'layer', 'util'], function () {
        var element = layui.element;
        var layer = layui.layer;
        var util = layui.util;

        //头部事件
        util.event('lay-header-event', {
            //左侧菜单事件
            menuLeft: function(othis){
                layer.msg('展开左侧菜单的操作', {icon: 0});
            }
            ,menuRight: function(){
                layer.open({
                    type: 1
                    ,title: '更多'
                    ,content: '<div style="padding: 15px;"></div>'
                    ,area: ['260px', '100%']
                    ,offset: 'rt' //右上角
                    ,anim: 5
                    ,shadeClose: true
                    ,scrollbar: false
                });
            }
        });
    });
</script>
</body>
</html>
