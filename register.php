<?php
require_once 'config.php';
include 'include/header.php';
?>

<center>
  <h2>Register</h2>
</center>

<div class='cover-container mt-5 d-flex w-100 h-100 p-3 mx-auto flex-column'>
  <form id="registerForm" action="register-code.php" method="POST" enctype="multipart/form-data">

    <div class="mb-3">
      <label>Name *</label>
      <input type="text" name="name" class="form-control" />
      <div id="nameError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label>First Name *</label>
      <input type="text" name="firstname" class="form-control" />
      <div id="firstnameError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label>Last Name *</label>
      <input type="text" name="lastname" class="form-control" />
      <div id="lastnameError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label>Username *</label>
      <input type="text" name="username" class="form-control" />
      <div id="usernameError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label>Password *</label>
      <input type="password" name="password" class="form-control" />
      <div id="passwordError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label>Phone Number *</label>
      <input type="tel" name="phonenum" class="form-control" />
      <div id="phoneError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label>Email *</label>
      <input type="email" name="email" class="form-control" />
      <div id="emailError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label>Address *</label>
      <input type="text" name="address" class="form-control" />
    </div>

    <div class="mb-3">
      <select id="state" name="state" class="form-select">
        <option value="" disabled selected>Select the State</option>
        <option value="gujrat">Gujrat</option>
        <option value="maharastra">Maharastra</option>
        <option value="goa">Goa</option>
      </select>
      <div id="stateError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <select id="district" name="district" class="form-select mb-3">
        <option value="" disabled selected>Select the District</option>
        <option value="ahmedabad">Ahmedabad</option>
        <option value="ganthinagar">Ganthinagar</option>
        <option value="arvali">Arvali</option>
      </select>
      <div id="districtError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <button type="submit" name="registerBtn" class="btn btn-primary mb-3">Register</button>
      You have an Account!!
      <a href="login.php">Login</a>
    </div>
  </form>
</div>

<script>
  document.getElementById('registerForm').addEventListener('submit', function (e) {
    let isValid = true;

    const form = e.target;

    const name = form.name.value.trim();
    const firstname = form.firstname.value.trim();
    const lastname = form.lastname.value.trim();
    const username = form.username.value.trim();
    const password = form.password.value.trim();
    const phone = form.phonenum.value.trim();
    const email = form.email.value.trim();
    const state = document.getElementById('state').value;
    const district = document.getElementById('district').value;

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

    if (!username) {
      document.getElementById('usernameError').textContent = "Username is required";
      isValid = false;
    }

    if (!password) {
      document.getElementById('passwordError').textContent = "Password is required";
      isValid = false;
    } else if (password.length < 6) {
      document.getElementById('passwordError').textContent = "Password must be at least 6 characters";
      isValid = false;
    }

    if (!phone) {
      document.getElementById('phoneError').textContent = "Phone number is required";
      isValid = false;
    } else if (!/^\d{10}$/.test(phone)) {
      document.getElementById('phoneError').textContent = "Phone number must be exactly 10 digits";
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
      'phoneError',
      'emailError',
      'stateError',
      'districtError'
    ];
    errorFields.forEach(id => document.getElementById(id).textContent = '');
  }

  allFields = ['name', 'firstname', 'lastname', 'username', 'password', 'phonenum', 'email']
  allFields.forEach(field => {
    document.querySelector(`[name="${field}"]`).addEventListener('input', function () {
      document.getElementById(field + 'Error').textContent = '';
    });
  });
</script>
</body>

<?php include 'include/footer.php'; ?>

</html>