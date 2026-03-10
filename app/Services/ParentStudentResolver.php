<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\ParentModel;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class ParentStudentResolver
{
    /**
     * Get the mahasiswa linked to this parent user.
     * Returns null if no valid parent-mahasiswa link found.
     */
    public function getLinkedStudent(User $user): ?Mahasiswa
    {
        $parent = ParentModel::where('user_id', $user->id)
            ->with(['mahasiswa.user'])
            ->first();

        return $parent?->mahasiswa;
    }

    /**
     * Get the parent record for this user.
     */
    public function getParentRecord(User $user): ?ParentModel
    {
        return ParentModel::where('user_id', $user->id)->first();
    }

    /**
     * Abort with 403 if no linked student found for the authenticated parent.
     * Returns the linked mahasiswa on success.
     */
    public function resolveOrAbort(User $user): Mahasiswa
    {
        $mahasiswa = $this->getLinkedStudent($user);

        if (! $mahasiswa) {
            abort(403, 'Akun Anda belum terhubung ke data mahasiswa. Hubungi administrator.');
        }

        return $mahasiswa;
    }

    /**
     * Ensure the given mahasiswa ID matches the parent's linked student.
     * Use this to guard any endpoint that receives a mahasiswa_id parameter.
     */
    public function ensureOwns(User $user, int $mahasiswaId): void
    {
        $mahasiswa = $this->resolveOrAbort($user);

        if ($mahasiswa->id !== $mahasiswaId) {
            abort(403, 'Anda tidak memiliki akses ke data mahasiswa tersebut.');
        }
    }
}
