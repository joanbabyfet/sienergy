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
                <div class="header"><img src="/images/visual/sideBar/sideBarHdContact.gif" alt="" width="218"
                                         height="20"/></div>
                <div class="blockA">
                    <div class="main menu">
                        <ul>
                            <li><a href="{{ route('web.contact.index') }}"{!! Request::is('contact') ? ' class="current"':'' !!}>公司資訊</a></li>
                            <li><a href="{{ route('web.contact.feedback') }}"{!! Request::is('feedback') ? ' class="current"':'' !!}>問題諮詢</a></li>
                        </ul>
                    </div>
                    <div class="footer"></div>
                </div>
            </div>
        </div>
        <div id="bodyMain">
            <div id="innerBodyMain">
                <div id="pagePath"><a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span>聯絡鑫盈</div>
                <div class="mainTitle titleBrand">聯絡鑫盈</div>
                <div id="mainContents">
                    <h2>鑫盈能源股份有限公司</h2>
                    <p>地址：桃園縣龍潭鄉渴望園區渴望路83號4F<br/>
                        電話：886-3-4072325　傳真：886-3-4072582<br/>
                        電子信箱：<a href="mailto:info@shining-energy.com">info@shining-energy.com</a><br/>
                        統編：53757193</p>
                    <p><img src="/images/googleMap.jpg" width="638" height="350"/><br/>
                        <small>
                            <a href="https://maps.google.com/maps?f=q&amp;source=embed&amp;hl=zh-TW&amp;geocode=&amp;q=%E6%A1%83%E5%9C%92%E7%B8%A3%E9%BE%8D%E6%BD%AD%E9%84%89%E6%B8%B4%E6%9C%9B%E5%9C%92%E5%8D%80%E6%B8%B4%E6%9C%9B%E8%B7%AF83%E8%99%9F&amp;aq=&amp;sll=37.0625,-95.677068&amp;sspn=67.840216,135.263672&amp;t=m&amp;ie=UTF8&amp;hq=%E6%B8%B4%E6%9C%9B%E5%9C%92%E5%8D%80%E6%B8%B4%E6%9C%9B%E8%B7%AF83%E8%99%9F&amp;hnear=%E5%8F%B0%E7%81%A3%E6%A1%83%E5%9C%92%E7%B8%A3%E9%BE%8D%E6%BD%AD%E9%84%89&amp;cid=12547802580784717119&amp;ll=24.845553,121.196451&amp;spn=0.02726,0.05476&amp;z=14"
                               target="_blank" style="color:#0000FF;text-align:left">檢視較大的地圖</a></small>
                    </p>
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
