<?php
$isLoggedIn = isset($_SESSION['user']);
$isAdmin = $isLoggedIn && $_SESSION['user']['role'] === 'admin';
?>

<nav class="main-nav">
    <div class="nav-left">
        <a href="/" class="nav-logo">
            <img src="/public/images/logo.svg" alt="BG3 Items" width="32" height="32">
        </a>
        <a href="/items" class="nav-link">Browse Items</a>
        <?php if ($isAdmin): ?>
            <a href="/management/items" class="nav-link">Manage Items</a>
        <?php endif; ?>
    </div>
    <div class="nav-right">
        <?php if ($isLoggedIn): ?>
            <div class="nav-user-menu">
                <button class="theme-toggle" aria-label="Toggle theme">
                    <i class="fas fa-sun"></i>
                </button>
                <a href="/management/profile" class="nav-link">
                    <i class="fas fa-user"></i>
                    Profile
                </a>
                <a href="/logout" class="nav-link nav-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        <?php else: ?>
            <div class="nav-auth">
                <button class="theme-toggle" aria-label="Toggle theme">
                    <i class="fas fa-sun"></i>
                </button>
                <a href="/login" class="nav-link">Login</a>
            </div>
        <?php endif; ?>
    </div>
</nav> 