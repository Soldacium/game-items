<div class="auth-container profile-container">
    <h1>My Profile</h1>
    
    <div class="profile-info">
        <div class="info-group">
            <label>Email:</label>
            <span><?php echo htmlspecialchars($user['email']); ?></span>
        </div>
        <div class="info-group">
            <label>Role:</label>
            <span class="role-badge <?php echo htmlspecialchars($user['role']); ?>"><?php echo htmlspecialchars($user['role']); ?></span>
        </div>
    </div>

    <div class="profile-actions">
        <a href="/" class="btn-secondary">Back to Home</a>
        <a href="/logout" class="btn-danger">Logout</a>
    </div>
</div>

<style>
.profile-container {
    max-width: 600px;
}

.profile-info {
    background-color: var(--surface-color);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 1.5rem;
    margin: 2rem 0;
}

.info-group {
    margin-bottom: 1rem;
}

.info-group:last-child {
    margin-bottom: 0;
}

.info-group label {
    display: block;
    color: var(--text-secondary);
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.info-group span {
    display: block;
    color: var(--text-color);
    font-size: 1rem;
}

.role-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: capitalize;
}

.role-badge.admin {
    background-color: #4a90e2;
    color: white;
}

.role-badge.user {
    background-color: #45b164;
    color: white;
}

.profile-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.btn-secondary {
    padding: 0.75rem 1.5rem;
    background-color: var(--surface-color);
    border: 1px solid var(--border-color);
    color: var(--text-color);
    text-decoration: none;
    border-radius: 4px;
    font-weight: 500;
    flex: 1;
    text-align: center;
    transition: background-color 0.2s;
}

.btn-secondary:hover {
    background-color: var(--hover-color);
}

.btn-danger {
    padding: 0.75rem 1.5rem;
    background-color: #dc3545;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 500;
    flex: 1;
    text-align: center;
    transition: background-color 0.2s;
}

.btn-danger:hover {
    background-color: #c82333;
}
</style> 