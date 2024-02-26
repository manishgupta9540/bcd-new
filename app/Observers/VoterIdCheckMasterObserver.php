<?php

namespace App\Observers;

use App\Models\Admin\VoterIdCheckMaster;

class VoterIdCheckMasterObserver
{
    /**
     * Handle the voter id check master "created" event.
     *
     * @param  \App\Models\Admin\VoterIdCheckMaster  $voterIdCheckMaster
     * @return void
     */
    public function created(VoterIdCheckMaster $voterIdCheckMaster)
    {
        //
    }

    /**
     * Handle the voter id check master "updated" event.
     *
     * @param  \App\Models\Admin\VoterIdCheckMaster  $voterIdCheckMaster
     * @return void
     */
    public function updated(VoterIdCheckMaster $voterIdCheckMaster)
    {
        //
    }

    /**
     * Handle the voter id check master "deleted" event.
     *
     * @param  \App\Models\Admin\VoterIdCheckMaster  $voterIdCheckMaster
     * @return void
     */
    public function deleted(VoterIdCheckMaster $voterIdCheckMaster)
    {
        //
    }

    /**
     * Handle the voter id check master "restored" event.
     *
     * @param  \App\Models\Admin\VoterIdCheckMaster  $voterIdCheckMaster
     * @return void
     */
    public function restored(VoterIdCheckMaster $voterIdCheckMaster)
    {
        //
    }

    /**
     * Handle the voter id check master "force deleted" event.
     *
     * @param  \App\Models\Admin\VoterIdCheckMaster  $voterIdCheckMaster
     * @return void
     */
    public function forceDeleted(VoterIdCheckMaster $voterIdCheckMaster)
    {
        //
    }
}
