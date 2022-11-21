<?php
namespace App\Commands;

/**
 * Base Command with some common funtionality
 */
abstract class BaseCommand implements Command {
    protected int $minArgs = 1;
    protected int $maxArgs = 1;
    protected string $user_id = '';

    function __construct(string $user_id) {
        $this->user_id = $user_id;
    }

    protected function hasMinArgs(array $args = []): bool {
        return count($args) >= $this->minArgs;
    }

    protected function overflowArgs(array $args = []): bool {
        return count($args) > $this->maxArgs;
    }
}
