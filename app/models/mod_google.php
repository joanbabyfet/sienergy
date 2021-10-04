<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Google\Cloud\Translate\V2\TranslateClient;

class mod_google extends mod_model
{
    /**
     * 翻译，文档地址：https://cloud.google.com/translate/docs/languages?hl=zh-Cn
     *
     * @param string $data
     * @return mixed
     */
    protected function translate($data)
    {
        //参数过滤
        $data_filter = mod_common::data_filter([
            'content'       => 'required', //需要翻译的内容
            'out_lang'      => '',         //翻译后的语言
        ], $data);

        $status = 1;
        $ret_data = '';
        try
        {
            $out_lang = !empty($data_filter['out_lang']) ? $data_filter['out_lang'] : 'zh-TW';

            if(!is_array($data_filter))
            {
                self::exception(trans('api.api_param_error'), -1);
            }

            $translate = new TranslateClient([
                'key'       => config('global.translate_key'), //验证身份用API私钥
                'target'    => $out_lang,
            ]);

            $result = $translate->translate($data_filter['content']);
            $ret_data = [
                'content'    => $result['text'],
                'out_lang'   => $out_lang,
            ];
        }
        catch (\Exception $e)
        {
            $status = self::get_exception_status($e);
            //记录日志
            mod_common::logger(__METHOD__, [
                'status'  => $status,
                'errcode' => $e->getCode(),
                'errmsg'  => $e->getMessage(),
                'data'    => $data_filter['content'],
            ]);
        }

        return $status < 0 ? $status : $ret_data;
    }
}
