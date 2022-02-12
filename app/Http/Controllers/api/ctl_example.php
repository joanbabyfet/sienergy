<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\models\mod_common;
use App\models\mod_example;
use App\models\mod_news_cat;

class ctl_example extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取文章列表
     *
     * Author   Alan
     * Created  2021-04-01 10:45
     * Modified By Alan
     * Modified 2020-04-01 10:45
     *
     * @apiSampleRequest off
     * @api {post} example 获取文章列表
     * @apiGroup example
     * @apiName index
     * @apiVersion 1.0.0
     * @apiDescription 获取文章列表
     * @apiParam {int} page_size  每页显示几条
     * @apiParam {int} page_no 第几页
     * @apiParam {int} cat_id 分類id
     * @apiSuccessExample {json} 返回示例:
    {
        "code": 0,
        "msg": "success",
        "timestamp": 1633067192,
        "data": {
            "data": [
                {
                "id": "cba20abff7d9481bf418ee8697014806",
                "cat_id": 1,
                "title": "博士",
                "content": "這並沒有空，便都上岸。母親又說是要到他也敢出言無狀麽？那時是連日的早在我們便接了孩子還有什麼罷。大家也仿佛從這一定是皇帝已經投降，是社戲了。”鄒七嫂，也覺得自己。他身上，下了。一個巡警，五行缺土，但總免不了偶然忘卻了，我于是想提倡文藝，于是我自己的名字會和“老”字面上，應該的。我的腦裡忽然又恨到七斤嫂沒有？——便好了。 夜間，我這《阿Q都早忘卻了。他的兒子的，三三兩兩的人叢去。”“老”字聯結起來。",
                "is_hot": 0,
                "status": 1,
                "sort": 0,
                "create_user": "0",
                "create_time": 1593349998,
                "status_dis": "啟用",
                "create_time_dis": "2020/06/28 21:13",
                "create_user_dis": "0",
                "img_dis": [],
                "img_url_dis": [],
                "file_dis": [],
                "file_url_dis": []
                }
            ],
        "total_page": 2,
        "total": 12
        }
    }
     */
    public function index(Request $request)
    {
        $page_size = $request->input('page_size', 10);
        $page_no = $request->input('page_no', 1);
        $page_no = !empty($page_no) ? $page_no : 1;

        $cat_id = $request->input('cat_id');
        $title    = $request->input('title') ?? '';

        //獲取數據
        $rows = mod_example::list_data([
            'cat_id'    => $cat_id,
            'title'     =>  $title,
            'page'      => $page_no,
            'page_size' => $page_size,
            'count'     => 1,
            'order_by'  => ['create_time', 'desc'],
        ]);

        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        if (mod_common::get_action() == 'export_list') //獲取調用方法名
        {
            $titles = [
                'title'             =>'標題',
                'sort'              =>'排序',
                'status_dis'        =>'狀態',
                'create_time_dis'   =>'添加時間',
            ];

            return mod_common::export_data([
                'page_no'   => $page_no,
                'rows'      => $rows,
                'file'      => $request->input('file', ''),
                'fields'    => $request->input('fields', []), //列表所有字段
                'titles'    => $titles, //輸出字段
                'total_page' => $pages->lastPage(),
            ]);
        }

        return mod_common::success([
            'data' => $rows['data'],
            'total_page' => $pages->lastPage(),
            'total' => $pages->total()
        ]);
    }

    //匯出功能
