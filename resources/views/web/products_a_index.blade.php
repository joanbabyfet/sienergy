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
                <div class="mainTitle titleBrand">產品 / 服務</div>
                <div id="mainContents">
                    <h2>系統整合</h2>
                    <p>優點：具有環保發電、投資效益、屋頂隔熱</p>
                    <div class="newsPhotos newsPhotosList">
                        <ul>
                            <li><a href="{{ route('web.products.list_a1') }}"><img src="/images/pdIcon01.jpg" width="76" height="76"/></a>
                                <div class="info">屋頂型</div>
                            </li>
                            <li><a href="{{ route('web.products.list_a2') }}"><img src="/images/pdIcon02.jpg" width="76" height="76"/></a>
                                <div class="info">落地型</div>
                            </li>
                            <li><a href="{{ route('web.products.list_a3') }}"><img src="/images/pdIcon03.jpg" width="76" height="76"/></a>
                                <div class="info">環境整合型</div>
                            </li>
                            <li><a href="{{ route('web.products.list_a4') }}"><img src="/images/pdIcon04.jpg" width="76" height="76"/></a>
                                <div class="info">套件型</div>
                            </li>
                        </ul>
                    </div>
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
