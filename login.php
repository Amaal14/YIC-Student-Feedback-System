
<?php
session_start();
require_once "config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user["password"] === $password) {
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["name"] = $user["name"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["role"] = $user["role"];

        if ($user["role"] === "admin") {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: student_dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
  <main class="center-box auth-wrapper">
    <section class="card auth-card">
      <img src="logo.png.png" alt="YIC Logo" class="auth-logo">

      <h2>Login</h2>
      <p class="form-help">Enter your email and password to continue</p>

      <?php if (!empty($error)): ?>
        <p style="color:red; text-align:center;"><?php echo $error; ?></p>
      <?php endif; ?>

      <form method="POST">
        <label for="loginEmail">Email</label>
        <input type="email" id="loginEmail" name="email" placeholder="Enter your email" required>

        <label for="loginPassword">Password</label>
        <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required>

        <button type="submit">Login</button>
      </form>

      <p>Do not have an account? <a href="register.php">Register here</a></p>
    </section>
  </main>
</body>
</html>