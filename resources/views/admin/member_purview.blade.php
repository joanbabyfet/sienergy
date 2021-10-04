<div class="layui-card-body ">
    <form id="layer-form" class="layui-form" action="{{ route("admin.member.purview") }}" method="POST">
        {{ csrf_field() }}
        <input type='hidden' name='id' value="{{ $row['id'] }}"/>
        <div class="layui-form-item">
            <label class="layui-form-label">用戶名:</label>
            <div class="layui-input-block">
                <p class="form-control-static">{{ $row['username'] }}</p>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用戶組:</label>
            <div class="layui-input-block">
                @if(!empty($row['role_name']))
                    <p class="form-control-static">{{ $row['role_name'] }}</p>
                @else
                    <p class="form-control-static"><font color="red">不屬於任何組</font></p>
                @endif
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">權限:</label>
            <div class="layui-input-block">
                @if(in_array('*', $row['purviews']))
                    <p class="form-control-static"><font color="red">此用戶擁有至高無上權限</font></p>
                @else
                    <fieldset class="layui-elem-field">
                        <legend><input type="checkbox" lay-skin="primary" lay-filter="checkall" title="全選"></legend>
                        <div class="layui-field-box">
                            @foreach($permissions as $group)
                                <div class="layui-card">
                                    <div class="layui-card-header">
                                        <input type="checkbox" lay-skin="primary" lay-filter="father" title="{{ $group['name'] }}">
                                    </div>
                                    <div class="layui-card-body">
                                        <div class="layui-row">
                                            @if(isset($group['children']))
                                                @foreach($group['children'] as $permission)
                                                    <div class="layui-col-sm4">
                                                        <input type="checkbox"
                                                               onclick="alert(this)"
                                                               name="permissions[]"
                                                               lay-skin="primary"
                                                               value="{{ $permission['id'] }}"
                                                               title="{{ $permission['display_name'] }}"
                                                            {{ in_array($permission['id'], $purviews) ? ' checked':'' }}>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </fieldset>
                @endif
            </div>
            {{--            <div class="layui-form-mid layui-word-aux">用戶權限 = 所屬組權限 + 獨立權限</div>--}}
        </div>
        @if(!in_array('*', $row['purviews']))
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn layui-btn-sm" lay-filter="save" lay-submit>提交</button>
                </div>
            </div>
        @endif
    </form>
</div>
<script>
    var verify = { //自定義表單驗證規則
    };

    layui.use('form', function(){
        var form = layui.form;
        var $ = layui.$;

        form.verify(verify);
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

        form.on('checkbox(father)', function(data){ //监听father过滤器
            if(data.elem.checked){ //返回所有同胞元素
                $(data.elem).parent().siblings('.layui-card-body').find('input').prop("checked", true);
                form.render();
            }else{
                $(data.elem).parent().siblings('.layui-card-body').find('input').prop("checked", false);
                form.render();
            }
        });

        form.on('checkbox(checkall)', function(data){ //监听checkall过滤器
            if(data.elem.checked){ //siblings返回所有同胞元素
                $(data.elem).parent().siblings('.layui-field-box').find('input').prop("checked", true);
                form.render();
            }else{
                $(data.elem).parent().siblings('.layui-field-box').find('input').prop("checked", false);
                form.render();
            }
        });
    });
</script>
