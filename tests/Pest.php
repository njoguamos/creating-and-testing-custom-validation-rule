<?php

use Illuminate\Contracts\Validation\ValidationRule;

uses(Tests\TestCase::class)->in('Feature');

// Other code

expect()
    ->extend(name: 'toPassWith', extend: function (mixed $value) {
        $rule = $this->value;

        if (!$rule instanceof ValidationRule) {
            throw new Exception(message: 'Value is not an invokable rule');
        }

        $passed = true;

        $fail = function () use (&$passed) {
            $passed = false;
        };

        $rule->validate(attribute: 'attribute', value: $value, fail: $fail);

        expect(value: $passed)->toBeTrue();
    });
