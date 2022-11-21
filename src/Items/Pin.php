<?php
namespace App\Items;

class Pin extends BaseItem {
    public function add(string $user_id, string $niu, string $pin): bool {
        $stmt = $this->conn->prepare('INSERT INTO pins(user_id, niu, pin) VALUES(:user_id, :niu, :pin)');
        $success = $stmt->execute([
            ':user_id' => $user_id,
            ':niu' => $niu,
            ':pin' => $pin
        ]);
        return $success;
    }

    public function get(string $pin): object {
        $stmt = $this->conn->prepare('SELECT id, `user_id`, `niu` FROM pins WHERE `pin`=:pin');
        $stmt->execute([
            ':pin' => $pin
        ]);
        $new_user = $stmt->fetch(\PDO::FETCH_OBJ);
        return $new_user !== false ? $new_user : null;
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare('DELETE FROM pins WHERE id=:id');
        $success = $stmt->execute([
            ':id' => $id
        ]);
        return $success;
    }

    public function update(string $user_id, string $niu, string $pin): bool {
        $stmt = $this->conn->prepare('UPDATE pins SET pin=:pin WHERE user_id=:user_id AND niu=:niu');
        $success = $stmt->execute([
            ':user_id' => $user_id,
            ':niu' => $niu,
            ':pin' => $pin
        ]);
        return $success;
    }

    public function hasUserSent(string $user_id, string $niu): bool {
        $stmt = $this->conn->prepare('SELECT 1 from pins WHERE user_id=:user_id AND niu=:niu LIMIT 1');
        $stmt->execute([
            ':user_id' => $user_id,
            ':niu' => $niu
        ]);

        if($stmt->fetchColumn()) {
            return true;
        }
        return false;
    }
}
