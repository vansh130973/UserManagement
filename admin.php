<?php
require_once 'config.php';
include 'include/header.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  echo "Access denied. Admins only.";
  exit();
}

if (isset($_GET['delete'])) {
  $usernameToDelete = $conn->real_escape_string($_GET['delete']);
  $checkRoleSql = "SELECT role FROM register WHERE username = '$usernameToDelete'";
  $checkRoleResult = $conn->query($checkRoleSql);
  if ($checkRoleResult && $checkRoleResult->num_rows > 0) {
    $row = $checkRoleResult->fetch_assoc();
    $deleteSql = "DELETE FROM register WHERE username = '$usernameToDelete'";
    if ($conn->query($deleteSql)) {
      echo "<script>alert('User deleted successfully.');</script>";
      exit();
    } else {
      echo "Error deleting user: " . $conn->error;
    }
  }
}

$limitOptions = [10, 15, 20];
$limit = isset($_GET['limit']) && in_array($_GET['limit'], $limitOptions) ? intval($_GET['limit']) : 10;

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$countResult = $conn->query("SELECT COUNT(*) AS total FROM register WHERE role != 'admin'");
$totalUsers = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalUsers / $limit);

// Get users with the specified limit and offset
$sql = "SELECT name, firstname, lastname, username, email, address, state, district 
        FROM register 
        WHERE role != 'admin' 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>
<link href="css/admin.css" rel="stylesheet">

<div>
  <br><br>
  <h2>Registered Users</h2>

  <div class="dropdown-wrapper">
    <form method="GET" action="">
      <label for="limit">Users per page:</label>
      <select name="limit" id="limit" onchange="this.form.submit()">
        <?php foreach ($limitOptions as $option): ?>
          <option value="<?= $option ?>" <?= $option == $limit ? 'selected' : '' ?>><?= $option ?></option>
        <?php endforeach; ?>
      </select>
    </form>
  </div>
</div>

<div class="table-wrapper">
  <table class="centered-table">
    <thead>
      <tr>
        <th>Name</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Address</th>
        <th>State</th>
        <th>District</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
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
              <a class="text text-danger"
                href="?delete=<?= urlencode($row['username']) ?>&page=<?= $page ?>&limit=<?= $limit ?>"
                onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="9">No users found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div class="pagination">
  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?page=<?= $i ?>&limit=<?= $limit ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
  <?php endfor; ?>
</div>
<br>

<?php include 'include/footer.php'; ?>