<?php
include '../partials/header.php';
include '../partials/side-bar.php';
include '../../functions.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $subjectCode = $_POST['subject_code'];
    $subjectName = $_POST['subject_name'];
    
    // Call the function to add the subject to the database
    addSubject($subjectCode, $subjectName);
}

// Fetch all subjects from the database to display in the table
$subjects = getSubjects();
?>

<html>
<head>
    <title>Add a New Subject</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            width: 80%;
            margin: 20px auto;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .breadcrumb {
            font-size: 14px;
            margin-bottom: 20px;
        }
        .breadcrumb a {
            color: #007bff;
            text-decoration: none;
        }
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        .form-container, .table-container {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #dee2e6;
        }
        table th {
            background-color: #f8f9fa;
        }
        .btn-edit {
            background-color: #17a2b8;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-edit:hover {
            background-color: #138496;
        }
        .btn-delete {
            background-color: #dc3545;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add a New Subject</h1>
        <div class="breadcrumb">
            <a href="#">Dashboard</a> / Add Subject
        </div>
        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <input type="text" name="subject_code" placeholder="Subject Code" required>
                </div>
                <div class="form-group">
                    <input type="text" name="subject_name" placeholder="Subject Name" required>
                </div>
                <button type="submit" class="btn">Add Subject</button>
            </form>
        </div>
        <div class="table-container">
            <h2>Subject List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($subjects): ?>
                    <?php foreach ($subjects as $subject): ?>
                        <tr>
                            <td><?= htmlspecialchars($subject['subject_code']); ?></td>
                            <td><?= htmlspecialchars($subject['subject_name']); ?></td>
                            <td>
                                <a href="edit.php?subject_id=<?= $subject['id']; ?>">
                                    <button class="btn-edit">Edit</button>
                                </a>
                                <a href="delete.php?subject_id=<?= $subject['id']; ?>">
                                    <button class="btn-delete">Delete</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No subjects available</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
