<?php
session_start();
include 'partials/new_dbconnect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: index.php");
    exit;
}

$rollno = $_SESSION['rollno'] ?? die("User not logged in or roll number not set.");

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'], $_POST['status'])) {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];
    if (in_array($status, ['pending', 'in_progress', 'completed'])) {
        mysqli_query($conn, "UPDATE tasks SET status='$status' WHERE id='$task_id' AND assigned_to='$rollno'");
        echo "success";
    } else {
        echo "invalid status";
    }
    exit;
}

// Fetch tasks
$tasks = mysqli_query($conn, "SELECT * FROM tasks WHERE assigned_to = '$rollno' AND status != 'completed'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e0f2ff, #80bfff);
        }
        .navbar-custom {
            background: #fff;
            padding: 10px 30px;
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar-brand {
            font-weight: bold;
            color: #0d6efd;
            font-size: 1.5rem;
        }
        .btn-logout {
            background: #0d6efd;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 12px;
        }
        .dashboard-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            padding: 40px 30px;
        }
        h2 { color: #0d6efd; text-align: center; margin-bottom: 30px; }
        select.form-select { min-width: 140px; }
    </style>
</head>
<body>
<nav class="navbar navbar-custom">
    <span class="navbar-brand">Student Task Manager</span>
    <div class="d-flex align-items-center gap-3">
        <div class="text-center">
            <div style="font-size: 24px; color: #0d6efd;">👤</div>
            <div style="font-size: 13px;">@<?= $_SESSION['username'] ?? 'User' ?></div>
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
                <th>File</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $i = 1;
        if (mysqli_num_rows($tasks) > 0) {
            while ($task = mysqli_fetch_assoc($tasks)) {
                $status = $task['status'];
                $due = $task['due_date'];
                $isOverdue = strtotime($due) < strtotime(date('Y-m-d'));
                $fileLink = $task['file_path'] ? "<a href='/loginsystem/tasks/uploads/".basename($task['file_path'])."' class='btn btn-sm btn-primary' download>Download</a>" : "<span class='text-muted'>No file</span>";
                ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $task['title'] ?></td>
                    <td><?= $task['description'] ?></td>
                    <td>
                        <select class="form-select status-dropdown" data-task-id="<?= $task['id'] ?>">
                            <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="in_progress" <?= $status == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                        </select>
                    </td>
                    <td><?= $due ?><?= $isOverdue && $status !== 'completed' ? ' <span class="text-danger">(overdue)</span>' : '' ?></td>
                    <td><?= $fileLink ?></td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='6' class='text-center'>No tasks found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<script>
document.querySelectorAll('.status-dropdown').forEach(dropdown => {
    dropdown.setAttribute('data-original', dropdown.value);
    dropdown.addEventListener('change', function () {
        const taskId = this.dataset.taskId;
        const status = this.value;
        const original = this.getAttribute('data-original');

        fetch('my_tasks.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'task_id=' + encodeURIComponent(taskId) + '&status=' + encodeURIComponent(status)
        })
        .then(res => res.text())
        .then(data => {
            if (data.trim() === 'success') {
                this.setAttribute('data-original', status);
            } else {
                alert('Failed to update status: ' + data);
                this.value = original;
            }
        });
    });
});
</script>
</body>
</html>
