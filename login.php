<?php require_once 'config.php';
include 'include/header.php';
?>

<div class="cover-container mt-5 d-flex w-100 h-100 p-3 mx-auto flex-column">
  <h2>Login</h2>
  <form action="login-code.php" method="POST">
    <div class="mb-3">
      <label>Username *</label>
      <input type="text" name="username" class="form-control" />
    </div>

    <div class="mb-3">
      <label>Password *</label>
      <input type="password" name="password" class="form-control" />
    </div>

    <button type="submit" name="loginBtn" class="btn btn-primary">login</button>
  </form>
</div>
</body>
<?php include 'include/footer.php'; ?>

</html>