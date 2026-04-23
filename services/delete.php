<?php
require_once '../includes/db.php';
requireLogin();

$id = intval($_GET['id'] ?? 0);

// Task 7: prepared statement
$stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: /campus_hub/dashboard.php");
    exit();
}

$row = $result->fetch_assoc();

// Task 3: only owner or admin can delete
if (!isAdmin() && $row['user_id'] != $_SESSION['user_id']) {
    header("Location: /campus_hub/dashboard.php");
    exit();
}

// Delete image file from server
if ($row['image'] && file_exists('../uploads/' . $row['image'])) {
    unlink('../uploads/' . $row['image']);
}

// Delete from database
$stmt2 = $conn->prepare("DELETE FROM services WHERE id = ?");
$stmt2->bind_param("i", $id);
$stmt2->execute();

header("Location: /campus_hub/dashboard.php?deleted=1");
exit();
?>
