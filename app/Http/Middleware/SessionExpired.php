<?php

namespace App\Http\Middleware;

use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Session;
use Closure;
use Illuminate\Support\Facades\DB;

class SessionExpired
{

    protected $session;
    protected $timeout = 1800;
     
    public function __construct(Store $session){
        $this->session = $session;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // dd(  time() - $this->session->get('lastActivityTime'));
        $isLoggedIn = $request->path() != '/logout';
        if(!session('lastActivityTime')){
            
            $this->session->put('lastActivityTime', time());
        }
        else if(time() - $this->session->get('lastActivityTime') > $this->timeout){
            
            if (Auth::check()) {
                $email= Auth::user()->email;
                DB::table('users')->where(['email' =>$email])->update(['session_id'=>NULL]);
                auth()->logout();
            }
            
            $this->session->forget('lastActivityTime');
            $cookie = cookie('intend', $isLoggedIn ? url()->current() : '/home');
            
        }
        $isLoggedIn ? $this->session->put('lastActivityTime', time()) : $this->session->forget('lastActivityTime');
        
        return $next($request);
    }
}
