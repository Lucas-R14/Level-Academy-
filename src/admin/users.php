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

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['user_id'])) {
    try {
        $user->deleteUser($_POST['user_id']);
        $_SESSION['success'] = 'User deleted successfully';
        header('Location: users.php');
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get all users
$users = $user->getAllUsers();



require_once __DIR__ . '/includes/header.php';
?>

<div class="content-header">
    <h2>Users Management</h2>
    <a href="create-user.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New User
    </a>
</div>

<div class="card">
    <?php if (isset($error)): ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert" style="background: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
            <?php 
            echo htmlspecialchars($_SESSION['success']); 
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                <th style="padding: 12px; text-align: left;">ID</th>
                <th style="padding: 12px; text-align: left;">Username</th>
                <th style="padding: 12px; text-align: left;">Email</th>
                <th style="padding: 12px; text-align: left;">Role</th>
                <th style="padding: 12px; text-align: left;">Created At</th>
                <th style="padding: 12px; text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 12px;"><?php echo htmlspecialchars($user['id']); ?></td>
                    <td style="padding: 12px;"><?php echo htmlspecialchars($user['username']); ?></td>
                    <td style="padding: 12px;"><?php echo htmlspecialchars($user['email']); ?></td>
                    <td style="padding: 12px;">
                        <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; background: <?php echo $user['role'] === 'admin' ? '#e3f2fd' : '#e8f5e9'; ?>; color: <?php echo $user['role'] === 'admin' ? '#0d47a1' : '#2e7d32'; ?>; font-weight: 500;">
                            <?php echo ucfirst(htmlspecialchars($user['role'])); ?>
                        </span>
                    </td>
                    <td style="padding: 12px;"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                    <td style="padding: 12px; text-align: right;">
                        <a href="edit-user.php?id=<?php echo $user['id']; ?>" class="btn" style="padding: 4px 8px; background: #ffc107; color: #000; margin-right: 5px;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <?php if ($_SESSION['user']['id'] != $user['id']): ?>
                            <form method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" class="btn btn-danger" style="padding: 4px 8px;">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
