<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class seed_permission_groups extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $created_at = date('Y-m-d H:i:s');

        $fields = [
            'name',
            'created_at',
        ];

        $rows = [
            ['用戶管理', $created_at],
            ['用戶組别', $created_at],
            ['權限管理', $created_at],
            ['權限組别', $created_at],
            ['菜單管理', $created_at],
            ['日志管理', $created_at],
            ['會員管理', $created_at],
            ['會員等級', $created_at],
            ['H5管理', $created_at],
            ['報表管理', $created_at],
            ['新聞管理', $created_at],
            ['新聞分類', $created_at],
            ['問答管理', $created_at],
            ['問答分類', $created_at],
            ['友善連結', $created_at],
            ['文章管理', $created_at],
            ['緩存管理', $created_at],
            ['配置管理', $created_at],
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
        //DB::table('permission_groups')->truncate(); //干掉所有数据,并将自增重設为0
        DB::table('permission_groups')->insert($insert_data);
    }
}
