<?php

namespace App\Providers;

use App\Models\Installment;
use App\Models\InstallmentRequest;
use App\Models\Invoice;
use App\Models\PaymentProof;
use App\Policies\InstallmentRequestPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\PaymentProofPolicy;
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
        // Register Payment System Policies
        Gate::policy(Invoice::class, InvoicePolicy::class);
        Gate::policy(InstallmentRequest::class, InstallmentRequestPolicy::class);
        Gate::policy(PaymentProof::class, PaymentProofPolicy::class);
    }
}
