<?php
session_start();
require_once '../config/config.php';
require_once '../Controllers/ArticleController.php';
require_once '../Controllers/CategoryController.php';
require_once '../Controllers/User.php';

$user = new User(getPDO());

// Ensure user is logged in and is admin
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    $_SESSION['error'] = 'You do not have permission to perform this action';
    header('Location: login.php');
    exit();
}
// Initialize controllers
$articleController = new ArticleController(getPDO());
$categoryController = new CategoryController(getPDO());

// Get article ID from URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: articles.php');
    exit;
}

// Get article data
$articleData = $articleController->getId($id);
if (!$articleData) {
    header('Location: articles.php');
    exit;
}

$decodedContent = html_entity_decode($articleData['content']);

// Get all categories
$categories = $categoryController->getAll();

// Get all images from Uploads directory
function getUploadedImages() {
    $upload_dir = '../../public/assets/images/uploads/articles/';
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (!is_dir($upload_dir)) {
        return [];
    }

    $images = [];
    $files = scandir($upload_dir);
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($extension, $allowed_extensions)) {
            $images[] = 'assets/images/uploads/articles/' . $file;
        }
    }
    
    return $images;
}

$uploaded_images = getUploadedImages();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article_submit'])) {
    try {
        // Sanitize inputs
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $content = $_POST['content'] ?? '';
        $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
        $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);

        // Update article
        $articleController->update($id, htmlspecialchars($title), nl2br(htmlspecialchars($content)), htmlspecialchars($author), $category);
        header('Location: articles.php?success=Article updated successfully!');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Include header after form handling
ob_start();
require_once 'includes/header.php';
?>

