<?php
// Start output buffering to avoid "headers already sent" errors.
ob_start();

// Include necessary files
include '../partials/header.php';
include '../partials/side-bar.php';
include '../../functions.php';

// Handle deletion if the "Delete Subject Record" button is clicked
if (isset($_POST['delete_subject'])) {
    $subjectId = (int)$_POST['subject_id'];
    deleteSubject($subjectId);

    // Redirect after deletion, ensure this is before any HTML output
    header("Location: add.php");
    exit();  // Always call exit after header redirection
}

// Get the subject ID from the URL
if (isset($_GET['subject_id']) && is_numeric($_GET['subject_id'])) {
    $subjectId = (int)$_GET['subject_id'];

    // Fetch subject details based on the subject_id
    $subject = getSubjectById($subjectId);

    // If no subject found, display error or fallback values
    if (!$subject) {
        $subject = ['subject_code' => 'N/A', 'subject_name' => 'N/A'];
    }
} else {
    // Redirect back if no valid ID is provided
    header("Location: add.php");
    exit();
}

// End output buffering and send output to the browser
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Subject</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f8f9fa; }
        .container { max-width: 800px; margin: 50px auto; padding: 20px; background-color: #fff; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h1 { font-size: 24px; margin-bottom: 20px; }
        .breadcrumb { margin-bottom: 20px; }
        .breadcrumb a { color: #007bff; text-decoration: none; margin-right: 5px; }
        .breadcrumb span { margin-right: 5px; }
        .confirmation-box { border: 1px solid #e0e0e0; padding: 20px; border-radius: 5px; }
        .confirmation-box ul { list-style-type: none; padding: 0; }
        .buttons { display: flex; gap: 10px; margin-top: 20px; }
        .buttons button { padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .cancel-btn { background-color: #6c757d; color: #fff; }
        .delete-btn { background-color: #007bff; color: #fff; }
        .cancel-btn:hover, .delete-btn:hover { opacity: 0.8; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete Subject</h1>
        <div class="breadcrumb">
            <a href="#">Dashboard</a> / 
            <a href="#">Add Subject</a> / 
            <span>Delete Subject</span>
        </div>
        <div class="confirmation-box">
            <p>Are you sure you want to delete the following subject record?</p>
            <ul>
                <li><strong>Subject Code:</strong> <?= htmlspecialchars($subject['subject_code'] ?? 'N/A'); ?></li>
                <li><strong>Subject Name:</strong> <?= htmlspecialchars($subject['subject_name'] ?? 'N/A'); ?></li>
            </ul>
            <div class="buttons">
                <form method="POST">
                    <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subjectId); ?>">
                    <button type="submit" name="delete_subject" class="delete-btn">Delete Subject Record</button>
                </form>
                <a href="add.php">
                    <button type="button" class="cancel-btn">Cancel</button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
