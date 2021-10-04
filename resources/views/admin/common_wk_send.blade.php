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
          <a><cite>测试workerman</cite></a>
        </span>
    </div>
@endsection

{{--內容--}}
@section('content')
    <div class="layui-card-body ">
        <form id="layer-form" class="layui-form" action="" method="POST">
            {{ csrf_field() }}
            <div class="layui-form-item">
                <label class="layui-form-label">內容:</label>
                <div class="layui-input-block">
                <textarea id="content" name="content" placeholder="請輸入內容"
                          class="layui-textarea"></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn layui-btn-sm" id="submit">发送</button>
                </div>
            </div>
        </form>
    </div>
@endsection

{{--自定義js--}}
@section('script')
    <script>
        //连接服务端
        ws = new WebSocket("ws://127.0.0.1:2347");
        //webSocket连接开启时调用
        ws.onopen = function() {
            console.log("服务已开启");
            say_hi();
            heartbeat();
        };
        //服务端送来消息时
        ws.onmessage = function(e) {
            console.log("收到服务端的消息：" + e.data);
        };
        //连接断开时调用
        ws.onclose = function(e) {
            console.log("服务已断开" );
        };
        //连接出错时调用
        ws.onerror = function(e) {
            console.log("服务端错误" );
        };

        //客戶端第一次连接向服务器say hi
        function say_hi()
        {
            var obj = {action: "say_hi", token: "{{ $token }}"};
            var str = JSON.stringify(obj); //对象转json字符串，字符串转对象用JSON.parse(str)
            console.log("~~Say Hi~~");
            ws.send(str);
        }

        //連接上即發送心跳包，在10秒内未向服务端发送數據，将会被切断
        function heartbeat()
        {
            console.log("~~Heartbeat~~");
            var str = "~H#C~";
            ws.send(str);
            setInterval(function () { //轮询8秒一次
                console.log("~~Heartbeat~~");
                ws.send(str);
            }, 10 * 1000);
        }

        function send()
        {
            var msg = $('#content').val();
            var obj = {action: "timestamp", token: "{{ $token }}", data: msg};
            var data = JSON.stringify(obj);
            ws.send(data);

            $('#content').val('');
            $('#content').focus();
        }

        layui.use(['form'], function() {
            var $ = layui.$;
            var form = layui.form;

            $('#submit').on('click', function (e) {
                e.preventDefault(); //当点击提交钮,阻止表單提交
                send();
                return false; //阻止表單跳转
            });

            $('#content').on("keydown", function(e) {
                // 按enter键自动提交
                if(e.keyCode === 13 && !e.ctrlKey) {
                    e.preventDefault();
                    send();
                    return false;
                }
                // 按ctrl+enter組合键换行
                if(e.keyCode === 13 && e.ctrlKey) {
                    $(this).val(function(i,val){
                        return val + "\n";
                    });
                }
            });
        });
    </script>
@endsection
