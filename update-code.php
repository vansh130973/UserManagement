<?php
require_once 'config.php';

if (isset($_POST['updateBtn'])) {
  $userName = $_SESSION['username'];
  $user_id = $_SESSION['user_id'];

  $name = $_POST['name'];
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];
  $address = $_POST['address'];
  $state = $_POST['state'];
  $district = $_POST['district'];

  $query = "UPDATE register SET 
    name='$name',
    firstname='$firstname', 
    lastname='$lastname',
    password='$password',
    email='$email', 
    address='$address', 
    state='$state',
    district='$district' 
    WHERE id='$user_id'";

  if (mysqli_query($conn, $query)) {
    $_SESSION['name'] = $name;
    $_SESSION['firstname'] = $firstname;
    $_SESSION['lastname'] = $lastname;
    $_SESSION['password'] = $password;
    $_SESSION['email'] = $email;
    $_SESSION['address'] = $address;
    $_SESSION['state'] = $state;
    $_SESSION['district'] = $district;
    $_SESSION['success']= "Profile Update Successfully";
    
    header("Location: update.php");
    exit();
  } else {
    $_SESSION['message'] = "Error updating profile!";
  }
}
?>