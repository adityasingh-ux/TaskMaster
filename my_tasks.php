<?php
session_start();
include 'partials/new_dbconnect.php';

// Get user's roll number (assuming it's stored in session)
if (!isset($_SESSION['rollno'])) {
    die("User not logged in or roll number not set.");
}
$rollno = $_SESSION['rollno'];

// Handle AJAX status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'], $_POST['status'])) {
    $task_id = intval($_POST['task_id']);
    $status = $_POST['status'];
    $allowed = ['pending', 'in_progress', 'completed'];
    if (in_array($status, $allowed)) {
        $sql = "UPDATE tasks SET status=? WHERE id=? AND assigned_to=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sis", $status, $task_id, $rollno);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "success";
    } else {
        echo "invalid status";
    }
    exit;
}

// Fetch tasks for this user
$sql = "SELECT * FROM tasks WHERE assigned_to = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $rollno);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$tasks = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tasks[] = $row;
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <style>
        body {
            background: linear-gradient(to left, #4595e4, white);
            font-family: Arial, sans-serif;
            min-height: 100vh;
        }
        .navbar-custom {
            background: #fff;
            border-bottom: 2px solid #0d6efd;
            padding: 10px 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar-brand {
            font-weight: bold;
            color: #0d6efd;
            font-size: 1.5rem;
            letter-spacing: 1px;
        }
        .btn-logout {
            background: #0d6efd;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 12px;
            font-size: 14px;
        }
        .dashboard-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            padding: 40px 30px;
        }
        h2 {
            color: #0d6efd;
            margin-bottom: 30px;
            text-align: center;
        }
        select.form-select {
            min-width: 140px;
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
                @<?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User' ?>
            </div>
        </div>
        <button class="btn-logout" onclick="location.href='logout.php'">Logout</button>
    </div>
</nav>
<div class="dashboard-container">
    <h2>📝 My Tasks</h2>
    <table class="table table-bordered align-middle">
        <thead class="table-primary">
            <tr>
                <th>S. No.</th>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($tasks) > 0): ?>
            <?php foreach ($tasks as $i => $task): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($task['title']) ?></td>
                    <td><?= htmlspecialchars($task['description']) ?></td>
                    <td>
                        <select class="form-select status-dropdown" data-task-id="<?= $task['id'] ?>">
                            <option value="pending" <?= $task['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="in_progress" <?= $task['status'] == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                            <option value="completed" <?= $task['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                        </select>
                    </td>
                    <td><?= htmlspecialchars($task['due_date']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" class="text-center">No tasks found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<script>
document.querySelectorAll('.status-dropdown').forEach(function(dropdown) {
    dropdown.addEventListener('change', function() {
        var taskId = this.getAttribute('data-task-id');
        var status = this.value;
        var selectElem = this;
        fetch('my_tasks.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'task_id=' + encodeURIComponent(taskId) + '&status=' + encodeURIComponent(status)
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() !== "success") {
                alert("Failed to update status: " + data);
                // Optionally revert dropdown
                selectElem.value = selectElem.getAttribute('data-original');
            } else {
                selectElem.setAttribute('data-original', status);
            }
        });
    });
});
// Store original status for revert
document.querySelectorAll('.status-dropdown').forEach(function(dropdown) {
    dropdown.setAttribute('data-original', dropdown.value);
});
</script>
</body>
</html>