<?php
// Task 5: AJAX search endpoint
// Task 7: prepared statement to prevent SQL injection

require_once 'includes/db.php';

header('Content-Type: application/json');

$q = trim($_GET['q'] ?? '');

if ($q === '') {
    echo json_encode([]);
    exit();
}

$like = '%' . $q . '%';

// Task 6: JOIN, WHERE, ORDER BY
$stmt = $conn->prepare(
    "SELECT services.id, services.title, services.price, users.name AS owner
     FROM services
     JOIN users ON services.user_id = users.id
     WHERE services.title LIKE ? OR services.description LIKE ?
     ORDER BY services.created_at DESC
     LIMIT 8"
);
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'id'    => $row['id'],
        'title' => htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'),
        'price' => number_format($row['price'], 2),
        'owner' => htmlspecialchars($row['owner'], ENT_QUOTES, 'UTF-8'),
    ];
}

echo json_encode($data);
?>
