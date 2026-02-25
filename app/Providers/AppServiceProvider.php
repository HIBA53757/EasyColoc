<?php

namespace App\Providers;

use App\Models\Colocation;
use App\Models\Expense;
use App\Models\User;
use App\Policies\ColocationPolicy;
use App\Policies\ExpensePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    protected $policies = [
    Colocation::class => ColocationPolicy::class,
    Expense::class => ExpensePolicy::class,
    User::class => UserPolicy::class,
];
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
