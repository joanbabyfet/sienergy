@extends('web.layouts.app')

@section('title', config('global.web.app_title'))

{{--自定義css--}}
@section('style')
    @parent
    <style>
    </style>
@endsection

{{--內容--}}
@section('content')
    <div id="innerMainRegion">
        <div id="sidebar">
            <div id="innerSidebar">
                <div class="header"><img src="/images/visual/sideBar/sideBarHdNews.gif" alt="" width="218"
                                         height="20"/></div>
                <div class="blockA">
                    <div class="main menu">
                        <ul>
                            @foreach($cats as $v)
                            <li><a href="{{ route('web.news.index').'?cat_id='.$v['id'] }}">{{ $v['name'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="footer"></div>
                </div>
            </div>
        </div>
        <div id="bodyMain">
            <div id="innerBodyMain">
                <div id="pagePath"><a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span>最新消息</div>
                <div class="mainTitle titleBrand">最新消息</div>
                <div id="mainContents">
                    @if(!empty($list))
                    <div class="newsList">
                        <ul>
                            @foreach($list as $v)
                                <li><a href="{{ route('web.news.detail').'?id='.$v['id'] }}">{{ $v['title'] }}</a>
                                    @if($v['is_hot'])<img src="styles/sienergy/images/iconNewsHot.gif" width="17" height="5" align="texttop"/>@endif</li>
                            @endforeach
                        </ul>
                    </div>
                    @else
                        <p>目前無最新訊息...</p>
                    @endif
                </div>
                <div id="page" style="text-align: center"></div>
            </div>
        </div>
        <div id="mainFooterSide"></div>
    </div>
@endsection

{{--自定義js--}}
@section('script')
    @parent
    <script>
        layui.use('laypage', function(){
            //分頁器
            web.paginate("{{ $pages->total() }}", "{{ $pages->currentPage() }}", "{{ $pages->perPage() }}");
        });
    </script>
@endsection
