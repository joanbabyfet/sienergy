(function($R) {
    $R.add('plugin', 'webuploadVideo', {
        translations: {
            en: {
                "webuploadVideo": "video",
                'title': 'video upload',
                'choose': 'choose'
            },
            zh_cn: {
                "webuploadVideo": "视频",
                "title": "请加入视频链接或嵌入代码",
                'choose': '选择'
            }
        },

        // 要插入的视频
        _html: '',

        //初始化
        init: function(app) {
            this.app = app;
            this.opts = app.opts;
            this.lang = app.lang;
            this.toolbar = app.toolbar;
            this.button = app.button;
            this.insertion = app.insertion;
            this.selection = app.selection;
            this.module = app.module;
        },

        start: function() {
            // create the button data
            var buttonData = {
                title: this.lang.get('webuploadVideo'),
                api: 'plugin.webuploadVideo.open'
            };

            // create the button
            var $button = this.toolbar.addButton('webuploadVideo', buttonData);
            $button.setIcon('<i class="fa fa-film"></i>');
        },

        // html 模板
        modals: {
            'webuploadVideo':
                '<textarea class="redactor-modal-tab" data-title="链接" id="redactor-insert-video-area" style="height: 160px;" placeholder="请输入链接或嵌入代码"></textarea>'
                +'<div id="redactor-uploader" data-title="上传" class="wu-example redactor-modal-tab" style="display:none;">'
                +'    <div class="queueList">'
                +'        <div id="dndArea" class="placeholder">'
                +'            <div id="filePicker" class="webuploader-container"><div class="webuploader-pick">点击选择视频</div></div>'
                +'            <p>或将视频拖到这里</p>'
                +'        </div>'
                +'    </div>'
                +'    <div class="statusBar" style="display:none;">'
                +'        <div class="progress" style="display: none;">'
                +'            <span class="text">0%</span>'
                +'            <span class="percentage" style="width: 0%;"></span>'
                +'        </div>'
                +'        <div class="info"></div>'
                +'        <div class="btns">'
                +'            <div id="filePicker2" class="webuploader-container"></div><div class="uploadBtn state-pedding">开始上传</div>'
                +'        </div>'
                +'    </div>'
                +'</div>'
                +'<div data-title="选择" class="redactor-modal-tab choose"></div>'
        },

        onmodal: {
            webuploadVideo: {
                opened: function($modal, $form)
                {
                    if(this.opts.videoGetJson) {
                        this._load($modal);
                    }

                    $form.getField('text').focus();
                    this._html = ''
                },
                insert: function($modal, $form)
                {
                    var data = $form.getData();
                    this._insert(data);
                    this._html = ''
                },
                cancel: function($modal, $form) {
                    this._html = ''
                }
            }
        },

        // private
		_load: function($modal) {
			$R.ajax.get({
        		url: this.opts.videoGetJson,
        		success: this._parse.bind(this)
    		});
        },

        _parse: function(data) {
            var _this = this;
            var $choose = $R.dom('.choose');
            if(data && data.length > 0 && data instanceof Array) {
                data.forEach(function(item) {
                    var $video = $R.dom('<video>');
                    var src = (item.thumb) ? item.thumb : item.video;

                    $video.attr('src', src);
                    $video.attr('data-params', encodeURI(JSON.stringify(item)));
                    $video.css({
                        width: '96px',
                        height: '72px',
                        margin: '0 4px 2px 0',
                        cursor: 'pointer'
                    });

                    $video.on('click', _this._insert.bind(_this));

                    $choose.append($video);
                })
            }
		},

        // 展示模板滴
        open: function () {
            var options = {
                title: this.lang.get('webuploadVideo'),
                width: '600px',
                name: 'webuploadVideo',
                handle: 'insert',
                commands: {
                    insert: { title: this.lang.get('insert') },
                    cancel: { title: this.lang.get('cancel') }
                }
            };

            this.app.api('module.modal.build', options);

            if(!this.opts.videoGetJson) {
                $('.redactor-modal-tabs a[rel="2"]').hide();
            }

            // 初始化webuploader
            this.initUploader();
        },

        // 实例化 webuploader
        initUploader: function () {
            // 设置参数
            var _this = this,
                videoFileNumLimit = this.opts.videoFileNumLimit ? this.opts.videoFileNumLimit : 1,
                videoFileSizeLimit = (this.opts.videoFileSizeLimit ? this.opts.videoFileSizeLimit : 800) * 1024 * 1024,
                videoFileSingleSizeLimit = (this.opts.videoFileSingleSizeLimit ? this.opts.videoFileSingleSizeLimit : 200) * 1024 * 1024,
                videoChunked  = this.opts.videoChunked ? this.opts.videoChunked : true,
                videoChunkSize = (this.opts.videoChunkSize ? this.opts.videoChunkSize : .7) * 1024 * 1024;

            var $wrap = $('#redactor-uploader'),
                // 视频容器
                $queue = $('<ul class="filelist"></ul>').appendTo($wrap.find('.queueList')),
                // 状态栏，包括进度和控制按钮
                $statusBar = $wrap.find('.statusBar'),
                // 文件总体选择信息。
                $info = $statusBar.find('.info'),
                // 上传按钮
                $upload = $wrap.find('.uploadBtn'),
                // 没选择文件之前的内容。
                $placeHolder = $wrap.find('.placeholder'),
                // 总体进度条
                $progress = $statusBar.find('.progress').hide(),
                // 添加的文件数量
                fileCount = 0,
                // 添加的文件总大小
                fileSize = 0,
                // 优化retina, 在retina下这个值是2
                ratio = window.devicePixelRatio || 1,
                // 缩略图大小
                thumbnailWidth = 110 * ratio,
                thumbnailHeight = 110 * ratio,
                // 可能有pedding, ready, uploading, confirm, done.
                state = 'pedding',
                // 所有文件的进度信息，key为file id
                percentages = {},
                // WebUploader实例
                uploader;

            // 实例化
            uploader = WebUploader.create({
                pick: {
                    id: '#filePicker',
                    label: '点击选择视频'
                },
                dnd: '#redactor-uploader .queueList',
                paste: document.body,
                accept: {
                    title: 'Videos',
                    extensions: 'mp4,mpeg,mpg,ogg,webm,mov',
                    mimeTypes: 'video/*'
                },
                formData: {
                    //'token'     :index ,
                    'randname'  : 1,             // 是否随机生成文件名
                    'dir'  : _this.opts.videoDir,            // 上传文件目录
                },
                headers:{
                    "X-Requested-With": "XMLHttpRequest"
                },
                // swf文件路径
                swf: '../../webuploader/Uploader.swf',
                disableGlobalDnd: true,
                chunked: videoChunked,
                chunkSize: videoChunkSize,
                server: _this.opts.videoUpload,
                fileNumLimit: videoFileNumLimit,
                fileSizeLimit: videoFileSizeLimit,
                fileSingleSizeLimit: videoFileSingleSizeLimit
            });

            // 添加“添加文件”的按钮，--- 后端不支持多上传，需要时再打开
            if(_this.opts.videoFileNumLimit > 1) {
                uploader.addButton({
                    id: '#filePicker2',
                    label: '继续添加'
                });
            }

            // 视频预览
            setTimeout(videoChange, 0);

            function videoChange () {
                $('#redactor-uploader input[accept="video/*"]').change(function() {
                    for(key in this.files) {
                        if(key == 'length') return;
                        var len = $(".filelist video").length;
                        var windowURL = window.URL || window.webkitURL;
                        var videoURL = windowURL.createObjectURL(this.files[key]);
                        var $videoWrap = $('.filelist li:eq('+len+') p.videoWrap');
                        $videoWrap.empty().append('<video style="width:146px;height:146px;"></video>');
                        var $video = $videoWrap.find("video");
                        $video.attr('src', videoURL);
                    };
                })
            }

            // 当有文件添加进来时执行，负责view的创建
            function addFile(file) {
                var $li = $('<li id="' + file.id + '">' +
                    '<p class="title">' + file.name + '</p>' +
                    '<p class="videoWrap"></p>' +
                    '<p class="progress"><span></span></p>' +
                    '</li>'),

                    $btns = $('<div class="file-panel"><span class="fa fa-trash-o"></span></div>').appendTo( $li ),
                    $prgress = $li.find('p.progress span'),
                    $info = $('<p class="error"></p>'),
                    showError = function (code) {
                        switch (code) {
                            case 'exceed_size':
                                text = '文件大小超出';
                                break;

                            case 'interrupt':
                                text = '上传暂停';
                                break;

                            default:
                                text = '上传失败，请重试';
                                break;
                        }

                        $info.text(text).appendTo($li);
                    };

                if (file.getStatus() === 'invalid') {
                    showError(file.statusText);
                } else {
                    percentages[file.id] = [file.size, 0];
                    file.rotation = 0;
                }

                file.on('statuschange', function (cur, prev) {
                    if (prev === 'progress') {
                        $prgress.hide().width(0);
                    } else if (prev === 'queued') {
                        $li.off('mouseenter mouseleave');
                        $btns.remove();
                    }

                    // 成功
                    if (cur === 'error' || cur === 'invalid') {
                        console.log(file.statusText);
                        showError(file.statusText);
                        percentages[file.id][1] = 1;
                    } else if (cur === 'interrupt') {
                        showError('interrupt');
                    } else if (cur === 'queued') {
                        percentages[file.id][1] = 0;
                    } else if (cur === 'progress') {
                        $info.remove();
                        $prgress.css('display', 'block');
                    } else if (cur === 'complete') {
                        $li.append('<span class="success"><i class="fa fa-check"></i></span>');
                    }

                    $li.removeClass('state-' + prev).addClass('state-' + cur);
                });

                //  删除视频
                $btns.on('click', 'span', function () {
                    uploader.removeFile(file);
                });

                $li.appendTo($queue);
            }

            // 负责view的销毁
            function removeFile(file) {
                var $li = $('#' + file.id);

                delete percentages[file.id];
                updateTotalProgress();
                $li.off().find('.file-panel').off().end().remove();
            }

            // 更新进度
            function updateTotalProgress() {
                var loaded = 0,
                    total = 0,
                    spans = $progress.children(),
                    percent;

                $.each(percentages, function (k, v) {
                    total += v[0];
                    loaded += v[0] * v[1];
                });

                percent = total ? loaded / total : 0;

                spans.eq(0).text(Math.round(percent * 100) + '%');
                spans.eq(1).css('width', Math.round(percent * 100) + '%');
                updateStatus();
            }

            // 更新上传状态
            function updateStatus() {
                var text = '', stats;

                if (state === 'ready') {
                    text = '选中' + fileCount + '个视频，共' +
                        WebUploader.formatSize(fileSize) + '。';
                } else if (state === 'confirm') {
                    stats = uploader.getStats();
                    if (stats.uploadFailNum) {
                        text = '已成功上传' + stats.successNum + '个视频<a class="retry" href="#">重新上传</a>失败视频'
                    }

                } else {
                    stats = uploader.getStats();
                    text = '共' + fileCount + '个（' + WebUploader.formatSize(fileSize) + '），已上传' + stats.successNum + '个';

                    if (stats.uploadFailNum) {
                        text += '，失败' + stats.uploadFailNum + '个';
                    }
                }

                $info.html(text);
            }

            // 设置状态
            function setState(val) {
                var file, stats;

                if (val === state) {
                    return;
                }

                $upload.removeClass('state-' + state);
                $upload.addClass('state-' + val);
                state = val;

                switch (state) {
                    case 'pedding':
                        $placeHolder.removeClass('element-invisible');
                        $queue.parent().removeClass('filled');
                        $queue.hide();
                        $statusBar.addClass('element-invisible');
                        uploader.refresh();
                        break;

                    case 'ready':
                        $placeHolder.addClass('element-invisible');
                        $('#filePicker2').removeClass('element-invisible');
                        $queue.parent().addClass('filled');
                        $queue.show();
                        $statusBar.removeClass('element-invisible');
                        uploader.refresh();
                        $upload.attr('class', 'uploadBtn state-finish');
                        break;

                    case 'uploading':
                        $( '#filePicker2' ).addClass( 'element-invisible' );
                        $progress.show();
                        // $upload.text( '暂停上传' );
                        break;

                    case 'confirm':
                        $progress.hide();
                        $upload.text('开始上传').addClass('disabled');

                        stats = uploader.getStats();
                        if (stats.successNum && !stats.uploadFailNum) {
                            setState('finish');
                            return;
                        }
                        break;
                    case 'finish':
                        stats = uploader.getStats();
                        if (stats.successNum) {
                            console.log('上传成功');
                        } else {
                            // 没有成功的视频，重设
                            state = 'done';
                            location.reload();
                        }
                        break;
                }

                updateStatus();
            }

            // 更新进度条
            uploader.on('uploadProgress', function (file, percentage) {
                var $li = $('#' + file.id),
                    $percent = $li.find('.progress span');

                $percent.css('width', percentage * 100 + '%');
                percentages[file.id][1] = percentage;
                updateTotalProgress();
            });

            // 当文件被加入队列以后触发
            uploader.on('fileQueued', function (file) {
                fileCount++;
                fileSize += file.size;

                if (fileCount === 1) {
                    $placeHolder.addClass('element-invisible');
                    $statusBar.show();
                }

                addFile(file);
                setState('ready');
                updateTotalProgress();
            });

            // 当文件被删除以后触发
            uploader.on('fileDequeued', function (file) {
                fileCount--;
                fileSize -= file.size;

                if (!fileCount) {
                    setState('pedding');
                }

                removeFile(file);
                updateTotalProgress();
                videoChange();
            });

            uploader.on('all', function (type) {
                switch (type) {
                    case 'uploadFinished':
                        setState('confirm');
                        break;

                    case 'startUpload':
                        setState( 'uploading' );
                        break;
                }
            });

                //加入队列前，判断文件格式，不合适的排除
            uploader.on('beforeFileQueued', function (file) {
                file.guid = WebUploader.Base.guid();
            });

            //文件分块上传前触发，加参数，文件的订单编号加在这儿
            uploader.on('uploadBeforeSend', function (object, data, headers) {
                data.guid = object.file.guid;
            });

            // 上传成功
            uploader.on('uploadSuccess', function (file, response) {
                var obj = JSON.parse(response._raw);
                if(obj.code == -1) {
                    uploader.removeFile( file );
                    $('.info').text('');
                    uploader.setStatus = 'inited';
                    parent.layer.msg(obj.msg);
                } else {
                    var name = $('#' + file.id).parents(".uploader-list").siblings("a").data("file"),
                        val = obj.data.filename,
                        src = obj.data.filelink;

                    _this._html += '<video controls="controls" src="' + src + '"></video>';
                }

            })

            // 查看错误提示
            uploader.on('error', function (code) {
                if (code == "Q_EXCEED_NUM_LIMIT") {
                    parent.layer.msg("最多上传"+videoFileNumLimit+"个文件");
                } else if(code == 'Q_EXCEED_SIZE_LIMIT'){
                    parent.layer.msg("当个视频最大为200M");
                } else if(code  == 'Q_TYPE_DENIED') {
                    parent.layer.msg("上传文件類型错误，请重新选择");
                }
            });

            $upload.on('click', function () {
                if ($(this).hasClass('disabled')) {
                    return false;
                }

                if (state === 'ready') {
                    uploader.upload();
                } else if (state === 'paused') {
                    uploader.upload();
                } else if (state === 'uploading') {
                    uploader.stop();
                }
            });

            $info.on('click', '.retry', function () {
                uploader.retry();
            });

            $info.on('click', '.ignore', function () {
                console.log('todo');
            });

            $upload.addClass('state-' + state);
            updateTotalProgress();
        },

        // 插入模板
        _insert: function (e) {
            var rel = $(".redactor-modal-tabs a.active").attr("rel");
            if(e && Object.keys(e).length > 0) {
                e.preventDefault();
                var $el = $R.dom(e.target);
                var data = JSON.parse(decodeURI($el.attr('data-params')));
                this.insertion.insertToPoint(e, $R.dom('<video controls="controls" src="'+data+'"><p></p>'),true)
            } else {
                this.app.api('module.modal.close');
                var data = $('#redactor-insert-video-area').val();
                // 插入链接还是文件
                if(rel == 0) {
                    if(/^((http|ftp|https):\/\/)*[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?/.test(data)) {
                        // 不知道为毛要 预加载地址;
                        this.insertion.insertToPoint(e, $R.dom('<video controls="controls" src="'+data+'"><p></p>'),true)
                    } else {
                        this.insertion.insertToPoint(e, $R.dom('<p>'+data+'<p>'),true);
                    }
                } else if(rel == 1){
                    this.insertion.insertToPoint(e, $R.dom(this._html+'<p></p>'),true)
                }
            }
        },

        msgTip: function(msg) {
            $("body").append('<div class="redactor-msg-tip">'+msg+'</div>');
            setTimeout(function() {
                $('.redactor-msg-tip').remove();
            }, 2500);
        }
    });
})(Redactor);

