<?php
require_once 'includes/db.php';

if (isLoggedIn()) {
    header("Location: /campus_hub/dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Task 4: Server-side validation
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if (empty($email) || empty($pass)) {
        $error = "Please fill in all fields.";
    } else {
        // Task 7: prepared statement
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Task 1: password_verify()
            if (password_verify($pass, $user['password'])) {
                // Task 1: store session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name']    = $user['name'];
                $_SESSION['email']   = $user['email'];
                $_SESSION['role']    = $user['role'];

                header("Location: /campus_hub/dashboard.php");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<?php require_once 'includes/header.php'; ?>

<div class="row justify-content-center">
<div class="col-md-5">
    <div class="card p-4">
        <h3>Login</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo clean($error); ?></div>
        <?php endif; ?>

        <!-- Task 4: onsubmit client-side validation -->
        <form method="POST" onsubmit="return validateLogin()">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo clean($_POST['email'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <p class="mt-3 text-center">No account? <a href="register.php">Register</a></p>
        <hr>
        <p class="text-muted small text-center">Demo admin: admin@campus.edu / admin123</p>
    </div>
</div>
</div>

<?php require_once 'includes/footer.php'; ?>
