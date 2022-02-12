<div class="layui-header">
    <a href="{{ route("admin.index.index") }}">
        <div class="layui-logo">
            <img src="{{ ADMIN_IMG }}/hanboxLogoB.png" alt="Control Panel" class="logo" />
        </div>
    </a>
    <!-- 头部区域（可配合layui已有的水平導航） -->
    <ul class="layui-nav layui-layout-right">
        <li class="layui-nav-item">
            <a href="javascript:;">
                <img src="{{ ADMIN_IMG }}/user_info.png" class="layui-nav-img">
                @guest($guard)

                @endguest
                @auth($guard)
                    {{ $curr_user['username'] }}
                @endauth
            </a>
            <dl class="layui-nav-child">
                <dd style="text-align: center;"><a href="{{ route("admin.admin_user.editpwd") }}">修改密碼</a></dd>
                <hr>
                <dd style="text-align: center;"><a href="{{ route("admin.login.logout") }}">退出</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item" lay-header-event="menuRight" lay-unselect>
            <a href="javascript:;">
                <i class="layui-icon layui-icon-more-vertical"></i>
            </a>
        </li>
    </ul>
</div>
