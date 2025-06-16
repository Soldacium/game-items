<div class="item-detail-container">
    <div class="item-header">
        <h1><?php echo htmlspecialchars($item['name']); ?></h1>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <div class="admin-actions">
                <a href="/items/<?php echo $item['id']; ?>/edit" class="btn-secondary">Edit</a>
                <form method="POST" action="/items/<?php echo $item['id']; ?>/delete" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this item?');">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                    <button type="submit" class="btn-danger">Delete</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <div class="item-content">
        <div class="item-image">
            <?php if ($item['thumbnail_id']): ?>
                <img src="/thumbnail/<?php echo $item['thumbnail_id']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="item-thumbnail-large">
            <?php else: ?>
                <div class="no-image">No image available</div>
            <?php endif; ?>
        </div>

        <div class="item-info">
            <div class="info-group">
                <label>Type:</label>
                <span><?php echo ucfirst(htmlspecialchars($item['type'])); ?></span>
            </div>

            <div class="info-group">
                <label>Rarity:</label>
                <span class="rarity <?php echo htmlspecialchars($item['rarity']); ?>"><?php echo ucfirst(htmlspecialchars($item['rarity'])); ?></span>
            </div>

            <div class="info-group">
                <label>Act:</label>
                <span>Act <?php echo htmlspecialchars($item['act']); ?></span>
            </div>

            <div class="info-group description">
                <label>Description:</label>
                <p><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
            </div>
        </div>
    </div>

    <div class="back-link">
        <a href="/items" class="btn-secondary">← Back to Items</a>
    </div>
</div> 