<div id="innerHeaderIdx">
    <div id="top"><img class="idxTopLogo" src="/styles/images/spacer.gif" alt="logo" name="idxTopLogo"
                       width="182" height="75" border="0" id="idxTopLogo"/>
        <div class="nav">
            <a href="{{ route('web.index.index') }}"{!! Request::is('/') ? ' class="current"':'' !!}>HOME</a><span class="side">&nbsp;</span>
            <a href="#">English</a><span class="side">&nbsp;</span>
            <a href="#">中文</a><span class="side">&nbsp;</span>
            <a href="{{ route('web.links.index') }}"{!! Request::is('links') ? ' class="current"':'' !!}>友善連結</a>
{{--            @guest($guard)--}}
{{--                <a href="{{ route('web.login.showLoginForm') }}"{!! Request::is('links') ? ' class="current"':'' !!}>登入</a>--}}
{{--            @endguest--}}
{{--            @auth($guard)--}}
{{--                <a href="{{ route('web.login.logout') }}"{!! Request::is('links') ? ' class="current"':'' !!}>{{ $curr_user['username'] }} 登出</a>--}}
{{--            @endauth--}}
        </div>
    </div>
    <div id="HeaderNavIdx">
        <div id="innerHeaderNavIdx">
            <ul>
                <li><a href="{{ route('web.about.index') }}"{!! Request::is('about') ? ' class="current"':'' !!}>關於鑫盈</a></li>
                <li><a href="{{ route('web.news.index') }}"{!! Request::is('news') ? ' class="current"':'' !!}>最新消息</a></li>
                <li><a href="{{ route('web.products.index') }}"{!! Request::is('products') ? ' class="current"':'' !!}>產品 / 服務</a></li>
                <li><a href="{{ route('web.faq.index') }}"{!! Request::is('faq') ? ' class="current"':'' !!}>常見問題</a></li>
                <li><a href="{{ route('web.contact.index') }}"{!! Request::is('contact') || Request::is('feedback') ? ' class="current"':'' !!}>聯絡鑫盈</a></li>
            </ul>
            <div style="float:right; width:250px; background-color:#F6D900;">&nbsp;</div>
        </div>
    </div>
</div>
