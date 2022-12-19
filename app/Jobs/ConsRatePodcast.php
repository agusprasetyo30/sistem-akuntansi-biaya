<?php

namespace App\Jobs;

use App\Models\ConsRate;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConsRatePodcast implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $items;
    public $header;
    public $version;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($items, $header, $version)
    {
        $this->items  = $items;
        $this->header = $header;
        $this->version = $version;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->items as $data){
            $data_consrate = array_combine($this->header, $data);
            $data_consrate['version_id'] = (int) $this->version;
            $data_consrate['company_code'] = 'B000';
            $data_consrate['is_active'] = true;
            $data_consrate['created_by'] = auth()->user()->id;
//            $data_consrate['updated_by'] = auth()->user()->id;
            ConsRate::create($data_consrate);
        }
    }

    public function failed(\Throwable $exception)
    {
        //
    }
}
