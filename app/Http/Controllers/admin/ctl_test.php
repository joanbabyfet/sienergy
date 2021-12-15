<?php

namespace App\Http\Controllers\admin;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ctl_test extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        //调用接口
        //$url = 'http://admin.sienergy.local/ajax_get_area?pid=1';
        //$url = 'http://api.inova.local/ajax_get_area?pid=1';
        //$url = 'http://api.sienergy.local/ip';
        //$url = 'http://api.inova.local/ip';
        //$client = new Client(); //['verify' => false],不验证证书
        // = [];
        //$header['Access-Control-Allow-Origin'] = '*';
        //$res = $client->request('GET', $url, ['headers' => $header]);
        //$data = json_decode($res->getBody(), true);

        return view('admin.test_index', [
            //'data'  =>  $data,
        ]);
    }
}
