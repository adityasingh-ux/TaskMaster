<?php
session_start();
include 'partials/new_dbconnect.php';
if(!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin']!=true){
    header("location: admin_login.php");
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Task Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e0f2ff, #80bfff);
      margin: 0;
      min-height: 100vh;
    }
    .navbar-custom {
      background: #fff;
      padding: 10px 30px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.06);
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
    .container {
      min-height: 90vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .right-panel {
      background: white;
      border-radius: 20px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.15);
      padding: 40px 60px 50px 60px;
      max-width: 1000px;
      width: 100%;
      min-width: 400px;
      min-height: 450px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .btn-tile {
      background: white;
      border: 2px solid #0d6efd;
      border-radius: 15px;
      padding: 35px 10px;
      text-align: center;
      display: block;
      color: inherit;
      text-decoration: none;
      transition: 0.3s;
      font-size: 1.3rem;
      margin-bottom: 18px;
    }
    .btn-tile:hover {
      background: #0d6efd;
      color: white;
      transform: translateY(-4px) scale(1.04);
    }
    .icon {
      font-size: 48px;
      margin-bottom: 10px;
    }
    .highlight-red {
      background: #ff4d4d !important;
      color: white;
      font-weight: bold;
    }
    .text-center.mb-4 h2 {
      font-size: 2.2rem;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-custom d-flex justify-content-between align-items-center">
  <span class="navbar-brand">Student Task Manager</span>
  <div class="d-flex align-items-center gap-3">
    <div class="text-center">
      <div style="font-size: 24px; color: #0d6efd;">👤</div>
      <div style="font-size: 13px;">@<?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User' ?></div>
    </div>
    <button class="btn-logout" onclick="location.href='logout.php'">Logout</button>
  </div>
</nav>
<div class="container">
  <div class="right-panel">
    <div class="text-center mb-4">
      <h2>📚 Admin Dashboard</h2>
      <p>Monitor and Manage students' progress and deadlines.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-6"><a href="all_task.php" class="btn-tile"><div class="icon">📝</div><h4>All Students Tasks</h4></a></div>
      <div class="col-md-6"><a href="manage_stud.php" class="btn-tile"><div class="icon">👥</div><h4>Manage Students</h4></a></div>
      <div class="col-md-6"><a href="tasks/create_task2.php" class="btn-tile"><div class="icon">📅</div><h4>Add Task</h4></a></div>
      <div class="col-md-6"><a href="admin_prog_bar.php" class="btn-tile"><div class="icon">📊</div><h4>Track Progress</h4></a></div>
    </div>
  </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>