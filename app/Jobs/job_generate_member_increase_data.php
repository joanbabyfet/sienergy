<?php

namespace App\Jobs;

use App\models\mod_member_increase_data;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class job_generate_member_increase_data implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $from_date    = '';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data = [])
    {
        //這里暫不用靜態定義,避免報錯
        $this->from_date        = isset($data['from_date']) ? $data['from_date']:'';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        mod_member_increase_data::generate_data($this->from_date);
    }
}
