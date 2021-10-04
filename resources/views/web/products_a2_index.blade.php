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
                <div class="header"><img src="/images/visual/sideBar/sideBarHdProducts.gif" alt="" width="218"
                                         height="20"/></div>
                <div class="blockA">
                    <div class="main menu">
                        @include('web.common.sidebar')
                    </div>
                    <div class="footer"></div>
                </div>
            </div>
        </div>
        <div id="bodyMain">
            <div id="innerBodyMain">
                <div id="pagePath"><a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span><a
                            href="{{ route('web.products.index') }}">產品 / 服務</a><span class="side">&nbsp;</span>系統整合
                </div>
                <div class="mainTitle titleBrand">落地型</div>
                <div id="mainContents">
                    <div class="pgHdImg"><img src="/images/visual/pgHdImg02.jpg" width="638" height="180"/></div>
                    <h2>住宅或廠辦大樓屋頂是裝設的理想地點</h2>
                    <p><img src="/images/pgImgA02.jpg" width="638" height="718"/></p>
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
