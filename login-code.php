<?php
require_once 'config.php';
session_start();

$errors = [];

if (isset($_POST['loginBtn'])) {

    $loginUsername = mysqli_real_escape_string($conn, $_POST['username']);
    $loginPassword = $_POST['password'];  // Don't escape passwords; use password_verify instead

    if (empty($loginUsername)) {
        $errors[] = "Username is required";
    }
    if (empty($loginPassword)) {
        $errors[] = "Password is required";
    }

    if (count($errors) === 0) {
        // Fetch user by username
        $stmt = $conn->prepare("SELECT * FROM register WHERE username = ?");
        $stmt->bind_param("s", $loginUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if ($loginPassword === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: user.php');
                exit();
            } else {
                $errors[] = "Incorrect password";
            }
        } else {
            $errors[] = "User not found";
        }

        $stmt->close();
    }

    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
}
?>
