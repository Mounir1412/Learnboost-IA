<?php
$pageTitle = "Leçon - LearnBoost AI";
$headerClass = "nav-visible"; 
$headerLogo = "logo.png"; 
$assetBasePath = '../';
include(__DIR__ . '/../includes/header.php');

require_once(__DIR__ . '/../../../Controller/LessonController.php');
require_once(__DIR__ . '/../../../Controller/CourseController.php');

$lessonC = new LessonController();
$courseC = new CourseController();

$id = $_GET['id'] ?? null;
$course_id = $_GET['course_id'] ?? null;

if (!$id || !$course_id) {
    header('Location: courseList.php');
    exit;
}

$lesson = $lessonC->showLesson($id);
$course = $courseC->showCourse($course_id);

if (!$lesson || !$course) {
    header('Location: courseList.php?error=not_found');
    exit;
}
?>

<!-- Lesson Header Section -->
<div id="lesson-header" class="section lesson-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="white-text"><?php echo htmlspecialchars($lesson['title']); ?></h1>
                <p class="lead white-text">
                    <i class="fa fa-book"></i> Du cours: <?php echo htmlspecialchars($course['title']); ?>
                </p>
                <div class="breadcrumb-nav">
                    <a href="courseList.php" class="breadcrumb-link">Tous les cours</a> 
                    <span> / </span> 
                    <a href="courseDetail.php?id=<?php echo $course_id; ?>" class="breadcrumb-link"><?php echo htmlspecialchars($course['title']); ?></a>
                    <span> / </span> 
                    <span><?php echo htmlspecialchars($lesson['title']); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Lesson Header Section -->

<?php
// Chatbot removed: widget include deleted
?>

<!-- Lesson Content Section -->
<div id="lesson-content" class="section">
    <div class="container">
        <div class="row">
            <!-- Main Content (8 columns) -->
            <div class="col-md-8">
                <div class="lesson-main-content">
                    <!-- Video Section (if available) -->
                    <?php if (!empty($lesson['videoUrl'])): ?>
                        <div class="lesson-video-wrapper">
                            <iframe 
                                src="<?php echo htmlspecialchars($lesson['videoUrl']); ?>" 
                                allowfullscreen 
                                frameborder="0"
                                width="100%" 
                                height="400"
                                style="border-radius: 8px;">
                            </iframe>
                        </div>
                    <?php endif; ?>

                    <!-- Content Box -->
                    <div class="content-box">
                        <div class="content-header">
                            <h2><?php echo htmlspecialchars($lesson['title']); ?></h2>
                            
                            <!-- Meta Information -->
                            <div class="lesson-meta">
                                <?php if (!empty($lesson['duration']) && (int)$lesson['duration'] > 0): ?>
                                    <span class="meta-badge">
                                        <i class="fa fa-clock-o"></i> 
                                        Durée: <?php echo (int)$lesson['duration']; ?> secondes
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Lesson Text Content -->
                        <div class="lesson-text">
                            <?php echo nl2br(htmlspecialchars($lesson['content'])); ?>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="lesson-navigation">
                        <a href="courseDetail.php?id=<?php echo $course_id; ?>" class="nav-button nav-back">
                            <i class="fa fa-arrow-left"></i> Retour au cours
                        </a>
                        <a href="courseList.php" class="nav-button nav-list">
                            <i class="fa fa-bars"></i> Voir tous les cours
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar (4 columns) -->
            <div class="col-md-4">
                <!-- Course Info Card -->
                <div class="sidebar-card">
                    <div class="sidebar-card-header">
                        <h4><i class="fa fa-book"></i> Information du cours</h4>
                    </div>
                    <div class="sidebar-card-body">
                        <p class="course-name"><?php echo htmlspecialchars($course['title']); ?></p>
                        <p class="course-status">
                            <strong>Statut:</strong> <?php echo htmlspecialchars($course['status']); ?>
                        </p>
                        <a href="courseDetail.php?id=<?php echo $course_id; ?>" class="sidebar-link">
                            <i class="fa fa-arrow-right"></i> Voir le cours complet
                        </a>
                    </div>
                </div>

                <!-- Lesson Meta Card -->
                <div class="sidebar-card">
                    <div class="sidebar-card-header">
                        <h4><i class="fa fa-info-circle"></i> Information de la leçon</h4>
                    </div>
                    <div class="sidebar-card-body">
                        <?php if (!empty($lesson['duration']) && (int)$lesson['duration'] > 0): ?>
                            <div class="info-item">
                                <span class="info-label"><i class="fa fa-clock-o"></i> Durée:</span>
                                <span class="info-value"><?php echo (int)$lesson['duration']; ?> sec</span>
                            </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <span class="info-label"><i class="fa fa-id-card"></i> ID Leçon:</span>
                            <span class="info-value">#<?php echo htmlspecialchars($id); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Lesson Content Section -->

