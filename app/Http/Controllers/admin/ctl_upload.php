<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\models\mod_common;
use Illuminate\Support\Facades\Log;

class ctl_upload extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 普通上传
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $formname   = $request->input('formname', 'file'); //上传表单字段
        $dir        = $request->input('dir', 'image'); //文件上传目录
        $thumb_w    = $request->input('thumb_w', 0); //图片缩略图宽度
        $thumb_h    = $request->input('thumb_h', 0); //图片缩略图高度

        try
        {
            $ret = mod_common::upload($request, $formname, $dir, $thumb_w, $thumb_h);
            if (!empty($ret['filename']))
            {
             //上传到aws，未来再扩充
            }

            return mod_common::success($ret);
        }
        catch (\Exception $e)
        {
            return mod_common::error($e->getMessage());
        }
    }

    /**
     * 下載文件
     * @param string $file_name
     * @param string $dir
     * @return string|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Request $request)
    {
        $filename  = $request->input('file') ?? '';
        $dir        = $request->input('dir') ?? 'image';

        if(empty($filename))
        {
            return $filename;
        }

        $upload_dir = empty($dir) ? 'app/public/' : "app/public/{$dir}/";
        $file = storage_path($upload_dir.$filename);

        if (file_exists($file))
        {
            return response()->download($file, '');
        }
        else
        {
            mod_common::abort(-1, '文件不存在!');
        }
    }
}
