<?php

namespace App\Rules;

use App\Support\MoneyParser;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class Money implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string, ?string=): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $amount = MoneyParser::toFloat($value);

        if ($amount === null) {
            $fail('O campo valor nao e um valor monetario valido.');
            return;
        }

        if ($amount <= 0) {
            $fail('O valor do campo valor deve ser maior que R$ 0,00.');
        }
    }
}
