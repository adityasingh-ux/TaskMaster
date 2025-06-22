<?php
$severname = "localhost";
$username = "root";
$password = "";
$database = "task_db";


$conn = mysqli_connect($severname, $username, $password, $database);


if (!$conn) {
  die("sorry we failed to connect:" . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $title = $_POST["title"];
  $description = $_POST["description"];
  $due_date = $_POST["due_date"];

  $sql = "INSERT INTO `task_table` (`title`,`description`, `due_date`) VALUES ('$title','$description', '$due_date')";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    echo " the record was not inserted " . mysqli_error($conn);
  }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Task - TaskMaster</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to left, #4595e4, white);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }



    .task-form-container {
      background: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      width: 400px;
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
      color: #333;
    }

    input[type="text"],
    textarea,
    input[type="datetime-local"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #981c1c;
      border-radius: 5px;
      font-size: 14px;
    }

    textarea {
      resize: vertical;
      min-height: 80px;
    }

    button {
      width: 100%;
      padding: 10px;
      background: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 20px;
    }

    button:hover {
      background: #0056b3;
    }
  </style>
</head>

<body>

  <div class="task-form-container">
    <h2>Add New Task</h2>
    <form action="create_task.php" method="post">
      <label for="title">Task Title</label>
      <input type="text" id="title" name="title" placeholder="e.g. Submit Assignment" required>

      <label for="description">Task Description</label>
      <textarea id="description" name="description" placeholder="Details about the task..." required></textarea>

      <label for="due_date">Due Date</label>
      <input type="date" id="due_date" name="due_date">
      <button type="submit">Add Task</button>
    </form>
  </div>


</body>

</html>