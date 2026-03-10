<?php

namespace App\Policies;

use App\Models\InstallmentRequest;
use App\Models\User;

class InstallmentRequestPolicy
{
    /**
     * Determine if the user can view the installment request.
     */
    public function view(User $user, InstallmentRequest $request): bool
    {
        if (in_array($user->role, ['finance', 'keuangan'])) {
            return true;
        }

        if ($user->role === 'mahasiswa') {
            $student = $user->student;
            return $student && $request->student_id === $student->id;
        }

        return false;
    }

    /**
     * Determine if the user can create installment requests.
     */
    public function create(User $user): bool
    {
        return $user->role === 'mahasiswa';
    }

    /**
     * Determine if the user can approve/reject the request.
     */
    public function review(User $user, InstallmentRequest $request): bool
    {
        return in_array($user->role, ['finance', 'keuangan']) && $request->status === 'SUBMITTED';
    }

    /**
     * Determine if the user can cancel the request.
     */
    public function cancel(User $user, InstallmentRequest $request): bool
    {
        if ($user->role === 'mahasiswa') {
            $student = $user->student;
            return $student 
                && $request->student_id === $student->id 
                && $request->status === 'SUBMITTED';
        }

        return false;
    }
}
