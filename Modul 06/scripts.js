  document.getElementById("registrationForm").addEventListener("submit", function(event) {
    var isValid = true;

    var nameInput = document.getElementById("name");
    var nameError = document.getElementById("nameError");
    if (nameInput.value.trim() === "") {
      nameError.textContent = "Nama harus diisi";
      isValid = false;
    } else {
      nameError.textContent = "";
    }

    var usernameInput = document.getElementById("username");
    var usernameError = document.getElementById("usernameError");
    if (usernameInput.value.trim() === "") {
      usernameError.textContent = "Username harus diisi";
      isValid = false;
    } else {
      usernameError.textContent = "";
    }

    var emailInput = document.getElementById("email");
    var emailError = document.getElementById("emailError");
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(emailInput.value.trim())) {
      emailError.textContent = "Email tidak valid";
      isValid = false;
    } else {
      emailError.textContent = "";
    }

    var passwordInput = document.getElementById("password");
    var passwordError = document.getElementById("passwordError");
    if (passwordInput.value.length < 6) {
      passwordError.textContent = "Password harus terdiri dari minimal 8 karakter";
      isValid = false;
    } else {
      passwordError.textContent = "";
    }

    var confirmPasswordInput = document.getElementById("confirmPassword");
    var confirmPasswordError = document.getElementById("confirmPasswordError");
    if (confirmPasswordInput.value !== passwordInput.value) {
      confirmPasswordError.textContent = "Konfirmasi password tidak cocok dengan password";
      isValid = false;
    } else {
      confirmPasswordError.textContent = "";
    }

    var phoneInput = document.getElementById("phone");
    var phoneError = document.getElementById("phoneError");
    var phonePattern = /^\d{10,}$/;
    if (!phonePattern.test(phoneInput.value.trim())) {
      phoneError.textContent = "Nomor telepon tidak valid";
      isValid = false;
    } else {
      phoneError.textContent = "";
    }

    var genderInputs = document.querySelectorAll('input[name="gender"]:checked');
    var genderError = document.getElementById("genderError");
    if (genderInputs.length === 0) {
      genderError.textContent = "Jenis kelamin harus dipilih";
      isValid = false;
    } else {
      genderError.textContent = "";
    }

    var websiteInput = document.getElementById("website");
    var websiteError = document.getElementById("websiteError");
    if (websiteInput.value.trim() === "") {
      websiteError.textContent = "Alamat website harus diisi";
      isValid = false;
    } else if (!isValidUrl(websiteInput.value.trim())) {
      websiteError.textContent = "Alamat website tidak valid";
      isValid = false;
    } else {
      websiteError.textContent = "";
    }

    if (!isValid) {
      event.preventDefault();
    }
  });

  function isValidUrl(url) {
    var pattern = /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;
    return pattern.test(url);
  }

  function togglePasswordVisibility(inputId) {
    var passwordInput = document.getElementById(inputId);
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
    } else {
      passwordInput.type = "password";
    }
  }