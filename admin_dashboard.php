<?php
session_start();
include 'partials/new_dbconnect.php';
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] != true) {
  header("location: admin_login.php");
  exit;
}
$dueDates = [];
$sql = "SELECT due_date FROM tasks";
$result = mysqli_query($conn, $sql);
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $dueDates[] = date('Y-m-d', strtotime($row['due_date']));
  }
}
$notifications = [];

$subQ = "SELECT id, task_id, title, submitted_by, username, department, submission_date 
         FROM submitted_tasks
         ORDER BY submission_date DESC
         LIMIT 20";
$subR = mysqli_query($conn, $subQ);
while ($r = mysqli_fetch_assoc($subR)) {
  $ts = strtotime($r['submission_date']) ?: time();
  $notifications[] = [
    'type' => 'submission',
    'ts' => $ts,
    'text' => "{$r['username']} ({$r['department']}, {$r['submitted_by']}) submitted \"{$r['title']}\"",
  ];
}

$missQ = "SELECT t.id,t.title,t.due_date,t.assigned_to,u.username,u.department
          FROM tasks t
          LEFT JOIN users u ON t.assigned_to = u.rollno
          WHERE t.due_date < CURDATE() AND t.status != 'completed'
          ORDER BY t.due_date DESC
          LIMIT 20";
$missR = mysqli_query($conn, $missQ);
while ($r = mysqli_fetch_assoc($missR)) {
  $ts = strtotime($r['due_date']) ?: time();
  $stu = $r['username'] ? "{$r['username']} ({$r['department']}, {$r['assigned_to']})" : "{$r['assigned_to']}";
  $notifications[] = [
    'type' => 'missed',
    'ts' => $ts,
    'text' => "$stu missed \"$r[title]\" (due {$r['due_date']})",
  ];
}

$createdCol = '';
$colRes = mysqli_query($conn, "SHOW COLUMNS FROM tasks LIKE 'created_at'");
if ($colRes && mysqli_num_rows($colRes) > 0) $createdCol = 'created_at';


if ($createdCol) {
  $newQ = "SELECT t.id, t.title, t.assigned_to, u.department, `$createdCol` 
             FROM tasks t
             LEFT JOIN users u ON t.assigned_to = u.rollno
             ORDER BY t.`$createdCol` DESC
             LIMIT 20";
} else {
  $newQ = "SELECT t.id, t.title, t.assigned_to, u.department, t.id as created_id
             FROM tasks t
             LEFT JOIN users u ON t.assigned_to = u.rollno
             ORDER BY t.id DESC
             LIMIT 20";
}
$newR = mysqli_query($conn, $newQ);
while ($r = mysqli_fetch_assoc($newR)) {
  $ts = $createdCol ? strtotime($r[$createdCol]) : (int)$r['created_id'];
  $dept = !empty($r['department']) ? $r['department'] : $r['assigned_to'];
  $notifications[] = [
    'type' => 'new_task',
    'ts' => $ts ?: time(),
    'text' => "New task \"{$r['title']}\" (assigned to \"{$dept}\" department)",
  ];
}

usort($notifications, function ($a, $b) {
  return ($b['ts'] ?? 0) <=> ($a['ts'] ?? 0);
});

