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
                <div class="mainTitle titleBrand">建材一體型</div>
                <div id="mainContents">
                    <div class="pgHdImg"><img src="/images/visual/pgHdImgB02.jpg" width="638" height="180"/>
                    </div>
                    <h2>PEAK IN P210-60</h2>
                    <h3>BIPV glass/glass solar module</h3>
                    <p>Frameless PV module for building <br/>
                        integrated application</p>
                    <p>Highly efficient poly crystalline solar cells <br/>
                        156 X 156mm with 2 bus bars configuration</p>
                    <p>Certified materials- junction box,EVA or PVB foil</p>
                    <p>Specially tempered high transparency solar glass </p>
                    <p>State-of-the-art production equipment</p>
                    <p>Designed and manufactured in Taiwan at a highly <br/>
                        automated production line</p>
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
