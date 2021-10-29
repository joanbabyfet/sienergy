@extends('web.layouts.home')

@section('title', config('global.web.app_title'))

{{--自定義css--}}
@section('style')
    @parent
    <style>
    </style>
@endsection

{{--內容--}}
@section('content')
    <div id="innerIndMain">
        <div id="idxSidebar">
            <div id="innerIndSidebar">
                <div class="header"><img src="/images/visual/sideBar/sideBarHdNewServices.gif" width="218"
                                         height="20"/></div>
                <div class="banner"><a href="http://www.pvtaiwan.com/zh_TW/index.html" target="_blank"><img
                                src="/images/ad/PvTaiwan.jpg" width="216" height="59"/></a>
                    <div class="info">台灣國際太陽光電論壇暨展覽會<br/>
                        攤位號碼：D0318
                    </div>
                </div>
            </div>
        </div>
        <div class="newsA">
            <div class="header"><img src="/styles/sienergy/images/idxHdTxLatestNews.gif" width="218"
                                     height="18"/></div>
            <div class="imgIcon"><img src="/styles/sienergy/images/idxNewsIcon.jpg" width="52" height="52"/>
            </div>
            <ul>
                @foreach($news as $v)
                <li><a href="{{ route('web.news.detail').'?id='.$v['id'] }}">{{ str_limit($v['title'], 60) }}</a>
                    @if($v['is_hot'])
                    <img src="/styles/sienergy/images/iconNewsHot.gif" width="17" height="5" align="texttop"/>
                    @endif
                </li>
                @endforeach
            </ul>
            <div class="more"><a href="{{ route('web.news.index') }}"><img src="/styles/sienergy/images/iconNewsMore.gif" alt="more"
                                                      width="34" height="10" border="0"/></a></div>
        </div>
        <div class="newsPhotos">
            <div class="header"><img src="/styles/sienergy/images/idxHdTxTheProducts.gif" width="218"
                                     height="18"/></div>
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
                <li><a href="{{ route('web.products.list_b1') }}"><img src="/images/pdIcon05.jpg" width="76" height="76"/></a>
                    <div class="info">標準型</div>
                </li>
                <li><a href="{{ route('web.products.list_b2') }}"><img src="/images/pdIcon06.jpg" width="76" height="76"/></a>
                    <div class="info">建材一體型</div>
                </li>
            </ul>
        </div>
        <div id="idxMainSide"></div>
    </div>
@endsection

{{--自定義js--}}
@section('script')
    @parent
    <script>
    </script>
@endsection
