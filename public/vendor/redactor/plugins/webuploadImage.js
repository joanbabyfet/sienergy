(function($R) {
    $R.add('plugin', 'webuploadImage', {
        translations: {
            en: {
                "webuploadImage": "image",
                'title': 'image upload',
                'choose': 'choose'
            },
            zh_cn: {
                "webuploadImage": "图片",
                "title": "请加入图片链接或嵌入代码",
                'choose': '选择'
            }
        },

        // 要插入的图片
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
                title: this.lang.get('webuploadImage'),
                api: 'plugin.webuploadImage.open'
            };

            // create the button
            var $button = this.toolbar.addButton('webuploadImage', buttonData);
            $button.setIcon('<i class="re-icon-image"></i>');
        },

        // html 模板
        modals: {
            'webuploadImage':
                '<textarea class="redactor-modal-tab" data-title="链接" id="redactor-insert-image-area" style="height: 160px;" placeholder="请输入链接或嵌入代码"></textarea>'
                +'<div id="redactor-uploader" data-title="上传" class="wu-example redactor-modal-tab" style="display:none;">'
                +'    <div class="queueList">'
                +'        <div id="dndArea" class="placeholder">'
                +'            <div id="filePicker" class="webuploader-container"><div class="webuploader-pick">点击选择图片</div></div>'
                +'            <p>或将图片拖到这里</p>'
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
            webuploadImage: {
                opened: function($modal, $form)
                {
                    this._load($modal);
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
            if(this.opts.imageGetJson){
                $R.ajax.get({
                    url: this.opts.imageGetJson,
                    success: this._parse.bind(this)
                });
            }
        },

        _parse: function(data) {
            var _this = this;
            var $choose = $R.dom('.choose');
            if(data && data.length > 0 && data instanceof Array) {
                data.forEach(function(item) {
                    var $img = $R.dom('<img>');
                    var src = (item.thumb) ? item.thumb : item.image;

                    $img.attr('src', src);
                    $img.attr('data-params', encodeURI(JSON.stringify(item)));
                    $img.css({
                        width: '96px',
                        height: '72px',
                        margin: '0 4px 2px 0',
                        cursor: 'pointer'
                    });

                    $img.on('click', _this._insert.bind(_this));

                    $choose.append($img);
                })
            }
		},

        // 展示模板滴
        open: function () {
            var options = {
                title: this.lang.get('webuploadImage'),
                width: '600px',
                name: 'webuploadImage',
                handle: 'insert',
                commands: {
                    insert: { title: this.lang.get('insert') },
                    cancel: { title: this.lang.get('cancel') }
                }
            };

            this.app.api('module.modal.build', options);

            if(!this.opts.imageGetJson) {
                $('.redactor-modal-tabs a[rel="2"]').hide();
            }

            // 初始化webuploader
            this.initUploader();
        },

        // 实例化 webuploader
        initUploader: function () {
            // 设置参数
            var _this = this,
                imgFileNumLimit = this.opts.imgFileNumLimit ? this.opts.imgFileNumLimit : 1,
                imgFileSizeLimit = (this.opts.imgFileSizeLimit ? this.opts.imgFileSizeLimit : 20) * 1024 * 1024,
                imgFileSingleSizeLimit = (this.opts.imgFileSingleSizeLimit ? this.opts.imgFileSingleSizeLimit : 5) * 1024 * 1024,
                imgChunked  = this.opts.imgChunked ? this.opts.imgChunked : true,
                imgChunkSize = (this.opts.imgChunkSize ? this.opts.imgChunkSize : 1) * 1024 * 1024;

            var $wrap = $('#redactor-uploader'),
                // 图片容器
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
                    label: '点击选择图片'
                },
                dnd: '#redactor-uploader .queueList',
                //paste: document.body,
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/*'
                },
                formData: {
                    //'token'     :index ,
                    'thumb_w': _this.opts.thumbWidth,
                    'thumb_h': _this.opts.thumbHeight,
                    'randname'  : 1,             // 是否随机生成文件名
                    'dir'  : _this.opts.imageDir,             // 上传文件目录
                },
                // swf文件路径
                swf: '../../webuploader/Uploader.swf',
                disableGlobalDnd: true,
                chunked: imgChunked,
                chunkSize: imgChunkSize,
                server: _this.opts.imageWebUpload,
                fileNumLimit: imgFileNumLimit,
                fileSizeLimit: imgFileSizeLimit,    // 200 M
                fileSingleSizeLimit: imgFileSingleSizeLimit    // 50 M
            });

            // 添加“添加文件”的按钮，--- 后端不支持多上传，需要时再打开

            if(_this.opts.imgFileNumLimit > 1) {
                uploader.addButton({
                    id: '#filePicker2',
                    label: '继续添加'
                });
            }

            // 当有文件添加进来时执行，负责view的创建
            var $imageWrap;
            function addFile(file) {
                var $li = $('<li id="' + file.id + '">' +
                    '<p class="title">' + file.name + '</p>' +
                    '<p class="imageWrap"></p>' +
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
                        $info.text(text).appendTo($li)
                    },

                    $imageWrap = $li.find('p.imageWrap');

                if (file.getStatus() === 'invalid') {
                    showError(file.statusText);
                } else {
                    $imageWrap.text( '预览中' );
                    uploader.makeThumb( file, function( error, src ) {
                        if ( error ) {
                            $imageWrap.text( '不能预览' );
                            return;
                        }

                        var img = $('<img src="'+src+'">');
                        $imageWrap.empty().append( img );
                    }, thumbnailWidth, thumbnailHeight );

                    percentages[ file.id ] = [ file.size, 0 ];
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

                //  删除图片
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
                    text = '选中' + fileCount + '个图片，共' +
                        WebUploader.formatSize(fileSize) + '。';
                } else if (state === 'confirm') {
                    stats = uploader.getStats();
                    if (stats.uploadFailNum) {
                        text = '已成功上传' + stats.successNum + '个图片<a class="retry" href="#">重新上传</a>失败图片'
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
                            // 没有成功的图片，重设
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
                // data.tmp_path = 'image'
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

                    _this._html += '<image controls="controls" src="' + src + '"></image>';
                }
            })

            // 查看错误提示
            uploader.on('error', function (code) {
                if (code == "Q_EXCEED_NUM_LIMIT") {
                    parent.layer.msg("最多上传"+imgFileNumLimit+"个文件");
                } else if(code == 'Q_EXCEED_SIZE_LIMIT' || code=='F_EXCEED_SIZE'){
                    parent.layer.msg("单张图最大不能超过5M");
                } else if(code  == 'Q_TYPE_DENIED') {
                    parent.layer.msg("上传文件類型错误");
                }else if(code=='F_DUPLICATE'){
                    parent.layer.msg("重复上传");
                }
                console.log(code)
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
                this.app.api('module.modal.close');
                var $el = $R.dom(e.target);
                var data = JSON.parse(decodeURI($el.attr('data-params')));
                this.insertion.insertToPoint(e, $R.dom('<img src="'+data.image+'"> <p></p>'),true)
            } else {
                this.app.api('module.modal.close');
                var data = $('#redactor-insert-image-area').val();
                // 插入链接还是文件
                if(rel == 0) {
                    if(/^((http|ftp|https):\/\/)*[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?/.test(data)) {
                        this.insertion.insertToPoint(e, $R.dom('<img src="'+data+'"><p></p>'),true)
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
