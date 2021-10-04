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
                <div class="header"><img src="/images/visual/sideBar/sideBarHdLinks.gif" width="218"
                                         height="20"/></div>
                <div class="banner"><a href="http://www.pvtaiwan.com/zh_TW/index.html" target="_blank"><img
                                src="/images/ad/PvTaiwan.jpg" width="216" height="59"/></a>
                    <div class="info">台灣國際太陽光電論壇暨展覽會<br/>
                        攤位號碼：D0318
                    </div>
                </div>
            </div>
        </div>
        <div id="bodyMain">
            <div id="innerBodyMain">
                <div id="pagePath"><a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span>友善連結</div>
                <div class="mainTitle titleBrand">友善連結</div>
                <div id="mainContents">
                    @foreach($list as $v)
                        <p><a href="{{ $v['url'] }}" target="_blank">
                                <img src="{{ $v['img'] }}" alt="" width="220" height="70" border="0"/></a></p>
                    @endforeach
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