//    public function export_list(Request $request)
//    {
//        $this->index($request);
//    }

    /**
     * 获取文章详请
     *
     * Author   Alan
     * Created  2021-04-01 10:45
     * Modified By Alan
     * Modified 2020-04-01 10:45
     *
     * @apiSampleRequest off
     * @api {post} example/detail 获取文章详请
     * @apiGroup example
     * @apiName detail
     * @apiVersion 1.0.0
     * @apiDescription 获取文章详请
     * @apiParam {String} id  id
     * @apiSuccessExample {json} 返回示例:
    {
        "code": 0,
        "msg": "success",
        "timestamp": 1633067402,
        "data": {
            "id": "3f4e27e38e76f16cca5b1ac279411688",
            "cat_id": 1,
            "title": "博士",
            "content": "衣袋里，藍皮阿五，睡眼蒙朧的跟定他，問他可以責備的。」 「我想：他和趙太太也正是雙十節之後，雖然是出雜誌，名目。孔子曰，“沒有人說，「竊書！……” “救命，移植到他家的秤又是橫笛，很像久餓的人，便給他穿上棉襖；現在想念水生卻又粗又笨重，便坐在床上就要喫飯的人，都埋着死刑宣告討論，我家來要錢，一千字也不行的拼法寫他為阿Q抓出衙門裏面的屋子裏，替他宣傳，而且奇怪，似乎看翻筋斗。」 小栓也趁着熱鬧，拚。",
            "img": "",
            "file": "",
            "is_hot": 0,
            "sort": 0,
            "status": 1,
            "create_time": 821712958,
            "create_user": "0",
            "update_time": 0,
            "update_user": "0",
            "delete_time": 0,
            "delete_user": "0",
            "status_dis": "啟用",
            "create_time_dis": "1996/01/15 21:35",
            "create_user_dis": "0",
            "img_dis": [],
            "img_url_dis": [],
            "file_dis": [],
            "file_url_dis": []
        }
    }
     */
    public function detail(Request $request)
    {
        $id = $request->input('id');
        $row = mod_example::detail(['id' => $id]);

        return mod_common::success($row);
    }

    /**
     * 删除文章
     *
     * Author   Alan
     * Created  2021-04-01 10:45
     * Modified By Alan
     * Modified 2020-04-01 10:45
     *
     * @apiSampleRequest off
     * @api {post} example/delete 删除文章
     * @apiGroup example
     * @apiName delete
     * @apiVersion 1.0.0
     * @apiDescription 删除文章
     * @apiParam {array} ids  id
     * @apiSuccessExample {json} 返回示例:
     {
        "code": 0,
        "msg": "刪除成功",
        "timestamp": 1619311833,
        "data": []
     }
     */
    public function delete(Request $request)
    {
        if ($request->isMethod('POST'))
        {
            $id = $request->input('ids', []);

            $status = mod_example::del_data([
                'id'            => $id + [-1],
                'delete_user'   => $this->uid,
            ]);
            if($status < 0)
            {
                return mod_common::error(mod_example::get_err_msg($status), $status);
            }
            return mod_common::success([], trans('api.api_delete_success'));
        }
    }

    //开启
    public function enable(Request $request)
    {
        if ($request->isMethod('POST'))
        {
            $id     = $request->input('ids', []);
            $status = mod_example::change_status([
                'id'        => $id,
                'status'    => mod_example::ENABLE,
                'update_user'   => $this->uid,
            ]);
            if($status < 0)
            {
                return mod_common::error(mod_example::get_err_msg($status), $status);
            }

            return mod_common::success([], trans('api.api_enable_success'));
        }
    }

    //禁用
    public function disable(Request $request)
    {
        if ($request->isMethod('POST'))
        {
            $id = $request->input('ids', []);
            $status = mod_example::change_status([
                'id'        => $id,
                'status'    => mod_example::DISABLE,
                'update_user'   => $this->uid,
            ]);

            if($status < 0)
            {
                return mod_common::error(mod_example::get_err_msg($status), $status);
            }

            return mod_common::success([], trans('api.api_disable_success'));
        }
    }

    /**
     * 添加文章
     *
     * Author   Alan
     * Created  2021-04-01 10:45
     * Modified By Alan
     * Modified 2020-04-01 10:45
     *
     * @apiSampleRequest off
     * @api {post} example/add 添加文章
     * @apiGroup example
     * @apiName add
     * @apiVersion 1.0.0
     * @apiDescription 添加文章
     * @apiParam {int} cat_id  分類id
     * @apiParam {String} title  标题
     * @apiParam {String} content  内容
     * @apiParam {int} status  状态
     * @apiSuccessExample {json} 返回示例:
     {
        "code": 0,
        "msg": "保存成功",
        "timestamp": 1619312083,
        "data": []
     }
     */
    public function add(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $status = $this->save($request);
            if($status < 0)
            {
                return mod_common::error(mod_example::get_err_msg($status), $status);
            }
            return mod_common::success([], trans('api.api_add_success'));
        }
        else
        {
            $cats = mod_news_cat::list_data([]);
            return mod_common::success([
                'cats' => $cats,
            ]);
        }
    }

    /**
     * 修改文章
     *
     * Author   Alan
     * Created  2021-04-01 10:45
     * Modified By Alan
     * Modified 2020-04-01 10:45
     *
     * @apiSampleRequest off
     * @api {post} example/edit 修改文章
     * @apiGroup example
     * @apiName edit
     * @apiVersion 1.0.0
     * @apiDescription 修改文章
     * @apiParam {String} id  id
     * @apiParam {int} cat_id  分類id
     * @apiParam {String} title  标题
     * @apiParam {String} content  内容
     * @apiParam {int} status  状态
     * @apiSuccessExample {json} 返回示例:
        {
            "code": 0,
            "msg": "保存成功",
            "timestamp": 1619312083,
            "data": []
        }
     */
    public function edit(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $status = $this->save($request);
            if($status < 0)
            {
                return mod_common::error(mod_example::get_err_msg($status), $status);
            }
            return mod_common::success([], trans('api.api_update_success'));
        }
        else
        {
            $id = $request->input('id');
            $row = mod_example::detail(['id' => $id]);
            $cats = mod_news_cat::list_data([]);

            return mod_common::success([
                'row' => $row,
                'cats' => $cats,
            ]);
        }
    }

    /**
     * 保存
     */
    private function save(Request $request)
    {
        $status = mod_example::save_data([
            'do'        => mod_common::get_action(),
            'id'        => $request->input('id'),
            'title'     => $request->input('title'),
            'content'    => $request->input('content', ''),
            'status'    => $request->input('status', 0),
            'file'      => $request->input('file', []),
            'img'       => $request->input('img', []),
            'create_user'   => $this->uid,
            'update_user'   => $this->uid,
        ]);

        return $status;
    }
}
