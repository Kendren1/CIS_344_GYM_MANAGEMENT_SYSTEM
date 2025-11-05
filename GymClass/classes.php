<?php
// Include database connection
include 'includes/db_connect.php';

// Check if a class's enrolled members should be shown
$show_class_id = isset($_GET['show_members']) ? intval($_GET['show_members']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Our Classes - CIS-344 Gym</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<section class="class-list">
  <h2>Available Classes</h2>
  <p>Enroll in a class to take your fitness to the next level!</p>

  <?php
  // Get all classes with trainer info and count of enrolled members
  $sql = "SELECT c.class_id, c.class_name, c.description, t.full_name AS trainer_name, COUNT(e.member_id) AS enrolled_count
          FROM classes c
          LEFT JOIN trainers t ON c.trainer_id = t.trainer_id
          LEFT JOIN enrollments e ON c.class_id = e.class_id
          GROUP BY c.class_id
          ORDER BY c.class_name ASC";
  $result = $conn->query($sql);

  if ($result->num_rows > 0):
      while ($row = $result->fetch_assoc()):
          $class_id = intval($row['class_id']);
          $class_name = htmlspecialchars($row['class_name']);
          $description = htmlspecialchars($row['description']);
          $trainer_name = htmlspecialchars($row['trainer_name'] ?? 'TBD');
          $enrolled_count = intval($row['enrolled_count']);
  ?>
      <div class='class-card'>
          <h3><?php echo $class_name; ?></h3>
          <p><?php echo $description; ?></p>
          <p><strong>Trainer:</strong> <?php echo $trainer_name; ?></p>
          <p><strong>Enrolled Members:</strong> <?php echo $enrolled_count; ?></p>

          <?php if ($enrolled_count > 0): ?>
            <?php
            // Toggle: hide if already showing
            $toggle_class_id = ($show_class_id === $class_id) ? 0 : $class_id;
            ?>
            <form method="get" style="display:inline;">
                <input type="hidden" name="show_members" value="<?php echo $toggle_class_id; ?>">
                <button type="submit" class="toggle-btn">
                    <?php echo ($show_class_id === $class_id) ? 'Hide Enrolled Members' : 'View Enrolled Members'; ?>
                </button>
            </form>

            <?php if ($show_class_id === $class_id): ?>
                <ul class="member-list">
                  <?php
                  $members_sql = "SELECT m.full_name, m.email FROM enrollments e 
                                  JOIN members m ON e.member_id = m.member_id
                                  WHERE e.class_id = $class_id";
                  $members_result = $conn->query($members_sql);
                  while ($m = $members_result->fetch_assoc()):
                      $m_name = htmlspecialchars($m['full_name']);
                      $m_email = htmlspecialchars($m['email']);
                      echo "<li>$m_name ($m_email)</li>";
                  endwhile;
                  ?>
                </ul>
            <?php endif; ?>
          <?php endif; ?>
      </div>
  <?php
      endwhile;
  else:
      echo "<p>No classes found.</p>";
  endif;
  ?>
</section>

<hr>

<section class="form-section">
  <h2>Add a New Class</h2>
  <?php
  $add_class_msg = '';
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_class'])) {
      $class_name = trim($_POST['class_name']);
      $class_desc = trim($_POST['class_description']);
      $trainer_id = intval($_POST['trainer_id']);

      if ($class_name === '' || $trainer_id === 0) {
          $add_class_msg = "Please fill in all required fields.";
      } else {
          $stmt = $conn->prepare("INSERT INTO classes (class_name, description, trainer_id) VALUES (?, ?, ?)");
          $stmt->bind_param("ssi", $class_name, $class_desc, $trainer_id);
          if ($stmt->execute()) {
              header("Location: " . $_SERVER['PHP_SELF']);
              exit();
          } else {
              $add_class_msg = "Error: " . $stmt->error;
          }
          $stmt->close();
      }
  }

  if ($add_class_msg) echo "<p class='feedback'>" . htmlspecialchars($add_class_msg) . "</p>";
  ?>

  <form method="POST" action="">
    <label>Class Name:</label>
    <input type="text" name="class_name" required>

    <label>Description:</label>
    <textarea name="class_description" rows="3" required></textarea>

    <label>Trainer:</label>
    <select name="trainer_id" required>
      <option value="">Select Trainer</option>
      <?php
      $trainer_sql = "SELECT trainer_id, full_name FROM trainers ORDER BY full_name ASC";
      $trainer_result = $conn->query($trainer_sql);
      if ($trainer_result->num_rows > 0) {
          while ($trainer = $trainer_result->fetch_assoc()) {
              $t_id = intval($trainer['trainer_id']);
              $t_name = htmlspecialchars($trainer['full_name']);
              echo "<option value='$t_id'>$t_name</option>";
          }
      }
      ?>
    </select>

    <button type="submit" name="add_class">Add Class</button>
  </form>
</section>

<?php include 'includes/footer.php'; ?>
<?php $conn->close(); ?>
</body>
</html>
