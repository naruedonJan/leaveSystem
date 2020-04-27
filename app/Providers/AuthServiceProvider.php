<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('group-admin', function ($user) {
			if($user->permission == 'boss' || $user->permission == 'programer' || $user->permission == 'admin'){
				return true;
			}
			return false;
        });

        Gate::define('group-emp', function ($user) {
			if($user->permission == 'employee' || $user->permission == 'programer' && $user->status == 1){
				return true;
			}
			return false;
        });
        Gate::define('new-emp', function ($user) {
			if(($user->permission == 'employee'  && $user->status == 1) || ($user->permission == 'programer' && $user->status == 1) || $user->status == 0){
				return true;
			}
			return false;
		});

        //
    }
}
