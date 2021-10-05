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
                <div class="header"><img src="/images/visual/sideBar/sideBarHdQA.gif" alt="" width="218"
                                         height="20"/></div>
                <div class="blockA">
                    <div class="main menu">
                        <ul>
                            @foreach($cats as $v)
                            <li><a href="{{ route('web.faq.index').'?cat_id='.$v['id'] }}">{{ $v['name'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="footer"></div>
                </div>
            </div>
        </div>
        <div id="bodyMain">
            <div id="innerBodyMain">
                <div id="pagePath"><a href="{{ route('web.index.index') }}">首頁</a><span class="side">&nbsp;</span>常見問題</div>
                <div class="mainTitle titleBrand">常見問題</div>
                <div id="mainContents">
                    @php
                        $i = 1;
                    @endphp
                    @foreach($list as $v)
                    <div class="msgBox">
                        <div class="msgBoxA">
                            <div class="topSide">&nbsp;</div>
                            <div class="mbContent"><strong>Q{{ $i }}.</strong> {!! $v['question'] !!}</div>
                            <div class="belowSide">&nbsp;</div>
                            <div class="mbArrow">&nbsp;</div>
                        </div>
                        <div class="msgBoxB">
                            <div class="topSide">&nbsp;</div>
                            <div class="mbContent"><strong>ANS:</strong>
                                {!! $v['answer'] !!}
                            </div>
                            <div class="belowSide">&nbsp;</div>
                            <div class="mbArrow">&nbsp;</div>
                        </div>
                    </div>
                        @php
                            $i++;
                        @endphp
                    @endforeach
                </div>
                <div id="page" style="text-align:center; margin-top:20px"></div>
            </div>
        </div>
        <div id="mainFooterSide"></div>
    </div>
@endsection

{{--自定義js--}}
@section('script')
    @parent
    <script>
        layui.use('laypage', function(){
            //分頁器
            web.paginate("{{ $pages->total() }}", "{{ $pages->currentPage() }}", "{{ $pages->perPage() }}");
        });
    </script>
@endsection
