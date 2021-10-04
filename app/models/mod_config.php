<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class mod_config extends mod_model
{
    //主键
    public $primaryKey = 'name';
    //主键是否支持自增,默认支持
    public $incrementing = false;
    //表名称
    public $table = 'config';
    //使用其他数据库连接
    //protected $connection = '';
    //字段
    public static $field = [
    ];
    //每页展示几笔
    public static $page_size = 10;
    //分組選項
    public static $group_options = [
        'config'     => '基本配置',
    ];
    private $module = 'config';

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
        //名稱
        $name       = !empty($conds['name']) ? $conds['name']:'';
        //組別
        $group       = !empty($conds['group']) ? $conds['group']:'';

        $where = [];
        $name and $where[] = ['name', 'like', "%{$name}%"];
        $group and $where[] = ['group', '=', $group];

        $order_by = !empty($order_by) ? $order_by : ['create_time', 'desc'];
        $group_by = !empty($group_by) ? $group_by : [];

        $rows = self::get_list([
            'fields'    => ['type', 'name', 'value', 'title', 'group', 'sort'
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

        foreach ($list as $k => $v)
        {
            $row_plus = [
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
            'do'        => 'required',
            'type'      => 'required',
            'name'      => 'required',
            'value'     => 'required',
            'title'     => 'required',
            'group'     => 'required',
            'sort'      => '',
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

            if($do == 'add')
            {
                $exists = mod_config::get_count(['where' => [
                    ['name', '=', $data_filter['name']]
                ]]);
                if(!empty($exists)) //检测变量名
                {
                    self::exception('变量名称已经存在！', -2);
                }
            }

            $do     = $data_filter['do'];
            $name     = $data_filter['name'];
            $create_user  = $data_filter['create_user'];
            $update_user  = $data_filter['update_user'];

            unset($data_filter['do'], $data_filter['create_user'], $data_filter['update_user']);

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
                self::update_data($data_filter, ['name'=>$name]);
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
            'name'                => 'required',
        ], $data);

        //开启事务
        DB::beginTransaction();
        $status = 1;
        try
        {
            $name = $data_filter['name'];
            unset($data_filter['name']);

            if(!is_array($data_filter))
            {
                self::exception(trans('api.api_param_error'), -1);
            }

            self::del(['name'=>$name]);
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

    //获取变量值从库
    protected function get_value($key)
    {
        $config = self::get_one(['where' => [
            ['name', '=', $key]
        ]]);
        return $config['value'];
    }

    /**
     * 获取配置
     * @param $key 变量名
     * @param array $extra 其他信息
     * @return float|int|mixed|string
     */
    protected function get($key, array $extra=[])
    {
        //参数过滤
        $data_filter = mod_common::data_filter([
            'type' => '', //返回类型
            'default' => '', //返回默认值
            'group' => '', //分组
        ], $extra);

        $configs = [];
        if (empty($data_filter['group'])) //没有给分组,则获取所有组别
        {
            if (empty($configs)) {
                $this->module = '';
                $configs = $this->cache();
            }
            $val = $data_filter['default'];
            foreach ($configs as $group => $config)
            {
                if (isset($config[$key]))
                {
                    $val = $config[$key];
                    break;
                }
            }
        }
        else
        {
            $this->module = $data_filter['group'];
            $db_config = $this->cache(); //获取自redis
            $val = isset($db_config[$key]) ? $db_config[$key] : null;
        }

        if ($val === null)
        {
            return $data_filter['default'];
        }

        switch ($data_filter['type'])
        {
            case 'int':
                return (int)$val;
            case 'text':
            case 'string':
                return (string)$val;
            case 'float':
                return (float)$val;
        }
        return $val;
    }

    /**
     * 设置配置缓存
     * @param bool $update 是否更新缓存, 默认 false
     * @return array|mixed
     */
    protected function cache(bool $update = false)
    {
        $cache_key = 'sys_db_config';
        $configs = mod_redis::get($cache_key);

        if($update || empty($configs))
        {
            $rows = mod_config::list_data([ //获取所有配置
                'fields'    => ['name', 'value', 'group']
            ]);

            $configs = [];
            foreach($rows as $row)
            {
                $configs[$row['group']][$row['name']] = $row['value'];
            }

            mod_redis::set($cache_key, $configs, 0);
        }

        if(!empty($this->module))
        {
            $configs = isset($configs[$this->module]) ? $configs[$this->module] : [];
        }
        return $configs;
    }
}
