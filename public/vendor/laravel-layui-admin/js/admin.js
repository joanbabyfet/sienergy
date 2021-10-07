;!function (win) {

  var Admin = function () {

  };

  Admin.prototype.paginate = function (count, curr, limit, limits) {
    layui.laypage.render({
      elem: 'page',
      count: count,
      curr: curr,
      limit: limit,
      limits: limits ? limits : [10, 20, 50, 100, 200, 500, 1000, 2000],
      layout: ['limit', 'count', 'prev', 'page', 'next', 'refresh', 'skip'],
      jump: function(obj, first){
        if(!first){
          location.href= window.location.pathname + '?' + $("#search-form").serialize() + '&page='+obj.curr+'&limit='+obj.limit;
        }
      }
    });
  };

  Admin.prototype.tableDataDelete = function (url, th, isRefresh) {
    layui.layer.confirm('你確認刪除嗎？', {
      btn: ['確定', '取消']
    }, function () {
      $.ajax({
        type: "POST",
        url: url,
        success: function() {
          if (isRefresh) {
            window.location = window.location.href
            return false;
          }
          $(th).parent().parent().parent().remove();
          layui.layer.close();
          layui.layer.msg("刪除成功", {time: 2000, icon: 6})
          location.reload(); //刷新页面,总条数才会更新
        },
      });
    }, function () {
      layui.layer.close();
    });
  };

    Admin.prototype.openLayerConfirm = function (url, th, content, success_msg, isRefresh) {
        layui.layer.confirm(content, {
            icon: 3, title: "提示", btn: ['確定', '取消']
        }, function () {
            $.ajax({
                type: "POST",
                url: url,
                success: function() {
                    if (isRefresh) {
                        window.location = window.location.href
                        return false;
                    }
                    $(th).parent().parent().parent().remove();
                    layui.layer.close();
                    layui.layer.msg(success_msg, {time: 2000, icon: 6})
                    location.reload(); //刷新页面,总条数才会更新
                },
            });
        }, function () {
            layui.layer.close();
        });
    };

    Admin.prototype.openLayerIframe = function (url, title, is_mobile, width, height) {
        var layer_id;
        if(is_mobile)
        {
            layer_id = layui.layer.open({
                type: 2,
                title: title,
                shadeClose: true, //是否点击遮罩关闭
                shade: 0.5, //遮罩
                area: [
                    width ? width : '100%',
                    height ? height : '100%'
                ],
                content: [url, 'yes'] //iframe出现滚动条 yes或no
            });
        }
        else
        {
            layer_id = layui.layer.open({
                type: 2,
                title: title,
                shadeClose: true, //是否点击遮罩关闭
                shade: 0.5, //遮罩
                area: [
                    width ? width : '80%',
                    height ? height : '60%'
                ],
                content: [url, 'yes'] //iframe出现滚动条 yes或no
            });
        }
        return layer_id;
    };

  Admin.prototype.openLayerForm = function (url, title, method, width, height, noRefresh, formId) {
    var formId = formId ? formId : "#layer-form";
    $.get(url, function(view) {
      layui.layer.open({
        type: 1,
        title: title,
        anim: 2,
        shadeClose: true,
        content: view,
        success: function() {
          layui.form.render();
        },
        area:[
          width ? width : '50%',
          height ? height : '500px'
        ],
        //btn: ['提交', '取消'],
        yes: function (index, layero) {
          var formObj = $(formId);
          $.ajax({
            type: method ? method : 'POST',
            url: formObj.attr("action"),
            dataType: "json", //告诉服务器返回json给我
            data: formObj.serialize(), //送到服务器数据
            success: function(response) {
              if (response.code === 0) {
                layui.layer.close(index);
                layui.layer.msg(response.msg, {time: 2000, icon: 6})
                if (!noRefresh) {
                  window.location = window.location.href
                }
              } else {
                layui.layer.msg(response.msg, {time: 3000, icon: 5})
              }
            },
            error: function() {
            }
          });
        },
        btn2: function (index, layero) {
          //$(formId)[0].reset();
          layui.layer.close(index); // 关闭弹出层
          return false;
        }
      });
    });
  };

  win.admin = new Admin();
}(window);

layui.config({
  base: "/vendor/laravel-layui-admin/js/"
});

layui.use("jquery", function() {
  $ = layui.jquery; //重点处,后面就跟你平时使用jQuery一样

    //文件加载完后执行方法,菜单选中展开
    $(function() {
        var url = window.location.pathname.toLowerCase();
        //if(url.substr(0, 1) == '/') url = url.substr(1);
        $('.layui-nav-child a').each(function(){
            var href = $(this).attr('href');
            if(href == url)
            {
                $(this).parent().addClass('layui-this');
                $(this).parent().parent().parent().addClass('layui-nav-itemed');
            }
        });
    });

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      var status = XMLHttpRequest.status;
      var responseText = XMLHttpRequest.responseText;
      var msg = '不好，有错误';
      switch (status) {
        case 400:
          msg = responseText != '' ? responseText : '失败了';
          break;
        case 401:
          msg = responseText != '' ? responseText : '你没有权限';
          break;
        case 403:
          msg =  '你没有权限执行此操作!';
          break;
        case 404:
          msg = '你访问的操作不存在';
          break;
        case 406:
          msg = '请求格式不正确';
          break;
        case 410:
          msg = '你访问的资源已被删除';
          break;
        case 422:
          var errors = $.parseJSON(XMLHttpRequest.responseText);

          if (errors instanceof Object) {
            var m = '';
            $.each(errors, function(index, item) {
              if (item instanceof Object) {
                $.each(item, function(index, i) {
                  m = m + i + '<br>';
                });
              } else {
                m = m + item + '<br>';
              }
            });
            msg = m;
          }
          break;
        case 429:
          msg = '超出访问频率限制';
          break;
        case 500:
          msg = '500 INTERNAL SERVER ERROR';
          break;
        default:
          return true;
      }

      layer.msg(msg, {time: 3000, icon: 5});
    }
  });
});

//開啟excel文件
function openwin(excel_file) {
    var iframe = document.createElement('iframe');
    iframe.src = excel_file;
    iframe.width = "0px";
    iframe.height = "0px";
    iframe.style.display = "none";
    iframe.frameborder = "0";
    iframe.seamless = '';
    document.body.appendChild(iframe);
}

//導出excel
function export_excel(url, data, search_str)
{
    var file = '';
    var _page_no = 1;

    layer.open({ //彈層進度條
        type: 3,
        content: $('#show_progress'),
        success: function (layero, index) {
            //index彈層
            getfile(url, data, search_str, file, _page_no, index);
        },
        end: function () {
            layui.element.progress('pro', '0%');
        }
    });
}

function getfile(url, data, search_str, file, _page_no, index)
{
     $.ajax({
        url: url+'?'+'file='+file+'&page='+_page_no+'&'+search_str,
        type: 'get',
        data: data,
        success: function(res) {
            if (res.code === 0)
            {
                file = res.data.file;
                var _total_page = res.data.total_page;
                var _pro = ((_page_no/_total_page)*100).toFixed();
                layui.element.progress('pro', _pro + '%');
                if(_page_no >= _total_page){
                    setTimeout(function(){ //N秒後只調用一次函數
                        layui.layer.close(index);
                        openwin(res.data.excel_file);
                    }, 1000); //毫秒
                    return;
                }else{
                    _page_no++;
                    getfile(url, data, search_str, file, _page_no, index);
                }
            }
            else {
                layui.layer.msg(res.msg, {time: 2000, icon: 5});
            }
        }
    });
}
