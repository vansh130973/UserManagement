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
  $date = $_POST['date'];
  $email = $_POST['email'];
  $address = $_POST['address'];
  $state = $_POST['state'];
  $district = $_POST['district'];

  if (isset($_FILES['fileImage']) && $_FILES['fileImage']['error'] == 0) {
    $imageTmp = $_FILES['fileImage']['tmp_name'];
    $imageName = $_FILES['fileImage']['name'];

    $imagePath = 'uploads/' . time() . '_' . basename($imageName);

    if (move_uploaded_file($imageTmp, $imagePath)) {
      $query = "UPDATE register SET 
        name='$name',
        firstname='$firstname', 
        lastname='$lastname',
        username='$username',
        password='$password',
        DoB='$date', 
        email='$email', 
        address='$address', 
        state='$state',
        district='$district', 
        image='$imagePath'
        WHERE id='$user_id'";
    } else {
      $_SESSION['message'] = "Error uploading image!";
    }
  } else {
    $query = "UPDATE register SET 
      name='$name',
      firstname='$firstname', 
      lastname='$lastname',
      username='$username',
      password='$password',
      DoB='$date', 
      email='$email', 
      address='$address', 
      state='$state',
      district='$district'
      WHERE id='$user_id'";
  }

  if (mysqli_query($conn, $query)) {
    $_SESSION['name'] = $name;
    $_SESSION['firstname'] = $firstname;
    $_SESSION['lastname'] = $lastname;
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;
    $_SESSION['date'] = $date;
    $_SESSION['email'] = $email;
    $_SESSION['address'] = $address;
    $_SESSION['state'] = $state;
    $_SESSION['district'] = $district;

    if (isset($imagePath)) {
      $_SESSION['image'] = $imagePath;
    }

    $_SESSION['success'] = "Profile updated successfully";
    header("Location: update.php");
    exit();
  } else {
    $_SESSION['message'] = "Error updating profile!";
  }
}
?>