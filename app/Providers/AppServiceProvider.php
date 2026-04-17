<?php

namespace App\Providers;

use App\Models\Installment;
use App\Models\InstallmentRequest;
use App\Models\Invoice;
use App\Models\PaymentProof;
use App\Policies\InstallmentRequestPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\PaymentProofPolicy;
use App\Services\SchedulingLogService;
use App\Services\ConflictCheckerService;
use App\Services\RoomMatcherService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register scheduling services with singleton pattern for shared state
        $this->app->singleton(SchedulingLogService::class, function ($app) {
            return new SchedulingLogService();
        });

        $this->app->singleton(ConflictCheckerService::class, function ($app) {
            return new ConflictCheckerService(
                $app->make(SchedulingLogService::class)
            );
        });

        $this->app->singleton(RoomMatcherService::class, function ($app) {
            return new RoomMatcherService(
                $app->make(SchedulingLogService::class),
                $app->make(ConflictCheckerService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Payment System Policies
        Gate::policy(Invoice::class, InvoicePolicy::class);
        Gate::policy(InstallmentRequest::class, InstallmentRequestPolicy::class);
        Gate::policy(PaymentProof::class, PaymentProofPolicy::class);
    }
}
