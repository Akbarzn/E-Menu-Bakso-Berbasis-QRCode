<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use App\Models\User;

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
        //
       Filament::serving(function(){
        $user = Filament::auth()->user();
        
        if(!$user) return;

        if($user?->hasRole('admin')){
            Filament::registerNavigationItems([
                // halaman yg bisa di akses oleh admin
                // \Filament\Pages\Dashboard::class,
                // \App\Filament\Resources\MenuResource::getNavigationGroup(),
                // \App\Filament\Resources\TransactionResource::getNavigationGroup(),
            ]);
        }
        if($user?->hasRole('super_admin')){

        }
        });
    }
}
