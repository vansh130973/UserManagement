<?php
include 'include/header.php';
require_once 'config.php';
$userName = $_SESSION['username'];
$userObj = $conn->prepare("SELECT * FROM register WHERE username = ?");
$userObj->bind_param("s", $userName);
$userObj->execute();
$result = $userObj->get_result();
$user = $result->fetch_assoc();
?>

<style>
  label {
    display: flex;
  }
</style>
<div class="mt-4">
  <?php
  if ($_SESSION['success'] == true) {
    echo "<h4 class='text-success text-lg'>Profile update Successfully</h4>";
    unset($_SESSION['success']);
  }
  ?>
</div>
<div class='cover-container mt-4 d-flex w-100 h-100 p-3 mx-auto flex-column'>
  <form id="updateForm" action="update-code.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Name *</label>
      <input type="text" name="name" class="form-control" value="<?= $user['name'] ?>" />
      <div id="nameError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label>First Name *</label>
      <input type="text" name="firstname" class="form-control" value="<?= $user['firstname'] ?>" />
      <div id="firstnameError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label>Last Name *</label>
      <input type="text" name="lastname" class="form-control" value="<?= $user['lastname'] ?>" />
      <div id="lastnameError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label>Username *</label>
      <input type="text" name="username" class="form-control" value="<?= $user['username'] ?>" />
      <div id="usernameError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label>Password *</label>
      <input type="number" name="password" class="form-control" value="<?= $user['password'] ?>" />
      <div id="passwordError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label>Email *</label>
      <input type="email" name="email" class="form-control" value="<?= $user['email'] ?>" />
      <div id="emailError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label>Address *</label>
      <input type="text" name="address" class="form-control" value="<?= $user['address'] ?>" />
      <div id="addressError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label class="form-label">State*</label>
      <input type="text" name="state" class="form-control" value="<?= $user['state'] ?>" />
      <div id="stateError" class="text-danger"></div>
    </div>

    <div class="mb-4">
      <label class="form-label">District*</label>
      <input type="text" name="district" class="form-control" value="<?= $user['district'] ?>" />
      <div id="districtError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label>Image *</label>
      <input type="file" name="fileImage" class="form-control mb-3" />
      <?php if (!empty($user['image'])): ?>
        <img src="<?= $user['image'] ?>" alt="Profile Image" style="max-width: 150px; max-height: 150px;" />
      <?php endif; ?>
      <div id="imageError" class="text-danger"></div>
    </div>

    <div class="mb-3"><button type="submit" name="updateBtn" class="btn btn-primary mb-3">Update Profile</button></div>
  </form>
</div>

<script>
  document.getElementById('updateForm').addEventListener('submit', function (e) {
    let isValid = true;

    const form = e.target;
    const name = form.name.value.trim();
    const firstname = form.firstname.value.trim();
    const lastname = form.lastname.value.trim();
    const username = form.username.value.trim();
    const password = form.password.value.trim();
    const email = form.email.value.trim();
    const address = form.address.value.trim();
    const state = form.state.value.trim();
    const district = form.district.value.trim();
    const fileImage = document.getElementById('fileImage');
    clearErrors();

    if (!name) {
      document.getElementById('nameError').textContent = "Name is required";
      isValid = false;
    }
    if (!firstname) {
      document.getElementById('firstnameError').textContent = "First name is required";
      isValid = false;
    }
    if (!lastname) {
      document.getElementById('lastnameError').textContent = "Last name is required";
      isValid = false;
    }
    if (username) {
      fetch('checkUsername.php?username=' + username)
        .then(response => response.json())
        .then(data => {
          if (data.exists) {
            document.getElementById('usernameError').textContent = "Username is already taken";
            isValid = false;
          }
        })
        .catch(error => {
          document.getElementById('usernameError').textContent = "Error checking username availability";
          isValid = false;
        });
    }

    if (!password) {
      document.getElementById('passwordError').textContent = "Password is required";
      isValid = false;
    } else if (password.length < 6) {
      document.getElementById('passwordError').textContent = "Password must be at least 6 characters";
      isValid = false;
    }
    if (!email) {
      document.getElementById('emailError').textContent = "Email is required";
      isValid = false;
    } else {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(email)) {
        document.getElementById('emailError').textContent = "Enter a valid email address";
        isValid = false;
      }
    }
    if (!address) {
      document.getElementById('addressError').textContent = "State is required";
      isValid = false;
    }
    if (!state) {
      document.getElementById('stateError').textContent = "State is required";
      isValid = false;
    }
    if (!district) {
      document.getElementById('districtError').textContent = "District is required";
      isValid = false;
    }
    if (fileImage.files.length === "") {
      document.getElementById('imageError').textContent = "Image is required";
      isValid = false;
    }

    if (!isValid) {
      e.preventDefault();
    }
  });

  function clearErrors() {
    const errorFields = [
      'nameError',
      'firstnameError',
      'lastnameError',
      'usernameError',
      'passwordError',
      'emailError',
      'addressError',
      'stateError',
      'districtError',
      'imageError'
    ];
    errorFields.forEach(id => document.getElementById(id).textContent = '');
  }

  document.querySelector('[name="username"]').addEventListener('blur', function () {
    const username = this.value.trim();
    if (username) {
      fetch('check_username.php?username=' + username)
        .then(response => response.json())
        .then(data => {
          if (data.exists) {
            document.getElementById('usernameError').textContent = "Username is already taken";
          } else {
            document.getElementById('usernameError').textContent = "";
          }
        });
    }
  });
</script>

<?php include 'include/footer.php'; ?>