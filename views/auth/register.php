<div class="auth-container">
    <h1>Register</h1>
    
    <?php if (isset($error)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/register" class="auth-form">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="account_name">Account Name:</label>
            <input type="text" id="account_name" name="account_name" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required 
                   pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$"
                   title="Password must be at least 8 characters long and include both letters and numbers">
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <button type="submit" class="btn-primary">Register</button>
    </form>

    <p class="auth-links">
        Already have an account? <a href="/login">Login here</a>
    </p>
</div>

<script>
document.querySelector('.auth-form').addEventListener('submit', function(e) {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    
    if (password.value !== confirmPassword.value) {
        e.preventDefault();
        alert('Passwords do not match!');
    }
});
</script> 