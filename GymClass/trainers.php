<?php
include 'includes/db_connect.php'; // Database connection
include 'includes/header.php';     // Header with navigation

// Feedback message for form submission
$feedbackMessage = '';

// Add Trainer form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_trainer') {
    $trainerName = trim($_POST['trainer_name'] ?? '');
    $trainerSpecialty = trim($_POST['trainer_specialty'] ?? '');
    $trainerExperience = intval($_POST['trainer_experience'] ?? 0);
    $trainerBio = trim($_POST['trainer_bio'] ?? '');

    // Validation
    if ($trainerName === '') {
        $feedbackMessage = "Please provide the trainer's full name.";
    } else {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO trainers (full_name, specialty, experience_years, bio) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $trainerName, $trainerSpecialty, $trainerExperience, $trainerBio);
        if ($stmt->execute()) {
            $feedbackMessage = "Trainer added successfully!";
            header("Location: " . $_SERVER['PHP_SELF']); // Refresh page to show new trainer
            exit();
        } else {
            $feedbackMessage = "Database error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Our Trainers - CIS-344 Gym</title>
<link rel="stylesheet" href="css/style.css">
<style>
/* Styling for feedback messages */
.feedbackSuccess { color: #9fe39f; }
.feedbackError { color: #ff9b9b; }
</style>
</head>
<body>

<section class="trainerDashboard">
  <h2>Professional Trainers You Can Trust</h2>

  <!-- Display all trainers -->
  <div class="trainerList">
    <?php
    $query = "SELECT trainer_id, full_name, specialty, experience_years, bio FROM trainers ORDER BY experience_years ASC";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='trainerProfile'>";
            echo "<h3>" . htmlspecialchars($row['full_name']) . "</h3>";
            echo "<p><strong>Specialty:</strong> " . htmlspecialchars($row['specialty']) . "</p>";
            echo "<p><strong>Experience:</strong> " . intval($row['experience_years']) . " Years</p>";
            echo "<p>\"" . nl2br(htmlspecialchars($row['bio'])) . "\"</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No trainers found.</p>";
    }
    ?>
  </div>

  <hr>

  <!-- Add Trainer Form -->
  <div class="trainerForm">
    <h3>Add a New Trainer</h3>
    <?php if ($feedbackMessage): ?>
      <p class="<?php echo strpos($feedbackMessage,'error')!==false ? 'feedbackError' : 'feedbackSuccess'; ?>">
        <?php echo htmlspecialchars($feedbackMessage); ?>
      </p>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="hidden" name="action" value="add_trainer">
      <label>Full Name:</label>
      <input type="text" name="trainer_name" required>

      <label>Specialty:</label>
      <input type="text" name="trainer_specialty" required>

      <label>Experience (Years):</label>
      <input type="number" name="trainer_experience" min="0" required>

      <label>Bio:</label>
      <textarea name="trainer_bio" rows="3" required></textarea>

      <button type="submit">Add Trainer</button>
    </form>
  </div>

</section>

<?php include 'includes/footer.php'; ?>
<?php $conn->close(); ?>
</body>
</html>
