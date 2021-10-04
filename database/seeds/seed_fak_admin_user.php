<?php

use Illuminate\Database\Seeder;

class seed_fak_admin_user extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        factory(App\models\mod_admin_user::class)->times(1)->make()->each(function ($model){
//            return $model->save();
//        });
        factory(App\models\mod_admin_user::class, 10)->create();
    }
}