<div class="content-header">
    <h2>Edit Article</h2>
    <div class="actions">
        <a href="articles.php" class="btn btn-secondary">Back to Articles</a>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="article-form-container">
    <div class="form-wrapper">
        <form class="main-form" method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required class="form-control" value="<?php echo htmlspecialchars($articleData['title']); ?>">
            </div>

            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" required class="form-control" value="<?php echo htmlspecialchars($articleData['author']); ?>">
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required class="form-control">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>"
                            <?php echo $category['id'] == $articleData['Category'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <div class="content-editor" id="content-editor">
                    <div class="editor-toolbar">
                        <button type="button" class="toolbar-btn" data-action="bold"><i class="fas fa-bold"></i></button>
                        <button type="button" class="toolbar-btn" data-action="italic"><i class="fas fa-italic"></i></button>
                        <button type="button" class="toolbar-btn" data-action="link"><i class="fas fa-link"></i></button>
                        <button type="button" class="toolbar-btn" data-action="image"><i class="fas fa-image"></i></button>
                    </div>
                    <div id="editor-content" contenteditable="true" class="editor-content">
                        <?php echo $decodedContent; ?>
                    </div>
                    <input type="hidden" id="content-value" name="content" value="<?php echo htmlspecialchars($decodedContent); ?>">
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary" name="article_submit">Update Article</button>
            </div>
        </form>
    </div>
    <div class="image-section">
        <div class="image-upload-container">
            <div class="form-group">
                <label for="image" class="upload-label">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span>Carregar Imagem</span>
                    <input type="file" id="image" name="image" accept="image/*" class="form-control-file hidden-file-input">
                </label>
                <div class="upload-progress-container" id="upload-progress-container">
                    <div class="upload-progress" id="upload-progress"></div>
                </div>
            </div>
        </div>

        <div class="image-gallery">
            <h3>Imagens Carregadas</h3>
            <div class="gallery-container" id="image-gallery">
                <?php foreach ($uploaded_images as $image): ?>
                    <div class="image-item">
                        <img src="/Level-Academy-/public/<?php echo htmlspecialchars($image); ?>" alt="Uploaded Image">
                        <button type="button" class="delete-image-btn" data-image="<?php echo htmlspecialchars($image); ?>">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
.article-form-container {
    display: flex;
    gap: 20px;
    margin: 20px;
    max-width: 1200px;
    width: 100%;
}

.form-wrapper {
    flex: 7;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.image-upload-container {
    background: #f9f9f9;
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    margin-bottom: 20px;
    transition: all 0.3s;
}

.image-upload-container:hover {
    border-color: #007bff;
}

.upload-label {
    display: block;
    cursor: pointer;
    padding: 15px;
    font-size: 16px;
    color: #6c757d;
    transition: color 0.3s;
}

.upload-label:hover {
    color: #007bff;
}

.upload-label i {
    font-size: 32px;
    display: block;
    margin-bottom: 10px;
}

.hidden-file-input {
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: -1;
}

.upload-progress-container {
    height: 5px;
    width: 100%;
    background-color: #f0f0f0;
    border-radius: 3px;
    margin-top: 15px;
    overflow: hidden;
    display: none;
}

.upload-progress {
    height: 100%;
    width: 0;
    background-color: #007bff;
    transition: width 0.3s;
}

.dragover {
    border-color: #007bff;
    background-color: rgba(0, 123, 255, 0.05);
}
.image-section {
    flex: 3;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.gallery-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 10px;
    margin-top: 20px;
}

.image-item {
    position: relative;
    cursor: default;
    padding: 10px;
    border-radius: 4px;
    transition: all 0.2s;
}

.image-item img {
    width: 100%;
    height: auto;
    border-radius: 4px;
    object-fit: cover;
}

.delete-image-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(255, 0, 0, 0.7);
    border: none;
    color: white;
    padding: 5px 8px;
    border-radius: 4px;
    cursor: pointer;
    transition: opacity 0.2s;
}

.delete-image-btn:hover {
    opacity: 0.9;
}

.btn {
    background-color: #007bff;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.btn:hover {
    background-color: #0056b3;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

/* Editor styles */
.content-editor {
    position: relative;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
}

.editor-toolbar {
    display: flex;
    gap: 8px;
    margin-bottom: 10px;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 4px;
}

.toolbar-btn {
    background: none;
    border: none;
    padding: 8px;
    color: #6c757d;
    cursor: pointer;
    transition: color 0.2s;
}

.toolbar-btn:hover {
    color: #007bff;
}

.editor-content {
    min-height: 200px;
    padding: 10px;
    outline: none;
    position: relative;
}

.editor-content::before {
    content: attr(placeholder);
    color: #6c757d;
    position: absolute;
    left: 10px;
    top: 10px;
    pointer-events: none;
}

.editor-content:not(:empty)::before {
    display: none;
}

.editor-content img {
    max-width: 100%;
    height: auto;
    margin: 10px 0;
}

.dragged-image {
    opacity: 0.5;
    transform: scale(0.95);
}

.drag-handle {
    position: absolute;
    top: 5px;
    left: 5px;
    background: rgba(0, 123, 255, 0.1);
    border-radius: 4px;
    padding: 5px;
    cursor: move;
}

.drag-handle i {
    color: #007bff;
}

.editor-content.dragover {
    border: 2px dashed #007bff;
    background: rgba(0, 123, 255, 0.05);
}

/* Add some spacing between form elements */
.form-group label {
    display: block;
    margin-bottom: 5px;
}

@keyframes fadeInScale {
    0% {
        opacity: 0;
        transform: scale(0.8);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes fadeOutScale {
    0% {
        opacity: 1;
        transform: scale(1);
    }
    100% {
        opacity: 0;
        transform: scale(0.8);
    }
}

.new-image-animation {
    animation: fadeInScale 0.3s ease-in-out;
}

.remove-image-animation {
    animation: fadeOutScale 0.3s ease-in-out;
}

.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 4px;
    color: white;
    z-index: 1000;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    display: none;
}

.notification.success {
    background-color: #28a745;
}

.notification.error {
    background-color: #dc3545;
}

.notification.info {
    background-color: #17a2b8;
}

.notification.warning {
    background-color: #ffc107;
    color: #333;
}

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

.loading-image {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100px;
    background-color: #f8f9fa;
    border-radius: 4px;
    animation: pulse 1.5s ease-in-out;
    animation-iteration-count: 4; /* Limita a 4 repetições (2 segundos) */
}

.image-loader {
    font-size: 20px;
    color: #007bff;
}

/* Animação de pulsação para o carregador */
@keyframes pulse {
    0% {
        transform: scale(0.95);
        opacity: 0.7;
    }
    50% {
        transform: scale(1.05);
        opacity: 1;
    }
    100% {
        transform: scale(0.95);
        opacity: 0.7;
    }
}

.gallery-loading, .no-images-message, .gallery-error {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 100%;
    min-height: 150px;
    color: #6c757d;
    text-align: center;
    padding: 20px;
}

.gallery-loading i, .no-images-message i, .gallery-error i {
    font-size: 32px;
    margin-bottom: 10px;
}

.no-images-message {
    color: #6c757d;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 2px dashed #dee2e6;
}

.gallery-error {
    color: #721c24;
    background-color: #f8d7da;
    border-radius: 8px;
    border: 1px solid #f5c6cb;
}

.gallery-loading {
    background-color: #f8f9fa;
    border-radius: 8px;
}

/* Estilos para o seletor de imagens */
.image-selector-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.image-selector-content {
    background-color: white;
    border-radius: 8px;
    width: 80%;
    max-width: 800px;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
}

.image-selector-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #ddd;
}

.image-selector-header h3 {
    margin: 0;
}

.close-modal-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #6c757d;
}

.image-selector-body {
    padding: 20px;
    overflow-y: auto;
    flex-grow: 1;
}

.image-selector-footer {
    display: flex;
    justify-content: flex-end;
    padding: 15px 20px;
    border-top: 1px solid #ddd;
}

.image-selector-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
}

