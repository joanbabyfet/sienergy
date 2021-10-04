<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\models\mod_model;

class mod_member_increase_data extends mod_model
{
    //主键
    public $primaryKey = 'date';
    //主键是否支持自增,默认支持
    public $incrementing = false;
    //表名称
    public $table = 'member_increase_data';
    //使用其他数据库连接
    //protected $connection = '';
    //字段
    public static $field = [
    ];
    //每页展示几笔
    public static $page_size = 10;
    //今天日期
    public static $today    = '';
    //时区
    public static $timezone    = '';

    public function __construct()
    {
        parent::__construct();

        self::$timezone = mod_common::get_admin_timezone(); //例：ETC/GMT-7
        self::$today    = mod_common::format_date(time(), self::$timezone, 'Y/m/d');
    }

    protected function list_data(array $conds)
    {
        //参数过滤
        $data_filter = mod_common::data_filter([
            'date1'         => '',
            'date2'         => '',
            'origin'        => '',
            'group_by'      => '',
            'page_size'     => 'required',
            'page'          => 'required',
            'order_by'      => '',
            'count'         => '',
            'limit'         => '',
            'index'         => '',
        ], $conds);

        $date1 = $data_filter['date1'];
        $date2 = $data_filter['date2'];
        $page_size  = !empty($data_filter['page_size']) ? $data_filter['page_size']:self::$page_size;
        $page       = $data_filter['page'] ?? null;
        $order_by   = $data_filter['order_by'] ?? null;
        $count      = $data_filter['count'] ?? null;
        $limit      = $data_filter['limit'] ?? null;
        $index      = $data_filter['index'] ?? null;
        $group_by   = $data_filter['group_by'] ?? null;
        $field      = $data_filter['field'] ?? null;
        $next_page  = $conds['next_page'] ?? null;
        $origin     = $data_filter['origin']; //来源

        $where = [];
        $date1 and $where[] = ['date', '>=', $date1]; //开始时间
        $date2 and $where[] = ['date', '<=', $date2]; //结束时间
        is_numeric($origin) and $where[] = ['origin', '=', $origin];

        $order_by = !empty($order_by) ? $order_by : ['date', 'desc'];
        $group_by = !empty($group_by) ? $group_by : [];

        //刷新今天的数据，例：搜2021/04/01-2021/05/01且第一页就更新
        if ($date2 >= self::$today && $page == 1)
        {
            self::generate_data(self::$today);
        }

        $rows = self::get_list([
            'fields'    => [
                'date',
                'origin',
                self::expr('SUM(`member_count`) As member_count'),
                self::expr('SUM(`member_increase_count`) As member_increase_count'),
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

    //格式化数据
    private function format_data($data)
    {
        if(empty($data)) return $data;

        $list = is_array(reset($data)) ? $data : [$data];

        foreach ($list as $k => $v)
        {
            $row_plus = [
                'origin_dis'    => array_key_exists($v['origin'], mod_user::$origin_map) ?
                    mod_user::$origin_map[$v['origin']] : '',
            ];

            $list[$k] = array_merge($v, $row_plus);
        }

        return is_array(reset($data)) ? $list : reset($list);
    }

    /**
     * 生成数据
     *
     * @param string $from_date
     * @return mixed
     */
    protected function generate_data($from_date='')
    {
        $from_date = empty($from_date) ? '2019/01/01' : $from_date; //2019/01/01
        $timezone  = self::$timezone;
        $from_time = mod_common::date_convert_timestamp("{$from_date} 00:00:00", $timezone);

        //开启事务
        DB::beginTransaction();
        $status = 1;
        try
        {
            $rows = mod_model::get_list([
                'table'     => 'users',
                'fields'    => [
                    self::expr('count(*) AS member_increase_count'),
                    'origin',
                    self::expr("DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(`create_time`, '%Y/%m/%d %H:00'), '+8:00', '+7:00'), '%Y/%m/%d') As date"),
                ],
                'where'     => [
                    ['delete_time', '=', 0],
                    ['create_time', '>=', $from_time],//获取昨日以後数据 做更新
                ],
                'group_by'  => ['date', 'origin'], //依注册时间,来源做分组
            ]);

            $count_map = [];
            $pre_member_counts = [];
            foreach($rows as $item)
            {
                //查找最近一条统计
                if (!isset($pre_member_counts[$item['origin']]))
                {
                    $pre_member_counts[$item['origin']] = (int)self::get_field_value([
                        'fields'    => ['member_count'],
                        'where'     => [
                            ['date', '<', $item['date']],
                            ['origin', '=', $item['origin']],
                        ],
                        'order_by' => ['date', 'desc'],
                    ]);
                }
                $key = "{$item['date']}_{$item['origin']}";
                $member_count = $item['member_increase_count'] + $pre_member_counts[$item['origin']];
                $count_map[$key] = [
                    'member_increase_count' => $item['member_increase_count'],
                    'member_count'          => $member_count
                ];
                $pre_member_counts[$item['origin']] = $member_count;
            }

            $count_fields = ['member_count', 'member_increase_count']; //统计字段
            $data = [];
            foreach ($count_map as $k => $row)
            {
                $key = explode('_', $k);
                $data_item = [
                    'date'          => $key[0],
                    'origin'        => $key[1],
                    'timezone'      => $timezone,
                    'create_time'   => time()
                ];

                foreach ($count_fields as $field) //匹配字段
                {
                    $data_item[$field] = empty($count_map[$k][$field]) ? 0 : $count_map[$k][$field];
                }
                $data[] = $data_item;
            }

            self::insertOrUpdate($data, '');
        }
        catch (\Exception $e)
        {
            $status = self::get_exception_status($e);
            //记录日志
            mod_common::logger(__METHOD__, [
                'status'  => $status,
                'errcode' => $e->getCode(),
                'errmsg'  => $e->getMessage(),
                'data'    => $from_date,
            ]);
        }

        if ($status > 0)
        {
            DB::commit();   //手動提交事务
        }
        else
        {
            DB::rollback(); //手動回滚事务
        }

        return $status;
    }
}
