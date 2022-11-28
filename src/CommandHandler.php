<?php
namespace App;

use App\Commands\Command;
use App\Commands\PinCommand;
use App\Commands\PollCommand;
use App\Commands\ResetCommand;
use App\Commands\VerifyCommand;
use App\Constants\Commands;
use App\Constants\Messages;

class CommandHandler {
    static public function run(string $command, string $user_id, array $args = []): void {
        $command_class = self::getCommandClass($command, $user_id);
        $msg = '';

        if ($command_class) {
            $msg = $command_class->run($args);
        } else {
            $msg = Messages::COMMAND_NOT_FOUND;
        }

        $twitter = new Twitter;
        $twitter->reply($msg, $user_id);
    }

    static private function getCommandClass(string $command, string $user_id): ?Command {
        $class = null;
        switch ($command) {
            case Commands::VERIFY:
                $class = VerifyCommand::class;
                break;
            case Commands::PIN:
                $class = PinCommand::class;
                break;
            case Commands::RESET:
                $class = ResetCommand::class;
                break;
            case Commands::POLL:
                $class = PollCommand::class;
                break;
        }
        return $class ? new $class($user_id) : null;
    }
}
