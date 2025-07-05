<?php
session_start();
include 'config.php';
include 'include/header.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $date = new DateTime();
    $date = $date->format('Y-m-d H:i:s'); // Current date and time for reset_expire

    // Check if token exists and is valid
    $stmt = $conn->prepare("SELECT username FROM register WHERE reset_token=? AND reset_expire > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        if (isset($_POST['resetBtn'])) {
            $newPassword = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($newPassword !== $confirmPassword) {
                $_SESSION['message'] = "<div class='alert alert-danger'>Passwords do not match.</div>";
            } else {

                $stmt = $conn->prepare("UPDATE register SET password=?, reset_token='$token', reset_expire=? WHERE reset_token=?");
                $stmt->bind_param("sss", $newPassword, $date, $token);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "<div class='alert alert-success'>Password updated successfully!</div>";
                    header("Location: login.php");
                    exit();
                } else {
                    $_SESSION['message'] = "<div class='alert alert-danger'>An error occurred. Please try again later.</div>";
                }
            }
        }
    } else {
        echo "<div class='alert alert-danger'>Invalid or expired token.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>Token not provided.</div>";
    exit();
}
?>

<!-- HTML Reset Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Add any CSS or bootstrap links here if needed -->
</head>
<body>
    <br><br>
    <h3>Reset Password</h3><br>

    <form method="post">
        <?php 
        if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        }
        ?>
        <input type="password" name="password" placeholder="New Password" class="form-control mb-2" required><br>
        <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control mb-2" required><br>
        <button type="submit" name="resetBtn" class="btn btn-success">Update Password</button>
    </form>
</body>
</html>
