<?php
session_start();
include 'partials/new_dbconnect.php';

$showSuccess = false;
$showError = false;

// Get task id from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid task ID.");
}
$task_id = intval($_GET['id']);

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "DELETE FROM tasks WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $task_id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        // Redirect to all_task.php (or show_task.php) after deletion
        header("Location: all_task.php?msg=Task+deleted+successfully");
        exit();
    } else {
        $showError = "Failed to delete task. Please try again.";
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Task - TaskMaster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-5">
    <?php if ($showError): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($showError) ?></div>
    <?php endif; ?>
    <a href="show_task.php" class="btn btn-primary">Back to All Tasks</a>
</div>