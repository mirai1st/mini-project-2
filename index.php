<?php require_once 'includes/header.php'; ?>

<!-- Hero -->
<div class="p-4 mb-4 bg-primary text-white rounded">
    <h2>Campus Service Hub</h2>
    <p>Find or offer student skills &amp; services on campus.</p>
    <a href="search.php" class="btn btn-light">Browse Services</a>
    <?php if (!isLoggedIn()): ?>
        <a href="register.php" class="btn btn-outline-light ms-2">Register</a>
    <?php endif; ?>
</div>

<!-- Latest Services (Task 6: JOIN + ORDER BY) -->
<h4>Latest Services</h4>
<div class="row">
<?php
$sql = "SELECT services.*, users.name AS owner
        FROM services
        JOIN users ON services.user_id = users.id
        ORDER BY services.created_at DESC
        LIMIT 6";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0):
    while ($row = mysqli_fetch_assoc($result)):
?>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <?php if ($row['image'] && file_exists('uploads/' . $row['image'])): ?>
                <img src="/campus_hub/uploads/<?php echo clean($row['image']); ?>" class="service-img" alt="image">
            <?php else: ?>
                <div class="no-img">No Image</div>
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?php echo clean($row['title']); ?></h5>
                <p class="card-text text-muted"><?php echo clean(substr($row['description'], 0, 80)); ?>...</p>
                <p><strong>RM <?php echo number_format($row['price'], 2); ?></strong> &nbsp; by <?php echo clean($row['owner']); ?></p>
                <a href="services/view.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">View</a>
            </div>
        </div>
    </div>
<?php
    endwhile;
else:
?>
    <div class="col"><p class="text-muted">No services yet. <a href="services/add.php">Be the first to post!</a></p></div>
<?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
