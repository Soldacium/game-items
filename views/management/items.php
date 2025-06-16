<?php
$activeTab = 'items';
$description = 'Manage your game items catalog.';
ob_start();
?>

<div class="content-section">
    <div class="section-header">
        <h2>Items</h2>
        <a href="/management/items/new" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Add New Item
        </a>
    </div>

    <div class="section-body">
        <div class="results-grid">
            <?php if (empty($items)): ?>
                <p class="text-secondary">No items found. Create your first item!</p>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <div class="item-card">
                        <div class="item-image">
                            <?php if ($item->getThumbnailId()): ?>
                                <img src="/blob/<?php echo $item->getThumbnailId(); ?>" 
                                     alt="<?php echo htmlspecialchars($item->getName()); ?>"
                                     onerror="this.src='/public/img/default-item.svg'; this.onerror=null;">
                            <?php else: ?>
                                <img src="/public/img/default-item.svg" 
                                     alt="Default item image">
                            <?php endif; ?>
                        </div>
                        <div class="item-info">
                            <h3><?php echo htmlspecialchars($item->getName()); ?></h3>
                            <div class="item-meta">
                                <span class="badge badge-<?php echo htmlspecialchars($item->getRarity()); ?>">
                                    <?php echo htmlspecialchars($item->getRarity()); ?>
                                </span>
                                <span class="badge badge-type">
                                    <?php echo htmlspecialchars($item->getType()); ?>
                                </span>
                                <span class="badge badge-act">
                                    Act <?php echo htmlspecialchars($item->getAct()); ?>
                                </span>
                            </div>
                            <p class="item-description">
                                <?php echo htmlspecialchars($item->getDescription()); ?>
                            </p>
                            <div class="item-actions">
                            <a href="/management/items/edit/<?php echo $item->getId(); ?>" class="btn btn-secondary">
                                <i class="fas fa-edit"></i>
                                Edit
                            </a>
                            <button class="btn btn-danger delete-item" data-item-id="<?php echo $item->getId(); ?>">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal" id="deleteConfirmModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Delete Item</h3>
            <button type="button" class="modal-close" aria-label="Close">×</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this item? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary modal-close">Cancel</button>
            <form id="deleteItemForm" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?> 