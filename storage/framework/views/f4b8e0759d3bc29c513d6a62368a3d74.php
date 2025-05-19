<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Use only one version of Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>">
</head>
<body>
    <div class="main-container">
        <!-- Left Section -->
        <div class="left-section">
            <div class="logo-container">
                <img src="css/uploads/logo1.png" alt="TasteBud Logo" class="logo">
            </div>
            <h2>Welcome to ConnectED</h2>
            <p>Here at ConnectEd, we connect you with your past to pave the path to the future.</p>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <div class="glass-container">
                <h1>Login</h1>
                <form action="#" method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
    <label for="password">Password</label>
    <div class="password-container">
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
        <i class="fa fa-eye-slash eye-icon" id="togglePassword"></i> <!-- Icon for password visibility toggle -->
    </div>
</div>
                    <button type="submit" class="btn">Login</button>
                </form>
                
                <p class="mt-3">Don't have an account?<a href="<?php echo e(route('register')); ?>" class="register-link"> Register here</a></p>
                <p><a href="#" class="forgot-password-link" target="_blank">Forgot your password?</a></p>
                <!-- Forgot Password Link -->
                
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="js/script.js"></script> <!-- Link to the updated JS file -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
    // Password toggle functionality
    const togglePasswordIcon = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");

    // Ensure the icon starts correctly based on password field type
    if (passwordInput && togglePasswordIcon) {
        if (passwordInput.type === "password") {
            togglePasswordIcon.classList.remove("fa-eye");
            togglePasswordIcon.classList.add("fa-eye-slash");
        }

        // Toggle password visibility on click
        togglePasswordIcon.addEventListener("click", function () {
            if (passwordInput.type === "password") {
                passwordInput.type = "text"; // Show password
                togglePasswordIcon.classList.remove("fa-eye-slash");
                togglePasswordIcon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password"; // Hide password
                togglePasswordIcon.classList.remove("fa-eye");
                togglePasswordIcon.classList.add("fa-eye-slash");
            }
        });
    }
});
    </script>
</body>
</html>

<?php /**PATH C:\xampp\htdocs\CLSUHub\resources\views/testing/login.blade.php ENDPATH**/ ?>