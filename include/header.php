<!doctype html>
<html lang="en" class="h-100">
<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.84.0">
  <title>User Management</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>

  <!-- Custom styles for this template -->
  <link href="../cover.css" rel="stylesheet">
</head>

<body class="d-flex h-100 text-center text-white bg-dark">

  <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="mb-auto">
      <div>
        <h3 class="float-md-start mb-0">UserManage</h3>
        <nav class="nav nav-masthead justify-content-center float-md-end">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>

          <?php if(isset($_SESSION['user_id'])): ?>
            <a class="nav-link active" aria-current="page" href="logout.php">Logout</a>
            <a class="nav-link active" aria-current="page" href="update.php">Update</a>
          <?php else: ?>
            <a class="nav-link active" aria-current="page" href="register.php">Sign-Up</a>
            <a class="nav-link active" aria-current="page" href="login.php">Login</a>
          <?php endif; ?>
        </nav>
      </div>
    </header>