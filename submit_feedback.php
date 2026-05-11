
<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "student") {
    header("Location: login.php");
    exit();
}

$messageText = "";

$categoriesStmt = $conn->query("SELECT * FROM categories ORDER BY category_name");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $category_id = $_POST["category_id"];
    $message = trim($_POST["message"]);
    $user_id = $_SESSION["user_id"];

    $stmt = $conn->prepare("
        INSERT INTO feedback (user_id, category_id, title, message, status)
        VALUES (?, ?, ?, ?, 'pending')
    ");
    $stmt->execute([$user_id, $category_id, $title, $message]);

    header("Location: view_feedback.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Submit Feedback</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="site-header">
  <div class="container header-content">
    <div class="logo-box">
      <img src="logo.png.png" alt="YIC Logo" class="logo-img">
      <div class="brand-text">
        <h2>YIC Feedback System</h2>
        <p class="header-subtitle">Welcome, <?php echo $_SESSION["name"]; ?></p>
      </div>
    </div>

    <nav class="header-nav">
      <a href="student_dashboard.php">Dashboard</a>
      <a href="submit_feedback.php" class="active">Submit Feedback</a>
      <a href="view_feedback.php">My Feedback</a>
      <a href="logout.php">Logout</a>
    </nav>
  </div>
</header>

<main class="container">
  <section class="card">
    <h2>Submit New Feedback</h2>
    <p>Please provide clear details to help us improve services and campus experience.</p>

    <form method="POST">
      <label for="feedbackTitle">Title</label>
      <input type="text" id="feedbackTitle" name="title" placeholder="Example: Slow Wi-Fi in Building A" required>

      <label for="feedbackCategory">Category</label>
      <select id="feedbackCategory" name="category_id" required>
        <?php foreach ($categories as $cat): ?>
          <option value="<?php echo $cat["category_id"]; ?>">
            <?php echo $cat["category_name"]; ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label for="feedbackMessage">Message</label>
      <textarea id="feedbackMessage" name="message" placeholder="Write your feedback here..." required></textarea>

      <button type="submit">Submit Feedback</button>
    </form>
  </section>
</main>

<footer class="bottom-footer">
  <div class="container footer-content">
    <p>&copy; 2026 YIC Student Feedback System</p>
  </div>
</footer>

</body>
</html>