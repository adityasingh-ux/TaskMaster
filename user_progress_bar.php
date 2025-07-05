<?php
session_start();
if (!isset($_SESSION['rollno'])) {
    header("Location: login_page.php");
    exit;
}

$mysqli = new mysqli("localhost", "root", "", "new_task_manag_db");
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);

$rollno = $_SESSION['rollno'];
$today = date("Y-m-d");

// ✅ CORRECTED: column name should be rollno
$stmt = $mysqli->prepare("SELECT title, description, due_date, status FROM tasks WHERE assigned_to = ?");
$stmt->bind_param("s", $rollno);
$stmt->execute();
$result = $stmt->get_result();

$tasks = ["Pending" => [], "Completed" => [], "Overdue" => []];

while ($row = $result->fetch_assoc()) {
    $status = $row['status'];
    $due_date = $row['due_date'];
    
    if ($status === 'Completed') {
        
      $tasks["Completed"][] = $row;
    } elseif (strtotime($due_date) < strtotime($today)) {
       
      $tasks["Overdue"][] = $row;

    } else {
        $tasks["Pending"][] = $row;
    }
}

$stmt->close();
$mysqli->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Tasks</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f0f8ff; font-family: 'Segoe UI'; }
    .task-panel { padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 30px; }
    .task-title { font-weight: bold; }
    .status-heading { font-size: 20px; color: #0d6efd; margin-bottom: 15px; }
    .badge-pending { background-color: #ffc107; }
    .badge-overdue { background-color: #dc3545; }
    .badge-completed { background-color: #28a745; }
  </style>
</head>
<body>
<nav class="navbar navbar-light bg-white shadow-sm mb-4 px-4">
  <span class="navbar-brand mb-0 h1">📋 My Tasks</span>
  <div class="d-flex align-items-center">
    <span class="me-3">Roll No: <?= htmlspecialchars($rollno) ?></span>
    <a href="login.php" class="btn btn-danger btn-sm">Logout</a>
  </div>
</nav>

<div class="container">
  <?php foreach ($tasks as $status => $taskList): ?>
    <div class="task-panel">
      <h3 class="status-heading">
        <?= $status ?> 
        <span class="badge 
            <?= $status === 'Pending' ? 'badge-pending' : ($status === 'Completed' ? 'badge-completed' : 'badge-overdue') ?>">
          <?= count($taskList) ?>
        </span>
      </h3>
      <?php if (count($taskList) === 0): ?>
        <p class="text-muted">No <?= strtolower($status) ?> tasks.</p>
      <?php else: ?>
        <ul class="list-group">
          <?php foreach ($taskList as $task): ?>
            <li class="list-group-item">
              <div class="task-title"><?= htmlspecialchars($task['title']) ?></div>
              <div class="text-muted"><?= htmlspecialchars($task['description']) ?></div>
              <small>Due: <?= $task['due_date'] ?></small>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>

</body>
</html>