<?php
session_start();
require_once '../config/config.php';
require_once '../Controllers/User.php';


$user = new User(getPDO());

// Ensure user is logged in and is admin
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    $_SESSION['error'] = 'You do not have permission to perform this action';
    header('Location: login.php');
    exit();
}


require_once '../Controllers/TournamentController.php';

// Initialize TournamentController
$tournamentController = new TournamentController(getPDO());

$tournamentId = $_GET['id'];

// Get tournament data
try {
    $tournament = $tournamentController->get($tournamentId);
    if (!$tournament) {
        header('Location: tournaments.php?error=Tournament not found');
        exit;
    }
} catch (Exception $e) {
    header('Location: tournaments.php?error=' . urlencode($e->getMessage()));
    exit;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['title'], $_POST['format'], $_POST['event_date'], $_POST['location'], $_POST['prize'], $_POST['entry_fee'], $_POST['registration_link'])) {
            // Handle image upload
            $image_path = $tournament['image_path']; // Keep existing image path
            
            if (isset($_FILES['tournament_image']) && $_FILES['tournament_image']['error'] === 0) {
                $file = $_FILES['tournament_image'];
                $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($file_extension, $allowed_extensions)) {
                    throw new Exception('Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.');
                }

                // Direct upload without curl
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

            $tournamentController->update($tournamentId, [
                'title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
                'format' => filter_input(INPUT_POST, 'format', FILTER_SANITIZE_STRING),
                'event_date' => filter_input(INPUT_POST, 'event_date', FILTER_SANITIZE_STRING),
                'start_time' => filter_input(INPUT_POST, 'start_time', FILTER_SANITIZE_STRING),
                'location' => filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING),
                'prize' => filter_input(INPUT_POST, 'prize', FILTER_SANITIZE_STRING),
                'entry_fee' => filter_input(INPUT_POST, 'entry_fee', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                'registration_link' => filter_input(INPUT_POST, 'registration_link', FILTER_SANITIZE_URL),
                'image_path' => $image_path
            ]);
            header('Location: tournaments.php?success=Tournament updated successfully!');
            exit;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}


require_once 'includes/header.php';
?>

<div class="content-header">
    <h2>Edit Tournament</h2>
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
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($tournament['title']); ?>" required class="form-control">
        </div>
        <div class="form-group">
            <label for="format">Format</label>
            <input type="text" id="format" name="format" value="<?php echo htmlspecialchars($tournament['Format']); ?>" required class="form-control">
        </div>
        <div class="form-group">
            <label for="event_date">Event Date</label>
            <input type="date" id="event_date" name="event_date" value="<?php echo htmlspecialchars($tournament['event_date']); ?>" required class="form-control">
        </div>
        <div class="form-group">
            <label for="start_time ">Start Time (24-hour format)</label>
            <?php 
            // Extract just the time part from the database value (HH:MM:SS)
            $timeValue = '';
            if (!empty($tournament['start_time'])) {
                $timeParts = explode(' ', $tournament['start_time']);
                $timeValue = end($timeParts); // Get the last part in case it includes date
                $timeValue = substr($timeValue, 0, 5); // Get just HH:MM
            }
            ?>
            <input type="time" id="start_time" name="start_time" step="1" value="<?php echo htmlspecialchars($timeValue); ?>" class="form-control">
            <input type="hidden" id="formatted_start_time" name="formatted_start_time">
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($tournament['location']); ?>" required class="form-control">
        </div>
        <div class="form-group">
            <label for="prize">Prize</label>
            <select id="prize" name="prize" required class="form-control">
                <option value="0" <?php echo $tournament['prize'] == 0 ? 'selected' : ''; ?>>No Prize</option>
                <option value="1" <?php echo $tournament['prize'] == 1 ? 'selected' : ''; ?>>Prize Available</option>
            </select>
        </div>
        <div class="form-group">
            <label for="entry_fee">Entry Fee</label>
            <input type="number" id="entry_fee" name="entry_fee" step="0.01" value="<?php echo htmlspecialchars($tournament['entry_fee']); ?>" required class="form-control">
        </div>
        <div class="form-group">
            <label for="registration_link">Registration Link</label>
            <input type="url" id="registration_link" name="registration_link" value="<?php echo htmlspecialchars($tournament['registration_link']); ?>" required class="form-control">
        </div>
        <div class="form-group">
            <label for="tournament_image">Tournament Image</label>
            <div class="image-upload-container" id="drop-zone">
                <div class="drop-zone-content">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Drag & drop your image here or click to browse</p>
                    <input type="file" id="tournament_image" name="tournament_image" class="file-input" accept="image/jpeg,image/png,image/gif">
                </div>
                <?php if (!empty($tournament['image_path'])): ?>
                    <div class="current-image">
                        <img src="/Level-Academy-/public/<?php echo htmlspecialchars($tournament['image_path']); ?>" alt="Current Image" id="current-image-preview">
                        <p>Current Image</p>
                    </div>
                <?php endif; ?>
                <div id="preview-container" class="preview-container" style="display: none;">
                    <img id="image-preview" src="" alt="Preview">
                    <button type="button" id="remove-image" class="btn btn-danger btn-sm">Remove</button>
                </div>
            </div>
        </div>
        <div class="form-buttons">
            <button type="submit" class="btn btn-primary">Save Changes</button>
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

