<?php
namespace App\Commands;

interface Command {
    public function run(array $args = []): string;
}
