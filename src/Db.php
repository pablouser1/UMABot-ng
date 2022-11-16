<?php
namespace App;

use App\Helpers\Misc;

class Db {
    private \PDO $conn;

    function __construct() {
        $driver = Misc::env('DB_DRIVER', 'mysql');
        $host = Misc::env('DB_HOST', 'localhost');
        $port = Misc::env('DB_PORT', 3306);
        $db = Misc::env('DB_NAME', 'umabot');
        $username = Misc::env('DB_USERNAME', '');
        $password = Misc::env('DB_PASSWORD', '');
        $dns = $driver .
        ':host=' . $host .
        ';port=' . $port .
        ';dbname=' . $db . ';charset=utf8mb4';
        $this->conn = new \PDO($dns, $username, $password);
    }

    public function getPin(string $pin): ?object {
        $stmt = $this->conn->prepare('SELECT id, `user_id`, `niu` FROM pins WHERE `pin`=:pin');
        $stmt->execute([
            ':pin' => $pin
        ]);
        $new_user = $stmt->fetch(\PDO::FETCH_OBJ);
        return $new_user !== false ? $new_user : null;
    }

    public function createPin(string $user_id, string $niu): string {
        $pin = Misc::randomNumber(6);
        $stmt = $this->conn->prepare('INSERT INTO pins(user_id, niu, pin) VALUES(:user_id, :niu, :pin)');
        $stmt->execute([
            ':user_id' => $user_id,
            ':niu' => $niu,
            ':pin' => $pin
        ]);
        return $pin;
    }

    public function deletePin(int $id) {
        $stmt = $this->conn->prepare('DELETE FROM pins WHERE id=:id');
        $stmt->execute([
            ':id' => $id
        ]);
    }

    public function addUser(string $user_id, string $niu) {
        $stmt = $this->conn->prepare('INSERT INTO users(user_id, niu) VALUES(:user_id, :niu)');
        $stmt->execute([
            ':user_id' => $user_id,
            ':niu' => $niu
        ]);
    }

    public function isUserVerified(string $user_id): bool {
        $stmt = $this->conn->prepare('SELECT 1 from users WHERE user_id =:user_id LIMIT 1');
        $stmt->execute([
            ':user_id' => $user_id
        ]);

        if($stmt->fetchColumn()) {
            return true;
        }
        return false;
    }

    public function addContent(string $msg, string $user_id, ?string $media_id = null, ?string $media_url = null, string $type = 'text'): bool {
        $stmt = $this->conn->prepare('INSERT INTO contents(user_id, msg, media_id, media_url, `type`) VALUES(:user_id, :msg, :media_id, :media_url, :type)');
        $success = $stmt->execute([
            ':user_id' => $user_id,
            ':msg' => $msg,
            ':media_id' => $media_id,
            ':media_url' => $media_url,
            ':type' => $type
        ]);
        return $success;
    }

    public function getContents(): array {
        $contents = [];
        $stmt = $this->conn->prepare('SELECT id, msg, `media_id`, `media_url`, `type` FROM contents WHERE published=0 AND approved=0 AND blocked=0 ORDER BY created_at DESC');
        $success = $stmt->execute();
        if ($success) {
            $contents = $stmt->fetchAll(\PDO::FETCH_OBJ);
        }
        return $contents;
    }

    public function getPublishable(): ?object {
        $content = null;
        $stmt = $this->conn->prepare('SELECT id, `user_id`, msg, `media_id`, `media_url`, `type`, `created_at` FROM contents WHERE published=0 AND approved=1 AND blocked=0 ORDER BY created_at DESC LIMIT 1');
        $success = $stmt->execute();
        if ($success) {
            $content = $stmt->fetch(\PDO::FETCH_OBJ);
        }
        return $content ? $content : null;
    }

    public function getContent(int $id): ?object {
        $content = null;
        $stmt = $this->conn->prepare('SELECT id, `user_id`, msg, `media_id`, `media_url`, `type`, `created_at` FROM contents WHERE id=:id LIMIT 1');
        $success = $stmt->execute([
            ':id' => $id
        ]);
        if ($success) {
            $content = $stmt->fetch(\PDO::FETCH_OBJ);
        }
        return $content;
    }

    public function getModerationQueue(): int {
        $position = $this->conn->query('SELECT COUNT(*) FROM contents WHERE published=0 AND approved=0 AND blocked=0')->rowCount();
        return $position;
    }

    public function getContentQueue(): int {
        $position = $this->conn->query('SELECT COUNT(*) FROM contents WHERE published=0 AND approved=1 AND blocked=0')->rowCount();
        return $position;
    }

    public function setContentPublished(int $id) {
        $stmt = $this->conn->prepare('UPDATE contents SET published=1 WHERE id=:id');
        $stmt->execute([
            ':id' => $id
        ]);
    }

    public function setContentApproved(int $id) {
        $stmt = $this->conn->prepare('UPDATE contents SET approved=1 WHERE id=:id');
        $stmt->execute([
            ':id' => $id
        ]);
    }

    public function setContentBlocked(int $id) {
        $stmt = $this->conn->prepare('UPDATE contents SET blocked=1 WHERE id=:id');
        $stmt->execute([
            ':id' => $id
        ]);
    }

    public function getAdmin(string $username): ?object {
        $stmt = $this->conn->prepare('SELECT id, username, `password` FROM admins WHERE `username`=:username');
        $stmt->execute([
            ':username' => $username
        ]);
        $admin = $stmt->fetch(\PDO::FETCH_OBJ);
        return $admin !== false ? $admin : null;
    }
}