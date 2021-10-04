<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class seed_model_has_roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fields = [
            'role_id',
            'model_type',
            'model_id',
        ];

        $rows = [
            [1, 'App\models\mod_admin_user', '1'],
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
        DB::table('model_has_roles')->insert($insert_data);
    }
}
