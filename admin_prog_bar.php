<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "new_task_manag_db");
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);

$tasksByStudent = [];
$sql = "SELECT assigned_to, title, due_date, status FROM tasks ORDER BY assigned_to, due_date";
$result = $mysqli->query($sql);

while ($row = $result->fetch_assoc()) {
    $tasksByStudent[$row['assigned_to']][] = $row;
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Task Status</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f0f8ff; margin: 0; padding: 20px; }
    .student-section { background: white; border-radius: 10px; padding: 20px; margin-bottom: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .task-bar { display: flex; align-items: center; justify-content: space-between; border: 1px solid #ccc; border-left: 6px solid; border-radius: 5px; padding: 10px 15px; margin-bottom: 10px; }
    .task-pending { border-left-color: #ffc107; background-color: #fff9e6; }
    .task-completed { border-left-color: #28a745; background-color: #e8f5e9; }
    .task-overdue { border-left-color: #dc3545; background-color: #fdecea; }
    .task-title { font-weight: 600; }
    .status-badge { font-size: 0.8rem; padding: 5px 10px; border-radius: 20px; }
    .badge-pending { background-color: #ffc107; color: #212529; }
    .badge-completed { background-color: #28a745; }
    .badge-overdue { background-color: #dc3545; }
  </style>
</head>
<body>
  <div class="container">
    <h2 class="mb-4">📋 Task Status by Student</h2>
    <?php foreach ($tasksByStudent as $student => $tasks): ?>
      <div class="student-section">
        <h4>@<?= htmlspecialchars($student) ?></h4>
        <?php foreach ($tasks as $task):
          $status = strtolower($task['status']);
          $class = 'task-pending';
          $badge = 'badge-pending';
          if ($status === 'completed') {
            $class = 'task-completed';
            $badge = 'badge-completed';
          } elseif ($status === 'overdue') {
            $class = 'task-overdue';
            $badge = 'badge-overdue';
          }
        ?>
        <div class="task-bar <?= $class ?>">
          <span class="task-title">📌 <?= htmlspecialchars($task['title']) ?> (Due: <?= $task['due_date'] ?>)</span>
          <span class="status-badge <?= $badge ?> text-uppercase"><?= $status ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>