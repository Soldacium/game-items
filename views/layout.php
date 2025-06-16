<?php
use App\Utils\AssetHelper;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Game Items Catalog'; ?></title>
    <base href="/">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Base Styles -->
    <link rel="stylesheet" href="<?php echo AssetHelper::asset('public/css/normalize.css'); ?>">
    <link rel="stylesheet" href="<?php echo AssetHelper::asset('public/css/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo AssetHelper::asset('public/css/landing.css'); ?>">
    <link rel="stylesheet" href="<?php echo AssetHelper::asset('public/css/auth.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <meta name="description" content="A comprehensive catalog of items from Baldur's Gate 3">
    <meta name="csrf-token" content="<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo AssetHelper::asset('public/images/logo.svg'); ?>">
</head>
<body>


    <?php echo $content; ?>
        <!-- 
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <img src="public/images/logo.svg" alt="BG3 Items" width="32" height="32">
                <p>&copy; <?php echo date('Y'); ?> Game Items Catalog. All rights reserved.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="items">Browse Items</a></li>
                    <li><a href="about">About</a></li>
                    <li><a href="contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Legal</h3>
                <ul>
                    <li><a href="privacy">Privacy Policy</a></li>
                    <li><a href="terms">Terms of Service</a></li>
                </ul>
            </div>
        </div>
    </footer>
        -->


    <div class="modal-container"></div>
    <div class="toast-container"></div>

    <script>
        document.querySelectorAll('.theme-toggle').forEach(function(button) {
            button.addEventListener('click', function() {
                const html = document.documentElement;
                const currentTheme = html.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                html.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-sun');
                icon.classList.toggle('fa-moon');
            });
        });

        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
        if (savedTheme === 'light') {
            document.querySelectorAll('.theme-toggle i').forEach(function(icon) {
                icon.classList.replace('fa-sun', 'fa-moon');
            });
        }
    </script>
</body>
</html> 