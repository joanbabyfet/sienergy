<div class="layui-card-body ">
    <form id="layer-form" class="layui-form" action="" method="POST">
        {{ csrf_field() }}
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">標題:</label>
            <div class="layui-input-block">
                <p class="form-control-static">{{ $row['name'] }}</p>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">內容</label>
            <div class="layui-input-block">
                <p class="form-control-static">{!! $row['content'] !!}</p>
            </div>
        </div>
    </form>
</div>
