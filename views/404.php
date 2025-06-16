<?php
$title = '404 - Page Not Found';
?>
<div class="error-container">
    <h1>404</h1>
    <h2>Page Not Found</h2>
    <p>The page you're looking for doesn't exist or has been moved.</p>
    <a href="/" class="btn btn-primary">Go Home</a>
</div>

<style>
.error-container {
    min-height: 60vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 2rem;
}

.error-container h1 {
    font-size: 6rem;
    color: #4a90e2;
    margin: 0;
    line-height: 1;
}

.error-container h2 {
    font-size: 2rem;
    color: #666;
    margin: 1rem 0;
}

.error-container p {
    color: #999;
    margin-bottom: 2rem;
}

.btn {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-primary {
    background-color: #4a90e2;
    color: white;
}

.btn-primary:hover {
    background-color: #357abd;
}
</style> 