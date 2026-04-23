<?php
require_once 'includes/db.php';

if (isLoggedIn()) {
    header("Location: /campus_hub/dashboard.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Task 4: Server-side validation
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $pass2 = $_POST['confirm_password'] ?? '';

    if (empty($name)) {
        $error = "Name is required.";
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Valid email is required.";
    } elseif (strlen($pass) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($pass !== $pass2) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists (Task 7: prepared statement)
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email is already registered.";
        } else {
            // Task 1: password_hash()
            $hashed = password_hash($pass, PASSWORD_DEFAULT);

            $stmt2 = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt2->bind_param("sss", $name, $email, $hashed);

            if ($stmt2->execute()) {
                $success = "Registration successful! <a href='login.php'>Login here</a>.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<?php require_once 'includes/header.php'; ?>

<div class="row justify-content-center">
<div class="col-md-5">
    <div class="card p-4">
        <h3>Register</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo clean($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Task 4: onsubmit client-side validation -->
        <form method="POST" onsubmit="return validateRegister()">
            <div class="mb-3">
                <label>Full Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo clean($_POST['name'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo clean($_POST['email'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label>Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
        <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
    </div>
</div>
</div>

<?php require_once 'includes/footer.php'; ?>
