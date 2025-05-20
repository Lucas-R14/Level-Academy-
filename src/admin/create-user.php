<?php
require_once __DIR__ . '/../Controllers/User.php';

// Initialize User
$user = new User(getPDO());

// Check if user is logged in and is admin
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    $_SESSION['error'] = 'You do not have permission to access this page';
    header('Location: login.php');
    exit();
}

$error = '';
$formData = [
    'username' => '',
    'email' => '',
    'role' => 'user'
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'username' => trim($_POST['username'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'role' => $_POST['role'] ?? 'user',
        'password' => $_POST['password'] ?? '',
        'confirm_password' => $_POST['confirm_password'] ?? ''
    ];

    // Basic validation
    if (empty($formData['username']) || empty($formData['email']) || empty($formData['password'])) {
        $error = 'All fields are required';
    } elseif ($formData['password'] !== $formData['confirm_password']) {
        $error = 'Passwords do not match';
    } else {
        try {
            $userId = $user->register(
                $formData['username'],
                $formData['password'],
                $formData['email'],
                $formData['role'],
                true // Require admin privileges
            );
            
            $_SESSION['success'] = 'User created successfully';
            header('Location: users.php');
            exit();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}



require_once __DIR__ . '/includes/header.php';
?>

<div class="content-header">
    <h2>Create New User</h2>
    <a href="users.php" class="btn">
        <i class="fas fa-arrow-left"></i> Back to Users
    </a>
</div>

<div class="card">
    <?php if ($error): ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($formData['username']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($formData['email']); ?>" required>
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="user" <?php echo $formData['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo $formData['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Create User
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
