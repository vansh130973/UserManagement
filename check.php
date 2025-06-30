<?php
require 'config.php';

// Check if 'id' exists in the query string
if (isset($_GET['id'])) {
    $verification_id = mysqli_real_escape_string($conn, $_GET['id']); 

    if (empty($verification_id)) {
        echo "Invalid verification ID.";
        exit();
    }

    $query_check = "SELECT * FROM register WHERE verification_id='$verification_id'";
    $result_check = mysqli_query($conn, $query_check);

    if (mysqli_num_rows($result_check) == 0) {
        echo "No user found with this verification ID.";
        exit();
    }

    $query_check_status = "SELECT * FROM register WHERE verification_id='$verification_id' AND verification_status='1'";
    $result_check_status = mysqli_query($conn, $query_check_status);

    if (mysqli_num_rows($result_check_status) > 0) {
        echo "This account has already been verified.";
        exit();
    }

    $query = "UPDATE register SET verification_status='1' WHERE verification_id='$verification_id'";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_affected_rows($conn) > 0) {
        echo "Your account has been verified.";
    } else {
        echo "Error: The verification failed. Please check the verification link or try again later.";
        if (mysqli_error($conn)) {
            echo " MySQL Error: " . mysqli_error($conn);
        }
    }
} else {
    echo "Verification ID is missing in the URL.";
}
?>
<a href="login.php">Click here to Login</a>