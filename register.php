<?php
session_start();
require_once 'config.php';
include 'include/header.php';
$msg = "";

// Fetch existing usernames from the database
$usernames = [];
$sql = "SELECT username FROM register";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
  $usernames[] = $row['username'];
}
?>
<style>
  label {
    display: flex;
  }
</style>
<center>
  <h2>Register</h2>
</center>

<div class='cover-container mt-4 d-flex w-100 h-100 p-3 mx-auto flex-column'>
  <form id="registerForm" action="register-code.php" method="POST" enctype="multipart/form-data">

    <?php if (isset($_SESSION['message'])): ?>
      <p class="text-center">
        <?php
        echo $_SESSION['message'];
        unset($_SESSION['message']);
        ?>
      </p>
    <?php endif; ?>


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
      <label>DoB</label>
      <input type="date" name="date" class="form-control" />
    </div>

    <div class="mb-3">
      <label>Email *</label>
      <input type="email" name="email" class="form-control" />
      <div id="emailError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label>Address *</label>
      <input type="text" name="address" class="form-control" />
      <div id="addressError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label for="state" class="form-label">State*</label>
      <select id="state" name="state" class="form-control">
        <option value="">Select state</option>
        <option value="Gujarat">Gujarat</option>
        <option value="Maharashtra">Maharashtra</option>
        <option value="Rajasthan">Rajasthan</option>
      </select>
      <div id="stateError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label for="district" class="form-label">District*</label>
      <select id="district" name="district" class="form-control">
        <option value="">-- Select District --</option>
      </select>
      <div id="districtError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <label>Image *</label>
      <input type="file" id="fileImage" name="fileImage" class="form-control" />
      <div id="imageError" class="text-danger"></div>
    </div>

    <div class="mb-3">
      <button type="submit" name="registerBtn" class="btn btn-primary mb-3">Register</button>
      You have an Account!!
      <a href="login.php">Login</a>
    </div>

  </form>
</div>

<script>
  // List of usernames from PHP
  const allUsernames = <?php echo json_encode($usernames); ?>;

  var stateDistrictMap = {
    Gujarat: ["Ahmedabad", "Surat", "Rajkot"],
    Maharashtra: ["Mumbai", "Pune", "Nagpur"],
    Rajasthan: ["Jaipur", "Udaipur", "Jodhpur"],
  };

  var stateDropdown = document.getElementById("state");
  var districtDropdown = document.getElementById("district");

  stateDropdown.onchange = function () {
    var selectedState = stateDropdown.value;
    var districts = stateDistrictMap[selectedState];

    districtDropdown.innerHTML = "";

    var defaultOption = document.createElement("option");
    defaultOption.text = "-- Select District --";
    defaultOption.value = "";
    districtDropdown.add(defaultOption);

    if (districts) {
      for (var i = 0; i < districts.length; i++) {
        var option = document.createElement("option");
        option.text = districts[i];
        option.value = districts[i];
        districtDropdown.add(option);
      }
    }
  };

  document.querySelector('[name="username"]').addEventListener('blur', function () {
    const username = this.value.trim();
    if (allUsernames.includes(username)) {
      document.getElementById('usernameError').textContent = "Username is already taken";
    } else {
      document.getElementById('usernameError').textContent = "";
    }
  });

  document.getElementById('registerForm').addEventListener('submit', function (e) {
    let isValid = true;

    const form = e.target;
    const name = form.name.value.trim();
    const firstname = form.firstname.value.trim();
    const lastname = form.lastname.value.trim();
    const username = form.username.value.trim();
    const password = form.password.value.trim();
    const email = form.email.value.trim();
    const address = form.address.value.trim();
    const state = document.getElementById('state').value;
    const district = document.getElementById('district').value;
    const fileImage = document.getElementById('fileImage').value;

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
    } else if (allUsernames.includes(username)) {
      document.getElementById('usernameError').textContent = "Username is already taken";
      isValid = false;
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
      document.getElementById('addressError').textContent = "Address is required";
      isValid = false;
    }

    if (state === "") {
      document.getElementById('stateError').textContent = "State is required";
      isValid = false;
    }

    if (district === "") {
      document.getElementById('districtError').textContent = "District is required";
      isValid = false;
    }

    if (fileImage === "") {
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

  allFields = ['name', 'firstname', 'lastname', 'username', 'password', 'email', 'address', 'state', 'district', 'fileImage']
  allFields.forEach(field => {
    document.querySelector(`[name="${field}"]`).addEventListener('input', function () {
      document.getElementById(field + 'Error').textContent = '';
    });
  });
</script>
</body>

<?php include 'include/footer.php'; ?>