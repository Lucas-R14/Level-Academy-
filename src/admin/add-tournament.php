<?php
session_start();
require_once '../config/config.php';
require_once dirname(__FILE__) . '/../Controllers/TournamentController.php';

// Initialize TournamentController
$tournamentController = new TournamentController(getPDO());

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate and sanitize input
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $format = filter_input(INPUT_POST, 'format', FILTER_SANITIZE_STRING);
        $event_date = filter_input(INPUT_POST, 'event_date', FILTER_SANITIZE_STRING);
        $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
        $prize = filter_input(INPUT_POST, 'prize', FILTER_SANITIZE_NUMBER_INT);
        $entry_fee = filter_input(INPUT_POST, 'entry_fee', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $registration_link = filter_input(INPUT_POST, 'registration_link', FILTER_SANITIZE_URL);
        $image_path = null;

        // Handle image upload
        if (isset($_FILES['tournament_image']) && $_FILES['tournament_image']['error'] === 0) {
            $file = $_FILES['tournament_image'];
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($file_extension, $allowed_extensions)) {
                throw new Exception('Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.');
            }

            // Method 1: Direct upload without curl
            $upload_dir = "../../public/assets/images/uploads/tournaments/";
            
            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $image_name = uniqid() . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $image_name;

            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $image_path = "assets/images/uploads/tournaments/" . $image_name;
            } else {
                throw new Exception('Failed to upload image.');
            }
        }

        // Check if all required fields are filled
        if (!$title || !$format || !$event_date || !$location || !$prize || !$entry_fee || !$registration_link) {
            throw new Exception('Please fill in all required fields');
        }

        // Create tournament
        $tournamentController->create([
            'title' => $title,
            'format' => $format,
            'event_date' => $event_date,
            'location' => $location,
            'prize' => $prize,
            'entry_fee' => $entry_fee,
            'registration_link' => $registration_link,
            'image_path' => $image_path
        ]);

        // Redirect to tournaments page with success message
        header('Location: tournaments.php?success=Tournament created successfully!');
        exit;

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Rest of the code remains unchanged
// Include header after form handling
ob_start();
require_once 'includes/header.php';
?>

<div class="content-header">
    <h2>Add New Tournament</h2>
    <div class="actions">
        <a href="tournaments.php" class="btn btn-secondary">Back to Tournaments</a>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required class="form-control" placeholder="Enter tournament title">
        </div>
        <div class="form-group">
            <label for="format">Format</label>
            <input type="text" id="format" name="format" required class="form-control" placeholder="e.g., Solo, Team">
        </div>
        <div class="form-group">
            <label for="event_date">Event Date</label>
            <input type="date" id="event_date" name="event_date" required class="form-control">
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" required class="form-control" placeholder="Enter location">
        </div>
        <div class="form-group">
            <label for="prize">Prize</label>
            <select id="prize" name="prize" required class="form-control">
                <option value="0">No Prize</option>
                <option value="1">Prize Available</option>
            </select>
        </div>
        <div class="form-group">
            <label for="entry_fee">Entry Fee</label>
            <input type="number" id="entry_fee" name="entry_fee" step="0.01" required class="form-control" placeholder="0.00">
        </div>
        <div class="form-group">
            <label for="registration_link">Registration Link</label>
            <input type="url" id="registration_link" name="registration_link" required class="form-control" placeholder="https://">
        </div>
        <div class="form-group">
            <label for="tournament_image">Tournament Image</label>
            <div class="image-upload-container">
                <input type="file" id="tournament_image" name="tournament_image" class="form-control" accept="image/jpeg,image/png,image/gif">
                <div id="image-preview" class="image-preview">
                    <img id="preview-img" src="" alt="Preview" style="display: none; max-width: 200px; max-height: 200px;">
                </div>
                <script>
                    // Image preview functionality
                    document.getElementById('tournament_image').addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const previewImg = document.getElementById('preview-img');
                                previewImg.src = e.target.result;
                                previewImg.style.display = 'block';
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                </script>
            </div>
        </div>
        <div class="form-buttons">
            <button type="submit" class="btn btn-primary">Create Tournament</button>
            <a href="tournaments.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<style>
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.form-group {
    margin-bottom: 15px;
}

.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-buttons {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.image-upload-container {
    position: relative;
}

.image-preview {
    margin-top: 10px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

#preview-img {
    width: auto;
    height: auto;
    max-width: 200px;
    max-height: 200px;
    object-fit: contain;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

/* Add some spacing between form elements */
.form-group label {
    display: block;
    margin-bottom: 5px;
}
</style>

<?php
// Close PHP tag at the end of the file
?>
