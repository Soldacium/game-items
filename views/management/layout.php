<?php
$isAdmin = isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management - <?php echo ucfirst($activeTab); ?></title>
    <link rel="stylesheet" href="/public/css/normalize.css">
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/management.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-header">
            <a href="/" class="logo">Logo</a>
        </div>
        <div class="header-actions">
            <button class="theme-toggle" aria-label="Toggle theme">
                <i class="fas fa-sun"></i>
            </button>
            <a href="/logout" class="user-logout" aria-label="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </nav>
    <div class="management-container">
        <aside class="management-sidebar">

            
            <nav class="sidebar-nav">
                <a href="/management/profile" class="nav-item <?php echo $activeTab === 'profile' ? 'active' : ''; ?>">
                    Profile
                </a>
                <a href="/management/account" class="nav-item <?php echo $activeTab === 'account' ? 'active' : ''; ?>">
                    Account
                </a>
                <a href="/management/activity" class="nav-item <?php echo $activeTab === 'activity' ? 'active' : ''; ?>">
                    Activity
                </a>
                <?php if ($isAdmin): ?>
                <div class="sidebar-nav-divider"></div>
                <a href="/management/items" class="nav-item <?php echo $activeTab === 'items' ? 'active' : ''; ?>">
                    Items
                </a>
                <?php endif; ?>
            </nav>
        </aside>

        <main class="management-content">
            <header class="content-header">
                <div class="header-title">
                    <h1><?php echo ucfirst($activeTab); ?></h1>
                    <?php if (isset($description)): ?>
                        <p class="header-description"><?php echo $description; ?></p>
                    <?php endif; ?>
                </div>

            </header>

            <?php if (isset($_SESSION['flash'])): ?>
                <div class="alert alert-<?php echo $_SESSION['flash']['type']; ?>">
                    <?php 
                        echo $_SESSION['flash']['message'];
                        unset($_SESSION['flash']);
                    ?>
                </div>
            <?php endif; ?>

            <div class="content-body">
                <?php echo $content; ?>
            </div>
        </main>
    </div>

    <div class="modal-container"></div>
    <div class="toast-container"></div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteConfirmModal" style="display: none;">
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
                <form id="deleteItemForm" method="POST" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Theme toggle functionality
        document.querySelector('.theme-toggle').addEventListener('click', function() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-sun');
            icon.classList.toggle('fa-moon');
        });

        // Set initial theme
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
        if (savedTheme === 'light') {
            document.querySelector('.theme-toggle i').classList.replace('fa-sun', 'fa-moon');
        }
    </script>
    <script src="/public/js/management.js"></script>
</body>
</html> 