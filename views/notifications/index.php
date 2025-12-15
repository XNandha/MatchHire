<h1 class="page-title">Notifikasi</h1>

<?php if (!empty($notifications)): ?>
    <ul class="notification-list">
        <?php foreach ($notifications as $n): ?>
            <li class="<?= $n['is_read'] ? 'read' : 'unread' ?>">
                <p><?= htmlspecialchars($n['message']) ?></p>
                <small><?= htmlspecialchars($n['created_at']) ?></small>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Belum ada notifikasi.</p>
<?php endif; ?>
