<?php
require_once '../includes/db.php';
requireLogin();

$id = intval($_GET['id'] ?? 0);

// Fetch service
$stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: /campus_hub/dashboard.php");
    exit();
}

$row = $result->fetch_assoc();

// Task 3: only owner or admin can edit
if (!isAdmin() && $row['user_id'] != $_SESSION['user_id']) {
    header("Location: /campus_hub/dashboard.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Task 4: Server-side validation
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = trim($_POST['price'] ?? '');
    $image_name  = $row['image']; // keep current image

    if (empty($title)) {
        $error = "Title is required.";
    } elseif (empty($description)) {
        $error = "Description is required.";
    } elseif ($price === '' || !is_numeric($price) || $price < 0) {
        $error = "Please enter a valid price.";
    } else {

        // Handle new image upload
        if (!empty($_FILES['image']['name'])) {
            $file_tmp  = $_FILES['image']['tmp_name'];
            $file_size = $_FILES['image']['size'];
            $file_type = mime_content_type($file_tmp);
            $ext       = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (!in_array($file_type, ['image/jpeg', 'image/png'])) {
                $error = "Only JPG or PNG images are allowed.";
            } elseif ($file_size > 2 * 1024 * 1024) {
                $error = "Image must be under 2MB.";
            } else {
                // Delete old image
                if ($row['image'] && file_exists('../uploads/' . $row['image'])) {
                    unlink('../uploads/' . $row['image']);
                }
                $image_name = uniqid() . '.' . $ext;
                move_uploaded_file($file_tmp, '../uploads/' . $image_name);
            }
        }

        if (empty($error)) {
            // Task 7: prepared statement
            $stmt2 = $conn->prepare("UPDATE services SET title=?, description=?, price=?, image=? WHERE id=?");
            $stmt2->bind_param("ssdsi", $title, $description, $price, $image_name, $id);

            if ($stmt2->execute()) {
                $success = "Service updated!";
                $row['title']       = $title;
                $row['description'] = $description;
                $row['price']       = $price;
                $row['image']       = $image_name;
            } else {
                $error = "Failed to update.";
            }
        }
    }
}
?>

<?php require_once '../includes/header.php'; ?>

<h3>Edit Service</h3>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo clean($error); ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?> <a href="../dashboard.php">Dashboard</a></div>
<?php endif; ?>

<div class="card p-4" style="max-width:600px;">
    <form method="POST" enctype="multipart/form-data" onsubmit="return validateService()">
        <div class="mb-3">
            <label>Title *</label>
            <input type="text" id="title" name="title" class="form-control"
                   value="<?php echo clean($row['title']); ?>">
        </div>
        <div class="mb-3">
            <label>Description *</label>
            <textarea id="description" name="description" class="form-control" rows="4"><?php echo clean($row['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label>Price (RM) *</label>
            <input type="number" id="price" name="price" class="form-control" step="0.01" min="0"
                   value="<?php echo clean($row['price']); ?>">
        </div>
        <div class="mb-3">
            <label>New Image (leave blank to keep current)</label>
            <?php if ($row['image'] && file_exists('../uploads/' . $row['image'])): ?>
                <div class="mb-2">
                    <img src="/campus_hub/uploads/<?php echo clean($row['image']); ?>"
                         style="max-height:100px; border-radius:6px;" alt="current">
                    <small class="text-muted d-block">Current image</small>
                </div>
            <?php endif; ?>
            <input type="file" id="image" name="image" class="form-control" accept=".jpg,.jpeg,.png"
                   onchange="previewImage(this)">
            <img id="imagePreview" src="" alt="Preview">
        </div>
        <button type="submit" class="btn btn-warning">Update Service</button>
        <a href="../dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
