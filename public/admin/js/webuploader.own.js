$(function() {

    var $btn = $('.uploader-btn');

    uploader = new Array();
    $('.uploader-group').each(function(index) {

        var token = $(this).data("token"); // token令牌必填
        var formname = $(this).data("formname"); // 上传表单名称
        var dir = $(this).data("dir"); // 上传目录
        var randname = $(this).data("randname"); // 是否随机生成文件名，是会以文件md5值作为名称
        var compress = $(this).data("compress"); // 是否压缩图片
        var chunked = $(this).data("chunked"); // 是否分片上传，一片1M
        var multiple = $(this).data("multiple"); // 是否允许多文件上传
        var extensions = $(this).data("extensions"); // 支持的文件后缀
        var mimeTypes = $(this).data("mimetypes"); // 支持的文件mimetype
        var thumb_w = $(this).data("thumb_w"); // 上传图片宽度
        var thumb_h = $(this).data("thumb_h"); // 上传图片高度
        var auto = $(this).data("auto"); // 是否自动上传
        var len = $(this).data("len"); // 是否单独上传
        var size = $(this).data("size") * 1024 * 1024; // 文件大小限制 ,配置单位M;
        var maxLen = $(this).data("maxlen"); // 允许同时上传的最大文件数量
        var imgWidth = $(this).data("width"); //只能上传指定图片宽度
        var imgHeight = $(this).data("height"); //只能上传指定图片高度
        var corpWidth = $(this).data("corp_width") //图片裁剪高度
        var corpHeight = $(this).data("corp_height") //图片裁剪宽度
        var corp = $(this).data("corp") //是否允许裁剪


        if ($(this).data("formname") == undefined) {
            formname = 'file';
        };
        if (dir == undefined) {
            dir = 'tmp';
        };
        if (randname == undefined) {
            randname = true;
        };
        if (chunked == undefined) {
            chunked = false;
        };
        if (multiple == undefined) {
            multiple = true;
        };
        if (extensions == undefined) {
            extensions = 'gif,jpg,jpeg,bmp,png,zip,pdf,doc,xls,docx,xlsx,rar';
        };
        if (mimeTypes == undefined) {
            mimeTypes = 'image/*,application/zip,application/pdf,application/msword,application/vnd.ms-excel,application/x-rar-compressed';
        };
        if (thumb_w == undefined) {
            thumb_w = 0;
        } else {
            thumb_w = parseInt(thumb_w);
        };
        if (thumb_h == undefined) {
            thumb_h = 0;
        } else {
            thumb_h = parseInt(thumb_h);
        };
        if (maxLen == undefined) {
            maxLen = undefined;
        };
        if ($(this).data("size") == undefined) {
            size = 20971529;
        };
        if (chunked) {
            server = '/upload';
        } else {
            server = '/upload';
        }


        var state = $(this).find(".item-hidFilename").length != 0 ? true : false //是否是编辑

        if (state != undefined) {
            var __this = $(this);
            var fileValArr = [];

            __this.find(".item-hidFilename").each(function() {
                var val = $(this).val();
                fileValArr.push(val);
            })

            var listValstr = fileValArr.toString();
            __this.find(".list").val(listValstr)
        }

        var filePicker = $(this).find('.uploader-picker'); //上传按钮实例
        var options = {
            // resize: false,                          // 不压缩image
            swf: './admin/webuploader/Uploader.swf', // swf文件路径
            server: server, // 文件接收服务端接口
            //pick: '.btn-dark',
            // 选择文件的按钮。可选
            pick: {
                id: filePicker, // 选择文件的按钮。可选
                multiple: multiple, // 默认为true，就是可以多选
            },
            compress: false, // 不压缩image
            duplicate: true,
            chunked: !!chunked, // 是否要分片处理大文件上传
            chunkSize: 1 * 1024 * 1024, // 分片上传，每片1M，PHP默认限制是2M
            auto: auto, // 选择文件后是否自动上传
            chunkRetry: 2, // 如果某个分片由于网络问题出错，允许自动重传次数
            runtimeOrder: 'html5,flash',
            accept: {
                title: 'Images',
                extensions: extensions,
                mimeTypes: mimeTypes
            },
            formData: {
                '_token': token,    // token令牌,不送会报错
                'formname': formname, // 上传表单名称
                'randname': randname, // 是否随机生成文件名
                'dir': dir, // 上传文件目录
                'thumb_w': thumb_w,
                'thumb_h': thumb_h,
            },
            fileNumLimit: maxLen,
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        }

        //如果传入压缩数据配置则删掉默认compress配置
        if (compress) {
            delete options.compress;
        }

        uploader[index] = WebUploader.create(options);
        uploader[index].on('fileQueued', function(file) {
            //验证图片尺寸
            if (imgWidth != undefined && imgHeight != undefined) {
                uploader[index].makeThumb(file, function(error, src) {
                    console.log(file);
                    imgW = file._info.width;
                    imgH = file._info.height;
                    if (!(imgW == imgWidth && imgH == imgHeight)) {
                        parent.layer.msg('请上传寬为' + imgWidth + '高为' + imgHeight + '的图片', 1000);
                        uploader[index].reset();
                        return false;
                    } else {
                        if (len == 1) {
                            $(".uploader-picker").eq(index).addClass("hide");
                        }
                        var m = size / 1024 / 1024;
                        if (file.size < size) {
                            if ($(".uploader-picker").eq(index).data("type") == "image") { //判断是否是图片
                                addFile(file, uploader[index]);
                            } else {
                                $(".uploader-picker").eq(index).siblings(".uploader-list").append('<div id="' + file.id + '" class="item other-item">' +
                                    '<h4 class="info">' + file.name + '<i class="fa fa-close close-btn" style="color:red"></i></h4> ' +
                                    '<p class="state">等待上传...</p>' +
                                    '</div>');

                            }
                        } else {
                            m = parseInt(m);
                            parent.layer.msg("文件大小不能超过" + m + "M");
                            return false;
                        }
                    }
                }, 100, 100);
            } else { //不设定宽度高度的图片或文件
                if (len == 1) {
                    $(".uploader-picker").eq(index).addClass("hide");
                }
                var m = size / 1024 / 1024;
                if (file.size < size) {
                    if ($(".uploader-picker").eq(index).data("type") == "image") { //判断是否是图片
                        addFile(file, uploader[index]);
                    } else {
                        $(".uploader-picker").eq(index).siblings(".uploader-list").append('<div id="' + file.id + '" class="item other-item">' +
                            '<h4 class="info">' + file.name + '<i class="fa fa-close close-btn" style="color:red"></i></h4> ' +
                            '<p class="state">等待上传...</p>' +
                            '</div>');

                    }
                } else {
                    m = parseInt(m);
                    parent.layer.msg("文件大小不能超过" + m + "M");
                    return false;
                }
            }

        });
        // 当有img添加进来时执行，负责view的创建
        function addFile(file, now_uploader) {
            now_uploader.makeThumb(file, function(error, src) {
                $(".uploader-picker").eq(index).siblings(".uploader-list").append('<div id="' + file.id + '" class="item img-item pull-left" style="margin-bottom:10px;margin-right:10px;">' +
                    '<img style="width:100px;height:100px;" src="' + src + '">' +
                    '<i class="fa fa-close close-btn"></i>' +
                    '<p class="state">等待上传...</p>' +
                    '</div>');
            });
        }

        //图片裁剪
        if (corp != undefined || corpWidth != undefined || corpHeight != undefined) {
            if (corp == undefined) {
                corp = false;
            }
            var corpObj = {
                width: corpWidth,
                height: corpHeight,
                type: 'image/*',
                crop: corp
            }
            console.log(corpObj)
            uploader[index].option('compress', corpObj);
        }


        //加入队列前，判断文件格式，不合适的排除
        uploader[index].on('beforeFileQueued', function(file) {
            var m = size / 1024 / 1024;
            if (file.size >= size) {
                m = parseInt(m);
                parent.layer.msg("文件大小不能超过" + m + "M");
                return false
            }
            file.guid = WebUploader.Base.guid();
        });
        //文件分块上传前触发，加参数，文件的订单编号加在这儿
        uploader[index].on('uploadBeforeSend', function(object, data, headers) {
            if (chunked && object.file.type.match('image') && object.file.size < .7 * 1024 * 1024) {
                data.chunks = 1
            }
            data.guid = object.file.guid;
        });
        // 文件上传过程中创建进度条实时显示。
        uploader[index].on('uploadProgress', function(file, percentage) {

            var $li = $('#' + file.id),
                $percent = $li.find('.layui-progress .layui-progress-bar');
            // 避免重复创建
            if (!$percent.length) {
                $percent = $('<div class="layui-progress" lay-filter="pro" lay-showPercent="true">' +
                    '<div class="layui-progress-bar" lay-percent="0%">' +
                    '</div>' +
                    '</div>').appendTo($li).find('.layui-progress-bar');
            }

            $li.find('p.state').text('上传中');

            //$percent.css('width', percentage * 100 + '%');
            layui.use('element', function(){
                var element = layui.element;
                element.progress('pro', percentage * 100 + '%');
            });
        });
        // 文件上传成功
        uploader[index].on('uploadSuccess', function(file, response) {
            if (response.code == -1) {
                $('#' + file.id).parents(".layui-form-item").find('.Validform_wrong').remove();
                var str = '<span class="Validform_checktip Validform_wrong"><i class="fa fa-times-circle"></i> ' + response.msg + '</span>';
                $('#' + file.id).parents(".layui-form-item").find('.hidden-input').append(str);
                if (len == 1) {
                    $('#' + file.id).parents(".uploader-list").siblings("a").removeClass("hide");
                }
                $('#' + file.id).remove();

                return false;
            }
            var name = $('#' + file.id).parents(".uploader-list").siblings("a").data("file"),
                val = response.data.filename,
                src = response.data.filelink;
            var str = '<input type="hidden" value="' + val + '" class="hid-filename item-hidFilename">';
            $('#' + file.id).append(str);
            var inputVal = [];
            $('#' + file.id).closest(".uploader-list").find(".item-hidFilename").each(function() {
                var val = $(this).val();
                inputVal.push(val);
            })

            var listVal = inputVal.toString();
            var inputstr = '<input type="hidden" value="' + listVal + '" name="' + name + '"  class="hid-filename list">';
            $('#' + file.id).closest(".uploader-list").find(".list").remove();
            $('#' + file.id).closest(".uploader-list").append(inputstr);

            $('#' + file.id).find("img").attr("src", src);
            $('#' + file.id).find('p.state').text('上传完成');
            if (len == 1) {
                $('#' + file.id).parents(".uploader-list").siblings("a").addClass("hide");
                hideBtnFn();
            }
            //新验证规则
            $('#' + file.id).parents(".layui-form-item").find(".Validform_checktip").remove();
            $('#' + file.id).parents(".layui-form-item").find(".control-label").css("color", "#555");
        });
        // 文件上传失败，显示上传出错
        uploader[index].on('uploadError', function(file) {
            $('#' + file.id).find('p.state').text('上传出错');
        });
        // 完全上传完
        uploader[index].on('uploadComplete', function(file) {
            $('#' + file.id).find('.progress').fadeOut();
        });

        //错误提提示
        uploader[index].on("error", function(type) {
            if (type == "Q_EXCEED_NUM_LIMIT") {
                parent.layer.msg("最多上传" + maxLen + "个文件");
            } else {

            }
        });


    })
    //每个上传button加data-id
    $(".uploader-btn").each(function(index) {
        $(this).attr("data-id", index);
    })
    $btn.on('click', function() {
        if ($(this).hasClass('disabled')) {
            return false;
        }
        $(this).siblings(".uploader-list").addClass("AF");
        var i = $(this).data("id");
        uploader[i].upload();
    });

    $("body").on("click", ".close-btn", function() { //点击删除按钮
        var val = $(this).parents(".item").find("input[type=hidden]").val();
        var len = $(this).parents(".uploader-group").data("len");
        var item = $(this).parents(".item");
        var str = $(this).parents(".item").find(".state").html();
        if (len == 1) {
            $(this).parents(".uploader-list").siblings("a").css("display", "inline-block").removeClass("hide");
            $(this).parents(".uploader-list").find(".list").remove();
        }
        var _list = $(this).closest(".uploader-list");
        console.log(_list);
        item.remove();


        var inputVal = [];
        _list.find(".item-hidFilename").each(function() {
            var val = $(this).val();
            inputVal.push(val);
        })
        var name = _list.siblings("a").data("file");
        var listVal = inputVal.toString();
        var inputstr = '<input type="hidden" value="' + listVal + '" name="' + name + '"  class="hid-filename list">';
        _list.find(".list").remove();
        _list.append(inputstr);
    })



    //        var uploader = WebUploader.create({
    //            resize: false,                          // 不压缩image
    //            swf: 'static/webuploader/uploader.swf', // swf文件路径
    //            server: '?ct=upload&ac=webuploader',    // 文件接收服务端。
    //            pick: '#picker',                        // 选择文件的按钮。可选
    //            chunked: true,                          // 是否要分片处理大文件上传
    //            chunkSize: 2*1024*1024,                 // 分片上传，每片2M，默认是5M
    //            auto: true,                             // 选择文件后是否自动上传
    //            chunkRetry : 2,                         // 如果某个分片由于网络问题出错，允许自动重传次数
    //            runtimeOrder: 'html5,flash',
    //            accept: {
    //                title: 'Images',
    //                extensions: 'gif,jpg,jpeg,bmp,png',
    //                mimeTypes: 'image/*'
    //            },
    //            formData: {
    //                'token'    : '12be3f5eb91c6f5a0a179b32a931226f',
    //                'randname' : '1',             // 是否随机生成文件名
    //                'dir'      : 'tmp',           // 上传文件目录
    //            }
    //        });
    // 当有文件被添加进队列的时候
});

function hideBtnFn() {
    $(".hide").hide();
};
