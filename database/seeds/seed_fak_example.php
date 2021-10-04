<?php

use Illuminate\Database\Seeder;

class seed_fak_example extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        factory(App\models\mod_example::class)->times(10)->make()->each(function ($model){
//            return $model->save();
//        });
        factory(App\models\mod_example::class, 12)->create();
    }
}
