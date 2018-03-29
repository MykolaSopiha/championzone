<?php

return [

    'currencies' => [
        'RUB',
        'USD',
        'UAH',
        'EUR'
    ],

    'card_types' => [
        0 => 'Яндекс.Деньги',
        1 => 'QIWI',
        2 => 'Пластиковая'
    ],

    'token_statuses' => ['active', 'confirmed', 'trash'],

    'token_actions' => [
        ['deposit', 'Пополнить'],
        ['withdraw', 'Списать'],
        ['transfer', 'Перевести'],
    ],

];

?>