<?php
$activeTab = 'activity';
$description = 'View your recent account activity and history.';
ob_start();
?>

<div class="content-section">
    <div class="section-header">
        <h2>Recent Activity</h2>
    </div>
    <div class="section-body">
        <?php if (empty($activities)): ?>
            <div class="empty-state">
                <i class="fas fa-history"></i>
                <p>No recent activity to show</p>
                <p class="text-muted">Your activity history will appear here</p>
            </div>
        <?php else: ?>
            <div class="activity-list">
                <?php foreach ($activities as $activity): ?>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-<?php echo $activity->icon; ?>"></i>
                        </div>
                        <div class="activity-details">
                            <div class="activity-message"><?php echo htmlspecialchars($activity->message); ?></div>
                            <div class="activity-meta">
                                <span class="activity-time"><?php echo $activity->created_at; ?></span>
                                <?php if ($activity->location): ?>
                                    <span class="activity-location"><?php echo htmlspecialchars($activity->location); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.empty-state {
    text-align: center;
    padding: 48px 0;
    color: var(--text-muted);
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
}

.empty-state p {
    margin: 0;
}

.empty-state .text-muted {
    font-size: 14px;
    margin-top: 8px;
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.activity-item {
    display: flex;
    gap: 16px;
    padding: 16px;
    background-color: var(--bg-color);
    border-radius: 6px;
    transition: background-color 0.2s;
}

.activity-item:hover {
    background-color: var(--hover-color);
}

.activity-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background-color: var(--accent-color);
    color: var(--btn-text);
    display: flex;
    align-items: center;
    justify-content: center;
}

.activity-details {
    flex: 1;
}

.activity-message {
    margin-bottom: 4px;
    color: var(--text-color);
}

.activity-meta {
    display: flex;
    gap: 16px;
    font-size: 12px;
    color: var(--text-muted);
}

.activity-location::before {
    content: "•";
    margin-right: 16px;
}
</style>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?> 