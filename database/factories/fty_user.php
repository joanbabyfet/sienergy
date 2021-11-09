<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\mod_common;
use App\models\mod_user;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(mod_user::class, function (Faker $faker) {
    return [
        'id'       => mod_common::random('web'),
        'realname' => $faker->name,
        'email' => $faker->safeEmail,
        'username' => $faker->unique()->userName,
        'password' => bcrypt('Bb123456'),
        'status' => 1,
        'is_first_login' => 1,
        'is_audit' => 0,
        'session_expire' => 1440,
        'session_id' => '',
        'reg_ip' => $faker->localIpv4,
        'login_time' => $faker->unixTime('now'),
        'login_ip' => $faker->ipv4,
        'remember_token' => mod_common::make_token(),
        'api_token' => mod_common::make_token(),
        'create_user' => '0',
        'update_time' => $faker->unixTime('now'),
        'create_time' => $faker->unixTime('now'),
        'update_user' => '0',
        'delete_time' => 0,
        'delete_user' => '0',
    ];
});
