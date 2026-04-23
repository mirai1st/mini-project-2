<?php
require_once '../includes/db.php';
requireAdmin(); // Task 1: admin only
?>

<?php require_once '../includes/header.php'; ?>

<h3>Admin — Manage Users</h3>

<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">User deleted.</div>
<?php endif; ?>

<?php
// Task 6: display all users with ORDER BY
$result = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
?>

<table class="table table-bordered table-hover bg-white">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Registered</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo clean($row['name']); ?></td>
            <td><?php echo clean($row['email']); ?></td>
            <td>
                <span class="badge <?php echo $row['role'] === 'admin' ? 'bg-danger' : 'bg-secondary'; ?>">
                    <?php echo clean($row['role']); ?>
                </span>
            </td>
            <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
            <td>
                <?php if ($row['id'] != $_SESSION['user_id']): ?>
                    <a href="delete_user.php?id=<?php echo $row['id']; ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Delete this user?')">Delete</a>
                <?php else: ?>
                    <span class="text-muted small">(You)</span>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<?php require_once '../includes/footer.php'; ?>
