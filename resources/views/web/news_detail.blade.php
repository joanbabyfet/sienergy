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
                <div class="header"><img src="/images/visual/sideBar/sideBarHdAbout.gif" width="218"
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
                <div id="pagePath"><a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span><a href="{{ route('web.news.index') }}">最新消息</a><span
                            class="side">&nbsp;</span>訊息內容
                </div>
                <div class="mainTitle titleBrand">最新消息</div>
                <div id="mainContents">
                    <h2>{{ $news['title'] }}</h2>
                    {!! $news['content'] !!}
                </div>
            </div>
        </div>
        <div id="mainFooterSide"></div>
    </div>
@endsection

{{--自定義js--}}
@section('script')
    @parent
    <script>
    </script>
@endsection
