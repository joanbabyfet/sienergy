@if($errors->any())
    <div class="layui-card" style="background: #f2dede">
        @foreach($errors->all() as $error)
            <p style="color: #b94a48;line-height:30px;padding-left:10px;">{{ $error }}</p>
        @endforeach
    </div>
@endif

@if(session('success'))
    <div class="layui-card" style="background: #5FB878">
        <p style="color: #ffffff;line-height:30px;padding-left:10px;">{{ session('success') }}</p>
    </div>
@endif
