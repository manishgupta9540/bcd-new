<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $allow_origin = env('ALLOWED_ORIGINS')!=null ? env('ALLOWED_ORIGINS') : '*';
         // return $next($request);
         header("Access-Control-Allow-Origin:".$allow_origin);

         // ALLOW OPTIONS METHOD
         $headers = [
            'Access-Control-Allow-Origin'=>null,
            'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers'=> 'Content-Type, Accept, Authorization, X-Requested-With, Application'
         ];
         //if($request->getMethod() == "OPTIONS") {
             // The client-side application can set only headers allowed in Access-Control-Allow-Headers
           //  return Response::make('OK', 200, $headers);
         //}
 
         $response = $next($request);
         foreach($headers as $key => $value)
                 $response->headers->set($key, $value);
                 $response->headers->set('Access-Control-Allow-Methods','POST, GET, OPTIONS, PUT, DELETE');
                 $response->headers->set('Access-Control-Allow-Origin',$allow_origin);
 //              $response->headers->set('Access-Control-Allow-Headers','Content-Type, Accept, Authorization, X-Requested-With, Application');
 //      header('Access-Control-Allow-Origin:https://ems.cogentlab.com/erpm');
         //header("Access-Control-Allow-Origin:https://ems.cogentlab.com/erpm");
         return $response;
 

    }
}
