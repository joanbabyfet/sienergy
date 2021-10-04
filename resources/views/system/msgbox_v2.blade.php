<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> {{ $title }} </title>
    <script type="text/javascript" src="{{ PLUGINS }}/laravel-layui-admin/js/jquery.min.js"></script>
    <script type="text/javascript" src="{{ PLUGINS }}/laravel-layui-admin/lib/layui/layui.js?"></script>
</head>
<body>

<div>
</div>

<script>
    layui.use('layer', function(){
        layer.msg("{{ $msg }}</br>{!! $jump_msg !!}", {time: 3000, icon: '{{ $icon }}'});
    });

    function JumpUrl(url) {
        location = url;
    }
    {!! $js_tmp !!}
</script>
</body>
</html>
