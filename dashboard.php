<?php
require_once 'includes/db.php';
requireLogin(); // Task 1: restrict unauthorized access
?>

<?php require_once 'includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Dashboard</h3>
    <a href="services/add.php" class="btn btn-success">+ Add Service</a>
</div>

<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">Service deleted successfully.</div>
<?php endif; ?>

<?php
// Task 6: JOIN + WHERE + ORDER BY
// Admin sees all, user sees own only
if (isAdmin()) {
    $sql = "SELECT services.*, users.name AS owner
            FROM services
            JOIN users ON services.user_id = users.id
            ORDER BY services.created_at DESC";
    $result = mysqli_query($conn, $sql);
} else {
    $uid = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT services.*, users.name AS owner
                            FROM services
                            JOIN users ON services.user_id = users.id
                            WHERE services.user_id = ?
                            ORDER BY services.created_at DESC");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<table class="table table-bordered table-hover bg-white">
    <thead class="table-primary">
        <tr>
            <th>#</th>
            <th>Title</th>
            <?php if (isAdmin()): ?><th>Owner</th><?php endif; ?>
            <th>Price</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $i = 1;
    if (mysqli_num_rows($result) > 0):
        while ($row = mysqli_fetch_assoc($result)):
    ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo clean($row['title']); ?></td>
            <?php if (isAdmin()): ?><td><?php echo clean($row['owner']); ?></td><?php endif; ?>
            <td>RM <?php echo number_format($row['price'], 2); ?></td>
            <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
            <td>
                <a href="services/view.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">View</a>
                <?php if (isAdmin() || $row['user_id'] == $_SESSION['user_id']): ?>
                    <a href="services/edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="services/delete.php?id=<?php echo $row['id']; ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Delete this service?')">Delete</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php
        endwhile;
    else:
    ?>
        <tr><td colspan="6" class="text-center text-muted">No services found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>
