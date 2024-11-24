<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Phone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $numpattern = "~^(?:\+7|8)\d{10}$~";
        if (!preg_match($numpattern, $value)) {
            $fail(trans('validation.not_in'));
        }
    }
}
