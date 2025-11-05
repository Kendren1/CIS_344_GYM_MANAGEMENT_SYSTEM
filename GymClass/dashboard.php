<?php
include 'includes/db_connect.php';

// Redirect user if not logged in
if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit();
}

$member_id = $_SESSION['member_id'];
$member_name = $_SESSION['member_name'];

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll_class'])) {
    $class_id = intval($_POST['class_id']);
    if ($class_id > 0) {
        // Check if already enrolled
        $check_sql = "SELECT * FROM enrollments WHERE member_id=$member_id AND class_id=$class_id";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows === 0) {
            // Enroll user safely using prepared statement
            $stmt = $conn->prepare("INSERT INTO enrollments (member_id, class_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $member_id, $class_id);
            if ($stmt->execute()) {
                $message = "Successfully enrolled in the class!";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "You are already enrolled in this class.";
        }
    }
}

// Get all classes the member is enrolled in
$enrolled_sql = "SELECT c.class_id, c.class_name, c.description, t.full_name AS trainer_name
                 FROM classes c
                 JOIN enrollments e ON c.class_id = e.class_id
                 LEFT JOIN trainers t ON c.trainer_id = t.trainer_id
                 WHERE e.member_id = $member_id";
$enrolled_result = $conn->query($enrolled_sql);

// Get all classes for enrollment dropdown
$all_classes_sql = "SELECT c.class_id, c.class_name
                    FROM classes c
                    LEFT JOIN enrollments e ON c.class_id = e.class_id AND e.member_id=$member_id
                    ORDER BY c.class_name ASC";
$all_classes_result = $conn->query($all_classes_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Member Dashboard - CIS-344 Gym</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<section class="dashboard">
  <h2>Welcome, <?php echo htmlspecialchars($member_name); ?>!</h2>

  <!-- Show enrollment messages -->
  <?php if ($message) echo "<p style='color:lightgreen;'>$message</p>"; ?>

  <h3>Your Enrolled Classes:</h3>
  <?php
  if ($enrolled_result->num_rows > 0) {
      echo "<ul>";
      while ($row = $enrolled_result->fetch_assoc()) {
          echo "<li><strong>" . htmlspecialchars($row['class_name']) . "</strong> (Trainer: " . ($row['trainer_name'] ?? 'TBD') . ")<br>";
          echo htmlspecialchars($row['description']) . "</li>";
      }
      echo "</ul>";
  } else {
      echo "<p>You are not enrolled in any classes yet.</p>";
  }
  ?>

  <hr>

  <h3>Enroll in a New Class</h3>
  <form method="POST" action="">
    <label>Select a Class:</label>
    <select name="class_id" required>
      <option value="">-- Choose a Class --</option>
      <?php
      if ($all_classes_result->num_rows > 0) {
          while ($row = $all_classes_result->fetch_assoc()) {
              $disable = '';
              $check = $conn->query("SELECT * FROM enrollments WHERE member_id=$member_id AND class_id={$row['class_id']}");
              if ($check->num_rows > 0) $disable = 'disabled';
              echo "<option value='{$row['class_id']}' $disable>" . htmlspecialchars($row['class_name']) . "</option>";
          }
      }
      ?>
    </select>
    <button type="submit" name="enroll_class">Enroll</button>
  </form>
</section>

<?php include 'includes/footer.php'; ?>
<?php $conn->close(); ?>
</body>
</html>
