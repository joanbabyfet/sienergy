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
                <div class="layui-upload-list uploader-list img">
                    @foreach($row['img_dis'] as $k => $v)
                        <div id="" class="file-item">
                            <div class="handle"><i class="layui-icon layui-icon-close"></i></div>
                            <img style="width: 100px;height: 100px;" src="{{ $row['img_url_dis'][$k] }}">
                            <input type="hidden" name="img[]" value="{{ $v }}" />
                            <div class="info"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
{{--        <div class="layui-form-item">--}}
{{--            <label class="layui-form-label">附件上傳:</label>--}}
{{--            <div class="layui-input-block">--}}
{{--                <div class="layui-upload-list uploader-list file">--}}
{{--                    @foreach($row['file_dis'] as $k => $v)--}}
{{--                        <div id="" class="file-item">--}}
{{--                            <div class="handle"><i class="layui-icon layui-icon-close"></i></div>--}}
{{--                            <img class="layui-upload-img" style="width: 100px;height: 100px;" src="{{ $row['file_url_dis'][$k] }}">--}}
{{--                            <input type="hidden" name="file[]" value="{{ $v }}" />--}}
{{--                            <div class="info"></div>--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </form>
</div>
