<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class seed_roles extends Seeder
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
            'guard_name',
            'created_at',
        ];

        $rows = [
            ['超級管理員', 'admin', $created_at],
            ['普通會員', 'web', $created_at],
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
        DB::table('roles')->insert($insert_data);
    }
}
