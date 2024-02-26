<?php

namespace App\Providers;

use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\ApiCheck' => [
            'App\Listeners\ApiCheckListener',
            ],
        'App\Events\CandidateApiCheck' => [
                'App\Listeners\CandidateCheckListener',
                ],
            'App\Events\ApiCheckByRestApi' => [
                'App\Listeners\ApiCheckByRestApiListener',
                ],
            'Illuminate\Auth\Events\Login' => [
                'App\Listeners\LogSuccessfulLogin',
            ],
            'Illuminate\Auth\Events\Logout' => [
                'App\Listeners\LogSuccessfulLogout',
            ],
            
            \SocialiteProviders\Manager\SocialiteWasCalled::class => [
                \SocialiteProviders\Microsoft\MicrosoftExtendSocialite::class.'@handle',
            ],
    ];
    
   

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
       
        //
    }
}
