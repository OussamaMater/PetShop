<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class BaseRequest extends Request
{
    public function wantsJson(): bool
    {
        return true;
    }

    public function expectsJson(): bool
    {
        return true;
    }
}
