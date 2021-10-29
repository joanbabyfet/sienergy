<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 使用 数据填充文件 生成基础数据
        $this->call(seed_roles::class);
        $this->call(seed_admin_user::class);
        $this->call(seed_model_has_roles::class);
        $this->call(seed_permission_groups::class);
        $this->call(seed_permissions::class);
        $this->call(seed_navigation::class);
        $this->call(seed_country::class);
        $this->call(seed_area::class);
        $this->call(seed_currency_type::class);

        //$this->call(seed_example::class);
        //$this->call(seed_fak_example::class);
        //$this->call(seed_fak_admin_user::class);

        // 使用 模型工厂文件 生成测试数据，類别名称路径大小写要一致不然会报错，无法生成数据
        //factory(App\models\mod_admin_user::class, 10)->create();
        //factory(App\models\mod_example::class, 12)->create();
        //factory(App\models\mod_user::class, 10)->create();
    }
}
