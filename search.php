<?php require_once 'includes/header.php'; ?>

<h3>Search Services</h3>

<!-- Search Form -->
<form method="GET" class="row g-2 mb-4">
    <div class="col-auto">
        <input type="text" name="q" class="form-control" placeholder="Search..."
               value="<?php echo clean($_GET['q'] ?? ''); ?>">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="search.php" class="btn btn-secondary">Clear</a>
    </div>
</form>

<?php
$keyword = trim($_GET['q'] ?? '');

if ($keyword !== '') {
    $like = '%' . $keyword . '%';
    $stmt = $conn->prepare(
        "SELECT services.*, users.name AS owner
         FROM services
         JOIN users ON services.user_id = users.id
         WHERE services.title LIKE ? OR services.description LIKE ?
         ORDER BY services.created_at DESC"
    );
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
    echo "<p>Results for: <strong>" . clean($keyword) . "</strong> — " . $result->num_rows . " found</p>";
} else {
    $result = mysqli_query($conn,
        "SELECT services.*, users.name AS owner
         FROM services
         JOIN users ON services.user_id = users.id
         ORDER BY services.created_at DESC"
    );
}
?>

<div class="row">
<?php if ($result && mysqli_num_rows($result) > 0):
    while ($row = mysqli_fetch_assoc($result)): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <?php if ($row['image'] && file_exists('uploads/' . $row['image'])): ?>
                <img src="/campus_hub/uploads/<?php echo clean($row['image']); ?>" class="service-img" alt="img">
            <?php else: ?>
                <div class="no-img">No Image</div>
            <?php endif; ?>
            <div class="card-body">
                <h5><?php echo clean($row['title']); ?></h5>
                <p class="text-muted"><?php echo clean(substr($row['description'], 0, 80)); ?>...</p>
                <p><strong>RM <?php echo clean(number_format($row['price'], 2)); ?></strong> — <?php echo clean($row['owner']); ?></p>
                <a href="services/view.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">View</a>
            </div>
        </div>
    </div>
<?php endwhile;
else: ?>
    <div class="col"><p class="text-muted">No services found.</p></div>
<?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>