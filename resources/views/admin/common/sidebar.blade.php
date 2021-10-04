<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <!-- 左侧導航区域（可配合layui已有的垂直導航） -->
        <ul class="layui-nav layui-nav-tree"  lay-filter="menu">
            @foreach($navigation as $item)
                @if(isset($item['children']) && !empty($item['children']))
                <li class="layui-nav-item">
                    <a class="" href="javascript:;">{{ $item['name'] }}</a>
                    <dl class="layui-nav-child">
                        @foreach ($item['children'] as $child)
{{--                                获取文件名不含url参数--}}
{{--                        <dd class="{{ request()->path() == trim($child['uri'], '/') ? 'layui-this' : '' }}"><a href="{{ $child['uri'] }}">{{ $child['name'] }}</a></dd>--}}
                            <dd class=""><a href="{{ $child['uri'] ?: 'javascript:;' }}">{{ $child['name'] }}</a></dd>
                        @endforeach
                    </dl>
                </li>
                @else
                <li class="layui-nav-item">
                    <a href="{{ empty($item['uri']) ? 'javascript:;' : $item['uri'] }}">{{ $item['name'] }}</a>
                </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
