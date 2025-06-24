<?php
include 'config.php';

if (isset($_POST['registerBtn'])) {

    $name = $_POST['name'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $district = $_POST['district'];

    $query = "INSERT INTO register (name, firstname, lastname, username, password, email, address, state, district) 
        VALUES ('$name', '$firstname', '$lastname', '$username', '$password', '$email', '$address', '$state', '$district')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Account created successfully!";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['message'] = "Error: " . mysqli_error($conn);
        header("Location: index.php");
        exit();
    }
}
?>