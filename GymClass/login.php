<?php
include 'includes/db_connect.php';

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['member_id'])) {
    header("Location: dashboard.php");
    exit();
}

$login_error = '';

if (isset($_POST['login_member'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validation
    if ($email === '' || $password === '') {
        $login_error = "Please enter both email and password.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $login_error = "Please enter a valid email address.";
    } else {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT member_id, full_name, password FROM members WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($member_id, $full_name, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['member_id'] = $member_id;
                $_SESSION['member_name'] = $full_name;

                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $login_error = "Invalid password.";
            }
        } else {
            $login_error = "Email not found. Please register first.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Member Login - CIS-344 Gym</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<section class="form-section">
  <h2>Member Login</h2>

  <?php if ($login_error): ?>
      <p style="color:red;"><?php echo htmlspecialchars($login_error); ?></p>
  <?php endif; ?>

  <form method="POST" action="">
    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <button type="submit" name="login_member">Login</button>
  </form>

  <p>Don't have an account? <a href="membership.php">Register here</a></p>
</section>

<?php include 'includes/footer.php'; ?>
</body>
</html>
