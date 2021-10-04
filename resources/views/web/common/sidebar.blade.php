<ul>
    <li><a href="{{ route('web.products.list_a') }}"{!! Request::is('products_a') ? ' class="current"':'' !!}>系統整合</a>
        <ul>
            <li><a href="{{ route('web.products.list_a1') }}"{!! Request::is('products_a1') ? ' class="current"':'' !!}>屋頂型</a></li>
            <li><a href="{{ route('web.products.list_a2') }}"{!! Request::is('products_a2') ? ' class="current"':'' !!}>落地型</a></li>
            <li><a href="{{ route('web.products.list_a3') }}"{!! Request::is('products_a3') ? ' class="current"':'' !!}>環境整合型</a></li>
            <li><a href="{{ route('web.products.list_a4') }}"{!! Request::is('products_a4') ? ' class="current"':'' !!}>套件型</a></li>
        </ul>
    </li>
    <li><a href="{{ route('web.products.list_b') }}"{!! Request::is('products_b') ? ' class="current"':'' !!}>模組客製化</a>
        <ul>
            <li><a href="{{ route('web.products.list_b1') }}"{!! Request::is('products_b1') ? ' class="current"':'' !!}>標準型</a></li>
            <li><a href="{{ route('web.products.list_b2') }}"{!! Request::is('products_b2') ? ' class="current"':'' !!}>建材一體型</a></li>
        </ul>
    </li>
</ul>
