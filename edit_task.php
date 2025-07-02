<?php
session_start();
include 'partials/new_dbconnect.php';

$task = null;
$showSuccess = false;
$showError = false;

// Get task id from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid task ID.");
}
$task_id = intval($_GET['id']);

// Fetch task details
$sql = "SELECT * FROM tasks WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $task_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$task = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$task) {
    die("Task not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $due_date = $_POST["due_date"];
    $status = $_POST["status"];

    $update_sql = "UPDATE tasks SET title=?, description=?, due_date=?, status=? WHERE id=?";
    $update_stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "ssssi", $title, $description, $due_date, $status, $task_id);

    if (mysqli_stmt_execute($update_stmt)) {
        $showSuccess = "Task updated successfully!";
        // Refresh task data
        $task['title'] = $title;
        $task['description'] = $description;
        $task['due_date'] = $due_date;
        $task['status'] = $status;
    } else {
        $showError = "Failed to update task. Please try again.";
    }
    mysqli_stmt_close($update_stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task - TaskMaster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to left, #4595e4, white);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        .navbar-custom {
            background: #fff;
            border-bottom: 2px solid #0d6efd;
            padding: 12px 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 24px;
            color: #0d6efd;
        }
        .btn-logout {
            background: #0d6efd;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 12px;
            font-size: 14px;
        }
        .task-form-container {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            min-width: 350px;
            max-width: 400px;
            margin: 40px auto;
        }
        h2 {
            color: #0d6efd;
            margin-bottom: 25px;
            text-align: center;
        }
        .input-holder {
            margin-bottom: 18px;
        }
        label {
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
        }
        .input-1, textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #bcdffb;
            border-radius: 6px;
            font-size: 15px;
        }
        button[type="submit"] {
            background: #0d6efd;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 10px 30px;
            font-size: 16px;
            margin-top: 10px;
            width: 100%;
        }
        button[type="submit"]:hover {
            background: #0b5ed7;
        }
    </style>
</head>
<body>
  <nav class="navbar navbar-custom">
    <span class="navbar-brand">Student Task Manager</span>
    <div class="d-flex align-items-center gap-3">
      <div class="text-center">
        <div style="font-size: 24px; color: #0d6efd;">👤</div>
        <div style="font-size: 13px;">
          <?php
            echo '@' . (isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User');
          ?>
        </div>
      </div>
      <button class="btn-logout" onclick="location.href='logout.php.php'">Logout</button>
    </div>
  </nav>
<div class="task-form-container">
    <h2>Edit Task</h2>
    <?php if ($showSuccess): ?>
        <div class="alert alert-success"><?= htmlspecialchars($showSuccess) ?></div>
    <?php endif; ?>
    <?php if ($showError): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($showError) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="input-holder">
            <label for="title">Title</label>
            <input type="text" name="title" class="input-1" id="title" value="<?= htmlspecialchars($task['title']) ?>" required>
        </div>
        <div class="input-holder">
            <label for="description">Description</label>
            <textarea name="description" class="input-1" id="description" required><?= htmlspecialchars($task['description']) ?></textarea>
        </div>
        <div class="input-holder">
            <label for="due_date">Due Date</label>
            <input type="date" name="due_date" class="input-1" id="due_date" value="<?= htmlspecialchars($task['due_date']) ?>" required>
        </div>
        <div class="input-holder">
            <label for="status">Status</label>
            <select name="status" class="input-1" id="status" required>
                <option value="pending" <?= $task['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="completed" <?= $task['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="in progress" <?= $task['status'] == 'in progress' ? 'selected' : '' ?>>In Progress</option>
            </select>
        </div>
        <button type="submit">Update Task</button>
    </form>
</div>
</body>
</html>