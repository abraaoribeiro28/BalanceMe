<?php

namespace App\Rules;

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
        $clean = str_replace(array('R$', '.', ' ', ','), array('', '', '', '.'), $value);

        if (!is_numeric($clean)) {
            $fail("O campo valor não é um valor monetário válido.");
            return;
        }

        $amount = (float) $clean;

        if ($amount <= 0) {
            $fail("O valor do campo valor deve ser maior que R$ 0,00.");
        }
    }
}
