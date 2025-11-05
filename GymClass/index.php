<?php
include 'includes/db_connect.php';
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CIS-344 Gym</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <main class="welcomeSection">
    <section class="welcomeMessage">
      <h2>Transform Your Body & Mind</h2>
      <p>Join CIS-344 Gym today where strength meets motivation!</p>
      <!-- Only show 'Get Started' if not logged in -->
      <?php if (!isset($_SESSION['user_id'])): ?>
        <form method="get" action="membership.php" style="display:inline;">
          <button type="submit">Get Started</button>
        </form>
      <?php endif; ?>
    </section>

    <section class="features">
      <div class="featureBox">
        <h3>Strength Training</h3>
        <p>State-of-the-art equipment to build your strength safely and effectively.</p>
      </div>
      <div class="featureBox">
        <h3>Fitness Classes</h3>
        <p>Join fun group sessions including yoga, HIIT, and more.</p>
      </div>
      <div class="featureBox">
        <h3>Personal Trainers</h3>
        <p>Get guidance from certified trainers who help you reach your goals.</p>
      </div>
    </section>
  </main>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
