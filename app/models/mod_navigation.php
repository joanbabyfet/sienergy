<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class mod_navigation extends mod_model
{
    //主键
    public $primaryKey = 'id';
    //主键是否支持自增,默认支持
    public $incrementing = true;
    //表名称
    public $table = 'navigation';
    //使用其他数据库连接
    //protected $connection = '';
    //字段
    public static $field = [
    ];
    //每页展示几笔
    public static $page_size = 10;
    //狀態
//    const DISABLE = 0;
//    const ENABLE = 1;
//    public static $status_map = [
//        self::DISABLE   => '禁用',
//        self::ENABLE    => '啟用'
//    ];
    //导航類型
    public static $type_map = [
        'admin' => '后台系统',
    ];

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
        $type       = !empty($conds['type']) ? $conds['type']:'';
        $guard_name = !empty($conds['guard_name']) ? $conds['guard_name']:'';
        $name       = !empty($conds['name']) ? $conds['name']:'';

        $where = [];
        $where[] = ['delete_time', '=', 0];
        $type and $where[] = ['type', '=', $type];
        $guard_name and $where[] = ['guard_name', '=', $guard_name];
        $name and $where[] = ['name', '=', $name];

        $order_by = !empty($order_by) ? $order_by : ['create_time', 'asc'];
        $group_by = !empty($group_by) ? $group_by : [];

        $rows = self::get_list([
            'fields'    => ['id', 'name', 'parent_id', 'uri', 'permission_name'
                , 'type', 'guard_name', 'sort', 'create_user', 'create_time'
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
        //获取守卫表
        $guards = config('global.guard_names');

        foreach ($list as $k => $v)
        {
            $row_plus = [
                //守卫
                'guard_dis'        => array_key_exists($v['guard_name'], $guards)
                                    ? $guards[$v['guard_name']] : '',
                //导航類型
                'type_dis'        => array_key_exists($v['type'], self::$type_map)
                    ? self::$type_map[$v['type']] : '',
                //添加日期
                'create_time_dis'  => mod_display::datetime($v['create_time']),
                //添加人
                'create_user_dis'  => $v['create_user'],
            ];

            $list[$k] = array_merge($v, $row_plus);
        }

        return is_array(reset($data)) ? $list : reset($list);
    }

    //保存
    protected function save_data(array $data)
    {
        $do             = isset($data['do']) ? $data['do'] : '';
        //参数过滤
        $data_filter = mod_common::data_filter([
            'do'                => 'required',
            'id'                => $do == 'edit' ? 'required' : '',
            'parent_id'         => 'required',
            'name'              => 'required',
            'guard_name'        => 'required',
            'uri'               => '',
            'type'              => 'required',
            'permission_name'   => '',
            'icon'              => '',
            'sort'              => '',
            'create_user'       => '',
            'update_user'       => '',
        ], $data);

        //开启事务
        DB::beginTransaction();
        $status = 1;
        try
        {
            if(!is_array($data_filter))
            {
                self::exception(trans('api.api_param_error'), -1);
            }

            $do = $data_filter['do'];
            $id = $data_filter['id'];
            $create_user  = $data_filter['create_user'];
            $update_user  = $data_filter['update_user'];
            unset($data_filter['do'], $data_filter['id'], $data_filter['create_user'], $data_filter['update_user']);

            if($do == 'add')
            {
                $data_filter['create_time'] = time();
                $data_filter['create_user'] = $create_user;
                self::insert_data($data_filter);
            }
            elseif($do == 'edit')
            {
                $data_filter['update_time'] = time();
                $data_filter['update_user'] = $update_user;
                self::update_data($data_filter, ['id'=>$id]);
            }
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

    //刪除
    protected function del_data(array $data)
    {
        //参数过滤
        $data_filter = mod_common::data_filter([
            'id'                => 'required',
            'delete_user'       => '',
        ], $data);

        //开启事务
        DB::beginTransaction();
        $status = 1;
        try
        {
            $id = $data_filter['id'];
            unset($data_filter['id']);

            if(!is_array($data_filter))
            {
                self::exception(trans('api.api_param_error'), -1);
            }

            $data_filter['delete_time'] = time();
            self::update_data($data_filter, ['id'=>$id]);
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

    //获取树形
    protected function get_tree(array $data)
    {
        //参数过滤
        $data_filter = mod_common::data_filter([
            'type'              => '',
            'guard'             => '',
            'order_by'          => '',
            'purviews'          => '', //权限列表
            'is_permission'     => '', //是否匹配权限,默认不匹配
        ], $data);

        $default_guard = get_default_guard(); //默认守卫
        $type = empty($data_filter['type']) ? $default_guard : $data_filter['type'];
        $guard = empty($data_filter['guard']) ? $default_guard : $data_filter['guard'];
        $order_by = empty($data_filter['order_by']) ? ['create_time', 'asc'] : $data_filter['order_by'];
        $purviews = empty($data_filter['purviews']) ? [] : $data_filter['purviews'];
        $is_permission = empty($data_filter['is_permission']) ? 0 : $data_filter['is_permission'];

        //获取菜单列表
        $rows = self::list_data([
            'type'          =>  $type,
            'guard_name'    =>  $guard,
            'order_by'      =>  $order_by,
        ]);
        //遍历过滤,返回权限关联为空或在用户权限列表里
        if($is_permission)
        {
            $rows = array_filter($rows, function($item) use ($purviews) {
                return in_array('*', $purviews) ||
                    empty($item['permission_name']) ||
                    (!empty($item['permission_name']) && in_array($item['permission_name'], $purviews));
            });
        }
        return make_tree($rows, 'id', 'parent_id');
    }
}
