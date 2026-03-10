<?php

namespace App\Policies;

use App\Models\PaymentProof;
use App\Models\User;

class PaymentProofPolicy
{
    /**
     * Determine if the user can view the payment proof.
     */
    public function view(User $user, PaymentProof $proof): bool
    {
        if (in_array($user->role, ['finance', 'keuangan'])) {
            return true;
        }

        if ($user->role === 'mahasiswa') {
            return $proof->uploaded_by === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can upload payment proof.
     */
    public function create(User $user): bool
    {
        return $user->role === 'mahasiswa';
    }

    /**
     * Determine if the user can approve/reject the proof.
     */
    public function review(User $user, PaymentProof $proof): bool
    {
        return in_array($user->role, ['finance', 'keuangan']) && $proof->status === 'UPLOADED';
    }
}
