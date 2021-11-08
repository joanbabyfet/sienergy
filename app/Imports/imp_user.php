<?php

namespace App\Imports;

use App\models\mod_common;
use App\Models\mod_user;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
//use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

/**
 * 批量导入 只支持 ToModel
 * Class imp_user
 * @package App\Imports
 */
class imp_user implements ToCollection, WithBatchInserts, WithChunkReading, WithStartRow, WithValidation
{
    use Importable;

    /**
     * 实现toModel, 设置字段对应
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
//    public function model(array $row)
//    {
//        return new mod_user([
//            'id'            => mod_common::random('web'),
//            'username'      => $row[0],
//            'password'      => Hash::make('Bb123456'),
//            'origin'        => 0,
//            'realname'      => $row[1],
//            'email'         => $row[2],
//            'phone_code'    => $row[3],
//            'phone'         => $row[4],
//            //'role_id'       => $row[5], //config('global.gen_mem_role_id')
//            'language'      => 'zh-tw',
//            'create_time'   => time(),
//        ]);
//    }

    /**
     * 使用 ToCollection
     * @param array $row
     *
     * @return User|null
     */
    public function collection(Collection $rows)
    {
        //如果需要去除表头
        //unset($rows[0]);
        $this->createData($rows);
    }

    public function createData($rows)
    {
        $success = 0;
        foreach ($rows as $row)
        {
            mod_user::save_data([
                'do'            => 'add',
                'origin'        => 0, //0=其他 1=官网 2=APP
                'username'      => $row[0],
                'password'      => Hash::make('Bb123456'),
                'realname'      => $row[1],
                'email'         => $row[2],
                'phone_code'    => $row[3],
                'phone'         => $row[4],
                'role_id'       => $row[5], //config('global.gen_mem_role_id')
                'language'      => 'zh-tw',
                'create_time'   => time(),
            ]);
            // 其他业务代码
            $success++;
        }
        return $success.'-'.count($rows);
    }

    //批量导入500条
    public function batchSize(): int
    {
        return 500;
    }

    //以500条数据基准切割数据
    public function chunkSize(): int
    {
        return 500;
    }

    // 从2行开始读取数据
    public function startRow(): int
    {
        return 2;
    }

    // 验证
    public function rules(): array
    {
        return [
            '0' => 'required',
        ];
    }

    // 自定义验证信息
    public function customValidationMessages()
    {
        return [
            '0.required' => '用戶名未填',
        ];
    }
}
