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
            </div>
        </div>
        <div id="bodyMain">
            <div id="innerBodyMain">
                <div id="pagePath"><a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span>關於鑫盈</div>
                <div class="mainTitle titleBrand">關於鑫盈</div>
                <div id="mainContents">
                    <p>鑫盈能源擁有專業太陽能發電系統以及模組設計製造完整豐富的實務經驗，我們秉持著客戶至上精神提供「諮詢、規劃、設計、申請、安裝、維護」等全方位優質服務。</p>
                    <p> 一般住家裝設太陽能發電系統為「高報酬零風險」的投資，若為企業客戶則同時可提升綠色環保形象，以及降低未來碳權稅問題衝擊風險潛在優勢。</p>
                    <p> 除使用市面上常見標準太陽能模組安裝發電系統外，鑫盈能源可依客戶需求量身設計製造模組以及建材一體成型式的BIPV(Building-integrated
                        photovoltaic)，替客戶打造出別具風格與特色之綠能建築。</p>
                    <h2>鑫盈光電系統</h2>
                    <p><img src="/images/Architecture.jpg" width="638" height="419"/></p>
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
