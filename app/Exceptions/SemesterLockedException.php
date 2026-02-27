<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class SemesterLockedException extends HttpException
{
    public function __construct(string $message = 'Semester sudah dikunci. Perubahan tidak diizinkan.')
    {
        parent::__construct(423, $message);
    }
}
