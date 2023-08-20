<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;
use ZxcvbnPhp\Zxcvbn;

class StrongPasswordRule implements ValidationRule, DataAwareRule
{
    public Zxcvbn $zxcvbn;

    protected array $data = [];

    const MIN_PASSWORD_SCORE = 3;

    public function __construct()
    {
        $this->zxcvbn = new Zxcvbn();
    }

    public function setData($data): self|static
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->isWeakPassword($value)) {
            $fail(trans(key: trans(key: 'validation.custom.password.strong')));
        }
    }

    public function isWeakPassword(string $value): bool
    {
        return $this->zxcvbn->passwordStrength(
            password: $value,
            userInputs: $this->getUserInputs()
            )['score'] < self::MIN_PASSWORD_SCORE;
    }

    protected function getUserInputs(): array
    {
        return array_values(
            array: Arr::except(
                array: $this->data,
                keys: ['password','password_confirmation']
            )
        );
    }
}
