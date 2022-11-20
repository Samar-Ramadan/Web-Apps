<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
class ProdcutPriceMission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        {
            $db = config ('database.redis.default.database',0);
            $pattern = '__keyevent @'. $db. '__: منتهي الصلاحية'  ;    
            Redis::subscribe ([$pattern] , function ($channel) {
       $key_type = str_before($channel,':');
       switch ($key_type) {
           case 'price_mission_start_time':
                                    $product_id = str_before (str_after ($channel , ':'), '-');
                                    $mission_id = str_after (str_after ($channel , ':'), '-');
               $product = DB::table('product')->find($product_id);
               $mission = DB::table('product_price_mission')->find($mission_id);
       if ($product && $mission) {
                                          
       $result_product = DB::table('product')->where('id', $product_id)->update(['is_limit_time'=>1]);
       $result_mission = DB::table('product_price_mission')->where('id', $mission_id)->update(['is_happened'=>1]);
        Log :: info ('Product ID'. $Product_id. ':'. $Result_product);
       
                                            // إذا كان حجم العمل كبيرًا ، فيمكننا وضع التشغيل الفعال في قائمة الانتظار للتنفيذ
                   // Job::dispatch($product_id, $mission_id, $is_limit_time = 1, $is_happened = 1);
       }
               break;

           case 'price_mission_end_time':
                $product_id = str_before (str_after ($channel, ':'), '-');
               $mission_id = str_after (str_after ($channel, ':'), '-') ;
               $product = DB::table('product')->find($product_id);
               $mission = DB::table('product_price_mission')->find($mission_id);
       if ($product && $mission) {
                                    
       $result_product = DB::table('product')->where('id', $product_id)->update(['is_limit_time'=>0]);
       $result_mission = DB::table('product_price_mission')->where('id', $mission_id)->update(['is_happened'=>-1]);
        Log :: info (' product id:'. $Product_id. ':'. $Result_product );
       
                                            // إذا كان حجم العمل كبيرًا ، فيمكننا وضع عمليات غير صالحة في قائمة الانتظار للتنفيذ
                   // Job::dispatch($product_id, $mission_id, $is_limit_time = 0, $is_happened = -1);
       }
               break;

           default:
               break;
       }
   });
    }
}
}