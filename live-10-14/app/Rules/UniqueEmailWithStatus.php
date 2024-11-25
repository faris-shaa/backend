<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;

class UniqueEmailWithStatus implements Rule
{
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        // Check if there's a user with the given email and status 0
        return User::where('email', $value)->where('status', 0)->exists();
    }

    public function message()
    {
        return 'The :attribute must be unique if the user status is 0.';
    }
}