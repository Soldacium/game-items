<?php
$activeTab = 'items';
$description = isset($item) ? 'Edit Item' : 'Add New Item';
ob_start();
?>

<div class="content-section">
    <div class="section-header">
        <h2><?php echo $description; ?></h2>
    </div>
    <div class="section-body">
        <form method="POST" action="<?php echo isset($item) ? "/management/items/edit/{$item->getId()}" : "/management/items/new"; ?>" class="form" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo isset($item) ? htmlspecialchars($item->getName()) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <div class="type-options">
                    <?php
                    $types = ['Light armor', 'Medium armor', 'Heavy armor', 'Helmets', 'Heavy axe'];
                    foreach ($types as $type):
                        $isSelected = isset($item) && $item->getType() === $type;
                    ?>
                        <label class="type-option">
                            <input type="radio" name="type" value="<?php echo $type; ?>" <?php echo $isSelected ? 'checked' : ''; ?> required>
                            <span><?php echo $type; ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="act">Act Available</label>
                <div class="act-options">
                    <?php for ($i = 1; $i <= 3; $i++): ?>
                        <label class="act-option">
                            <input type="radio" name="act" value="<?php echo $i; ?>" 
                                <?php echo (isset($item) && $item->getAct() == $i) ? 'checked' : ''; ?> required>
                            <span>Act <?php echo $i; ?></span>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="rarity">Rarity</label>
                <div class="rarity-options">
                    <?php
                    $rarities = ['Common', 'Rare', 'Very rare', 'Legendary'];
                    foreach ($rarities as $rarity):
                        $isSelected = isset($item) && $item->getRarity() === $rarity;
                    ?>
                        <label class="rarity-option <?php echo strtolower($rarity); ?>">
                            <input type="radio" name="rarity" value="<?php echo $rarity; ?>" <?php echo $isSelected ? 'checked' : ''; ?> required>
                            <span><?php echo $rarity; ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"><?php echo isset($item) ? htmlspecialchars($item->getDescription()) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" rows="4"><?php echo isset($item) ? htmlspecialchars($item->getNotes()) : ''; ?></textarea>
                <p class="form-help">Additional notes about the item (optional)</p>
            </div>

            <div class="form-group">
                <label for="image">Item Image</label>
                <?php if (isset($item) && $item->getImageUrl()): ?>
                    <div class="current-image">
                        <img src="<?php echo htmlspecialchars($item->getImageUrl()); ?>" alt="Current item image">
                        <p>Current image</p>
                    </div>
                <?php endif; ?>
                <div class="file-input-container">
                    <input type="file" id="image" name="image" accept="image/*">
                    <label for="image" class="file-input-trigger">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Choose a file or drag it here</span>
                    </label>
                    <div class="file-name"></div>
                </div>
                <div class="image-preview-container">
                    <img src="#" alt="Image preview" class="image-preview" id="imagePreview">
                </div>
                <p class="form-help">Upload an image of the item (optional). Maximum size: 2MB. Supported formats: JPG, PNG</p>
            </div>

            <div class="form-actions">
                <a href="/management/items" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <?php echo isset($item) ? 'Save Changes' : 'Create Item'; ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?> 