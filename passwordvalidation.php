
<input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
<div class="mt-2">
<input type="checkbox" id="showPassword"> Show Password
<p id="password-error" class="error-message" style="
    color: red;
    font-size: 12px;
  "></p>
</div>
<script>
  const passwordInput = document.getElementById('password');
  const showPasswordCheckbox = document.getElementById('showPassword');
  const passwordError = document.getElementById('password-error');

  passwordInput.addEventListener('input', validatePassword);

  function validatePassword() {
    const password = passwordInput.value;
    let errorMessage = '';

    if (!/(?=.*[a-z])/.test(password)) {
      errorMessage += 'At least one lowercase letter is required. ';
    }
    if (!/(?=.*[A-Z])/.test(password)) {
      errorMessage += 'At least one uppercase letter is required. ';
    }
    if (!/(?=.*\d)/.test(password)) {
      errorMessage += 'At least one digit is required. ';
    }
    if (!/(?=.*[@$!%*?&])/.test(password)) {
      errorMessage += 'At least one special character (@, $, !, %, *, ?, or &) is required. ';
    }
    if (password.length < 8) {
      errorMessage += 'Password must be at least 8 characters long. ';
    }

    passwordError.textContent = errorMessage;
  }

  showPasswordCheckbox.addEventListener('change', function() {
    passwordInput.type = this.checked ? 'text' : 'password';
  });
</script>
