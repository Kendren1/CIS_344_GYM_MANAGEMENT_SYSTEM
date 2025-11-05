<?php
include 'includes/db_connect.php';

//Update existing member information
if (isset($_POST['update_member']) && isset($_SESSION['member_id'])) {
    $member_id = $_SESSION['member_id'];
    $new_name = $conn->real_escape_string($_POST['full_name']);
    $new_email = $conn->real_escape_string($_POST['email']);
    $new_type = $conn->real_escape_string($_POST['membership_type']);

    $sql_update = "UPDATE members 
                   SET full_name='$new_name', email='$new_email', membership_type='$new_type'
                   WHERE member_id='$member_id'";

    if ($conn->query($sql_update)) {
        $_SESSION['member_name'] = $new_name;
        $update_msg = "Profile updated successfully!";
    } else {
        $update_msg = "Error: " . $conn->error;
    }
}

// Register a new member
if (isset($_POST['register_member']) && !isset($_SESSION['member_id'])) {
    $name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $type = $conn->real_escape_string($_POST['membership_type']);

    $check_sql = "SELECT * FROM members WHERE email='$email'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        $register_msg = "Email already registered. Please log in.";
    } else {
        $sql_insert = "INSERT INTO members (full_name, email, password, membership_type)
                       VALUES ('$name', '$email', '$password', '$type')";
        if ($conn->query($sql_insert)) {
            $member_id = $conn->insert_id;
            $_SESSION['member_id'] = $member_id;
            $_SESSION['member_name'] = $name;
            $register_msg = "Registration successful! Welcome, $name!";
        } else {
            $register_msg = "Error: " . $conn->error;
        }
    }
}

// Load current member information
if (isset($_SESSION['member_id'])) {
    $member_id = $_SESSION['member_id'];
    $sql = "SELECT * FROM members WHERE member_id='$member_id'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $member = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Membership - CIS-344 Gym</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<section class="form-section">
    <h2>Membership</h2>

    <?php
    // Displays feedback messages.
    if (isset($update_msg)) echo "<p style='color:lightgreen;'>$update_msg</p>";
    if (isset($register_msg)) echo "<p style='color:lightgreen;'>$register_msg</p>";
    ?>

    <?php if (isset($_SESSION['member_id']) && isset($member)): ?>
        <h3>Welcome, <?php echo htmlspecialchars($member['full_name']); ?>!</h3>
        <p>You are currently subscribed to: <strong><?php echo htmlspecialchars($member['membership_type']); ?></strong></p>

        <h3>Update Your Info</h3>
        <form method="POST" action="">
            <label>Full Name:</label>
            <input type="text" name="full_name" value="<?php echo htmlspecialchars($member['full_name']); ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($member['email']); ?>" required>

            <label>Membership Type:</label>
            <select name="membership_type" required>
                <option value="Monthly" <?php if($member['membership_type'] == "Monthly") echo "selected"; ?>>Monthly - $50</option>
                <option value="Yearly" <?php if($member['membership_type'] == "Yearly") echo "selected"; ?>>Yearly - $500</option>
            </select>

            <button type="submit" name="update_member">Update Info</button>
        </form>

    <?php else: ?>
        <h3>Register as a New Member</h3>
        <form method="POST" action="">
            <label>Full Name:</label>
            <input type="text" name="full_name" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <label>Membership Type:</label>
            <select name="membership_type" required>
                <option value="">Select...</option>
                <option value="Monthly">Monthly - $50</option>
                <option value="Yearly">Yearly - $500</option>
            </select>

            <button type="submit" name="register_member">Register</button>
        </form>
    <?php endif; ?>

</section>

<?php include 'includes/footer.php'; ?>
</body>
</html>
