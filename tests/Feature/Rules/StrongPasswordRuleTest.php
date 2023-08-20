<?php

use App\Rules\StrongPasswordRule;

beforeEach(function () {
    $this->rule = new StrongPasswordRule();
});

it(description: 'fails for password with score less than 3 `zxcvbn score`', closure: function (string $password) {
    expect(value: $this->rule)->not()->toPassWith($password);
})->with([
    '0 score' => ['p@$$word'],
    '1 score' => ['qwER43@!'],
    '2 score' => ['njogu'],
]);

it(description: 'passes for password with a `zxcvbn score` of 3 or 4', closure: function () {
    expect(value: $this->rule)->toPassWith('correct horse battery staple');
});

// Using examples.
it(description: 'fails when password includes user data', closure: function (string $password, array $data) {
    $this->rule->setData($data);

    expect(value: $this->rule)->not()->toPassWith($password);
})->with([
    'user name' => [ 'Amos Njogu 12', ['name' => 'Amos Njogu'] ],
    'user email' => ['njoguamos', ['email' => 'mail@njoguamos.me.ke'] ],
    'company names' => ['acmebrick', ['name' => 'Acme Brick Co'] ]
]);

// Using example passwords. The list is exhaustive
it(description: 'fails for most common passwords', closure: function (string $password) {
    expect(value: $this->rule)->not()->toPassWith($password);
})->with([
    '!@#$%^&*', '111111', '123123', '12345', '123456', '1234567', '12345678', '12345678',
    '123456789', '1234567890', '1q2w3e', '654321', '666666', 'aa123456', 'abc123',
    'admin', 'charlie', 'donald', 'football', 'iloveyou', 'monkey', 'password'
]);

// Using example passwords. The list is exhaustive
it(description: 'passes for strong password', closure: function (string $password) {
    expect(value: $this->rule)->toPassWith($password);
})->with([
    'gruel-sepal-capsize-cytolog','Took-gasp-halibut-property',
    'Liverish prelate executor outs', 'hurtle_impious_atypical_mediate'
]);
