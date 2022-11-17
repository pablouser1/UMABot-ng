<?php
namespace App\Constants;

abstract class Links {
    const list = [
        [
            'name' => 'Cómo usar',
            'endpoint' => '/howto',
            'color' => 'is-primary'
        ],
        [
            'name' => 'Acerca de / FAQ',
            'endpoint' => '/about',
            'color' => 'is-info'
        ],
        [
            'name' => 'Términos de uso',
            'endpoint' => '/terms',
            'color' => 'is-warning'
        ]
    ];
}
