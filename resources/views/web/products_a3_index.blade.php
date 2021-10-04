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
                <div class="mainTitle titleBrand">環境整合型</div>
                <div id="mainContents">
                    <div class="pgHdImg"><img src="/images/visual/pgHdImg03.jpg" width="638" height="180"/></div>
                    <h2>與建築物整合，凸顯綠建築設計感</h2>
                    <p><img src="/images/pgImgA03-BIPV.jpg" width="638" height="640"/></p>
                    <p>BIPV的定義：太陽光電模組取代部分建材，與建築物整合為一體。</p>
                    <p><img src="/images/pgImgA03.jpg" width="638" height="718"/></p>
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
