<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield('title')</title>
    <link href="{{ WEB_CSS.'/sienergy/style.css' }}" rel="stylesheet" type="text/css"/>

    <!-- get jQuery from the google apis -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>

    <!-- REVOLUTION BANNER CSS SETTINGS -->
    <link rel="stylesheet" type="text/css" href="{{ WEB_CSS.'/sienergy/slider.css' }}" media="screen"/>
    <link rel="stylesheet" type="text/css" href="{{ PLUGINS.'/slider/rs-plugin/css/settings.css' }}" media="screen"/>

    <!-- REVOLUTION BANNER JS FILES  -->
    <script type="text/javascript" src="{{ PLUGINS.'/slider/rs-plugin/js/jquery.themepunch.plugins.min.js' }}"></script>
    <script type="text/javascript" src="{{ PLUGINS.'/slider/rs-plugin/js/jquery.themepunch.revolution.min.js' }}"></script>
    @section('style')
        {{-- 自定义css --}}
    @show
</head>

<body id="home">
<div id="containerIdx">
    <div id="HeaderIdx">
        @include('web.common.header')
    </div>
    <div id="bannerSideIdx"></div>
    <div class="bannercontainer">
        <div class="bannerVisual">
            <ul>
                <!-- BOXFADE -->
                <li data-transition="boxfade" data-slotamount="5" data-link="http://www.hanbox.com.tw"
                    data-thumb="{{ WEB_IMG }}/thumbs/regular_thumb1.jpg"><img src="{{ WEB_IMG }}/visual/idxVisualA.jpg"/>
                </li>
                <!-- SLIDE LEFT -->
                <li data-transition="slideleft" data-slotamount="10" data-thumb="{{ WEB_IMG }}/thumbs/regular_thumb2.jpg"><img
                            src="{{ WEB_IMG }}/visual/idxVisualB.jpg"/>
                </li>
                <!-- SLIDE DOWN -->
                <li data-transition="slidedown" data-slotamount="1" data-thumb="{{ WEB_IMG }}/thumbs/regular_thumb3.jpg"><img
                            src="{{ WEB_IMG }}/visual/idxVisualC.jpg"/>
                </li>
                <!-- SLOTFADE HORIZONTAL -->
                <li data-transition="slotfade-horizontal" data-slotamount="20"
                    data-thumb="{{ WEB_IMG }}/thumbs/regular_thumb4.jpg"><img src="{{ WEB_IMG }}/visual/idxVisualD.jpg"/>
                </li>
                <!-- SLIDE UP -->
                <li data-transition="slideup" data-slotamount="20" data-delay="25000"
                    data-thumb="{{ WEB_IMG }}/thumbs/regular_thumb5.jpg"><img src="{{ WEB_IMG }}/visual/idxVisualE.jpg"/>
                </li>
            </ul>
            <div class="tp-bannertimer"></div>
        </div>
    </div>
    <script type="text/javascript">

        var tpj = jQuery;
        tpj.noConflict();

        tpj(document).ready(function () {

            if (tpj.fn.cssOriginal != undefined)
                tpj.fn.css = tpj.fn.cssOriginal;

            tpj('.bannerVisual').revolution(
                {
                    delay: 9000,
                    startheight: 210,
                    startwidth: 980,

                    hideThumbs: 200,

                    thumbWidth: 100,							// Thumb With and Height and Amount (only if navigation Tyope set to thumb !)
                    thumbHeight: 50,
                    thumbAmount: 5,

                    navigationType: "both",					//bullet, thumb, none, both		(No Thumbs In FullWidth Version !)
                    navigationArrows: "none",				//nexttobullets, verticalcentered, none
                    navigationStyle: "round",				//round,square,navbar

                    touchenabled: "on",						// Enable Swipe Function : on/off
                    onHoverStop: "on",						// Stop Banner Timet at Hover on Slide on/off

                    navOffsetHorizontal: 360,
                    navOffsetVertical: 6,

                    shadow: 0,								//0 = no Shadow, 1,2,3 = 3 Different Art of Shadows  (No Shadow in Fullwidth Version !)
                    fullWidth: "off"							// Turns On or Off the Fullwidth Image Centering in FullWidth Modus

                });
        });

    </script>
    <div id="mainIdx">
        <div id="idxMain">
            @yield('content')
        </div>
        <div id="mainFooterSideIdx"></div>
    </div>
    <div id="footerIdx">
        @include('web.common.footer')
    </div>
</div>
@section('script')
    {{-- 自定义js --}}
@show
@include('web.common.google_analytics')
</body>
</html>
