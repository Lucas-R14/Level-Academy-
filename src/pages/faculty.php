<?php
// Incluindo o header
include '../components/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Faculty - Level Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;800&family=Exo+2:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/style.css">
    <link rel="stylesheet" href="../../public/assets/faculty.css">
    <link rel="stylesheet" href="../../public/assets/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        .faculty-main-wrapper {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 3rem;
            width: 100%;
            max-width: 1600px;
            margin: 0 auto;
            min-height: 80vh;
        }
        @media (max-width: 1100px) {
            .faculty-main-wrapper {
                flex-direction: column;
                align-items: stretch;
                gap: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="faculty-main-wrapper">
        <section class="faculty-section">
            <h1 class="faculty-title">Faculty</h1>
            <div class="faculty-container">
                <div class="faculty-member fade-in">
                    <div class="faculty-image">
                        <img src="../../public/assets/images/faculty/kevin-spiteri-1.jpg" alt="Kevin Spiteri">
                    </div>
                    <div class="faculty-name">Kevin Spiteri</div>
                    <div class="faculty-role">Director</div>
                    <a href="https://www.linkedin.com/in/spiterikevin/?originalSubdomain=mt" class="linkedin-btn" target="_blank">
                        <i class="fab fa-linkedin"></i> LinkedIn
                    </a>
                </div>

                <div class="faculty-member fade-in">
                    <div class="faculty-image">
                        <img src="../../public/assets/images/faculty/naomi-1.jpg" alt="Naomi Cutajar">
                    </div>
                    <div class="faculty-name">Naomi Cutajar</div>
                    <div class="faculty-role">Executive Assistant, Coach</div>
                    <a href="https://www.linkedin.com/in/naomi-cutajar-847716219/?original_referer=https%3A%2F%2Fwww%2Egoogle%2Ecom%2F&originalSubdomain=mt" class="linkedin-btn" target="_blank">
                        <i class="fab fa-linkedin"></i> LinkedIn
                    </a>
                </div>

                <div class="faculty-member fade-in">
                    <div class="faculty-image">
                        <img src="../../public/assets/images/faculty/joseph-Facciol-1.jpg" alt="Joseph Facciol">
                    </div>
                    <div class="faculty-name">Joseph Facciol</div>
                    <div class="faculty-role">Coach</div>
                </div>
            </div>
        </section>
        <?php include '../components/sidebar.php'; ?>
    </div>

    <script src="../../public/assets/script.js"></script>

<?php
// Incluindo o footer
include '../components/footer.php';
?> 