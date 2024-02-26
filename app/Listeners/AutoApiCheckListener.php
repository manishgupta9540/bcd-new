<?php

namespace App\Listeners;

use App\Events\AutoApiCheck;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AutoApiCheckListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AutoApiCheck  $event
     * @return void
     */
    public function handle(AutoApiCheck $event)
    {
        //
    }
}
