<?php
// Header section for all pages;
?>
<header>
  <h1>CIS-344 Gym</h1>
  <nav>
    <!-- Main navigation links -->
    <a href="index.php">Home</a>
    <a href="classes.php">Classes</a>
    <a href="trainers.php">Trainers</a>
    <a href="membership.php">Membership</a>
    <a href="dashboard.php">Dashboard</a>

    <!-- Show Login if user not logged in, else show greeting and logout -->
    <?php if (!isset($_SESSION['member_name'])): ?>
      <a href="login.php">Login</a>
    <?php else: ?>
      <span style="margin-left:12px; color:#ccc;">Hello, <?php echo htmlspecialchars($_SESSION['member_name']); ?></span>
      <a href="logout.php" style="margin-left:10px;">Logout</a>
    <?php endif; ?>
  </nav>
</header>
