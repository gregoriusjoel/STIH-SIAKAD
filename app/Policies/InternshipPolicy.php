<?php

namespace App\Policies;

use App\Models\Internship;
use App\Models\User;

class InternshipPolicy
{
    /**
     * Admin can do everything.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->role === 'admin') {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['mahasiswa', 'dosen', 'admin']);
    }

    public function view(User $user, Internship $internship): bool
    {
        if ($user->role === 'mahasiswa') {
            return $user->student && $internship->mahasiswa_id === $user->student->id;
        }
        if ($user->role === 'dosen') {
            return $user->dosen && $internship->supervisor_dosen_id === $user->dosen->id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === 'mahasiswa';
    }

    public function update(User $user, Internship $internship): bool
    {
        if ($user->role === 'mahasiswa') {
            return $user->student
                && $internship->mahasiswa_id === $user->student->id
                && $internship->isEditable();
        }
        return false;
    }

    public function delete(User $user, Internship $internship): bool
    {
        if ($user->role === 'mahasiswa') {
            return $user->student
                && $internship->mahasiswa_id === $user->student->id
                && in_array($internship->status, [Internship::STATUS_DRAFT, Internship::STATUS_REJECTED]);
        }
        return false;
    }

    /**
     * Dosen can manage logbooks for internships they supervise.
     */
    public function manageLogbook(User $user, Internship $internship): bool
    {
        if ($user->role === 'dosen') {
            return $user->dosen && $internship->supervisor_dosen_id === $user->dosen->id;
        }
        if ($user->role === 'mahasiswa') {
            return $user->student && $internship->mahasiswa_id === $user->student->id;
        }
        return false;
    }
}
