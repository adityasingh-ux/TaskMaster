<?php
$showAlert = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "new_task_manag_db";

    // Create connection
    $conn = mysqli_connect($server, $username, $password, $database);

    // Check connection
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Get form data
    $rollno = $_POST["rollno"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $cpassword = $_POST["confirm_password"];

    // 1. Check if user already exists
    $existSql = "SELECT * FROM `users` WHERE `rollno` = '$rollno'";
    $result = mysqli_query($conn, $existSql);

    if (!$result) {
        $showError = "Error checking existing user: " . mysqli_error($conn);
    } elseif (mysqli_num_rows($result) > 0) {
        $showError = "User with this roll number already exists.";
    } else {
        // 2. Check if roll number exists in allowed list
        $sql1 = "SELECT * FROM `rollnos` WHERE `rollno` = '$rollno'";
        $result = mysqli_query($conn, $sql1);

        if (!$result) {
            $showError = "Error verifying roll number: " . mysqli_error($conn);
        } elseif (mysqli_num_rows($result) == 0) {
            $showError = "Roll number not recognized. Please contact admin.";
        } else {
            // 3. Check password match
            if ($password !== $cpassword) {
                $showError = "Passwords do not match.";
            } else {
                // 4. Store user in DB (you can add password hashing here)
                $sql = "INSERT INTO `users` (`username`, `password`, `dt`, `rollno`) 
                        VALUES ('$username', '$password', current_timestamp(), '$rollno')";

                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $showAlert = true;
                } else {
                    $showError = "Error inserting user: " . mysqli_error($conn);
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Student Task Manager - Sign Up</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to left, #4595e4, white);
      min-height: 100vh;
      margin: 0;
      padding: 0;
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
    .college-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .college-logo {
      height: 48px;
      width: auto;
    }
    .college-name {
      font-size: 1.1rem;
      font-weight: 600;
      color: #0d47a1;
      white-space: nowrap;
    }
    .container-main {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: center;
      padding: 50px 20px;
    }
    .left-content {
      max-width: 500px;
      margin-right: 50px;
    }
    .page-title {
      font-size: 40px;
      font-weight: bold;
      color: #0d47a1;
    }
    .subtitle {
      font-size: 18px;
      color: #333;
    }
    .signup-container {
      background: #ffffff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }
    .signup-container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #0d47a1;
    }
    .form-label {
      font-weight: 500;
    }
    .btn-primary {
      background-color: #0d47a1;
      border-color: #0d47a1;
    }
    .btn-primary:hover {
      background-color: #08397b;
    }
    .login {
      text-align: center;
      margin-top: 15px;
    }
    .login a {
      color: #0d47a1;
      text-decoration: none;
      font-weight: 500;
    }
    .login a:hover {
      text-decoration: underline;
    }
    @media (max-width: 768px) {
      .container-main {
        flex-direction: column;
        text-align: center;
      }
      .left-content {
        margin-right: 0;
        margin-bottom: 40px;
      }
      .college-name {
        font-size: 0.95rem;
      }
      .college-logo {
        height: 36px;
      }
    }
    .footer {
      text-align: center;
      font-size: 14px;
      color: #555;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-custom">
    <span class="navbar-brand">Student Task Manager</span>
    <div class="college-info">
      <img src="images.png" alt="College Logo" class="college-logo" />
      <span class="college-name">Guru Gobind Singh Indraprastha University</span>
    </div>
  </nav>

  <?php
    if ($showAlert) {
        echo ' <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Your account is now created and you can login
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div> ';
    }
    if ($showError) {
        echo ' <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> ' . $showError . '
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div> ';
    }
  ?>

  <div class="container-main">
    <div class="left-content">
      <h1 class="page-title">Student Task Manager</h1>
      <p class="subtitle">Welcome! Organize your tasks, set deadlines, and stay on track with your academic goals.</p>
    </div>

    <div class="signup-container">
      <h2>Create Your Account</h2>
      <form action="sign_up_page.php" method="post">
        <div class="mb-3">
          <label for="rollno" class="form-label">Roll Number<span class="required">*</span></label>
          <input type="text" class="form-control" id="rollno" name="rollno" placeholder="Enter Roll Number" required>
        </div>
        <div class="mb-3">
          <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="username" name="username" placeholder="Choose a Username" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Create Password" required>
        </div>
        <div class="mb-4">
          <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
          <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Sign Up</button>
        <p class="login">Already have an account? <a href="login_page.php">Login</a></p>
      </form>
    </div>
  </div>
  <div class="footer">
    <p>We sincerely thank <strong>DRDO-SSPL</strong> for giving us the opportunity to develop this project.</p>
  </div>
</body>
</html>