$notifCount = count($notifications);
$notifications = array_slice($notifications, 0, 20);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Task Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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
      font-size: 14px;
    }

    .main-layout {
      display: flex;
      gap: 30px;
      max-width: 1200px;
      margin: 40px auto;
      flex-wrap: wrap;
      padding: 0 20px;
    }

    .calendar-box {
      flex: 1 1 300px;
      max-width: 530px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      padding: 20px;
      cursor: pointer;
      min-width: 280px;
      margin-bottom: 30px;
      align-self: flex-start;
    }

    .calendar-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 5px;
      font-size: 12px;
    }

    .calendar-grid div {
      background: #f4f9ff;
      border: 1px solid #dbeeff;
      text-align: center;
      padding: 10px;
      border-radius: 4px;
    }

    .day-header {
      font-weight: 600;
      color: #0d6efd;
    }

    .highlight-red {
      background: #ff4d4d !important;
      color: white;
      font-weight: bold;
    }

    .right-panel {
      flex: 2 1 600px;
      background: white;
      border-radius: 20px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
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

    .text-center.mb-4 h2 {
      font-size: 2.2rem;
    }

    @media (max-width: 900px) {
      .main-layout {
        flex-direction: column;
        align-items: stretch;
      }

      .calendar-box {
        max-width: 100%;
        margin-right: 0;
      }

      .right-panel {
        padding: 30px 10px;
      }
    }

    .notif-btn {
      position: relative;
      border-radius: 50%;
      width: 44px;
      height: 44px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .notif-badge {
      position: absolute;
      top: 4px;
      right: 4px;
      font-size: 0.65rem;
      padding: 3px 6px;
      border-radius: 999px;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-custom d-flex justify-content-between align-items-center">
    <span class="navbar-brand">Student Task Manager</span>
    <div class="d-flex align-items-center gap-3">
      <div class="dropdown">
        <button class="btn btn-light notif-btn" id="adminNotifToggle" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
          🔔
          <?php if ($notifCount > 0): ?>
            <span class="badge bg-danger notif-badge"><?php echo $notifCount; ?></span>
          <?php endif; ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width:360px; max-width:420px;">
          <li class="dropdown-header">Notifications <?php if ($notifCount > 0) echo "({$notifCount})"; ?></li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <div style="max-height:320px; overflow:auto;">
            <?php if ($notifCount == 0): ?>
              <li class="px-3 py-2 text-muted">No notifications</li>
            <?php else: ?>
              <?php foreach ($notifications as $n): ?>
                <?php
                $bg = $n['type'] === 'missed' ? 'bg-danger text-white' : ($n['type'] === 'submission' ? 'bg-light' : 'bg-white');
                ?>
                <li class="px-2 py-2 mb-1 <?php echo $bg; ?>">
                  <a class="text-decoration-none <?php echo ($n['type'] === 'missed') ? 'text-white' : ''; ?>" href="<?php echo $n['link']; ?>">
                    <div style="font-size:0.95rem;"><strong><?php echo $n['text']; ?></strong></div>
                    <div class="text-muted" style="font-size:0.8rem;"><?php echo date('Y-m-d H:i', $n['ts']); ?></div>
                  </a>
                </li>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li class="px-3"><a href="all_task.php" class="btn btn-sm btn-outline-primary w-100">View all tasks</a></li>
        </ul>
      </div>

      <div class="text-center">
        <div style="font-size: 24px; color: #0d6efd;">👤</div>
        <div style="font-size: 13px;">@<?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User' ?></div>
      </div>
      <button class="btn-logout" onclick="location.href='logout.php'">Logout</button>
    </div>
  </nav>

  <div class="main-layout">
    <!-- Calendar Panel -->
    <div class="calendar-box" data-bs-toggle="modal" data-bs-target="#calendarModal">
      <h5>📅 Calendar</h5>
      <div id="calendarMonthMini" class="mb-2 text-primary"></div>
      <div class="calendar-grid" id="calendarMini"></div>
    </div>

    <!-- Dashboard Panel -->
    <div class="right-panel">
      <div class="text-center mb-4">
        <h2>📚 Admin Dashboard</h2>
        <p>Monitor and Manage students' progress and deadlines.</p>
      </div>
      <div class="row g-4">
        <div class="col-md-6">
          <a href="all_task.php" class="btn-tile">
            <div class="icon">📝</div>
            <h4>All Students Tasks</h4>
          </a>
        </div>
        <div class="col-md-6">
          <a href="manage_stud.php" class="btn-tile">
            <div class="icon">👥</div>
            <h4>Manage Students</h4>
          </a>
        </div>
        <div class="col-md-6">
          <a href="tasks/create_task.php" class="btn-tile">
            <div class="icon">📅</div>
            <h4>Add Task</h4>
          </a>
        </div>
        <div class="col-md-6">
          <a href="admin_prog_bar.php" class="btn-tile">
            <div class="icon">📊</div>
            <h4>Track Progress</h4>
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="calendarModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <button class="btn btn-sm btn-outline-primary" onclick="changeMonth(-1)">←</button>
          <h5 id="calendarMonth" class="mb-0 text-primary"></h5>
          <button class="btn btn-sm btn-outline-primary" onclick="changeMonth(1)">→</button>
        </div>
        <div class="calendar-grid" id="calendarFull"></div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const dueDates = <?php echo json_encode($dueDates); ?>;
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();

    function renderCalendar(month, year, gridId, titleId) {
      const grid = document.getElementById(gridId);
      const title = titleId ? document.getElementById(titleId) : null;
      if (grid) grid.innerHTML = "";
      if (title) title.textContent = `${monthNames[month]} ${year}`;
      const firstDay = new Date(year, month, 1).getDay();
      const daysInMonth = new Date(year, month + 1, 0).getDate();

      ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"].forEach(day => {
        const el = document.createElement("div");
        el.className = "day-header";
        el.textContent = day;
        grid.appendChild(el);
      });

      for (let i = 0; i < firstDay; i++) grid.appendChild(document.createElement("div"));

      for (let d = 1; d <= daysInMonth; d++) {
        const cell = document.createElement("div");
        const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        cell.textContent = d;
        if (dueDates.includes(dateStr)) cell.classList.add("highlight-red");
        grid.appendChild(cell);
      }
    }

    function changeMonth(offset) {
      currentMonth += offset;
      if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
      }
      if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
      }
      renderCalendar(currentMonth, currentYear, "calendarFull", "calendarMonth");
      renderCalendar(currentMonth, currentYear, "calendarMini", "calendarMonthMini");
    }

    function updateMiniMonth() {
      document.getElementById("calendarMonthMini").textContent = `${monthNames[currentMonth]} ${currentYear}`;
    }

    renderCalendar(currentMonth, currentYear, "calendarMini", "calendarMonthMini");
    updateMiniMonth();
    document.getElementById("calendarModal").addEventListener("shown.bs.modal", () => {
      renderCalendar(currentMonth, currentYear, "calendarFull", "calendarMonth");
    });

    document.getElementById("calendarModal").addEventListener("hidden.bs.modal", () => {
      renderCalendar(currentMonth, currentYear, "calendarMini", "calendarMonthMini");
      updateMiniMonth();
    });
  </script>
</body>

</html>