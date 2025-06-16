<?php
$activeTab = 'profile';
$description = 'Manage your profile settings and preferences.';
ob_start();
?>

<div class="content-section">
    <div class="section-header">
        <h2>Profile Information</h2>
    </div>
    <div class="section-body">
        <div class="profile-info">
            <div class="profile-avatar">
                <img src="<?php echo $profile->getAvatarUrl() ?: '/public/img/default-avatar.png'; ?>" alt="Profile picture">
                <form id="avatar-form" class="avatar-upload" method="POST" action="/management/profile/avatar" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <label for="avatar" class="btn btn-secondary">
                        <i class="fas fa-camera"></i>
                        Change Picture
                    </label>
                    <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;">
                </form>
            </div>
            <div class="profile-details">
                <form method="POST" action="/management/profile/update">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="form-group">
                        <label for="visible_name">Visible name</label>
                        <input type="text" id="visible_name" name="visible_name" 
                               value="<?php echo htmlspecialchars($profile->getVisibleName()); ?>" required>
                        <p class="form-help">This is the name that will be displayed to other users.</p>
                    </div>
                    <div class="form-group">
                        <label class="toggle-container">
                            <label class="switch">
                                <input type="checkbox" name="show_contact_info"
                                    <?php echo $profile->getShowContactInfo() ? 'checked' : ''; ?>>
                                <span class="slider"></span>
                            </label>
                            Show contact info
                        </label>
                        <p class="form-help">Allow other users to see your contact information.</p>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="reset" class="btn btn-secondary">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('avatar').addEventListener('change', function() {
    const form = document.getElementById('avatar-form');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.reload();
        } else {
            alert(data.message || 'Failed to upload avatar');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to upload avatar');
    });
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?> 