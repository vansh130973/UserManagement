<?php
session_start();
include 'config.php';
include 'include/header.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['forgetBtn'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);

    if (empty($username)) {
        $_SESSION['message'] = "<div class='alert alert-danger'>Username is required.</div>";
        header("Location: forget.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT email FROM register WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $email = $row['email'];
        $token = bin2hex(random_bytes(32));

        $stmt = $conn->prepare("UPDATE register SET reset_token=?, reset_expire=DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE username=?");
        $stmt->bind_param("ss", $token, $username);
        $stmt->execute();

        $resetLink = "http://user.local/resetPassword.php?token=$token";
        $msg = "Click to reset your password: <a href='$resetLink'>$resetLink</a>";

        if (smtp_mailer($email, 'Reset Your Password', $msg)) {
            $_SESSION['message'] = "<div class='alert alert-success'>Reset link sent to <b>$email</b>. Check your inbox.</div>";
        } else {
            $_SESSION['message'] = "<div class='alert alert-danger'>Failed to send reset email.</div>";
        }
        header("Location: forget.php");
        exit();
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger'>Username not found.</div>";
        header("Location: forget.php");
        exit();
    }
}

function smtp_mailer($to, $subject, $msg) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'vanshapanchal@gmail.com';
        $mail->Password = 'larewrkkphvxcanr';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('vanshapanchal@gmail.com', 'Vansh');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $msg;

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}
?>

<!-- HTML Form -->
<title>Forget Password</title>
<br><br>
<h3>Forget Password</h3><br>
<form method="post">
  <?php if (isset($_SESSION['message'])) {
      echo $_SESSION['message'];
      unset($_SESSION['message']);
  } ?>
  <input type="text" name="username" placeholder="Enter your username" class="form-control mb-3" required>
  <button type="submit" name="forgetBtn" class="btn btn-primary">Send Reset Link</button>
</form>
</body><br><br>
<?php include 'include/footer.php'; ?>
</html>