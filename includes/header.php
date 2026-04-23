<?php require_once __DIR__ . '/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Service Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/campus_hub/assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="/campus_hub/index.php">Campus Hub</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/campus_hub/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/campus_hub/search.php">Search</a></li>

                <?php if (isLoggedIn()): ?>
                    <li class="nav-item"><a class="nav-link" href="/campus_hub/dashboard.php">Dashboard</a></li>
                    <?php if (isAdmin()): ?>
                        <li class="nav-item"><a class="nav-link" href="/campus_hub/admin/users.php">Admin</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="/campus_hub/logout.php">Logout (<?php echo clean($_SESSION['name']); ?>)</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/campus_hub/login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="/campus_hub/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
