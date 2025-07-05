<?php
require_once 'config.php';
include 'include/header.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  echo "Access denied. Admins only.";
  exit();
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$limitOptions = [10, 20, 30, 50, 100];
$limit = isset($_GET['limit']) && in_array($_GET['limit'], $limitOptions) ? intval($_GET['limit']) : 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Sort order handling
$sort = isset($_GET['sort']) && $_GET['sort'] === 'username_desc' ? 'username_desc' : 'username_asc';
$orderBy = $sort === 'username_desc' ? 'username DESC' : 'username ASC';

// Handle user deletion
if (isset($_GET['delete'])) {
  $usernameToDelete = $conn->real_escape_string($_GET['delete']);
  $checkRoleSql = "SELECT role FROM register WHERE username = '$usernameToDelete'";
  $checkRoleResult = $conn->query($checkRoleSql);

  if ($checkRoleResult && $checkRoleResult->num_rows > 0) {
    $deleteSql = "DELETE FROM register WHERE username = '$usernameToDelete'";
    if ($conn->query($deleteSql)) {
      echo "<script>alert('User deleted successfully.'); window.location.href='?page=$page&limit=$limit&search=" . urlencode($search) . "&sort=$sort';</script>";
      exit();
    } else {
      echo "Error deleting user: " . $conn->error;
    }
  }
}

// Count total users
$countSql = "SELECT COUNT(*) AS total FROM register WHERE role != 'admin'";
if (!empty($search)) {
  $countSql .= " AND (name LIKE '%$search%' OR username LIKE '%$search%' OR email LIKE '%$search%')";
}
$countResult = $conn->query($countSql);
$totalUsers = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalUsers / $limit);

// Fetch paginated user list
$sql = "SELECT name, firstname, lastname, username, email, address, state, district 
        FROM register WHERE role != 'admin'";
if (!empty($search)) {
  $sql .= " AND (name LIKE '%$search%' OR username LIKE '%$search%' OR email LIKE '%$search%')";
}
$sql .= " ORDER BY $orderBy LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<link href="css/admin.css" rel="stylesheet">
<style>
  input[type="text"] {
    width: 200px;
    padding: 6px;
    margin-right: 10px;
    margin-left: 330px;
    border: none;
    border-radius: 5px;
  }

  .cover-container {
    max-width: 55%;
  }
</style>

<div>
  <br><br>
  <h2>Registered Users</h2>
  <p>
    Showing <?= $totalUsers ?> Result<?= $totalUsers != 1 ? 's' : '' ?>
    <?= !empty($search) ? "for search: <strong>" . htmlspecialchars($search) . "</strong>" : '' ?>
  </p>

  <span class="dropdown-wrapper m-3 float-md-start">
    <form method="GET">
      <label for="limit">Users per page:</label>
      <select name="limit" id="limit" onchange="this.form.submit()">
        <?php foreach ($limitOptions as $option): ?>
          <option value="<?= $option ?>" <?= $option == $limit ? 'selected' : '' ?>><?= $option ?></option>
        <?php endforeach; ?>
      </select>

      <input type="text" name="search" id="search"
        value="<?= htmlspecialchars($search) ?>" placeholder="Search User">
      <input type="hidden" name="sort" value="<?= $sort ?>">
      <button type="submit" class="btn btn-success">Search</button>
    </form>
  </span>
</div>

<div class="table-wrapper">
  <table class="centered-table">
    <thead>
      <tr>
        <th>Name</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>
          <a href="?sort=<?= $sort === 'username_asc' ? 'username_desc' : 'username_asc' ?>&page=<?= $page ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>">
            Username <?= $sort === 'username_asc' ? 'Z-A' : 'A-Z' ?>
          </a>
        </th>
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
                href="?delete=<?= urlencode($row['username']) ?>&page=<?= $page ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>&sort=<?= $sort ?>"
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
    <a href="?page=<?= $i ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>&sort=<?= $sort ?>"
       class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
  <?php endfor; ?>
</div>
<br>

<?php include 'include/footer.php'; ?>