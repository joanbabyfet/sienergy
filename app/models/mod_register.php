<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * 注册树模式
 * 例laravel自带config/app.php aliases就是用该模式
 * 在工厂模式中添加 mod_register::set('db1', $db)后,
 * 使用时调用 mod_register::get('db1')
 * Class mod_register
 * @package App\models
 */
class mod_register extends Model
{
    protected static $objects = []; //注册数组

    /**
     * 添加对象到全局树
     * @param $alias 别名
     * @param $object
     */
    public static function set($alias, $object)
    {
        self::$objects[$alias] = $object;
    }

    /**
     * 从全局树干掉对象
     * @param $alias 别名
     */
    public static function _unset($alias)
    {
        unset(self::$objects[$alias]);
    }

    /**
     * 获取对象
     * @param $alias 别名
     * @return mixed
     */
    public static function get($alias)
    {
        return self::$objects[$alias];
    }
}
