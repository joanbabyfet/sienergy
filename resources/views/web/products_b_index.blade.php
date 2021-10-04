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
                            href="{{ route('web.products.index') }}">產品 / 服務</a><span class="side">&nbsp;</span>模組客製化
                </div>
                <div class="mainTitle titleBrand">產品 / 服務</div>
                <div id="mainContents">
                    <h2>模組客製化</h2>
                    <div class="newsPhotos newsPhotosList">
                        <ul>
                            <li><a href="{{ route('web.products.list_b1') }}"><img src="/images/pdIcon05.jpg" width="76" height="76"/></a>
                                <div class="info">標準型</div>
                            </li>
                            <li><a href="{{ route('web.products.list_b2') }}"><img src="/images/pdIcon06.jpg" width="76" height="76"/></a>
                                <div class="info">建材一體型</div>
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
