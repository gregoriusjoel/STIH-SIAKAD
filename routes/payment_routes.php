<?php

use App\Http\Controllers\Finance\InstallmentRequestController;
use App\Http\Controllers\Finance\InvoiceController as FinanceInvoiceController;
use App\Http\Controllers\Finance\PaymentProofController;
use App\Http\Controllers\Mahasiswa\MahasiswaPaymentController;
use Illuminate\Support\Facades\Route;

// Mahasiswa Routes
Route::middleware(['auth', 'verified'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/invoices/{invoice}', [MahasiswaPaymentController::class, 'show'])->name('invoices.show');

    // Installment Requests
    Route::get('/invoices/{invoice}/installment-request/create', [MahasiswaPaymentController::class, 'createInstallmentRequest'])
        ->name('installment-requests.create');
    Route::post('/invoices/{invoice}/installment-request', [MahasiswaPaymentController::class, 'storeInstallmentRequest'])
        ->name('installment-requests.store');

    // Payment Proofs
    Route::get('/installments/{installment}/payment-proof/create', [MahasiswaPaymentController::class, 'createPaymentProof'])
        ->name('payment-proofs.create');
    Route::post('/payment-proofs', [MahasiswaPaymentController::class, 'storePaymentProof'])
        ->name('payment-proofs.store');

    // Payment History
    Route::get('/payments/history', [MahasiswaPaymentController::class, 'paymentHistory'])
        ->name('payments.history');
});

// Finance Routes
Route::middleware(['auth', 'verified'])->prefix('finance')->name('finance.')->group(function () {
    // Invoices
    Route::get('/invoices', [FinanceInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [FinanceInvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [FinanceInvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [FinanceInvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/invoices/{invoice}/publish', [FinanceInvoiceController::class, 'publish'])->name('invoices.publish');
    Route::post('/invoices/{invoice}/cancel', [FinanceInvoiceController::class, 'cancel'])->name('invoices.cancel');

    // Installment Requests
    Route::get('/installment-requests', [InstallmentRequestController::class, 'index'])->name('installment-requests.index');
    Route::get('/installment-requests/{installmentRequest}', [InstallmentRequestController::class, 'show'])
        ->name('installment-requests.show');
    Route::post('/installment-requests/{installmentRequest}/approve', [InstallmentRequestController::class, 'approve'])
        ->name('installment-requests.approve');
    Route::post('/installment-requests/{installmentRequest}/reject', [InstallmentRequestController::class, 'reject'])
        ->name('installment-requests.reject');

    // Payment Proofs
    Route::get('/payment-proofs', [PaymentProofController::class, 'index'])->name('payment-proofs.index');
    Route::get('/payment-proofs/{paymentProof}', [PaymentProofController::class, 'show'])->name('payment-proofs.show');
    Route::post('/payment-proofs/{paymentProof}/review', [PaymentProofController::class, 'review'])
        ->name('payment-proofs.review');
});
