<div class="item-form-container">
    <h1>Add New Item</h1>

    <?php if (isset($error)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/items/create" class="item-form" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="type">Type:</label>
            <select name="type" id="type" required>
                <option value="weapon">Weapon</option>
                <option value="armor">Armor</option>
                <option value="consumable">Consumable</option>
                <option value="misc">Miscellaneous</option>
            </select>
        </div>

        <div class="form-group">
            <label for="rarity">Rarity:</label>
            <select name="rarity" id="rarity" required>
                <option value="common">Common</option>
                <option value="uncommon">Uncommon</option>
                <option value="rare">Rare</option>
                <option value="very_rare">Very Rare</option>
                <option value="legendary">Legendary</option>
            </select>
        </div>

        <div class="form-group">
            <label for="act">Act:</label>
            <select name="act" id="act" required>
                <option value="1">Act 1</option>
                <option value="2">Act 2</option>
                <option value="3">Act 3</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="5" required></textarea>
        </div>

        <div class="form-group">
            <label for="thumbnail">Thumbnail:</label>
            <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
            <p class="help-text">Maximum file size: 2MB. Supported formats: JPG, PNG</p>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Create Item</button>
            <a href="/items" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div> 