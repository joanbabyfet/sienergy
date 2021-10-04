<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\mod_admin_user;
use App\models\mod_common;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

//定义仅该文件能用最大内存，建议不要定义在php.ini会造成每支文件都能用到512M
//生成10万条数据需至少512M
ini_set('memory_limit', '512M');

$factory->define(mod_admin_user::class, function (Faker $faker) {
    return [
        'id'       => mod_common::random('web'),
        'realname' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'username' => $faker->unique()->userName,
        'password' => bcrypt('Bb123456'),
        'status' => 1,
        'safe_ips' => '',
        'is_first_login' => 1,
        'is_audit' => 0,
        'session_expire' => 1440,
        'session_id' => '',
        'reg_ip' => $faker->localIpv4,
        'login_time' => $faker->unixTime('now'),
        'login_ip' => $faker->ipv4,
        'remember_token' => Str::random(10),
        'api_token' => Str::random(10),
        'create_time' => $faker->unixTime('now'),
        'create_user' => '0',
        'update_time' => 0,
        'update_user' => '0',
        'delete_time' => 0,
        'delete_user' => '0',
    ];
});
