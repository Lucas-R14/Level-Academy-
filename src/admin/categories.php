<?php
session_start();
require_once '../config/config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


require_once 'includes/header.php';
require_once dirname(__FILE__) . '/../Controllers/CategoryController.php';

// Initialize CategoryController
$categoryController = new CategoryController(getPDO());

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'add':
                    if (isset($_POST['name'])) {
                        $categoryController->create($_POST['name']);
                        $success = 'Category added successfully!';
                    }
                    break;

                case 'edit':
                    if (isset($_POST['id']) && isset($_POST['name'])) {
                        $categoryController->update($_POST['id'], $_POST['name']);
                        $success = 'Category updated successfully!';
                    }
                    break;

                case 'delete':
                    if (isset($_POST['id'])) {
                        $categoryController->delete($_POST['id']);
                        $success = 'Category deleted successfully!';
                    }
                    break;
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get all categories
$categories = $categoryController->getAll();
?>

<div class="content-header">
    <h2>Categories Management</h2>
    <div class="actions">
        <button class="btn btn-primary" onclick="openAddModal()">Add New Category</button>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if (isset($success)): ?>
    <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<!-- Add/Edit Category Form -->
<div id="categoryForm" class="modal" style="display: none;">
    <div class="modal-content">
        <h3>Add/Edit Category</h3>
        <form method="POST" onsubmit="document.getElementById('categoryForm').style.display = 'none';">
            <input type="hidden" name="action" value="<?php echo isset($_POST['id']) ? 'edit' : 'add'; ?>">
            <input type="hidden" name="id" value="<?php echo isset($_POST['id']) ? htmlspecialchars($_POST['id']) : ''; ?>">
            
            <div class="form-group">
                <label for="name">Category Name:</label>
                <input type="text" id="name" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
            </div>
            
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Edit Category</h2>
        <form id="editCategoryForm" method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="editCategoryId">
            <div class="form-group">
                <label for="editCategoryName">Category Name</label>
                <input type="text" id="editCategoryName" name="name" required class="form-control">
            </div>
            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Category Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddModal()">&times;</span>
        <h2>Add Category</h2>
        <form id="addCategoryForm" method="POST">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="addCategoryName">Category Name</label>
                <input type="text" id="addCategoryName" name="name" required class="form-control">
            </div>
            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">Add Category</button>
                <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>


<!-- Categories List -->
<div class="card">
    <h3>Categories</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?php echo htmlspecialchars($category['id']); ?></td>
                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                    <td class="actions-cell">
                        <button class="btn btn-edit" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($category)); ?>)" title="Edit Category">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($category['id']); ?>">
                            <button type="submit" class="btn btn-delete" title="Delete Category">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
// Add Category Modal functions
function openAddModal() {
    document.getElementById('addModal').style.display = 'block';
}

function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
    document.getElementById('addCategoryForm').reset();
}

// Edit Category Modal functions
function openEditModal(category) {
    document.getElementById('editModal').style.display = 'block';
    document.getElementById('editCategoryId').value = category.id;
    document.getElementById('editCategoryName').value = category.name;
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
    document.getElementById('editCategoryForm').reset();
}

// Close modals when clicking outside
window.onclick = function(event) {
    var editModal = document.getElementById('editModal');
    var addModal = document.getElementById('addModal');
    
    if (event.target == editModal) {
        closeEditModal();
    } else if (event.target == addModal) {
        closeAddModal();
    }
}

// Close modals when pressing Esc key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeEditModal();
        closeAddModal();
    }
});
</script>

<style>
.actions-cell {
    white-space: nowrap;
}

.btn {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    margin-right: 5px;
}

.btn i {
    font-size: 14px;
}

.btn-edit {
    background-color: #17a2b8;
    color: white;
    border: 1px solid #17a2b8;
}

.btn-edit:hover {
    background-color: #138496;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.btn-delete {
    background-color: #dc3545;
    color: white;
    border: 1px solid #dc3545;
}

.btn-delete:hover {
    background-color: #c82333;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 1000;
}

.modal-content {
    background-color: white;
    margin: 50px auto;
    padding: 20px;
    width: 50%;
    max-width: 500px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card {
    background: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.table th, .table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.table th {
    background-color: #f5f5f5;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-right: 5px;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
</style>
