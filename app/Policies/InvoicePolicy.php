<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    /**
     * Determine if the user can view any invoices.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the invoice.
     */
    public function view(User $user, Invoice $invoice): bool
    {
        // Finance can view all
        if (in_array($user->role, ['finance', 'keuangan'])) {
            return true;
        }

        // Mahasiswa can only view their own published/in_installment/lunas invoices
        if ($user->role === 'mahasiswa') {
            $student = $user->student;
            if (!$student) {
                return false;
            }

            // Allow mahasiswa to view invoices that belong to them.
            // Previously access was restricted to only published/installment/lunas statuses,
            // which caused 403 when a mahasiswa tried to open their invoice (e.g. draft).
            // Business rule: mahasiswa should be able to view their own invoice details
            // so they can see its current status or contact finance if needed.
            return $invoice->student_id === $student->id;
        }

        return false;
    }

    /**
     * Determine if the user can create invoices.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['finance', 'keuangan']);
    }

    /**
     * Determine if the user can update the invoice.
     */
    public function update(User $user, Invoice $invoice): bool
    {
        return in_array($user->role, ['finance', 'keuangan']);
    }

    /**
     * Determine if the user can delete the invoice.
     */
    public function delete(User $user, Invoice $invoice): bool
    {
        return in_array($user->role, ['finance', 'keuangan']) && $invoice->status === 'DRAFT';
    }

    /**
     * Determine if the user can publish the invoice.
     */
    public function publish(User $user, Invoice $invoice): bool
    {
        return in_array($user->role, ['finance', 'keuangan']) && $invoice->status === 'DRAFT';
    }
}
