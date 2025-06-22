<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TaskMaster - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e0f2ff, #80bfff);
      min-height: 100vh;
    }

    .navbar-custom {
      background-color: #ffffff;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
      padding: 12px 30px;
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 24px;
      color: #0d6efd;
    }

    .btn-logout {
      font-size: 14px;
      padding: 5px 12px;
      background-color: #0d6efd;
      color: white;
      border: none;
      border-radius: 4px;
      transition: background-color 0.3s;
    }

    .btn-logout:hover {
      background-color: #084298;
    }

    .container-box {
      background: #ffffff;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      padding: 40px;
      max-width: 1000px;
      margin: 50px auto;
    }

    .heading {
      text-align: center;
      margin-bottom: 40px;
    }

    .heading h2 {
      font-weight: bold;
      font-size: 2rem;
      margin-bottom: 10px;
    }

    .btn-tile {
      background-color: white;
      border: 2px solid #0d6efd;
      border-radius: 15px;
      padding: 30px;
      transition: all 0.3s ease-in-out;
      height: 100%;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
      text-align: center;
      color: inherit;
      text-decoration: none;
      display: block;
    }

    .btn-tile:hover {
      background-color: #0d6efd;
      color: white;
      transform: translateY(-5px);
      text-decoration: none;
    }

    .btn-tile h4 {
      margin-top: 15px;
      font-weight: bold;
    }

    .btn-tile p {
      margin: 0;
      font-size: 14px;
      color: #666;
    }

    .btn-tile:hover p {
      color: white;
    }

    .icon {
      font-size: 40px;
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-custom d-flex justify-content-between align-items-center">
    <span class="navbar-brand">TaskMaster</span>
    <button class="btn-logout" onclick="window.location.href='login.php'">Logout</button>
  </nav>

  <div class="container-box">
    <div class="heading">
      <h2>📚 Welcome to Your Dashboard</h2>
      <p>Choose an action to stay on top of your academic goals.</p>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <a href="tasks.html" class="btn-tile">
          <div class="icon">📝</div>
          <h4>My Tasks</h4>
          <p>Add, edit, and manage your assignments</p>
        </a>
      </div>
      <div class="col-md-4">
        <a href="calendar.html" class="btn-tile">
          <div class="icon">📅</div>
          <h4>Calendar</h4>
          <p>View your deadlines and schedule</p>
        </a>
      </div>
      <div class="col-md-4">
        <a href="tasks/create_task.php" class="btn-tile">
          <div class="icon">🆕</div>
          <h4>Create Task</h4>
          <p>Start a new task quickly</p>
        </a>
      </div>
      <div class="col-md-6">
        <a href="notifications.html" class="btn-tile">
          <div class="icon">🔔</div>
          <h4>Notifications</h4>
          <p>Keep track of reminders and alerts</p>
        </a>
      </div>
      <div class="col-md-6">
        <a href="progress.html" class="btn-tile">
          <div class="icon">📊</div>
          <h4>My Progress</h4>
          <p>Track your task completion stats</p>
        </a>
      </div>
    </div>
  </div>
</body>

</html>