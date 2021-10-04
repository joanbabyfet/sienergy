<?php

namespace App\models;


use Illuminate\Support\Facades\Session;

class mod_admin_user_oplog extends mod_mongodb
{
    //主键
    public $primaryKey = '_id';
    //表名称
    public $table = 'admin_users_oplog';
    //使用其他数据库连接
    protected $connection = 'mongodb'; //必填,否则用工厂生成测试数据时会报错
    //字段
    public static $field = [
    ];
    //每页展示几笔
    public static $page_size = 10;

    protected function list_data(array $conds)
    {
        $page_size  = !empty($conds['page_size']) ? $conds['page_size']:self::$page_size;
        $page       = $conds['page'] ?? null;
        $order_by   = $conds['order_by'] ?? null;
        $count      = $conds['count'] ?? null;
        $limit      = $conds['limit'] ?? null;
        $index      = $conds['index'] ?? null;
        $group_by   = $conds['group_by'] ?? null;
        $field      = $conds['field'] ?? null;
        $next_page  = $conds['next_page'] ?? null;
        //用戶名
        $username       = !empty($conds['username']) ? $conds['username']:'';
        $date1 = empty($conds['date1']) ? '' :mod_common::date_convert_timestamp("{$conds['date1']} 00:00:00", mod_common::get_admin_timezone());
        $date2   = empty($conds['date2']) ? '' :mod_common::date_convert_timestamp("{$conds['date2']} 23:59:59", mod_common::get_admin_timezone());

        $where = [];
        $date1 and $where[] = ['op_time', '>=', (int)$date1]; //开始时间
        $date2 and $where[] = ['op_time', '<=', (int)$date2]; //结束时间
        $username and $where[] = ['username', 'like', "%{$username}%"];

        $order_by = !empty($order_by) ? $order_by : ['op_time', 'desc'];
        $group_by = !empty($group_by) ? $group_by : [];

        $rows = self::get_list([
            'fields'    => ['_id', 'username', 'op_ip', 'op_country', 'op_time', 'msg',
            ],
            'where'     => $where,
            'page'      => $page,
            'page_size' => $page_size,
            'order_by'  => $order_by,
            'group_by'  => $group_by,
            'count'     => $count,
            'limit'     => $limit,
            'index'     => $index,
            'field'     => $field,
            'next_page' => $next_page, //对于app,不需要计算总条数，只需返回是否需要下一页
        ]);
        //格式化数据
        if($count) {
            $rows['data'] = self::format_data($rows['data']);
        }
        else {
            $rows = self::format_data($rows);
        }

        return $rows;
    }

    //详情
    protected function detail(array $conds)
    {
        $data = self::get_one(['where' => $conds]);

        if(!empty($data))
        {
            $data = self::format_data($data);
        }

        return $data;
    }

    //格式化数据
    private function format_data($data)
    {
        if(empty($data)) return $data;

        $list = is_array(reset($data)) ? $data : [$data];

        foreach ($list as $k => $v)
        {
            $row_plus = [
                //操作時間
                'op_time_dis'  => mod_display::datetime($v['op_time']),
            ];

            $list[$k] = array_merge($v, $row_plus);
        }

        return is_array(reset($data)) ? $list : reset($list);
    }

    //保存
    protected function save_data(array $data)
    {
        //参数过滤
        $data_filter = mod_common::data_filter([
            'uid'       => 'required',
            'username'  => 'required',
            'session_id'=> 'required',
            'msg'       => 'required',
            'op_time'   => 'required',
            'op_ip'     => 'required',
            'op_country'=> '',
            'op_url'    => '',
        ], $data);

        $status = 1;
        try
        {
            if(!is_array($data_filter))
            {
                self::exception(trans('api.api_param_error'), -1);
            }

            self::insert_data($data_filter);
        }
        catch (\Exception $e)
        {
            $status = self::get_exception_status($e);
            //记录日志
            mod_common::logger(__METHOD__, [
                'status'  => $status,
                'errcode' => $e->getCode(),
                'errmsg'  => $e->getMessage(),
                'data'    => $data,
            ]);
        }

        return $status;
    }

    //刪除
    protected function del_data(array $data)
    {
        //参数过滤
        $data_filter = mod_common::data_filter([
            '_id'           => 'required',
        ], $data);

        $status = 1;
        try
        {
            $id = $data_filter['_id'];
            unset($data_filter['_id']);

            if(!is_array($data_filter))
            {
                self::exception(trans('api.api_param_error'), -1);
            }

            self::del(['_id'=>$id]);
        }
        catch (\Exception $e)
        {
            $status = self::get_exception_status($e);
            //记录日志
            mod_common::logger(__METHOD__, [
                'status'  => $status,
                'errcode' => $e->getCode(),
                'errmsg'  => $e->getMessage(),
                'data'    => $data,
            ]);
        }

        return $status;
    }

    //保存操作日志
    protected function add_log($msg)
    {
        $guard = config('global.admin.guard');
        $msg = addslashes( $msg ); //替字符增加反斜線 ' => \'

        $this->save_data([
            'uid'               => auth($guard)->user()->getAuthIdentifier(),
            'username'          => auth($guard)->user()->username,
            'session_id'        => Session::getId(),
            'msg'               => $msg,
            'op_time'           => time(),
            'op_ip'             => request()->ip(),
            'op_country'        => '',
            'op_url'            => request()->path(),
        ]);
    }
}
