<div class="item-form-container">
    <h1>Edit Item</h1>

    <?php if (isset($error)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/items/<?php echo $item['id']; ?>/edit" class="item-form" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="type">Type:</label>
            <select name="type" id="type" required>
                <option value="weapon" <?php echo $item['type'] === 'weapon' ? 'selected' : ''; ?>>Weapon</option>
                <option value="armor" <?php echo $item['type'] === 'armor' ? 'selected' : ''; ?>>Armor</option>
                <option value="consumable" <?php echo $item['type'] === 'consumable' ? 'selected' : ''; ?>>Consumable</option>
                <option value="misc" <?php echo $item['type'] === 'misc' ? 'selected' : ''; ?>>Miscellaneous</option>
            </select>
        </div>

        <div class="form-group">
            <label for="rarity">Rarity:</label>
            <select name="rarity" id="rarity" required>
                <option value="common" <?php echo $item['rarity'] === 'common' ? 'selected' : ''; ?>>Common</option>
                <option value="uncommon" <?php echo $item['rarity'] === 'uncommon' ? 'selected' : ''; ?>>Uncommon</option>
                <option value="rare" <?php echo $item['rarity'] === 'rare' ? 'selected' : ''; ?>>Rare</option>
                <option value="very_rare" <?php echo $item['rarity'] === 'very_rare' ? 'selected' : ''; ?>>Very Rare</option>
                <option value="legendary" <?php echo $item['rarity'] === 'legendary' ? 'selected' : ''; ?>>Legendary</option>
            </select>
        </div>

        <div class="form-group">
            <label for="act">Act:</label>
            <select name="act" id="act" required>
                <option value="1" <?php echo $item['act'] === '1' ? 'selected' : ''; ?>>Act 1</option>
                <option value="2" <?php echo $item['act'] === '2' ? 'selected' : ''; ?>>Act 2</option>
                <option value="3" <?php echo $item['act'] === '3' ? 'selected' : ''; ?>>Act 3</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($item['description']); ?></textarea>
        </div>

        <div class="form-group">
            <?php if ($item['thumbnail_id']): ?>
                <div class="current-thumbnail">
                    <label>Current Thumbnail:</label>
                    <img src="/thumbnail/<?php echo $item['thumbnail_id']; ?>" alt="Current thumbnail" class="thumbnail-preview">
                </div>
            <?php endif; ?>

            <label for="thumbnail">New Thumbnail:</label>
            <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
            <p class="help-text">Maximum file size: 2MB. Supported formats: JPG, PNG. Leave empty to keep current thumbnail.</p>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Update Item</button>
            <a href="/items/<?php echo $item['id']; ?>" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div> 