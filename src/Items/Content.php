<?php
namespace App\Items;

use App\Constants\FilterTypes;
use App\Constants\StatusTypes;

class Content extends BaseItem {
    public function get(int $id): ?object {
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

    public function getAll(string $type = 'waiting'): array {
        $filter = $this->filter($type);
        $contents = [];
        $stmt = $this->conn->prepare("SELECT id, msg, `media_id`, `media_url`, `type`, approved, published, blocked FROM contents WHERE {$filter} ORDER BY created_at ASC");
        $success = $stmt->execute();
        if ($success) {
            $contents = $stmt->fetchAll(\PDO::FETCH_OBJ);
        }
        return $contents;
    }

    public function getPublishable(): ?object {
        $filter = $this->filter('approved');
        $content = null;
        $stmt = $this->conn->prepare("SELECT id, `user_id`, msg, `media_id`, `media_url`, `type`, `created_at` FROM contents WHERE {$filter} ORDER BY created_at ASC LIMIT 1");
        $success = $stmt->execute();
        if ($success) {
            $content = $stmt->fetch(\PDO::FETCH_OBJ);
        }
        return $content ? $content : null;
    }

    public function add(string $msg, string $user_id, ?string $media_id = null, ?string $media_url = null, string $type = 'text', bool $approved = false): bool {
        $stmt = $this->conn->prepare('INSERT INTO contents(user_id, msg, media_id, media_url, `type`, approved) VALUES(:user_id, :msg, :media_id, :media_url, :type, :approved)');
        $success = $stmt->execute([
            ':user_id' => $user_id,
            ':msg' => $msg,
            ':media_id' => $media_id,
            ':media_url' => $media_url,
            ':type' => $type,
            ':approved' => intval($approved)
        ]);
        return $success;
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare('DELETE FROM contents WHERE id=:id');
        $success = $stmt->execute([
            ':id' => $id
        ]);
        return $success;
    }

    public function updateState(int $id, string $status = StatusTypes::APPROVED): bool {
        if ($status === StatusTypes::APPROVED || $status === StatusTypes::BLOCKED || $status === StatusTypes::PUBLISHED) {
            $stmt = $this->conn->prepare("UPDATE contents SET {$status}=1 WHERE id=:id");
            $stmt->execute([
                ':id' => $id
            ]);
        }
        return false;
    }

    public function queue(string $type = FilterTypes::WAITING): int {
        $filter = $this->filter($type);
        $position = $this->conn->query("SELECT COUNT(id) FROM contents WHERE {$filter}")->fetchColumn();
        return $position;
    }

    private function filter(string $type): string {
        // Default FilterTypes::WAITING
        $published = '(0)';
        $approved = '(0)';
        $blocked = '(0)';
        switch ($type) {
            case FilterTypes::APPROVED:
                $approved = '(1)';
                break;
            case FilterTypes::BLOCKED:
                $blocked = '(1)';
                break;
            case FilterTypes::PUBLISHED:
                $published = '(1)';
                $approved = '(1)';
                break;
            case FilterTypes::ALL:
                $published = '(0, 1)';
                $approved = '(0, 1)';
                $blocked = '(0, 1)';
                break;
        }
        return "published IN {$published} AND approved IN {$approved} AND blocked IN {$blocked}";
    }
}
