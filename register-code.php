<?php
session_start();
include 'config.php';
require 'check.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if (isset($_POST['registerBtn']) && isset($_FILES['fileImage'])) {

    $name = $_POST['name'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $image = $_FILES['fileImage']['tmp_name'];
    $imageName = $_FILES['fileImage']['name'];

    $imagePath = 'uploads/' . $imageName;
    if (move_uploaded_file($image, $imagePath)) {

        $verification_id = rand(111111111, 999999999);

        $mailHtml = "Please confirm your account registration by clicking the button or link below: <a href='http://user.local/check.php?id=$verification_id'>http://user.local/check.php?id=$verification_id</a>";

        smtp_mailer($email, 'Account Verification', $mailHtml);

        $query = "INSERT INTO register (name, firstname, lastname, username, password, email, address, state, district, image ,verification_id) 
              VALUES ('$name', '$firstname', '$lastname', '$username', '$password', '$email', '$address', '$state', '$district', '$imagePath', '$verification_id')";

        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Account created successfully!";
            header("Location: register.php");
            exit();
        } else {
            $_SESSION['message'] = "Error: " . mysqli_error($conn);
            header("Location: index.php");
            echo 'User Name Already exists';
            exit();
        }

    } else {
        $_SESSION['message'] = "Error uploading image!";
        header("Location: index.php");
        echo 'Please select low size image';
        exit();
    }
}

function smtp_mailer($to, $subject, $msg)
{
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'vanshapanchal@gmail.com';
        $mail->Password   = 'larewrkkphvxcanr';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->SMTPDebug = 2; 

        $mail->setFrom('vanshapanchal@gmail.com', 'Vansh');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $msg;

        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}
?>