.selector-image-item {
    cursor: pointer;
    border-radius: 4px;
    overflow: hidden;
    transition: transform 0.2s;
    border: 2px solid transparent;
}

.selector-image-item:hover {
    transform: scale(1.05);
    border-color: #007bff;
}

.selector-image-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set up file input change event
    const fileInput = document.getElementById('image');
    const uploadContainer = document.querySelector('.image-upload-container');
    const progressContainer = document.getElementById('upload-progress-container');
    const progressBar = document.getElementById('upload-progress');
    
    // Obter o ID do artigo
    const articleId = '<?php echo $id; ?>';
    
    // Carregar a galeria ao iniciar
    loadImageGallery();
    
    // Add drag and drop functionality
    uploadContainer.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    
    uploadContainer.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    
    uploadContainer.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            handleFileUpload(e.dataTransfer.files[0]);
        }
    });
    
    // Handle file selection
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            handleFileUpload(this.files[0]);
        }
    });
    
    // Function to handle file upload
    function handleFileUpload(file) {
        // Show progress container
        progressContainer.style.display = 'block';
        progressBar.style.width = '0%';
        
        const formData = new FormData();
        formData.append('image', file);
        
        // Adicionar o parâmetro type=articles ao FormData
        formData.append('type', 'articles');
        
        // Create XMLHttpRequest for progress monitoring
        const xhr = new XMLHttpRequest();
        
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                progressBar.style.width = percentComplete + '%';
            }
        });
        
        xhr.addEventListener('load', function() {
            if (xhr.status === 200) {
                try {
                    const result = JSON.parse(xhr.responseText);
                    if (result.success) {
                        // Manter a barra de progresso a 100% durante 2 segundos
                        progressBar.style.width = '100%';
                        
                        // Mostrar mensagem de processamento
                        showNotification('A processar imagem...', 'info');
                        
                        // Adicionar um elemento de carregamento temporário à galeria
                        const gallery = document.getElementById('image-gallery');
                        const loadingItem = document.createElement('div');
                        loadingItem.className = 'image-item loading-image';
                        loadingItem.id = 'temp-loading-item';
                        loadingItem.innerHTML = `
                            <div class="image-loader">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        `;
                        gallery.appendChild(loadingItem);
                        
                        // Esperar 2 segundos e depois recarregar a galeria do servidor
                        setTimeout(() => {
                            // Esconder a notificação de processamento
                            hideNotification();
                            
                            // Atualizar a galeria via AJAX
                            loadImageGallery();
                            
                            // Reset progress
                            progressContainer.style.display = 'none';
                            progressBar.style.width = '0%';
                            
                            // Show success notification
                            showNotification('Imagem carregada com sucesso!', 'success');
                        }, 2000); // Atraso de 2 segundos
                    } else {
                        showNotification(result.message || 'Erro ao carregar a imagem', 'error');
                        progressContainer.style.display = 'none';
                    }
                } catch (error) {
                    showNotification('Erro ao processar a resposta do servidor', 'error');
                    progressContainer.style.display = 'none';
                }
            } else {
                showNotification('Erro do servidor: ' + xhr.status, 'error');
                progressContainer.style.display = 'none';
            }
        });
        
        xhr.addEventListener('error', function() {
            showNotification('Erro de rede ao carregar a imagem', 'error');
            progressContainer.style.display = 'none';
        });
        
        // Send the request
        xhr.open('POST', '../controllers/upload-image.php', true);
        xhr.send(formData);
    }
    
    // Função para carregar a galeria de imagens do servidor via AJAX
    function loadImageGallery() {
        // Mostrar um indicador de carregamento na galeria
        const gallery = document.getElementById('image-gallery');
        gallery.innerHTML = `
            <div class="gallery-loading">
                <i class="fas fa-spinner fa-spin"></i>
                <span>A carregar imagens...</span>
            </div>
        `;
        
        // Fazer uma chamada AJAX para obter a lista de imagens com o parâmetro type=articles
        fetch('../controllers/get-images.php?type=articles')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao obter a lista de imagens');
                }
                return response.json();
            })
            .then(data => {
                // Limpar a galeria
                gallery.innerHTML = '';
                
                // Verificar se há imagens para mostrar
                if (data.images && data.images.length > 0) {
                    // Adicionar cada imagem à galeria
                    data.images.forEach(image => {
                        const imageItem = document.createElement('div');
                        imageItem.className = 'image-item';
                        
                        imageItem.innerHTML = `
                            <img src="${image.path}" alt="${image.name || 'Article Image'}" onerror="this.onerror=null; this.src='assets/images/error-placeholder.png';">
                            <button type="button" class="delete-image-btn" data-image="${image.id || image.path}">
                                <i class="fas fa-trash"></i>
                            </button>
                        `;
                        
                        gallery.appendChild(imageItem);
                    });
                } else {
                    // Mostrar mensagem se não houver imagens
                    gallery.innerHTML = `
                        <div class="no-images-message">
                            <i class="fas fa-images"></i>
                            <p>Nenhuma imagem carregada</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Erro ao carregar imagens:', error);
                gallery.innerHTML = `
                    <div class="gallery-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Erro ao carregar imagens</p>
                    </div>
                `;
            });
    }
    
    // Function to show notification
    function showNotification(message, type) {
        // Create notification element if it doesn't exist
        let notification = document.getElementById('notification');
        if (!notification) {
            notification = document.createElement('div');
            notification.id = 'notification';
            document.body.appendChild(notification);
        }
        
        // Set message and type
        notification.textContent = message;
        notification.className = 'notification ' + type;
        
        // Show notification
        notification.style.display = 'block';
        
        // Hide notification after 3 seconds for success/error/warning
        if (type !== 'info') {
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }
    }
    
    // Function to hide notifications
    function hideNotification() {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.display = 'none';
        }
    }
    
    // Handle image deletion - delegação de eventos
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-image-btn') || 
            e.target.closest('.delete-image-btn')) {
            
            const btn = e.target.classList.contains('delete-image-btn') ? 
                e.target : e.target.closest('.delete-image-btn');
            
            if (confirm('Tem a certeza que deseja eliminar esta imagem?')) {
                const imageItem = btn.closest('.image-item');
                const imageId = btn.dataset.image;
                
                // Add remove animation
                imageItem.classList.add('remove-image-animation');
                
                // Remove from server - Corrigido para usar 'type' em vez de 'directory'
                fetch('../controllers/delete-image.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ image: imageId, type: 'articles' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove from gallery after animation
                        setTimeout(() => {
                            imageItem.remove();
                        }, 300);
                        
                        showNotification('Imagem eliminada com sucesso', 'success');
                        
                        // Se a galeria ficar vazia, atualizar para mostrar a mensagem
                        if (document.querySelectorAll('.image-item').length <= 1) {
                            setTimeout(() => {
                                loadImageGallery();
                            }, 500);
                        }
                    } else {
                        showNotification(data.error || data.message || 'Erro ao eliminar a imagem', 'error');
                        imageItem.classList.remove('remove-image-animation');
                    }
                })
                .catch(error => {
                    console.error('Erro ao eliminar imagem:', error);
                    showNotification('Erro de rede ao eliminar a imagem', 'error');
                    imageItem.classList.remove('remove-image-animation');
                });
            }
        }
    });
    
    // Initialize editor
    const editorContent = document.getElementById('editor-content');
    const contentValue = document.getElementById('content-value');
    
    // Update hidden input value when content changes
    editorContent.addEventListener('input', function() {
        contentValue.value = this.innerHTML;
    });

    // Initialize the content value with the current content
    contentValue.value = editorContent.innerHTML;

    // Add toolbar functionality
    const toolbarBtns = document.querySelectorAll('.toolbar-btn');
    toolbarBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.dataset.action;
            
            if (action === 'bold') {
                document.execCommand('bold', false, null);
            } else if (action === 'italic') {
                document.execCommand('italic', false, null);
            } else if (action === 'link') {
                const url = prompt('Enter URL:');
                if (url) {
                    document.execCommand('createLink', false, url);
                }
            } else if (action === 'image') {
                // Mostrar a galeria de imagens para selecionar
                showImageSelector();
            }
            
            contentValue.value = editorContent.innerHTML;
        });
    });
    
    // Função para mostrar um seletor de imagens
    function showImageSelector() {
        // Criar o modal de seleção de imagens
        const modal = document.createElement('div');
        modal.className = 'image-selector-modal';
        modal.innerHTML = `
            <div class="image-selector-content">
                <div class="image-selector-header">
                    <h3>Selecionar Imagem</h3>
                    <button type="button" class="close-modal-btn">&times;</button>
                </div>
                <div class="image-selector-body">
                    <div class="image-selector-loading">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>A carregar imagens...</span>
                    </div>
                </div>
                <div class="image-selector-footer">
                    <button type="button" class="btn btn-secondary close-btn">Cancelar</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Fechar o modal
        modal.querySelector('.close-modal-btn').addEventListener('click', function() {
            modal.remove();
        });
        
        modal.querySelector('.close-btn').addEventListener('click', function() {
            modal.remove();
        });
        
        // Clicar fora do modal para fechar
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
            }
        });
        
        // Carregar imagens para o seletor
        fetch('../controllers/get-images.php?type=articles')
            .then(response => response.json())
            .then(data => {
                const selectorBody = modal.querySelector('.image-selector-body');
                selectorBody.innerHTML = '';
                
                if (data.images && data.images.length > 0) {
                    const grid = document.createElement('div');
                    grid.className = 'image-selector-grid';
                    
                    data.images.forEach(image => {
                        const imageItem = document.createElement('div');
                        imageItem.className = 'selector-image-item';
                        imageItem.innerHTML = `
                            <img src="${image.path}" alt="${image.name || 'Image'}" onerror="this.onerror=null; this.src='assets/images/error-placeholder.png';">
                        `;
                        
                        // Adicionar a imagem ao editor quando clicada
                        imageItem.addEventListener('click', function() {
                            const img = document.createElement('img');
                            img.src = image.path;
                            img.alt = image.name || 'Article Image';
                            img.style.maxWidth = '100%';
                            
                            // Obter a seleção atual do editor
                            const selection = window.getSelection();
                            if (selection.rangeCount > 0) {
                                const range = selection.getRangeAt(0);
                                range.deleteContents();
                                range.insertNode(img);
                                
                                // Atualizar o valor do input escondido
                                contentValue.value = editorContent.innerHTML;
                                
                                // Fechar o modal
                                modal.remove();
                            }
                        });
                        
                        grid.appendChild(imageItem);
                    });
                    
                    selectorBody.appendChild(grid);
                } else {
                    selectorBody.innerHTML = `
                        <div class="no-images-message">
                            <i class="fas fa-images"></i>
                            <p>Nenhuma imagem disponível</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Erro ao carregar imagens:', error);
                modal.querySelector('.image-selector-body').innerHTML = `
                    <div class="gallery-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Erro ao carregar imagens</p>
                    </div>
                `;
            });
    }
});
</script>