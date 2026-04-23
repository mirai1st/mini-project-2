<?php
require_once '../includes/db.php';
requireAdmin();

$id = intval($_GET['id'] ?? 0);

// Cannot delete yourself
if ($id === $_SESSION['user_id']) {
    header("Location: /campus_hub/admin/users.php");
    exit();
}

// Task 7: prepared statement
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: /campus_hub/admin/users.php?deleted=1");
exit();
?>
