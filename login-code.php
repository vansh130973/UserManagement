<?php
require_once 'config.php';
$errors = [];

if (isset($_POST['loginBtn'])) {

    $loginUsername = mysqli_real_escape_string($conn, $_POST['username']);
    $loginPassword = $_POST['password'];  // Plaintext comparison for now (use password_verify if hashed)

    if (empty($loginUsername)) {
        $errors[] = "Username is required";
    }
    if (empty($loginPassword)) {
        $errors[] = "Password is required";
    }

    if (count($errors) === 0) {
        $stmt = $conn->prepare("SELECT * FROM register WHERE username = ?");
        $stmt->bind_param("s", $loginUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if ($loginPassword === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                $verification_status = $user['verification_status'];

                if ($verification_status == 0) {
                    $_SESSION['message'] = "<div class='alert alert-warning'>Email not verified. Please check your email and verify your account.</div>";
                    header('Location: login.php');
                    exit();
                } else {
                    $_SESSION['IS_LOGIN'] = 1;
                    if ($user['role'] === 'admin') {
                        header('Location: admin.php');
                    } else {
                        header('Location: user.php');
                    }
                    exit();
                }
            } else {
                $errors[] = "Incorrect password";
            }
        } else {
            $errors[] = "User not found";
        }

        $stmt->close();
    }

    if (!empty($errors)) {
        $_SESSION['message'] = "<div class='alert alert-danger'>" . implode("<br>", $errors) . "</div>";
        header('Location: login.php');
        exit();
    }
}
?>