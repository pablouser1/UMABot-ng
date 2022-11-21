<?php
namespace App\Constants;

abstract class Links {
    const list = [
        [
            'name' => 'Verificación',
            'endpoint' => '/verify',
            'color' => 'is-primary'
        ],
        [
            'name' => 'Información',
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
