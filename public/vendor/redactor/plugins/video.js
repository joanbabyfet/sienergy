(function ($) {
    $.Redactor.prototype.video = function () {
        return {
            langs: {
                en: {
                    "video": "Video",
                    "video-html-code": "Video Embed Code or Video Link"
                },
                zh_cn: {
                    "video": "视频",
                    "video-html-code": "请加入视频链接或嵌入代码"
                }
            },
            // 要插入的视频
            _html: '',
            // html 模板
            getTemplate: function() {
				return '<div class="modal-section" id="redactor-modal-video-insert">'
					+ '<section>'
                    +'      <div class="redactor-tabs">'
                    +'        <a href="#" id="redactor-tab-control-1" class="on">链接</a>'
                    +'        <a href="#" id="redactor-tab-control-2">上传</a>'
                    +'      </div>'
                    +'<textarea id="redactor-insert-video-area" style="height: 160px;" placeholder="请输入链接或嵌入代码"></textarea>'
                    +'<div id="redactor-uploader" class="wu-example" style="display:none;">'
                    +'    <div class="queueList">'
                    +'        <div id="dndArea" class="placeholder">'
                    +'            <div id="filePicker" class="webuploader-container"><div class="webuploader-pick">点击选择视频</div></div>'
                    +'            <p>或将视频拖到这里</p>'
                    +'        </div>'
                    +'        <ul class="filelist"></ul>'
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
					+ '</section>'
					+ '<section class="btns-wrap">'
					+ '     <button id="redactor-modal-button-action">Insert</button>'
					+ '     <button id="redactor-modal-button-cancel">取消</button>'
					+ '</section>'
				+ '</div>';
            },

            init: function () {
                // 添加图标
                var button = this.button.addAfter('image', 'video', '<i class="fa fa-film"></i>');
                this.button.buildButtonTooltip($("a.re-button.re-video"), "视频")
                // 添加事件
                this.button.addCallback(button, this.video.show);
            },

            show: function () {
                // 添加模板
                this.modal.addTemplate('video', this.video.getTemplate());
                this.modal.load('video', this.lang.get('video'), 700);

                // 点击事件
                this.modal.getActionButton().text(this.lang.get('insert')).on('click', this.video.insert);
                this.modal.show();

                // tag切换事件
                $(".redactor-tabs a").click(function () {
                    var index = $(this).index();
                    $(this).addClass("on").siblings("a").removeClass("on");
                    if (index == 0) {
                        $("#redactor-insert-video-area").show();
                        $("#redactor-uploader").hide();
                    } else {
                        $("#redactor-insert-video-area").hide();
                        $("#redactor-uploader").show();
                    }
                })

                // 初始化webuploader
                this.video.initUploader();
            },

            // 插入模板 
            insert: function () {
                var data = $('#redactor-insert-video-area').val();
                this.modal.close();
                this.placeholder.hide();
                this.buffer.set();
                this.air.collapsed();

                // 插入链接还是文件
                if($(".redactor-tabs #redactor-tab-control-1").hasClass("on")) {
                    this.insert.html(data);
                } else {
                    this.insert.html(this.video._html)
                }
            },

            // 实例化 webuploader
            initUploader: function () {
                // 设置参数
                var _this = this,
                    fileNumLimit = _this.opts.fileNumLimit ? _this.opts.fileNumLimit : 1,
                    fileSizeLimit = (_this.opts.fileSizeLimit ? _this.opts.fileSizeLimit : 10) * 1024 * 1024,
                    fileSingleSizeLimit = (_this.opts.fileSingleSizeLimit ? _this.opts.fileSingleSizeLimit : 10) * 1024 * 1024,
                    chunked  = _this.opts.chunked ? _this.opts.chunked : true,
                    chunkSize = (_this.opts.chunkSize ? _this.opts.chunkSize : 1)*1024*1024; 

                var $wrap = $('#redactor-uploader'),
                    // 视频容器
                    $queue = $('<ul class="filelist"></ul>')
                        .appendTo($wrap.find('.queueList')),
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
                        extensions: 'mp4,mpeg,mpg,ogg,webm',
                        mimeTypes: 'video/*'
                    },
                    // swf文件路径
                    swf: '../../webuploader/Uploader.swf',
                    disableGlobalDnd: true,
                    chunked: chunked,
                    chunkSize: chunkSize,
                    server: '?ct=upload&ac=webuploader',
                    fileNumLimit: fileNumLimit,
                    fileSizeLimit: fileSizeLimit,    // 200 M
                    fileSingleSizeLimit: fileSingleSizeLimit    // 50 M
                });

                 // 添加“添加文件”的按钮，
                //  uploader.addButton({
                //     id: '#filePicker2',
                //     label: '继续添加'
                // });

                // 视频预览
                setTimeout(function() {
                    $('#redactor-uploader input[accept="video/*"]').change(function() {
                        var videoFiles = this.files;
                        var windowURL = window.URL || window.webkitURL;
                        var videoURL = windowURL.createObjectURL(videoFiles[0]);
                        $videoWrap.empty().append('<video src style="width:180px;height:150px;"></video>');
                        var $video = $videoWrap.find("video");
                        $video.attr('src', videoURL);
                    })
                }, 0);

                // 当有文件添加进来时执行，负责view的创建
                var $videoWrap;
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
                    
                    $videoWrap = $li.find('p.videoWrap');

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
                            text = '已成功上传' + stats.successNum + '个视频<a class="retry" href="#">重新上传</a>失败视频或<a class="ignore" href="#">忽略</a>'
                        }

                    } else {
                        stats = uploader.getStats();
                        text = '共' + fileCount + '个（' +
                            WebUploader.formatSize(fileSize) +
                            '），已上传' + stats.successNum + '个';

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

                });

                uploader.on('all', function (type) {
                    switch (type) {
                        case 'uploadFinished':
                            setState('confirm');
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
                    data.tmp_path = 'video'
                });

                // 上传成功
                uploader.on('uploadSuccess', function (file, response) {
                    var obj = JSON.parse(response._raw);
                    var name = $('#' + file.id).parents(".uploader-list").siblings("a").data("file"),
                        val = obj.result.filename,
                        src = obj.result.filelink;
                        
                    _this.video._html = '<video controls="controls" src="' + src + '"></video>';
                })

                // 查看错误提示
                uploader.on('error', function (code) {
                    console.log('Eroor: ' + code);
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
            }
        }
    }
})(jQuery);
