
<?php
session_start();
require_once "config/db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirm_password"]);

    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            $error = "This email is already registered.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'student')");
            $stmt->execute([$name, $email, $password]);

            $success = "Account created successfully. You can login now.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
  <main class="center-box auth-wrapper">
    <section class="card auth-card">
      <img src="logo.png.png" alt="YIC Logo" class="auth-logo">

      <h2>Create Account</h2>
      <p class="form-help">New accounts are registered as student accounts</p>

      <?php if (!empty($error)): ?>
        <p style="color:red; text-align:center;"><?php echo $error; ?></p>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <p style="color:green; text-align:center;"><?php echo $success; ?></p>
      <?php endif; ?>

      <form method="POST">
        <label for="registerName">Full Name</label>
        <input type="text" id="registerName" name="name" placeholder="Enter your full name" required>

        <label for="registerEmail">Email</label>
        <input type="email" id="registerEmail" name="email" placeholder="Enter your email" required>

        <label for="registerPassword">Password</label>
        <input type="password" id="registerPassword" name="password" placeholder="Enter your password" required>

        <label for="confirmPassword">Confirm Password</label>
        <input type="password" id="confirmPassword" name="confirm_password" placeholder="Repeat your password" required>

        <button type="submit">Create Account</button>
      </form>

      <p>Already have an account? <a href="login.php">Login here</a></p>
    </section>
  </main>
</body>
</html>