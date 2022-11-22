<?php
namespace App\Commands;

use App\Constants\Values;
use App\Helpers\Misc;
use App\Items\Content;

class PollCommand extends BaseCommand {
    protected int $minArgs = 3;
    protected int $maxArgs = 5;

    public function run(array $args = []): string {
        if (!$this->hasMinArgs($args) || $this->overflowArgs($args)) {
            return "Parámetros inválidos";
        }
        $msg = $args[0];
        if (strlen($msg) > Values::MESSAGE_MAX_CHARS) {
            return "El cuerpo excede los " . Values::MESSAGE_MAX_CHARS . " caracteres máximos";
        }
        array_shift($args);

        $duration = $args[0];
        if (!(is_numeric($duration) && intval($duration) <= Values::POLL_MAX_DURATION)) {
            return "La duración debe de ser un número, máximo: " .  Values::POLL_MAX_DURATION . '(' . Values::POLL_MAX_DURATION / 60 / 24 . " días)";
        }

        // Check if options do not overflow max characters
        $invalidOptions = array_filter($args, fn($item) => strlen($item) > Values::POLL_MAX_CHARS);
        if (!empty($invalidOptions)) {
            return "Una de las opciones excede los " . Values::POLL_MAX_CHARS . " caracteres máximos";
        }

        $moderate = Misc::env('APP_MODERATION', true);
        $contentDb = new Content();
        $contentDb->add($msg, $this->user_id, !$moderate, [
            'type' => 'poll',
            'data' => implode(';', $args)
        ]);
        $res = 'Encuesta agregada con éxito, pendiente de moderación: ' . ($moderate ? 'Sí' : 'No');
        return $res;
    }
}
