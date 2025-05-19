<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>

  <!-- Font Awesome & Poppins -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo e(asset('css/register.css')); ?>">
</head>
<body>
  <div class="main-container">
    <!-- Form Section -->
    <div class="left-section">
      <div class="glass-container">
        <h1>Register</h1>

        
        <?php if($errors->any()): ?>
          <div class="alert alert-danger">
            <ul>
              <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
          </div>
        <?php endif; ?>

        <form id="signup-form" method="POST" action="<?php echo e(route('register')); ?>">
          <?php echo csrf_field(); ?>

          <!-- Username -->
          <div class="form-group mb-3">
            <label for="username">Username</label>
            <input
              type="text"
              class="form-control"
              id="username"
              name="username"
              placeholder="Enter username"
              required
              value="<?php echo e(old('username')); ?>"
            >
            <div class="invalid-feedback" id="username-feedback"></div>
          </div>

          <!-- Email -->
          <div class="form-group mb-3">
            <label for="email">Email</label>
            <input
              type="email"
              class="form-control"
              id="email"
              name="email"
              placeholder="Enter email"
              required
              value="<?php echo e(old('email')); ?>"
            >
            <div class="invalid-feedback" id="email-feedback"></div>
          </div>

          <!-- Password -->
          <div class="form-group mb-3 password-container">
            <label for="password">Password</label>
            <input
              type="password"
              class="form-control"
              id="password"
              name="password"
              placeholder="Enter password"
              required
            >
            <i class="fa fa-eye-slash eye-icon" id="togglePassword"></i>
            <div class="invalid-feedback" id="password-feedback"></div>
          </div>

          <!-- Confirm Password -->
          <div class="form-group mb-4 password-container">
            <label for="password_confirmation">Confirm Password</label>
            <input
              type="password"
              class="form-control"
              id="password_confirmation"
              name="password_confirmation"
              placeholder="Confirm password"
              required
            >
            <i class="fa fa-eye-slash eye-icon" id="toggleConfirmPassword"></i>
            <div class="invalid-feedback" id="password-confirmation-feedback"></div>
          </div>

          <button
            type="submit"
            class="btn btn-danger"
            id="signup-button"
            disabled
          >Register</button>
        </form>

        <p class="mt-3">
          Already have an account?
          <a href="<?php echo e(route('login')); ?>" class="login-link">Login</a>
        </p>
        <a href="#" class="forgot-password-link" target="_blank">Forgot your password?</a>
      </div>
    </div>

    <!-- Logo and Text -->
    <div class="right-section">
      <div class="logo-container">
        <img src="<?php echo e(asset('css/uploads/logo1.png')); ?>" alt="Logo 1" class="logo">
      </div>
      <h2>Welcome to ConnectEd</h2>
      <p>Join our community by registering for an account.</p>
    </div>
  </div>

  <!-- Bootstrap 5.3 Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const signupButton = document.getElementById('signup-button');

    const usernameField = document.getElementById('username');
    const usernameFeedback = document.getElementById('username-feedback');
    const emailField = document.getElementById('email');
    const emailFeedback = document.getElementById('email-feedback');
    const passwordField = document.getElementById('password');
    const passwordFeedback = document.getElementById('password-feedback');
    const passwordConfirmField = document.getElementById('password_confirmation');
    const passwordConfirmFeedback = document.getElementById('password-confirmation-feedback');

    const togglePasswordIcon = document.getElementById('togglePassword');
    const toggleConfirmIcon  = document.getElementById('toggleConfirmPassword');

    // Enable only when all fields valid
    const validateForm = () => {
      const ok = 
        !usernameField.classList.contains('is-invalid') &&
        !emailField.classList.contains('is-invalid') &&
        !passwordField.classList.contains('is-invalid') &&
        !passwordConfirmField.classList.contains('is-invalid');
      signupButton.disabled = !ok;
    };

    // Username check
    usernameField.addEventListener('input', () => {
      const val = usernameField.value;
      if (!val) {
        usernameField.classList.remove('is-invalid');
        usernameFeedback.textContent = '';
        return validateForm();
      }
      if (val.includes(' ')) {
        usernameField.classList.add('is-invalid');
        usernameFeedback.textContent = 'Username cannot contain spaces.';
        return validateForm();
    }
      fetch('<?php echo e(route('check.username')); ?>', {
        method:'POST',
        headers:{
          'Content-Type':'application/json',
          'X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ username: val })
      })
      .then(r=>r.json())
      .then(data=>{
        if (data.exists) {
          usernameField.classList.add('is-invalid');
          usernameFeedback.textContent = 'This username is already taken.';
        } else {
          usernameField.classList.remove('is-invalid');
          usernameFeedback.textContent = '';
        }
        validateForm();
      });
    });

    // Email check
    emailField.addEventListener('input', () => {
      const val = emailField.value;
      if (!val) return validateForm();
      fetch('<?php echo e(route('check.email')); ?>', {
        method:'POST',
        headers:{
          'Content-Type':'application/json',
          'X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ email: val })
      })
      .then(r=>r.json())
      .then(data=>{
        if (data.exists_in_users) {
          emailField.classList.add('is-invalid');
          emailFeedback.textContent = 'This email is already registered.';
        } else if (!data.exists_in_graduates) {
          emailField.classList.add('is-invalid');
          emailFeedback.textContent = 'This email is not in the alumni records.';
        } else {
          emailField.classList.remove('is-invalid');
          emailFeedback.textContent = '';
        }
        validateForm();
      });
    });

    // Password strength
    const pwdRules = {
      lowercase: /[a-z]/,
      uppercase: /[A-Z]/,
      digit:     /[0-9]/,
      special:   /[@$!%*?&#]/,
      minLen:    /.{8,}/,
    };
    const validatePassword = () => {
      const pwd = passwordField.value;
      if (!pwdRules.lowercase.test(pwd)) {
        passwordField.classList.add('is-invalid');
        passwordFeedback.textContent = 'Must contain a lowercase letter.';
      }
      else if (!pwdRules.uppercase.test(pwd)) {
        passwordField.classList.add('is-invalid');
        passwordFeedback.textContent = 'Must contain an uppercase letter.';
      }
      else if (!pwdRules.digit.test(pwd)) {
        passwordField.classList.add('is-invalid');
        passwordFeedback.textContent = 'Must contain a digit.';
      }
      else if (!pwdRules.special.test(pwd)) {
        passwordField.classList.add('is-invalid');
        passwordFeedback.textContent = 'Must contain a special character.';
      }
      else if (!pwdRules.minLen.test(pwd)) {
        passwordField.classList.add('is-invalid');
        passwordFeedback.textContent = 'Minimum 8 characters.';
      }
      else {
        passwordField.classList.remove('is-invalid');
        passwordFeedback.textContent = '';
      }
      validateForm();
    };
    passwordField.addEventListener('input', () => {
      validatePassword();
      // re-validate confirmation
      passwordConfirmField.dispatchEvent(new Event('input'));
    });

    // Confirm match
    passwordConfirmField.addEventListener('input', () => {
      if (passwordConfirmField.value !== passwordField.value) {
        passwordConfirmField.classList.add('is-invalid');
        passwordConfirmFeedback.textContent = 'Passwords do not match.';
      } else {
        passwordConfirmField.classList.remove('is-invalid');
        passwordConfirmFeedback.textContent = '';
      }
      validateForm();
    });

    // Toggle visibility for both
    const setupToggle = (iconEl, inputEl) => {
      iconEl.addEventListener('click', () => {
        if (inputEl.type === 'password') {
          inputEl.type = 'text';
          iconEl.classList.replace('fa-eye-slash','fa-eye');
        } else {
          inputEl.type = 'password';
          iconEl.classList.replace('fa-eye','fa-eye-slash');
        }
      });
    };
    setupToggle(togglePasswordIcon, passwordField);
    setupToggle(toggleConfirmIcon, passwordConfirmField);
  });
  </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\CLSUHub\resources\views/auth/register.blade.php ENDPATH**/ ?>