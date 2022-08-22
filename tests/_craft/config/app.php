<?php

return [
    'modules' => [
        'viget-generator' => [
            'class' => \viget\generator\Module::class,
        ],
    ],
    'bootstrap' => [
        'viget-generator',
    ],
];
