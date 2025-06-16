<?php
// This is our landing page content
?>
<nav class="main-nav">
    <a href="/" class="nav-logo">
        <img src="public/images/bg3-logo.png" alt="Baldur's Gate 3" class="logo-image">
    </a>
    <div class="nav-sections">
        <a href="/items" class="nav-link active">Items</a>
        <a href="/characters" class="nav-link">Characters</a>
        <a href="/statuses" class="nav-link">Statuses</a>
    </div>
    <div class="nav-controls">
        <button id="theme-toggle" class="theme-toggle" aria-label="Toggle theme">
            <i class="fas fa-sun"></i>
        </button>
        <?php if (isset($_SESSION['user'])): ?>
            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                <a href="/management/items" class="nav-link-route">
                    <i class="fas fa-cog"></i>
                </a>
            <?php endif; ?>
            <a href="/management/profile" class="nav-link-route">
                <i class="fas fa-user"></i>
            </a>
        <?php else: ?>
            <a href="/login" class="btn-login">
                <i class="fas fa-user"></i>
            </a>
        <?php endif; ?>
    </div>
</nav>
<div class="landing-container">


    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search items...">
        <div id="searchButton" class="btn-search">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <button id="filtersButton" class="btn-filters">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            Filters
        </button>
    </div>

    <div class="filters-panel" id="filtersPanel">
        <div class="filter-section">
            <h3>Type</h3>
            <div class="filter-group">
                <button class="filter-btn" data-type="Weapon">Weapon</button>
                <button class="filter-btn" data-type="Armor">Armor</button>
                <button class="filter-btn" data-type="Consumable">Consumable</button>
                <button class="filter-btn" data-type="Misc">Misc</button>
            </div>
        </div>
        <div class="filter-section">
            <h3>Act</h3>
            <div class="filter-group">
                <button class="filter-btn" data-act="1">Act 1</button>
                <button class="filter-btn" data-act="2">Act 2</button>
                <button class="filter-btn" data-act="3">Act 3</button>
            </div>
        </div>
        <div class="filter-section">
            <h3>Rarity</h3>
            <div class="filter-group">
                <button class="filter-btn" data-rarity="Common">Common</button>
                <button class="filter-btn" data-rarity="Rare">Rare</button>
                <button class="filter-btn" data-rarity="Epic">Epic</button>
                <button class="filter-btn" data-rarity="Legendary">Legendary</button>
            </div>
        </div>
    </div>
    <div class="separator-wrapper">
    <div class="line"></div>
    <div class="separator">
      <span class="label">Results</span>
    </div>
  </div>

    <div class="results-grid" id="resultsGrid">
        <!-- Items will be dynamically loaded here -->
    </div>
</div>

<link rel="stylesheet" href="public/css/normalize.css">
<link rel="stylesheet" href="public/css/style.css">
<script src="public/js/landing.js?v=<?php echo time(); ?>"></script> 