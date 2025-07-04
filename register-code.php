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
    $date = $_POST['date'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $image = $_FILES['fileImage']['tmp_name'];
    $imageName = $_FILES['fileImage']['name'];

    $imagePath = 'uploads/' . $imageName;
    if (move_uploaded_file($image, $imagePath)) {

        $verification_id = rand(111111111, 999999999);

        $mailHtml = "Please confirm your account registration by clicking the button or link below: 
        <a href='http://user.local/check.php?id=$verification_id'>http://user.local/check.php?id=$verification_id</a>";

        $mailSent = smtp_mailer($email, 'Account Verification', $mailHtml);

        if ($mailSent) {
            $query = "INSERT INTO register (name, firstname, lastname, username, password, DoB, email, address, state, district, image, verification_id) 
              VALUES ('$name', '$firstname', '$lastname', '$username', '$password', '$date', '$email', '$address', '$state', '$district', '$imagePath', '$verification_id')";

            if (mysqli_query($conn, $query)) {
                $_SESSION['message'] = "<div class='alert alert-success'>Account created successfully! Email sent successfully. Please check your email to verify your account.</div>";
                header("Location: register.php");
                exit();
            } else {
                $_SESSION['message'] = "<div class='alert alert-danger'>Account creation failed: " . mysqli_error($conn) . "</div>";
                header("Location: register.php");
                exit();
            }
        }
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger'>Error uploading image! Please select a smaller image.</div>";
        header("Location: register.php");
        exit();
    }
}

function smtp_mailer($to, $subject, $msg)
{
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'vanshapanchal@gmail.com';
        $mail->Password = 'larewrkkphvxcanr';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->SMTPDebug = 0; // Set to 0 to hide debug output

        $mail->setFrom('vanshapanchal@gmail.com', 'Vansh');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $msg;

        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>