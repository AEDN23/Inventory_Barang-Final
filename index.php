<?php
require "BackEnd/function.php";
session_start();

if (isset($_POST['login'])) {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = md5($_POST['password']);

  $query = mysqli_query($conn, "
      SELECT user.id, user.username, role.role_name 
      FROM user 
      JOIN role ON user.role_id = role.id 
      WHERE user.username='$username' AND user.password='$password'
  ");

  if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
    $_SESSION['id'] = $row['id'];
    $_SESSION['role'] = $row['role_name'];

    // Redirect berdasarkan role
    if ($row['role_name'] == 'admin') {
      header("Location: admin/index");
    } elseif ($row['role_name'] == 'user') {
      header("Location: user/index");
    }
    exit();
  } else {
    $error = "Username atau password salah!";
  }
}


?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LOGIN INVENTORY | ELASTOMIX</title>
  <link href="../assets/img/images.jpg" rel="icon">
  <link href="../css/bootstrap@5.3.3.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="../css/styles.css" rel="stylesheet" />
  <link href="../css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
  <script src="../js/jquery-3.6.0.min.js"></script>
  <script src="../js/bundle.js"></script>
  <script src="../js/all.min.js" crossorigin="anonymous"></script>
  <style>
    body,
    html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: Arial, sans-serif;
    }

    .background {
      background-image: url('../assets/img/WhatsApp Image 2024-12-04 at 08.39.56_4fb37ef0.jpg');
      background-size: cover;
      background-position: center;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-container {
      background-color: rgba(255, 255, 255, 0.8);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1), 0 0 5px rgba(0, 0, 0, 0.2);
      width: 350px;
      border: 1px solid black;
    }

    h2 {
      text-align: center;
      margin-top: 50;

    }


    .form-group {
      margin-bottom: 20px;
      width: 105%;
    }

    label {
      display: block;
      margin-bottom: 5px;
    }

    input {
      width: calc(100% - 20px);
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-right: 10px;
    }

    footer {
      background-color: black;
      color: white;
      text-align: center;
      padding: 1px 0;
      position: absolute;
      bottom: 0;
      width: 100%;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: blue;
    }
  </style>
</head>

<body>
  <div class="background">
    <div class="login-container">
      <h2>Form Login</h2>
      <?php if (isset($error)): ?>
        <p style="color: red; text-align: center;"><?= $error; ?></p>
      <?php endif; ?>
      <div style="opacity: 20px;">
        <form method="post">
          <div class="form-group">
            <input type="text" id="username" name="username" placeholder="Masukkan username " required>
          </div>
          <div class="form-group">
            <input type="password" id="password" name="password" placeholder="Masukkan Password" required>
          </div>
          <button type="submit" name="login">Login</button>
          <p style="margin-top: 20px;"><a href="../" style="text-decoration: none; color:black">Kembali </a></p>
        </form>
      </div>
    </div>
  </div>
  <footer class="py-2 bg-dark text-light mt-auto">
    <div class="container-fluid px-4">
      <div class="d-flex align-items-center justify-content-between small">
        <div class="text-muted">&copy; PT Elastomix Indonesia | PGA & IT DEPT.</div>
      </div>
    </div>
  </footer>

  <script src="../js/all.min2.js" crossorigin="anonymous"></script>
  <script src="../js/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
  <script src="../js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="../js/scripts.js"></script>
  <script src="../js/Chart.min.js" crossorigin="anonymous"></script>
  <script src="../assets/demo/chart-area-demo.js"></script>
  <script src="../assets/demo/chart-bar-demo.js"></script>
  <script src="../js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
  <script src="../js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
  <script src="../assets/demo/datatables-demo.js"></script>
  <script src="../js/bootstrap.bundle2.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>