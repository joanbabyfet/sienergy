<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class seed_permissions extends Seeder
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
            'display_name',
            'pg_id',
            'created_at',
        ];

        $rows = [
            ['admin.admin_user.index', 'admin', '用戶列表', 1, $created_at],
            ['admin.admin_user.add', 'admin', '用戶添加', 1, $created_at],
            ['admin.admin_user.edit', 'admin', '用戶修改', 1, $created_at],
            ['admin.admin_user.delete', 'admin', '用戶刪除', 1, $created_at],
            ['admin.admin_user.enable', 'admin', '用戶啟用', 1, $created_at],
            ['admin.admin_user.disable', 'admin', '用戶禁用', 1, $created_at],
            ['admin.admin_user.export_list', 'admin', '用戶匯出', 1, $created_at],
            ['admin.admin_user.purview', 'admin', '獨立權限', 1, $created_at],
            ['admin.admin_user.del_purview', 'admin', '清除權限', 1, $created_at],
            ['admin.role.index', 'admin', '用戶組列表', 2, $created_at],
            ['admin.role.add', 'admin', '用戶組添加', 2, $created_at],
            ['admin.role.edit', 'admin', '用戶組修改', 2, $created_at],
            ['admin.role.delete', 'admin', '用戶組刪除', 2, $created_at],
            ['admin.role.export_list', 'admin', '用戶組匯出', 2, $created_at],
            ['admin.permission.index', 'admin', '權限列表', 3, $created_at],
            ['admin.permission.add', 'admin', '權限添加', 3, $created_at],
            ['admin.permission.edit', 'admin', '權限修改', 3, $created_at],
            ['admin.permission.delete', 'admin', '權限刪除', 3, $created_at],
            ['admin.permission.export_list', 'admin', '權限匯出', 3, $created_at],
            ['admin.permission_group.index', 'admin', '權限組列表', 4, $created_at],
            ['admin.permission_group.add', 'admin', '權限組添加', 4, $created_at],
            ['admin.permission_group.edit', 'admin', '權限組修改', 4, $created_at],
            ['admin.permission_group.delete', 'admin', '權限組刪除', 4, $created_at],
            ['admin.permission_group.export_list', 'admin', '權限組匯出', 4, $created_at],
            ['admin.navigation.index', 'admin', '菜單列表', 5, $created_at],
            ['admin.navigation.add', 'admin', '菜單添加', 5, $created_at],
            ['admin.navigation.edit', 'admin', '菜單修改', 5, $created_at],
            ['admin.navigation.delete', 'admin', '菜單刪除', 5, $created_at],
            ['admin.admin_user_login.index', 'admin', '登入日志列表', 6, $created_at],
            ['admin.admin_user_login.delete', 'admin', '登入日志刪除', 6, $created_at],
            ['admin.admin_user_login.export_list', 'admin', '登入日志匯出', 6, $created_at],
            ['admin.admin_user_oplog.index', 'admin', '操作日志列表', 6, $created_at],
            ['admin.admin_user_oplog.delete', 'admin', '操作日志刪除', 6, $created_at],
            ['admin.admin_user_oplog.export_list', 'admin', '操作日志匯出', 6, $created_at],
            ['admin.api_req_log.index', 'admin', '訪問日志列表', 6, $created_at],
            ['admin.api_req_log.delete', 'admin', '訪問日志刪除', 6, $created_at],
            ['admin.api_req_log.export_list', 'admin', '訪問日志匯出', 6, $created_at],
            ['admin.member.index', 'admin', '會員列表', 7, $created_at],
            ['admin.member.edit', 'admin', '會員修改', 7, $created_at],
            ['admin.member.enable', 'admin', '會員啟用', 7, $created_at],
            ['admin.member.disable', 'admin', '會員禁用', 7, $created_at],
            ['admin.member.export_list', 'admin', '會員匯出', 7, $created_at],
            ['admin.member_login.index', 'admin', '登入日志列表', 7, $created_at],
            ['admin.member_login.delete', 'admin', '登入日志刪除', 7, $created_at],
            ['admin.member_login.export_list', 'admin', '登入日志匯出', 7, $created_at],
            ['admin.member_level.index', 'admin', '會員等級列表', 8, $created_at],
            ['admin.member_level.add', 'admin', '會員等級添加', 8, $created_at],
            ['admin.member_level.edit', 'admin', '會員等級修改', 8, $created_at],
            ['admin.member_level.delete', 'admin', '會員等級刪除', 8, $created_at],
            ['admin.member_level.export_list', 'admin', '會員等級匯出', 8, $created_at],
            ['admin.h5.index', 'admin', 'H5列表', 9, $created_at],
            ['admin.h5.add', 'admin', 'H5添加', 9, $created_at],
            ['admin.h5.edit', 'admin', 'H5修改', 9, $created_at],
            ['admin.h5.delete', 'admin', 'H5刪除', 9, $created_at],
            ['admin.h5.enable', 'admin', 'H5啟用', 9, $created_at],
            ['admin.h5.disable', 'admin', 'H5禁用', 9, $created_at],
            ['admin.h5.export_list', 'admin', 'H5匯出', 9, $created_at],
            ['admin.h5.detail', 'admin', 'H5查看', 9, $created_at],
            ['admin.report.member_increase_data', 'admin', '會員增長數列表', 10, $created_at],
            ['admin.report.export_list', 'admin', '會員增長數匯出', 10, $created_at],
            ['admin.news.index', 'admin', '新聞列表', 11, $created_at],
            ['admin.news.add', 'admin', '新聞添加', 11, $created_at],
            ['admin.news.edit', 'admin', '新聞修改', 11, $created_at],
            ['admin.news.delete', 'admin', '新聞刪除', 11, $created_at],
            ['admin.news.enable', 'admin', '新聞啟用', 11, $created_at],
            ['admin.news.disable', 'admin', '新聞禁用', 11, $created_at],
            ['admin.news.export_list', 'admin', '新聞匯出', 11, $created_at],
            ['admin.news_cat.index', 'admin', '新聞分類列表', 12, $created_at],
            ['admin.news_cat.add', 'admin', '新聞分類添加', 12, $created_at],
            ['admin.news_cat.edit', 'admin', '新聞分類修改', 12, $created_at],
            ['admin.news_cat.delete', 'admin', '新聞分類刪除', 12, $created_at],
            ['admin.news_cat.enable', 'admin', '新聞分類啟用', 12, $created_at],
            ['admin.news_cat.disable', 'admin', '新聞分類禁用', 12, $created_at],
            ['admin.news_cat.export_list', 'admin', '新聞分類匯出', 12, $created_at],
            ['admin.faq.index', 'admin', '問答列表', 13, $created_at],
            ['admin.faq.add', 'admin', '問答添加', 13, $created_at],
            ['admin.faq.edit', 'admin', '問答修改', 13, $created_at],
            ['admin.faq.delete', 'admin', '問答刪除', 13, $created_at],
            ['admin.faq.enable', 'admin', '問答啟用', 13, $created_at],
            ['admin.faq.disable', 'admin', '問答禁用', 13, $created_at],
            ['admin.faq.export_list', 'admin', '問答匯出', 13, $created_at],
            ['admin.faq_cat.index', 'admin', '問答分類列表', 14, $created_at],
            ['admin.faq_cat.add', 'admin', '問答分類添加', 14, $created_at],
            ['admin.faq_cat.edit', 'admin', '問答分類修改', 14, $created_at],
            ['admin.faq_cat.delete', 'admin', '問答分類刪除', 14, $created_at],
            ['admin.faq_cat.enable', 'admin', '問答分類啟用', 14, $created_at],
            ['admin.faq_cat.disable', 'admin', '問答分類禁用', 14, $created_at],
            ['admin.faq_cat.export_list', 'admin', '問答分類匯出', 14, $created_at],
            ['admin.link.index', 'admin', '友善連結列表', 15, $created_at],
            ['admin.link.add', 'admin', '友善連結添加', 15, $created_at],
            ['admin.link.edit', 'admin', '友善連結修改', 15, $created_at],
            ['admin.link.delete', 'admin', '友善連結刪除', 15, $created_at],
            ['admin.link.enable', 'admin', '友善連結啟用', 15, $created_at],
            ['admin.link.disable', 'admin', '友善連結禁用', 15, $created_at],
            ['admin.link.export_list', 'admin', '友善連結匯出', 15, $created_at],
            ['admin.example.index', 'admin', '文章列表', 16, $created_at],
            ['admin.example.detail', 'admin', '文章查看', 16, $created_at],
            ['admin.example.add', 'admin', '文章添加', 16, $created_at],
            ['admin.example.edit', 'admin', '文章修改', 16, $created_at],
            ['admin.example.delete', 'admin', '文章刪除', 16, $created_at],
            ['admin.example.enable', 'admin', '文章啟用', 16, $created_at],
            ['admin.example.disable', 'admin', '文章禁用', 16, $created_at],
            ['admin.example.export_list', 'admin', '文章匯出', 16, $created_at],
            ['admin.cache.redis_keys', 'admin', 'Redis鍵值列表', 17, $created_at],
            ['admin.cache.delete', 'admin', 'Redis鍵值刪除', 17, $created_at],
            ['admin.cache.detail', 'admin', 'Redis鍵值查看', 17, $created_at],
            ['admin.cache.redis_info', 'admin', 'Redis服務器信息', 17, $created_at],
            ['admin.config.index', 'admin', '配置列表', 18, $created_at],
            ['admin.config.add', 'admin', '配置添加', 18, $created_at],
            ['admin.config.edit', 'admin', '配置修改', 18, $created_at],
            ['admin.config.delete', 'admin', '配置刪除', 18, $created_at],
            ['admin.config.export_list', 'admin', '配置匯出', 18, $created_at],
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
        //DB::table('permissions')->truncate(); //干掉所有数据,并将自增重设为0
        DB::table('permissions')->insert($insert_data);
    }
}