<!-- Custom Styles for Lesson Detail -->
<style>
/* Lesson Header Background */
.lesson-header-bg {
    background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                url('../assets/img/carousel/img_1.jpg') center/cover;
    color: white;
    padding: 5rem 0;
    text-align: center;
}

.lesson-header-bg h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.lesson-header-bg .lead {
    font-size: 1.2rem;
    margin-bottom: 1.5rem;
}

.breadcrumb-nav {
    display: inline-block;
    background: rgba(255,255,255,0.1);
    padding: 0.5rem 1.5rem;
    border-radius: 4px;
    font-size: 0.95rem;
}

.breadcrumb-link {
    color: #fff;
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-link:hover {
    color: #e0e0e0;
    text-decoration: underline;
}

/* Main Content */
.lesson-main-content {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.lesson-video-wrapper {
    width: 100%;
    background-color: #000;
    margin-bottom: 0;
    border-radius: 0;
    overflow: hidden;
}

.content-box {
    padding: 2rem;
}

.content-header {
    border-bottom: 2px solid #007bff;
    padding-bottom: 1.5rem;
    margin-bottom: 2rem;
}

.content-header h2 {
    font-size: 1.8rem;
    color: #000;
    font-weight: 700;
    margin-bottom: 1rem;
}

.lesson-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.meta-badge {
    background: #e9ecef;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.9rem;
    color: #666;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.meta-badge i {
    color: #007bff;
}

.lesson-text {
    line-height: 1.8;
    color: #000;
    font-size: 1rem;
}

.lesson-text p {
    margin-bottom: 1.5rem;
}

.lesson-navigation {
    display: flex;
    gap: 1rem;
    padding: 2rem;
    border-top: 1px solid #eee;
    background: #f9f9f9;
    justify-content: center;
    flex-wrap: wrap;
}

.nav-button {
    background: #007bff;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    font-weight: 500;
}

.nav-button:hover {
    background: #0056b3;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.nav-back {
    background: #6c757d;
}

.nav-back:hover {
    background: #5a6268;
}

.nav-list {
    background: #28a745;
}

.nav-list:hover {
    background: #218838;
}

/* Sidebar Cards */
.sidebar-card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: all 0.3s ease;
}

.sidebar-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.12);
}

.sidebar-card-header {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    padding: 1rem;
}

.sidebar-card-header h4 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sidebar-card-body {
    padding: 1.5rem;
}

.course-name {
    font-size: 1.1rem;
    color: #000;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.course-status {
    color: #000;
    margin-bottom: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #eee;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    color: #666;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-label i {
    color: #007bff;
}

.info-value {
    color: #000;
    font-weight: 600;
}

.sidebar-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.sidebar-link:hover {
    color: #0056b3;
    gap: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .lesson-header-bg {
        padding: 3rem 0;
    }

    .lesson-header-bg h1 {
        font-size: 1.8rem;
    }

    .content-box {
        padding: 1.5rem;
    }

    .lesson-navigation {
        padding: 1rem;
    }

    .nav-button {
        flex: 1;
        justify-content: center;
        min-width: calc(50% - 0.5rem);
    }

    .content-header h2 {
        font-size: 1.4rem;
    }
}
</style>

<?php
$assetBasePath = '../';
include(__DIR__ . '/../includes/footer.php');
?>
