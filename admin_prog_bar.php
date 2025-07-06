<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "new_task_manag_db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT users.username, tasks.title, tasks.due_date, tasks.status 
        FROM tasks 
        INNER JOIN users ON tasks.assigned_to = users.rollno 
        ORDER BY users.username, tasks.due_date";

$result = mysqli_query($conn, $sql);

$tasksByStudent = array();
while ($row = mysqli_fetch_assoc($result)) {
    $username = $row['username'];
    if (!isset($tasksByStudent[$username])) {
        $tasksByStudent[$username] = array();
    }
    $tasksByStudent[$username][] = $row;
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Task Status</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to left, #4595e4, white);
      margin: 0;
      padding: 0;
    }
    .navbar-custom {
      background: #fff;
      padding: 12px 30px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
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
    }
    .container {
      margin-top: 30px;
    }
    .student-section {
      background: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 30px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .task-bar {
      display: flex;
      align-items: left;
      justify-content: space-between;
      border-left: 6px solid;
      border-radius: 5px;
      padding: 15px 20px;
      margin-bottom: 10px;
      background-color: #f8f9fa;
    }
    .task-pending {
      border-left-color: #ffc107;
      background-color: #fff9e6;
    }
    .task-completed {
      border-left-color: #28a745;
      background-color: #e8f5e9;
    }
    .task-overdue {
      border-left-color: #dc3545;
      background-color: #fdecea;
    }
    .task-title {
      font-weight: 600;
      font-size: 16px;
    }
    .status-badge {
      font-size: 0.8rem;
      padding: 6px 12px;
      border-radius: 20px;
    }
    .badge-pending {
      background-color: #ffc107;
      color: #212529;
    }
    .badge-completed {
      background-color: #28a745;
      color: white;
    }
    .badge-overdue {
      background-color: #dc3545;
      color: white;
    }
    .view-submission {
      background-color: #0d6efd;
      color: white;
      border: none;
      border-radius: 4px;
      padding: 5px 10px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-custom d-flex justify-content-between align-items-center">
  <span class="navbar-brand">Student Task Manager</span>
  <div class="d-flex align-items-center gap-3">
    <div class="text-center">
      <div style="font-size: 24px; color: #0d6efd;">👤</div>
      <div style="font-size: 13px;">
        <?php
        if (isset($_SESSION['username'])) {
            echo $_SESSION['username'];
        } else {
            echo 'User';
        }
        ?>
      </div>
    </div>
    <button class="btn-logout" onclick="location.href='logout.php'">Logout</button>
  </div>
</nav>

<div class="container">
  <?php
  foreach ($tasksByStudent as $username => $taskList) {
      echo '<div class="student-section">';
      echo '<h4 class="mb-3">' . $username . '</h4>';
      for ($i = 0; $i < count($taskList); $i++) {
          $task = $taskList[$i];
          $status = strtolower($task['status']);
          $class = 'task-pending';
          $badge = 'badge-pending';

          if ($status == 'completed') {
              $class = 'task-completed';
              $badge = 'badge-completed';
          } else if ($status == 'overdue') {
              $class = 'task-overdue';
              $badge = 'badge-overdue';
          }

          echo '<div class="task-bar ' . $class . '">';
          echo '<span class="task-title">📌 ' . $task['title'] . ' <small class="text-muted">(Due: ' . $task['due_date'] . ')</small></span>';
          echo '<div style="display: flex; align-items: center; gap: 10px; margin-top: 8px;">';
          if ($status == 'completed') {
              echo '<button class="view-submission" onclick="alert(\'Viewing submission for ' . $task['title'] . '\')">View Submission</button>';
          }
          echo '<span class="status-badge ' . $badge . ' text-uppercase">' . $status . '</span>';
          echo '</div>';
          echo '</div>';
      }
      echo '</div>';
  }
  ?>
</div>

</body>
</html>
