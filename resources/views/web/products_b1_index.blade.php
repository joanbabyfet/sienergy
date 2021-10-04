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
                <div class="mainTitle titleBrand">標準型</div>
                <div id="mainContents">
                    <div class="pgHdImg"><img src="/images/visual/pgHdImgB01.jpg" width="638" height="180"/>
                    </div>
                    <h2>PEAK ON P3-275-72</h2>
                    <h3>glass/backsheet solar module</h3>
                    <p>Highly efficient poly crystalline solar cells <br/>
                        156 X 156mm with 3 bus bars configuration</p>
                    <p>Certified materials- junction box,EVA foil, <br/>
                        backsheet foil</p>
                    <p>Specially tempered high transparency<br/>
                        solar glass </p>
                    <p>State-of-the-art production equipment</p>
                    <p>Produced by highly automated production line</p>
                    <p>Positive power rating -0/+5W</p>
                    <p>High strength with wind and snow<br/>
                        loads guaranteed up to 5400 Pa</p>
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
