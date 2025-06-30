<?php
require_once 'config.php';

if (isset($_GET['username'])) {
  $username = $_GET['username'];

  $stmt = $conn->prepare("SELECT id FROM register WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    echo json_encode(['exists' => true]);
  } else {
    echo json_encode(['exists' => false]); 
  }
}
?>  