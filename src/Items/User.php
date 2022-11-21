<?php
namespace App\Items;

class User extends BaseItem {
    public function add(string $user_id, string $niu): bool {
        $stmt = $this->conn->prepare('INSERT INTO users(user_id, niu) VALUES(:user_id, :niu)');
        $success = $stmt->execute([
            ':user_id' => $user_id,
            ':niu' => $niu
        ]);
        return $success;
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare('DELETE FROM users WHERE id=:id');
        $success = $stmt->execute([
            ':id' => $id
        ]);
        return $success;
    }

    public function isTwitterVerified(string $user_id): bool {
        $stmt = $this->conn->prepare('SELECT 1 from users WHERE user_id =:user_id LIMIT 1');
        $stmt->execute([
            ':user_id' => $user_id
        ]);

        if($stmt->fetchColumn()) {
            return true;
        }
        return false;
    }

    public function isNiuVerified(string $niu): bool {
        $stmt = $this->conn->prepare('SELECT 1 from users WHERE niu=:niu LIMIT 1');
        $stmt->execute([
            ':niu' => $niu
        ]);

        if($stmt->fetchColumn()) {
            return true;
        }
        return false;
    }
}