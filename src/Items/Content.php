<?php
namespace App\Items;

use App\Constants\FilterTypes;
use App\Constants\StatusTypes;

class Content extends BaseItem {
    public function get(int $id): ?object {
        $content = null;
        $stmt = $this->conn->prepare('SELECT contents.id, contents.user_id, contents.msg, contents.created_at,
        attachments.data AS attachData, attachments.type as attachType FROM contents
        LEFT JOIN attachments ON contents.attachment_id=attachments.id WHERE contents.id=:id LIMIT 1');
        $success = $stmt->execute([
            ':id' => $id
        ]);
        if ($success) {
            $content = $stmt->fetch(\PDO::FETCH_OBJ);
        }
        return $content;
    }

    public function getAll(string $type = 'waiting'): array {
        $filter = $this->__filter($type);
        $sql = <<<SQL
        SELECT contents.id, contents.user_id, contents.msg, contents.created_at,
        contents.approved, contents.published, contents.blocked,
        attachments.data AS attachData, attachments.type as attachType FROM contents
        LEFT JOIN attachments ON contents.attachment_id=attachments.id
        WHERE {$filter} ORDER BY created_at ASC
        SQL;

        $contents = [];
        $stmt = $this->conn->prepare($sql);
        $success = $stmt->execute();
        if ($success) {
            $contents = $stmt->fetchAll(\PDO::FETCH_OBJ);
        }
        return $contents;
    }

    public function getPublishable(): ?object {
        $filter = $this->__filter('approved');
        $content = null;
        $sql = <<<SQL
        SELECT contents.id, contents.user_id, contents.msg, contents.created_at,
        contents.approved, contents.published, contents.blocked,
        attachments.data AS attachData, attachments.type as attachType FROM contents
        LEFT JOIN attachments ON contents.attachment_id=attachments.id
        WHERE {$filter} ORDER BY created_at ASC LIMIT 1
        SQL;
        $stmt = $this->conn->prepare($sql);
        $success = $stmt->execute();
        if ($success) {
            $content = $stmt->fetch(\PDO::FETCH_OBJ);
        }
        return $content ? $content : null;
    }

    public function add(string $msg, string $user_id, bool $approved = false, array $attachment = []): bool {
        $this->conn->beginTransaction();
        // Check attachments
        $attachment_id = null;
        if (!empty($attachment)) {
            $type = $attachment['type'];
            $data = $attachment['data'];
            $stmt = $this->conn->prepare('INSERT INTO attachments(`data`, `type`) VALUES(:data, :type)');
            $success = $stmt->execute([
                ':data' => $data,
                ':type' => $type
            ]);

            if (!$success) {
                $this->conn->rollBack();
                return false;
            }

            $attachment_id = $this->conn->lastInsertId();
        }

        $stmt = $this->conn->prepare('INSERT INTO contents(user_id, msg, attachment_id, approved) VALUES(:user_id, :msg, :attachment_id, :approved)');
        $success = $stmt->execute([
            ':user_id' => $user_id,
            ':msg' => $msg,
            ':attachment_id' => $attachment_id,
            ':approved' => intval($approved)
        ]);

        if (!$success) {
            $this->conn->rollBack();
            return false;
        }

        $this->conn->commit();
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
        $filter = $this->__filter($type);
        $position = $this->conn->query("SELECT COUNT(id) FROM contents WHERE {$filter}")->fetchColumn();
        return $position;
    }

    private function __filter(string $type): string {
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
        return "contents.published IN {$published} AND contents.approved IN {$approved} AND contents.blocked IN {$blocked}";
    }
}
