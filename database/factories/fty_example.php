<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\mod_example;
use Faker\Generator as Faker;
use App\models\mod_common;

$factory->define(mod_example::class, function (Faker $faker) {
    return [
        'id' => mod_common::random('web'),
        'cat_id' => $faker->numberBetween(0, 3), // 数字在 0-3 之间随机
        'title' => $faker->title(10), //回3个单词，false表示返回一个数组；true表示返回一个字符串，单词之间用空格分开
        'content' => $faker->realText(200),
        'img' => '',
        'file' => '',
        'is_hot' => 0,
        'sort' => 0,
        'status' => 1,
        'create_time' => $faker->unixTime('now'),
        'create_user' => '0',
        'update_time' => 0,
        'update_user' => '0',
        'delete_time' => 0,
        'delete_user' => '0',
    ];
});
