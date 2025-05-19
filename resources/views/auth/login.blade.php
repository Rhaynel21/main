{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Login</title>

  {{-- Font Awesome --}}
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
  >

  {{-- Poppins --}}
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap"
    rel="stylesheet"
  >

  {{-- Your custom CSS --}}
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">

  <style>
    /* ensure icon is vertically centered */
    .password-container {
      position: relative;
    }
    .password-container input {
      padding-right: 2.5rem;
    }
    .eye-icon {
      position: absolute;
      right: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 18px;
      color: #555;
    }
  </style>
</head>
<body>
  <div class="main-container">
    {{-- Left Section --}}
    <div class="left-section">
      <div class="logo-container">
        <img
          src="{{ asset('css/uploads/logo1.png') }}"
          alt="ConnectED Logo"
          class="logo"
        >
      </div>
      <h2>Welcome to ConnectED</h2>
      <p>
        Here at ConnectED, we connect you with your past to pave the path
        to the future.
      </p>
    </div>

    {{-- Right Section --}}
    <div class="right-section">
      <div class="glass-container">
        <h1>Login</h1>

        {{-- Breeze needs CSRF and the named route --}}
        <form method="POST" action="{{ route('login') }}">
          @csrf

          <div class="form-group mb-3">
            <label for="email">Email</label>
            <input
              id="email"
              type="email"
              name="email"
              placeholder="Enter your email"
              value="{{ old('email') }}"
              required
              autofocus
            >
            {{-- show any Fortify validation error --}}
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group mb-4">
            <label for="password">Password</label>
            <div class="password-container">
              <input
                id="password"
                type="password"
                name="password"
                placeholder="Enter your password"
                required
                autocomplete="current-password"
              >
              <i
                class="fa fa-eye-slash eye-icon"
                id="togglePassword"
              ></i>
            </div>
            @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Remember Me --}}
          <div class="form-group mb-3 remember-me">
  <input id="remember_me" type="checkbox" name="remember">
  <label for="remember_me">Remember me</label>
</div>

          {{-- Submit --}}
          <button type="submit" class="btn">Login</button>
        </form>

        <p class="mt-3">
          Don’t have an account?
          <a href="{{ route('register') }}" class="register-link">
            Register here
          </a>
        </p>

        @if (Route::has('password.request'))
          <p>
            <a
              href="{{ route('password.request') }}"
              class="forgot-password-link"
            >Forgot your password?</a>
          </p>
        @endif
      </div>
    </div>
  </div>

  {{-- Scripts --}}
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
  ></script>

  {{-- your toggle script --}}
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const toggle = document.getElementById("togglePassword");
      const pwd    = document.getElementById("password");

      // initialize icon
      if (pwd.type === "password") {
        toggle.classList.replace("fa-eye", "fa-eye-slash");
      }

      toggle.addEventListener("click", () => {
        if (pwd.type === "password") {
          pwd.type = "text";
          toggle.classList.replace("fa-eye-slash", "fa-eye");
        } else {
          pwd.type = "password";
          toggle.classList.replace("fa-eye", "fa-eye-slash");
        }
      });
    });
  </script>
</body>
</html>
