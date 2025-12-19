<?php

require_once __DIR__ . '/../../controllers/QuizController.php';
require_once __DIR__ . '/../../controllers/QuestionController.php';
require_once __DIR__ . '/../../controllers/QuizQuestionController.php';

$quizId = $_GET['id'] ?? null;

if (!$quizId) {
    die("Quiz ID is required.");
}

$quizCtrl = new QuizController();
$questionCtrl = new QuestionController();
$quizQuestionCtrl = new QuizQuestionController();

// Load quiz
$quiz = $quizCtrl->getById($quizId);

if (!$quiz) {
    die("Quiz not found.");
}

// Load ordered questions inside quiz
$questions = $quizQuestionCtrl->getQuestionsForQuiz($quizId);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($quiz->getName()) ?></title>

    <style>
        :root {
            --primary: #00aff2;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #f3f4f6;
        }

        /* Centering */
        .page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
        }

        /* Blue background panel */
        .blue-wrapper {
            width: 100%;
            max-width: 900px;
            background-color: var(--primary);
            padding: 6px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        /* White content card */
        .card {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 32px;
        }

        /* Question card */
        .question-card {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 20px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.15s ease;
        }

        .question-card:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 14px rgba(0, 175, 242, 0.2);
            transform: translateY(-2px);
        }

        /* Inputs */
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(0, 175, 242, 0.25);
        }

        input[type="checkbox"],
        input[type="radio"],
        input[type="range"] {
            accent-color: var(--primary);
            cursor: pointer;
        }

        label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
            cursor: pointer;
        }

        label:hover span {
            color: var(--primary);
        }

        /* Button */
        .btn-primary {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: none;
            background-color: var(--primary);
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: box-shadow 0.2s ease, transform 0.15s ease;
        }

        .btn-primary:hover {
            box-shadow: 0 8px 20px rgba(0, 175, 242, 0.4);
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 4px 12px rgba(0, 175, 242, 0.3);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .description {
            color: #6b7280;
            margin-bottom: 24px;
        }
    </style>
</head>

<body>

<div class="page">
    <div class="blue-wrapper">
        <div class="card">

            <h1><?= htmlspecialchars($quiz->getName()) ?></h1>

            <p class="description">
                <?= nl2br(htmlspecialchars($quiz->getDescription())) ?>
            </p>

            <form method="post" action="../quiz/handle-submission.php">

                <input type="hidden" name="quiz_id" value="<?= $quizId ?>">

                <?php foreach ($questions as $i => $q): ?>
                    <?php $details = $q->getDetails(); ?>

                    <div class="question-card">
                        <strong>
                            <?= ($i + 1) . '. ' . htmlspecialchars($q->getLabel()) ?>
                        </strong>

                        <div style="margin-top: 10px;">
                            <?php if ($q->getType() === 'TEXT'): ?>
                                <input
                                    type="text"
                                    name="answers[<?= $q->getId() ?>]"
                                    placeholder="Your answer...">

                            <?php elseif ($q->getType() === 'SWITCH'): ?>
                                <label>
                                    <input type="checkbox" name="answers[<?= $q->getId() ?>]" value="1">
                                    <span>Yes</span>
                                </label>

                            <?php elseif ($q->getType() === 'CHECKBOX'): ?>
                                <?php foreach ($q->getChoices() as $c): ?>
                                    <label>
                                        <input
                                            type="checkbox"
                                            name="answers[<?= $q->getId() ?>][]"
                                            value="<?= htmlspecialchars($c['id']) ?>">
                                        <span><?= htmlspecialchars($c['label']) ?></span>
                                    </label>
                                <?php endforeach; ?>

                            <?php elseif ($q->getType() === 'RADIO'): ?>
                                <?php foreach ($q->getChoices() as $c): ?>
                                    <label>
                                        <input
                                            type="radio"
                                            name="answers[<?= $q->getId() ?>]"
                                            value="<?= htmlspecialchars($c['id']) ?>">
                                        <span><?= htmlspecialchars($c['label']) ?></span>
                                    </label>
                                <?php endforeach; ?>

                            <?php elseif ($q->getType() === 'SLIDER'): ?>
                                <input
                                    type="range"
                                    min="<?= htmlspecialchars($details['min'] ?? 0) ?>"
                                    max="<?= htmlspecialchars($details['max'] ?? 100) ?>"
                                    name="answers[<?= $q->getId() ?>]">
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endforeach; ?>

                <button type="submit" class="btn-primary">
                    Submit Survey
                </button>

            </form>

        </div>
    </div>
</div>

</body>
</html>
