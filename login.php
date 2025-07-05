<?php require_once 'config.php';
include 'include/header.php';
?>
<style>
  label {
    display: flex;
    margin-bottom: 8px;
  }
</style>

<div class="cover-container mt-5 d-flex w-100 h-100 p-3 mx-auto flex-column">
  <h2>Login</h2><br>
  <form action="login-code.php" method="POST">
    <?php if (isset($_SESSION['message'])): ?>
      <div class="text-center">
        <?php
        echo $_SESSION['message'];
        unset($_SESSION['message']);
        ?>
      </div>
    <?php endif; ?>

    <div class="mb-3">
      <label>Username *</label>
      <input type="text" name="username" class="form-control" />
    </div>

    <div class="mb-3">
      <label>Password *</label>
      <input type="password" name="password" class="form-control" />
    </div>
    <button type="submit" name="loginBtn" class="float-md-start btn btn-primary d-grid gap-2 col-6 mb-3">login</button>
    <h5><a class="float-md-end d-grid gap-2 col-6 mb-3 mt-2" href="forget.php">forget password</a></h5>
  </form>
  <br><br>
</div>
</body>
<?php include 'include/footer.php'; ?>

</html>