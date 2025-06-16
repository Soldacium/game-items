<?php
$activeTab = 'account';
$description = 'Manage your account settings and security preferences.';
ob_start();
?>

<div class="content-section">
    <div class="section-header">
        <h2>Account Information</h2>
    </div>
    <div class="section-body">
        <form method="POST" action="/management/account/update">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user->email ?? ''); ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update email</button>
            </div>
        </form>
    </div>
</div>

<div class="content-section">
    <div class="section-header">
        <h2>Change Password</h2>
    </div>
    <div class="section-body">
        <form method="POST" action="/management/account/password">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div class="form-group">
                <label for="previous_password">Current password</label>
                <input type="password" id="previous_password" name="previous_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New password</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_new_password">Confirm new password</label>
                <input type="password" id="confirm_new_password" name="confirm_new_password" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Change password</button>
            </div>
        </form>
    </div>
</div>

<div class="content-section">
    <div class="section-header">
        <h2>Delete Account</h2>
    </div>
    <div class="section-body">
        <p>Once you delete your account, there is no going back. Please be certain.</p>
        <form method="POST" action="/management/account/delete" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
            <div class="form-actions">
                <button type="submit" class="btn btn-secondary" style="background-color: var(--error-bg); color: var(--error-text); border-color: var(--error-border);">Delete account</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?> 