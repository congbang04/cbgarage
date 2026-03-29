<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Xác minh địa chỉ email')
                ->line('Hãy nhấp vào nút bên dưới để xác nhận địa chỉ email của bạn.')
                ->action('Nhấn vào đây để xác nhận', $url);
        });
        Gate::define('is-super-admin', function ($user) {
            return $user->role === User::ROLE_BRANCH_ADMIN && $user->branch_id == 1;
        });
        Gate::define('is-admin', function ($user) {
            return $user->role === User::ROLE_BRANCH_ADMIN;
        });
        Gate::define('is-technician', function ($user) {
            return $user->role === User::ROLE_TECHNICIAN;
        });
        Gate::define('access-reception', function ($user) {
            return in_array($user->role, [User::ROLE_BRANCH_ADMIN, User::ROLE_ADVISOR]);
        });
        Gate::define('access-warehouse', function ($user) {
            return in_array($user->role, [User::ROLE_BRANCH_ADMIN, User::ROLE_WAREHOUSE]);
        });
        Gate::define('access-cashier', function ($user) {
            return in_array($user->role, [User::ROLE_BRANCH_ADMIN, User::ROLE_CASHIER]);
        });
    }
}
