<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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

    //  ↓↓講師アカウントにのサイドバーの「スクール予約確認」「スクール枠登録」を表示させる為の権限の設定の記述。(2024/7/15)
    public function boot()
    {
        $this->registerPolicies();

        //講師(role=1～3)のみに表示
         Gate::define('admin_only', function ($user) {
            return ($user->role = 1 && $user->role <= 3);
        });
    }
}
