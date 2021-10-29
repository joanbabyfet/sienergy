<div class="layui-card-body ">
    <form id="layer-form" class="layui-form" action="" method="POST">
        {{ csrf_field() }}
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">標題:</label>
            <div class="layui-input-block">
                <p class="form-control-static">{{ $row['title'] }}</p>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">狀態:</label>
            <div class="layui-input-block">
                <p class="form-control-static">{{ $row['status_dis'] }}</p>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">內容</label>
            <div class="layui-input-block">
                <p class="form-control-static">{!! $row['content'] !!}</p>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">封面圖片:</label>
            <div class="layui-input-block">
                <!--用来存放文件信息-->
                <div class="uploader-list">
                    @foreach($row['img_dis'] as $k => $v)
                        <div id="WU_FILE_{{ $k + 10 }}" class="item img-item pull-left" style="margin-bottom:10px;margin-right:10px;">
                            <img style="width:100px;height:100px;" src="{{ $row['img_url_dis'][$k] }}">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">附件上傳:</label>
            <div class="layui-input-block">
                <!--用来存放文件信息-->
                <div class="uploader-list">
                    @foreach($row['file_dis'] as $k => $v)
                        <div id="WU_FILE_{{ $k + 10 }}" class="item img-item pull-left" style="margin-bottom:10px;margin-right:10px;">
                            <p class="form-control-static"><a href="{{ $row['file_url_dis'][$k] }}" target="_blank">{{ $v }}</a></p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </form>
</div>