/* Image Upload Styles */
.image-upload-container {
    border: 2px dashed #ccc;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    min-height: 150px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.image-upload-container.dragover {
    border-color: #666;
    background-color: #f8f9fa;
}

.drop-zone-content {
    text-align: center;
    color: #666;
}

.drop-zone-content i {
    font-size: 48px;
    margin-bottom: 10px;
    color: #6c757d;
}

.drop-zone-content p {
    margin: 10px 0;
}

.file-input {
    display: none;
}

.preview-container {
    margin-top: 15px;
    text-align: center;
}

#image-preview {
    max-width: 100%;
    max-height: 200px;
    border-radius: 4px;
    margin-bottom: 10px;
}

.current-image {
    margin-top: 15px;
    text-align: center;
}

.current-image img {
    max-width: 200px;
    max-height: 200px;
    object-fit: contain;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.btn-danger {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
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

.image-upload-container {
    position: relative;
}

.image-preview {
    margin-top: 10px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.current-image {
    margin-top: 10px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
}

.current-image img {
    max-width: 200px;
    max-height: 200px;
    object-fit: contain;
}

#preview-img {
    width: auto;
    height: auto;
    max-width: 200px;
    max-height: 200px;
    object-fit: contain;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('tournament_image');
    const previewContainer = document.getElementById('preview-container');
    const imagePreview = document.getElementById('image-preview');
    const removeButton = document.getElementById('remove-image');
    const currentImage = document.getElementById('current-image-preview');
    const dropZoneContent = dropZone.querySelector('.drop-zone-content');

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    // Highlight drop zone when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop, false);
    
    // Handle click on drop zone
    dropZone.addEventListener('click', () => {
        fileInput.click();
    });

    // Handle file selection via input
    fileInput.addEventListener('change', handleFiles, false);
    
    // Handle remove image button
    if (removeButton) {
        removeButton.addEventListener('click', resetFileInput);
    }

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight() {
        dropZone.classList.add('dragover');
    }

    function unhighlight() {
        dropZone.classList.remove('dragover');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles({ target: { files } });
    }

    function handleFiles(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Check if the file is an image
        if (!file.type.match('image.*')) {
            alert('Please select an image file (JPEG, PNG, or GIF)');
            return;
        }

        // Create a preview of the image
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            previewContainer.style.display = 'block';
            dropZoneContent.style.display = 'none';
            
            // Hide current image if exists
            if (currentImage) {
                currentImage.parentElement.style.display = 'none';
            }
        };
        reader.readAsDataURL(file);
    }

    function resetFileInput() {
        fileInput.value = '';
        previewContainer.style.display = 'none';
        dropZoneContent.style.display = 'flex';
        
        // Show current image if exists
        if (currentImage) {
            currentImage.parentElement.style.display = 'block';
        }
    }

    // Handle form submission
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Ensure the file input is included in the form data
        if (fileInput.files.length > 0) {
            // File is already attached to the input, let it submit normally
            return true;
        } else if (previewContainer.style.display === 'block') {
            // If there's a preview but no file in the input (can happen with drag & drop)
            e.preventDefault();
            alert('Please select the image again before submitting');
            return false;
        }
        // If no new image is being uploaded, let the form submit normally
        return true;
    });
});
</script>

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

</body>
</html>
<?php
// Close PHP tag at the end of the file
?>