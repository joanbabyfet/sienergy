<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        *{ margin:0; padding:0; color:#333; font-size:12px; }
        .msgbox { background:#CBCCD0; padding:5px; width:500px; margin:50px auto; }
        .msgbox h2{ font-size:12px; height:28px; line-height:28px; border-bottom:1px #909090 solid; padding:0 10px; background:#E1E1E1; }
        .msgbox .content{ padding:20px 0; text-align:center; background:#fff; }
        .msgbox h4{ font-size:14px; line-height:24px; margin-bottom:10px; }
    </style>
    <title> {{ $title }} </title>
</head>
<body>

<div class="msgbox">
    <div style="border:1px #909090 solid;">
        <h2>{{ $title }}</h2>
        <div class="content">
            <h4> {{ $msg }} </h4>
            {!! $jump_msg !!}
        </div>
    </div>
</div>
<script lang='javascript'>
    function JumpUrl(url) {
        location = url;
    }
    {!! $js_tmp !!}
</script>
</body>
</html>
