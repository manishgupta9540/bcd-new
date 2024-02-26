<?php

namespace App\Providers;

use App\Models\Admin\AadharCheck;
use App\Models\Admin\AadharCheckMaster;
use App\Models\Admin\Candidate;
use App\Models\Admin\CustomerSla;
use App\Models\Admin\JafFormData;
use App\Models\Admin\Job;
use App\Models\Admin\JobItem;
use App\Models\Admin\JobSlaItem;
use App\Models\Admin\KeyAccountManager;
use App\Models\Admin\PanCheck;
use App\Models\Admin\PanCheckMaster;
use App\Models\Admin\RcCheck;
use App\Models\Admin\RcCheckMaster;
use App\Models\Admin\Task;
use App\Models\Admin\TaskAssignment;
use App\Models\Admin\UserBusiness;
use App\Models\Admin\UserBusinessContact;
use App\Models\Admin\UserCheck;
use App\Observers\AadharCheckMasterObserver;
use App\Observers\AadharCheckObserver;
use App\Observers\CandidateObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\UserObserver;
use App\Observers\CustomerSlaObserver;
use App\Observers\JafFormdataObserver;
use App\Observers\JobItemObserver;
use App\Observers\JobObserver;
use App\Observers\JobSlaItemObserver;
use App\Observers\KeyAccountManagerObserver;
use App\Observers\PanCheckMasterObserver;
use App\Observers\PanCheckObserver;
use App\Observers\RcCheckMasterObserver;
use App\Observers\RcCheckObserver;
use App\Observers\TaskAssignmentObserver;
use App\Observers\TaskObserver;
use App\Observers\UserBusinessContactObserver;
use App\Observers\UserBusinessObserver;
use App\Observers\UserCheckObserver;
use App\Observers\VerificationInsufficiencyObserver;
use App\User;
use App\VerificationInsufficiency;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
//use URL;
//use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //

        /**

         * Paginate a standard Laravel Collection.

         * @param int $perPage

         * @param int $total

         * @param int $page

         * @param string $pageName

         * @return array

         */

        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {

            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(

                $this->forPage($page, $perPage),

                $total ?: $this->count(),

                $perPage,

                $page,

                [

                    'path' => LengthAwarePaginator::resolveCurrentPath(),

                    'pageName' => $pageName,

                ]

            );

        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        CustomerSla::observe(CustomerSlaObserver::class);
        UserCheck::observe(UserCheckObserver::class);
        Job::observe(JobObserver::class);
        JobItem::observe(JobItemObserver::class);
        JobSlaItem::observe(JobSlaItemObserver::class);
        Task::observe(TaskObserver::class);
        TaskAssignment::observe(TaskAssignmentObserver::class);
        Candidate::observe(CandidateObserver::class);
        KeyAccountManager::observe(KeyAccountManagerObserver::class);
        UserBusiness::observe(UserBusinessObserver::class);
        UserBusinessContact::observe(UserBusinessContactObserver::class);
        JafFormData::observe(JafFormdataObserver::class);
        VerificationInsufficiency::observe(VerificationInsufficiencyObserver::class);
        AadharCheck::observe(AadharCheckObserver::class);
        AadharCheckMaster::observe(AadharCheckMasterObserver::class);
        PanCheck::observe(PanCheckObserver::class);
        PanCheckMaster::observe(PanCheckMasterObserver::class);
        RcCheck::observe(RcCheckObserver::class);
        RcCheckMaster::observe(RcCheckMasterObserver::class);

        //URL::forceRootUrl(Config::get('app.url'));
        //URL::forceScheme('https'); 
    }
}
