<?php
require_once '../includes/db.php';

$id = intval($_GET['id'] ?? 0);

// Task 7: prepared statement
$stmt = $conn->prepare(
    "SELECT services.*, users.name AS owner, users.email AS owner_email
     FROM services
     JOIN users ON services.user_id = users.id
     WHERE services.id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: /campus_hub/search.php");
    exit();
}

$row = $result->fetch_assoc();
?>

<?php require_once '../includes/header.php'; ?>

<a href="/campus_hub/search.php" class="btn btn-secondary btn-sm mb-3">&larr; Back</a>

<div class="card p-4">
    <div class="row">
        <div class="col-md-5">
            <?php if ($row['image'] && file_exists('../uploads/' . $row['image'])): ?>
                <img src="/campus_hub/uploads/<?php echo clean($row['image']); ?>"
                     class="img-fluid rounded" alt="image">
            <?php else: ?>
                <div class="no-img" style="height:220px;">No Image</div>
            <?php endif; ?>
        </div>
        <div class="col-md-7">
            <h3><?php echo clean($row['title']); ?></h3>
            <p class="text-muted">By: <?php echo clean($row['owner']); ?> (<?php echo clean($row['owner_email']); ?>)</p>
            <h4 class="text-primary">RM <?php echo number_format($row['price'], 2); ?></h4>
            <hr>
            <p><?php echo nl2br(clean($row['description'])); ?></p>
            <p class="text-muted small">Posted: <?php echo date('d M Y', strtotime($row['created_at'])); ?></p>

            <?php if (isLoggedIn() && (isAdmin() || $row['user_id'] == $_SESSION['user_id'])): ?>
                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger"
                   onclick="return confirm('Delete this service?')">Delete</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
