<?php
// models/Notification.php

require_once __DIR__ . '/../core/Model.php';

class Notification extends Model
{
    public function create(int $recipientUserId, ?int $senderUserId, string $type, string $message): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO notifications (recipient_user_id, sender_user_id, type, message)
            VALUES (:recipient, :sender, :type, :message)
        ");

        $stmt->execute([
            'recipient' => $recipientUserId,
            'sender'    => $senderUserId,
            'type'      => $type,
            'message'   => $message,
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function findByRecipient(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM notifications
            WHERE recipient_user_id = :id
            ORDER BY created_at DESC
        ");
        $stmt->execute(['id' => $userId]);

        return $stmt->fetchAll();
    }

    public function markAsRead(int $notificationId): void
    {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE notification_id = :id");
        $stmt->execute(['id' => $notificationId]);
    }
}
