<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PromoCodeExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promocode:expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change the promocode expiry status based on the end date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $promocodes=DB::table('promocodes')
                    ->where(['is_deleted'=>0,'is_expired'=>0])
                    ->get();

        if(count($promocodes)>0)
        {
            foreach($promocodes as $p)
            {
                if($p->end_date!=NULL || $p->end_date!="")
                {
                    $end_date=date('Y-m-d h:i a',strtotime($p->end_date));
                    $end_times=strtotime($end_date);

                    if(strtotime(date('Y-m-d h:i a')) >= $end_times)
                    {
                        DB::table('promocodes')->where('id',$p->id)->update(['is_expired'=>1]);
                    }
                }
            } 
        }

        return 0;
    }
}
