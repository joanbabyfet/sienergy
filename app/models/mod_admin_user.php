<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class mod_admin_user extends Authenticatable
{
    use Notifiable, HasRoles;

    //主键
    public $primaryKey = 'id';
    //主键是否支持自增,默认支持
    public $incrementing = false;
    //表名称
    public $table = 'admin_users';
    //使用其他数据库连接
    //protected $connection = '';
    //字段
    public static $field = [
    ];
    //添加时间字段
    const CREATED_AT = 'create_time';
    //修改时间字段
    const UPDATED_AT = 'update_time';
    //false=禁用自动填充时间戳
    public $timestamps = false;
    //时间使用时间戳
    public $dateFormat = 'U';
    //每页展示几笔
    public static $page_size = 10;
    //狀態
    const DISABLE = 0;
    const ENABLE = 1;
    public static $status_map = [
        self::DISABLE   => '禁用',
        self::ENABLE    => '啟用'
    ];
    //允许批量注入字段 $post = Post::create($request->all());
    protected $fillable = [
    ];
    //返回不含以下字段
    protected $hidden = [
        'password', 'remember_token', 'api_token'
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
        //用戶名
        $username       = !empty($conds['username']) ? $conds['username']:'';
        $status     = $conds['status'] ?? null;

        $where = [];
        $where[] = ['delete_time', '=', 0];
        //搜加密字段
        $username and $where[] = ['username', 'like', "%{$username}%"];
        is_numeric($status) and $where[] = ['status', '=', $status];

        $order_by = !empty($order_by) ? $order_by : ['create_time', 'desc'];
        $group_by = !empty($group_by) ? $group_by : [];

        $rows = mod_model::get_list([
            'table'     => $this->table,
            'fields'    => ['id', 'realname', 'username', 'email',
                'status', 'login_time', 'login_ip', 'create_user', 'create_time'
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
        $data = mod_model::get_one([
            'table'     => $this->table,
            'where' => $conds
        ]);

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
            //获取用户所属用户组
            $roles = mod_role::get_roles_by_uid([
                'model_type' => get_class($this),
                'model_id' => $v['id']
            ]);
            $role_options = mod_array::one_array($roles, ['id', 'name']);
            //获取用户全部权限 (组权限 + 独立权限)
            $purviews = self::get_purviews([
                'id'    => $v['id'],
                'field' => 'id',
            ]);

            $row_plus = [
                //状态
                'status_dis'       => array_key_exists($v['status'], self::$status_map) ? self::$status_map[$v['status']]:'',
                //用户组
                'role_name'        => implode(',', $role_options),
                //用户全部权限 (组权限 + 独立权限)
                'purviews'         => $purviews,
                //添加日期
                'create_time_dis'  => mod_display::datetime($v['create_time']),
                //上次登入日期
                'login_time_dis'  => mod_display::datetime($v['login_time']),
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
            'username'          => in_array($do, ['edit', 'editpwd']) ? '' : 'required',
            'password'          => $do == 'edit'  ? '' : 'required',
            'realname'          => '',
            'safe_ips'          => '',
            'session_expire'    => '',
            'roles'             => '',
            'email'             => '',
            'reg_ip'            => '',
            'login_time'        => '',
            'login_ip'          => '',
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
                mod_model::exception("{$data_filter}", -1);
            }

            $do         = $data_filter['do'];
            $id         = $data_filter['id'] ?? '';
            $username   = $data_filter['username'] ?? '';
            $password   = $data_filter['password'] ?? '';
            $create_user  = $data_filter['create_user'] ?? '';
            $update_user  = $data_filter['update_user'] ?? '';
            $reg_ip = $data_filter['reg_ip'] ?? '';
            $roles = empty($data_filter['roles']) ? [] : $data_filter['roles'];
            $is_sync_roles = isset($data_filter['roles']) ? 1 : 0; //是否同步用户组

            if($do == 'add' && empty($username))
            {
                mod_model::exception('用戶名不可空白', -1);
            }

            unset($data_filter['do'], $data_filter['id'], $data_filter['username']
                , $data_filter['password'], $data_filter['reg_ip']
                , $data_filter['create_user'], $data_filter['update_user']
                , $data_filter['roles']);

            if($do == 'add')
            {
                $data_filter['id'] = mod_common::random('web');
                $data_filter['username'] = $username;
                $data_filter['password'] = mod_common::password_hash($password);
                $data_filter['create_time'] = time();
                $data_filter['create_user'] = $create_user;
                $data_filter['reg_ip'] = $reg_ip;
                mod_model::insert_data($data_filter, $this->table);

                //同步该用户的用户组
                if($is_sync_roles)
                {
                    $user = self::find($data_filter['id']);
                    $user->syncRoles($roles);
                }
            }
            elseif($do == 'edit' || $do == 'editpwd')
            {
                if($password != '')
                {
                    $data_filter['password'] = mod_common::password_hash($password);
                }
                $data_filter['update_time'] = time();
                $data_filter['update_user'] = $update_user;
                mod_model::update_data($data_filter, ['id'=>$id], $this->table);

                //同步该用户的用户组
                if($is_sync_roles)
                {
                    $user = self::find($id);
                    $user->syncRoles($roles);
                }
            }
        }
        catch (\Exception $e)
        {
            $status = mod_model::get_exception_status($e);
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

            if(empty($id))
            {
                mod_model::exception('id is required', -1);
            }
            $data_filter['delete_time'] = time();
            mod_model::update_data($data_filter, ['id'=>$id], $this->table);
        }
        catch (\Exception $e)
        {
            $status = mod_model::get_exception_status($e);
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

    //啟用或禁用
    protected function change_status(array $data)
    {
        //参数过滤
        $data_filter = mod_common::data_filter([
            'id'                => 'required',
            'update_user'       => '',
        ], $data);

        //开启事务
        DB::beginTransaction();
        $status = 1;
        try
        {
            $id     = $data_filter['id'];
            unset($data_filter['id']);

            if(empty($id))
            {
                mod_model::exception('id is required', -1);
            }
            if(!is_numeric($data_filter['status']))
            {
                mod_model::exception('參數錯誤', -2);
            }

            $data_filter['update_time'] = time();
            mod_model::update_data($data_filter, ['id'=>$id], $this->table);
        }
        catch (\Exception $e)
        {
            $status = mod_model::get_exception_status($e);
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

    //获取用户权限 (用户权限 = 用户权限 + 组权限)
    protected function get_purviews(array $data)
    {
        //参数过滤
        $data_filter = mod_common::data_filter([
            'id'        => 'required',
            'type'      => '', //0=全部(默认) 1=独立权限 2=组权限
            'field'     => '',
        ], $data);

        $type = empty($data_filter['type']) ? 0 : $data_filter['type'];
        $field = empty($data_filter['field']) ? 'id' : $data_filter['field']; //默认返回id字段

        $user = self::find($data_filter['id']); //用户信息
        //获取该用户全部权限
        $purviews = [];

        if($type == 0){ //用户权限
            $purviews = $user->getAllPermissions()->pluck($field)->toArray();
        }
        elseif($type == 1) { //独立权限
            $purviews = $user->getDirectPermissions()->pluck($field)->toArray();
        }
        elseif($type == 2) { //组权限
            $purviews = $user->getPermissionsViaRoles()->pluck($field)->toArray();
        }

        //检测是否有超级管理员权限,独立权限跳过
        if (in_array($type, [0, 2]) && $user->hasRole(config('global.super_role_id'))) //1=超级管理员
        {
            $purviews = ['*'];
        }
        return $purviews;
    }

    //清除用戶獨立權限
    protected function del_purview(array $data)
    {
        //参数过滤
        $data_filter = mod_common::data_filter([
            'id'                => 'required',
        ], $data);

        //开启事务
        DB::beginTransaction();
        $status = 1;
        try
        {
            $id = $data_filter['id'];
            unset($data_filter['id']);

            if(empty($id))
            {
                mod_model::exception('id is required', -1);
            }
            //清除用戶獨立權限
            $user = self::find($id); //id不能为数组
            $user->syncPermissions([]); //同步
        }
        catch (\Exception $e)
        {
            $status = mod_model::get_exception_status($e);
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
}
