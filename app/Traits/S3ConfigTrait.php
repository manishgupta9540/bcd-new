<?php


namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

trait S3ConfigTrait
{
    static function s3Config()
    {
        $response = NULL;
        $apiURL = env('AWS_SERVER_URL');

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_URL, $apiURL);
        // $resp = curl_exec ( $ch );
        // $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // curl_close ( $ch );
        // $array_data =  json_decode($resp,true);

        // if($response_code==200 && $array_data!=NULL && count($array_data)>0)
        // {
                $config = array(
                        // 'driver'=> 's3',
                        // 'key' => $array_data['AccessKeyId'],
                        // 'secret' => $array_data['SecretAccessKey'],
                        // 'region' => env('AWS_DEFAULT_REGION'),
                        // 'bucket' => env('AWS_BUCKET'),
                        // 'url' => env('AWS_URL'),
                        // 'endpoint' => env('AWS_ENDPOINT'),
                        // 'token' => $array_data['Token'],
                        // 'visibility' => 'public',
                        'driver'=> 's3',
                        //'key' => $array_data['AccessKeyId'],
                        //'secret' => $array_data['SecretAccessKey'],
                        'key' => env('AWS_ACCESS_KEY_ID'),
                        'secret' => env('AWS_SECRET_ACCESS_KEY'),
                        'region' => env('AWS_DEFAULT_REGION'),
                        'bucket' => env('AWS_BUCKET'),
                        //'token' => $array_data['Token'],
                        'version' => '2006-03-01',
                );
                //dd($config);

                Config::set('filesystems.disks.s3',$config);

                $response = Config::get('filesystems.disks.s3');        
        // }

        return $response;
    }
}

?>