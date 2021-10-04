;!function (win) {

    var Web = function () {

    };

    Web.prototype.paginate = function (count, curr, limit, limits) {
        layui.laypage.render({
            elem: 'page',
            count: count,
            curr: curr,
            limit: limit,
            limits: limits ? limits : [10, 20, 50, 100, 200, 500, 1000, 2000],
            layout: ['prev', 'page', 'next'],
            jump: function(obj, first){
                if(!first){
                    location.href= window.location.pathname + '?' + $("#search-form").serialize() + '&page='+obj.curr+'&limit='+obj.limit;
                }
            }
        });
    };

    win.web = new Web();
}(window);

layui.config({
    base: "/vendor/laravel-layui-admin/js/"
});

layui.use("jquery", function() {
    $ = layui.jquery; //重点处,后面就跟你平时使用jQuery一样
});
