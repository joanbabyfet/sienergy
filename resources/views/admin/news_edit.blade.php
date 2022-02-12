<div class="layui-card-body ">
    <form id="layer-form" class="layui-form" action="{{ route("admin.news.edit") }}" method="POST">
        {{ csrf_field() }}
        <input type='hidden' name='id' value="{{ $row['id'] }}"/>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">新聞分類:</label>
            <div class="layui-input-block">
                <select name="cat_id" lay-verify="required">
                    <option value=""></option>
                    {!! make_options($cats, $row['cat_id']) !!}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">新聞標題:</label>
            <div class="layui-input-block">
                <input type="text" name="title" value="{{ $row['title'] }}" placeholder="請輸入新聞標題"
                       class="layui-input" required lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">狀態:</label>
            <div class="layui-input-block">
                <input type="checkbox" {!! ($row['status'] == 1) ? ' checked="checked"':'' !!} name="status" lay-skin="switch" lay-filter="status"
                       lay-text="啟用|禁用">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">新聞內容</label>
            <div class="layui-input-block">
                <textarea id="content" name="content" class="layui-textarea">{{ $row['content'] }}</textarea>
            </div>
        </div>
        <div class="layui-form-item uploader-group uploader-group-img"
             data-token="{{ csrf_token() }}"
             data-dir="image"
             data-extensions="jpg,jpeg,png,bmp"
             data-multiple="true"
             data-auto="true"
             data-size="20"
             data-thumb_w="{{ $img_thumb_with }}"
             data-len="1"
             data-chunked='chunked'>
            <label class="layui-form-label">封面圖片:</label>
            <div class="layui-input-block">
                <!--用来存放文件信息-->
                <div class="uploader-list">
                    @foreach($row['img_dis'] as $k => $v)
                        <div id="WU_FILE_{{ $k + 10 }}" class="item img-item pull-left" style="margin-bottom:10px;margin-right:10px;">
                            <img style="width:100px;height:100px;" src="{{ $row['img_url_dis'][$k] }}">
                            <i class="fa fa-close close-btn"></i>
                            <div class="layui-progress" lay-filter="pro" lay-showPercent="true" style="display: none;">
                                <div class="layui-progress-bar" lay-percent="100%"></div>
                            </div>
                            <input type="hidden" name="img[]" value="{{ $v }}" class="hid-filename">
                        </div>
                    @endforeach
                </div>
                <a class="layui-btn layui-btn-sm layui-btn-primary uploader-picker" data-file="img[]" data-type="image">
                    <i class="layui-icon">&#xe67c;</i> 选择文件</a>
                <div class="layui-row">
                    <div class="layui-col-md12">
                        <div class="layui-form-mid layui-word-aux">格式：jpg、jpeg、png、bmp</div>
                    </div>
                </div>
                <input type="hidden" class="form-control file" datatype="file" nullmsg="至少上传一张" errmsg="至少上传一张">
            </div>
        </div>
        <div class="layui-form-item uploader-group"
             data-token="{{ csrf_token() }}"
             data-dir="doc"
             data-extensions="pdf,doc,docx,xls,xlsx"
             data-multiple="false"
             data-auto="true"
             data-size="20"
             data-thumb_w=""
             data-len="1"
             data-chunked='chunked'>
            <label class="layui-form-label">附件上傳:</label>
            <div class="layui-input-block">
                <!--用来存放文件信息-->
                <div class="uploader-list">
                    @foreach($row['file_dis'] as $k => $v)
                        <div id="WU_FILE_{{ $k + 10 }}" class="item img-item pull-left" style="margin-bottom:10px;margin-right:10px;">
                            <p class="form-control-static"><a href="{{ $row['file_url_dis'][$k] }}">{{ $v }}</a><i class="fa fa-close close-btn" style="color:red"></i></p>
                            <div class="layui-progress" lay-filter="pro" lay-showPercent="true" style="display: none;">
                                <div class="layui-progress-bar" lay-percent="100%"></div>
                            </div>
                            <input type="hidden" name="file[]" value="{{ $v }}" class="hid-filename">
                        </div>
                    @endforeach
                </div>
                <!--data-type=image/file/video-->
                <a class="layui-btn layui-btn-sm layui-btn-primary uploader-picker" data-file="file[]" data-type="file">
                    <i class="layui-icon">&#xe67c;</i> 选择文件</a>
                <div class="layui-row">
                    <div class="layui-col-md12">
                        <div class="layui-form-mid layui-word-aux">格式：pdf、doc、docx、xls、xlsx</div>
                    </div>
                </div>
                <input type="hidden" class="form-control file" datatype="file" nullmsg="至少上传一张" errmsg="至少上传一张">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-sm" lay-filter="save" lay-submit>提交</button>
            </div>
        </div>
    </form>
</div>
<script src="{{ ADMIN_JS }}/webuploader.own.js"></script>
<script>
    var verify = { //自定義表單驗證規則
    };

    layui.use(['form', 'layedit'], function(){
        var form = layui.form;
        var $ = layui.$;
        {{--var layedit = layui.layedit;--}}
        {{--layedit.set({ //要放在 layedit.build前--}}
        {{--    uploadImage: {--}}
        {{--        url: '{{ route('admin.upload.upload') }}', //接口url--}}
        {{--        type: 'post' //默认post--}}
        {{--    }--}}
        {{--});--}}
        {{--var content = layedit.build('content'); //初始化富文本id="content"--}}

        form.verify({
            // content: function(value) { //數據异步提交未同步的原因，使用layedit.sync(index) 解决
            //     return layedit.sync(content);
            // }
        });
        form.on('submit(save)', function(data){
            $.post($('#layer-form').attr("action"), data.field, function(res) {
                if (res.code === 0) {
                    layui.layer.close(layer.index);//关闭弹出层
                    layui.layer.msg(res.msg, {time: 2000, icon: 6});
                    parent.location.reload();//刷新父頁面
                } else {
                    layui.layer.msg(res.msg, {time: 2000, icon: 5});
                }
            });
            return false; //阻止表單跳转
        });

        $('#content').redactor({
            imageWebUpload: '{{ route('admin.upload.upload') }}',
            videoUpload: '{{ route('admin.upload.upload') }}',
            imageDir: 'image',
            videoDir: 'video',
            thumbWidth: 0,
            thumbHeight: 0,
            plugins: ['webuploadImage', 'webuploadVideo', 'fontcolor'],
            minHeight: '250px',
            maxHeight: '250px',
            lang: 'zh_cn'
        });
    });
</script>
