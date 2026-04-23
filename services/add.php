<?php
require_once '../includes/db.php';
requireLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Task 4: Server-side validation
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = trim($_POST['price'] ?? '');
    $image_name  = null;

    if (empty($title)) {
        $error = "Title is required.";
    } elseif (empty($description)) {
        $error = "Description is required.";
    } elseif ($price === '' || !is_numeric($price) || $price < 0) {
        $error = "Please enter a valid price.";
    } else {

        // Task 3: File upload with validation
        if (!empty($_FILES['image']['name'])) {
            $file_tmp  = $_FILES['image']['tmp_name'];
            $file_size = $_FILES['image']['size'];
            $file_type = mime_content_type($file_tmp);
            $ext       = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            // Validate file type JPG/PNG only
            if (!in_array($file_type, ['image/jpeg', 'image/png'])) {
                $error = "Only JPG or PNG images are allowed.";
            } elseif ($file_size > 2 * 1024 * 1024) {
                $error = "Image must be under 2MB.";
            } else {
                $image_name = uniqid() . '.' . $ext;
                move_uploaded_file($file_tmp, '../uploads/' . $image_name);
            }
        }

        if (empty($error)) {
            $uid = $_SESSION['user_id'];
            // Task 7: prepared statement
            $stmt = $conn->prepare("INSERT INTO services (user_id, title, description, price, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issds", $uid, $title, $description, $price, $image_name);

            if ($stmt->execute()) {
                $success = "Service added successfully!";
            } else {
                $error = "Failed to add service. Please try again.";
            }
        }
    }
}
?>

<?php require_once '../includes/header.php'; ?>

<h3>Add New Service</h3>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo clean($error); ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?> <a href="../dashboard.php">Go to Dashboard</a></div>
<?php endif; ?>

<div class="card p-4" style="max-width:600px;">
    <!-- Task 4: onsubmit JS validation -->
    <form method="POST" enctype="multipart/form-data" onsubmit="return validateService()">
        <div class="mb-3">
            <label>Title *</label>
            <input type="text" id="title" name="title" class="form-control"
                   value="<?php echo clean($_POST['title'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label>Description *</label>
            <textarea id="description" name="description" class="form-control" rows="4"><?php echo clean($_POST['description'] ?? ''); ?></textarea>
        </div>
        <div class="mb-3">
            <label>Price (RM) *</label>
            <input type="number" id="price" name="price" class="form-control" step="0.01" min="0"
                   value="<?php echo clean($_POST['price'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label>Image (JPG/PNG, max 2MB)</label>
            <input type="file" id="image" name="image" class="form-control" accept=".jpg,.jpeg,.png"
                   onchange="previewImage(this)">
            <img id="imagePreview" src="" alt="Preview">
        </div>
        <button type="submit" class="btn btn-success">Add Service</button>
        <a href="../dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
