<?php
include 'include/header.php';
?>

<?php
require_once 'config.php';

if (isset($_GET['delete'])) {
  $usernameToDelete = $conn->real_escape_string($_GET['delete']);
  $checkRoleSql = "SELECT role FROM register WHERE username = '$usernameToDelete'";
  $checkRoleResult = $conn->query($checkRoleSql);
  if ($checkRoleResult && $checkRoleResult->num_rows > 0) {
    $row = $checkRoleResult->fetch_assoc();
    if ($row['role'] === 'admin') {
      echo "Cannot delete admin users.";
    } else {
      $deleteSql = "DELETE FROM register WHERE username = '$usernameToDelete'";
      if ($conn->query($deleteSql)) {
        echo "<script>alert('User deleted successfully.'); window.location.href = '" . $_SERVER['PHP_SELF'] . "';</script>";
        exit();
      } else {
        echo "Error deleting user: " . $conn->error;
      }
    }
  }
}


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  echo "Access denied. Admins only.";
  exit();
}

$sql = "SELECT name, firstname, lastname, username, email, address, state, district, image FROM register WHERE role != 'admin'";
$result = $conn->query($sql);
?>

<style>
  table {
    border-collapse: collapse;
    width: 100%;
    margin: 20px auto;
    display: flex;
    justify-content: center;
  }

  th,
  td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: center;
  }

  th {
    background-color: rgb(0, 0, 0);
  }

  img {
    max-width: 100px;
    height: auto;
  }
</style>
</head>

<body>
  <h2 style="text-align:center;">Registered Users</h2>
  <table>
    <tr>
      <th>Name</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Username</th>
      <th>Email</th>
      <th>Address</th>
      <th>State</th>
      <th>District</th>
      <th>Image</th>
      <th>Delete</th>
    </tr>
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['firstname']) ?></td>
          <td><?= htmlspecialchars($row['lastname']) ?></td>
          <td><?= htmlspecialchars($row['username']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['address']) ?></td>
          <td><?= htmlspecialchars($row['state']) ?></td>
          <td><?= htmlspecialchars($row['district']) ?></td>
          <td>
            <?php if (!empty($row['image'])): ?>
              <img src="<?= $row['image'] ?>" alt="Profile Image" style="max-width: 150px; max-height: 150px;" />

            <?php else: ?>
              No Image
            <?php endif; ?>
          </td>
          <td>
            <a style="color: rgb(195,29,58);" href="?delete=<?= urlencode($row['username']) ?>"
              onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
          </td>

        </tr>
      <?php endwhile; ?>
    <?php endif; ?>
  </table>
</body>

</html>


<?php include 'include/footer.php'; ?>