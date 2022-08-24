<?php

return [
    'modules' => [
        'viget-builder' => [
            'class' => \viget\builder\Module::class,
        ],
    ],
    'bootstrap' => [
        'viget-builder',
    ],
];
