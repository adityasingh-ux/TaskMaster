<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "new_task_manag_db");
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);
$_SESSION['username'] = 'admin';

// Fetch all students from users table
$students = [];
$sql = "SELECT username, rollno FROM users ORDER BY id ASC";
$result = $mysqli->query($sql);

// Print SQL error if any
if (!$result) {
    echo '<div style="color:red; text-align:center; margin-top:20px;">MySQL Error: ' . $mysqli->error . '</div>';
}

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students - Student Task Manager</title>
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
        .container-main {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            padding: 40px 30px;
        }
        .table th, .table td {
            vertical-align: middle !important;
        }
        .add-btn {
            background: #0d6efd;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 8px 18px;
            font-size: 15px;
            margin-bottom: 18px;
        }
        .add-btn:hover {
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
      <button class="btn-logout" onclick="location.href='login.html'">Logout</button>
    </div>
  </nav>

  <div class="container-main">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="mb-0" style="color:#0d6efd;">All Students</h2>
      <a href="add_student.php" class="add-btn">+ Add Student</a>
    </div>
    <table class="table table-bordered align-middle">
      <thead class="table-primary">
        <tr>
          <th>S. No.</th>
          <th>Username</th>
          <th>Roll Number</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($students) > 0): ?>
          <?php foreach ($students as $i => $stud): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($stud['username']) ?></td>
              <td><?= htmlspecialchars($stud['rollno']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="3" class="text-center">No students found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>