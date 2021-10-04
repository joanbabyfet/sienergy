<?php

use App\models\mod_common;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class seed_example extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fields = [
            'id',
            'cat_id',
            'title',
            'content',
            'img',
            'file',
            'is_hot',
            'sort',
            'status',
            'create_time',
            'create_user'
        ];

        $rows = [
            [mod_common::random('web'), 0, '標題1', '內容1', '', '', 0, 0, 1, time(), '0'],
            [mod_common::random('web'), 0, '標題2', '內容2', '', '', 0, 0, 1, time(), '0'],
        ];

        $insert_data = [];
        foreach ($rows as $row)
        {
            $item = [];
            foreach ($fields as $k => $field)
            {
                $item[$field] = $row[$k];
            }
            $insert_data[] = $item;
        }
        DB::table('example')->insert($insert_data); //走批量插入
    }
}
