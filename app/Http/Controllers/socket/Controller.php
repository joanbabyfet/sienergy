<?php

namespace App\Http\Controllers\socket;

use App\models\mod_common;
use App\models\mod_socket;
use GatewayWorker\Lib\Gateway;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $action = '';
    protected $client_id = '';
    protected $ct = '';
    protected $data      = [];
    protected $uid       = '';
    protected $user      = [];
    protected $lang      = 'zh-tw';

    public function __construct()
    {

    }

    //处理主入口
    public function handle(Request $request)
    {
        $action             = $request->input('action');
        $token              = $request->input('token');
        $client_id          = $request->input('client_id');
        $ct                 = $request->input('ct');
        $data               = $request->input('data', []);
        $this->action       = $action;
        $this->client_id    = $client_id;
        $this->ct           = $ct;
        $this->data         = $data;

        if (empty($action) || empty($token) || empty($client_id))
        {
            return null;
        }

        $uid = $this->get_uid_by_token($token);
        $this->uid = $uid;
        $user = $this->user; //用户信息
        //加载语言
        $user = $this->user;
        $this->lang = empty($user['language']) ? 'zh-tw' : $user['language'];

        //记录日志
        mod_common::logger(__METHOD__, [
            'action'    => $action,
            'token'     => $token,
            'client_id' => $client_id,
            'uid'       => $uid,
            'data'      => $data
        ]);

        if (empty($uid))
        {
            //系统访问直接返回
            return $this->error(trans('api.api_not_login'), mod_socket::NO_AUTH);
        }

        $method = 'action_'.$action;
        if (method_exists($this, $method))
        {
            $res = app()->call([$this, $method], [
                'request' => $request
            ]);
            return $res;
        }
    }

    /**
     * 客户端第一次连接返回信息
     * @param Request $request
     * @return int|mixed
     */
    public function action_say_hi()
    {
        //挤掉之前登录的账号（h5版本允许多端链接）
        $old_client_id = Gateway::getClientIdByUid($this->uid);
        //绑定用户到链接
        Gateway::bindUid($this->client_id, $this->uid);

        $res = $this->success();

        $_SESSION['uid'] = [
            'ct'            => $this->ct,
            'old_client_id' => $old_client_id,
            'new_client_id' => $this->client_id,
            'uid'           => $this->uid
        ];

        return $res;
    }

    /**
     * 成功返回
     * @param array $data
     * @param string $msg
     * @return int|mixed
     */
    public function success($data = [], $msg = 'success')
    {
        return mod_socket::send([
            'type'          => $this->ct, //用户端
            'uid'           => $this->uid,
            'client_id'     => $this->client_id,
            'action'        => $this->action,
            'code'          => mod_socket::SUCCESS,
            'msg'           => $msg,
            'data'          => $data
        ]);
    }

    /**
     * 失败返回
     * @param string $msg
     * @param int $code
     * @param array $data
     * @return mixed
     */
    public function error($msg = 'error', $code = mod_socket::FAIL, $data = [])
    {
        return mod_socket::send([
            'type'          => $this->ct, //用户端
            'uid'           => $this->uid,
            'client_id'     => $this->client_id,
            'action'        => $this->action,
            'code'          => $code,
            'msg'           => $msg,
            'data'          => $data
        ]);
    }

    /**
     * 根据uid获取认证用户uid
     * @param $token
     * @return string
     */
    public function get_uid_by_token($token)
    {
        return '';
    }

    /**
     * 根据uid获取认证用户uid
     * @param $token
     * @return string
     */
    public function action_timestamp(Request $request)
    {
        return $this->success(['timestamp' => time()]);
    }
}
