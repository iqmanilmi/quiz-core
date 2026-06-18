<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
    <style>
        /* Disabled state */
        #submit:disabled {
            background-color: #eee;
            color: #aaa;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .password-wrapper {
            display: flex;
            flex-direction: column;
        }

        .toggle-container {
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .toggle-container input {
            width: auto; /* Prevents checkbox from stretching */
            margin: 0;
        }

    </style>
    
</head>
<body>
    

    <div class="auth-container">

        <h2>Reset Password</h2>

        @if (session('message'))
            <div class="message-box">{{session('message')}}</div>
        @endif

        @if(session()-> has('email'))
            <p style="margin-bottom: 20px;">Resetting for: <strong>{{ session('email') }}</strong></p>
            
            <form class="auth-form" method="POST" action="{{ route('user.resetPassword') }}">
                @csrf
                <label>Enter Token</label>
                <input type="text" id="token" name="token_input" required placeholder="Paste token from email">
                
                <label>New Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="new_password" minlength="8" placeholder="Enter new password" required>
                    <div class="toggle-container">
                        <input type="checkbox" id="togglePassword"> 
                        <label for="togglePassword" style="display:inline; font-size: 0.8em;">Show Password</label>
                    </div>
                </div>
                <p id="password-strength" style="color: red; text-align: center;">Password must consist of at least 8 characters, a lowercase letter, an uppercase letter, a number, and a special character</p>

                
                <input type="hidden" name="email" value="{{ session('email')}}">
                <input type="hidden" name="action" value="reset_password">
                <input type="submit" id="submit" value="Change Password">
            </form>
            
            <!--<a href="forgetpassword.php?clear=1" class="start-over">Not you? Start over.</a>-->
            <div class="login-form">
                <p>Not you?<a href="{{ route('user.clearResetSession') }}">Start over.</a></p>
            </div>
        @else
     
            <form class="auth-form" method="POST" action="{{ route('user.sendToken') }}">
                @csrf
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="Enter your email">
                <input type="hidden" name="action" value="request_token">
                <input type="submit" value="Send Reset Token">

            </form>
            <!--<a href="login.php" class="start-over">back to login.</a>-->
            <div class="login-form">
                <p>Back to <a href="{{ route('user.login') }}">Login</a></p>
            </div>

        @endif
                    
    </div>

    <footer>
        <hr>
        &copy; 2026 AI-Assisted Quiz Generation. All rights reserved.
    </footer>

    <script>
        
        const tokenInput = document.getElementById("token");
        const passwordInput = document.getElementById("password");
        const strengthText = document.getElementById("password-strength");
        const submitButton = document.getElementById("submit");

        // Initial state
        submitButton.disabled = true;

        function checkPasswordStrength() {
            const password = passwordInput.value;
            const requirements = [];

            // Password Validation Logic
            if (password.length < 8) requirements.push("8+ characters");
            if (!/[a-z]/.test(password)) requirements.push("lowercase");
            if (!/[A-Z]/.test(password)) requirements.push("uppercase");
            if (!/[0-9]/.test(password)) requirements.push("number");
            if (!/[^A-Za-z0-9]/.test(password)) requirements.push("special character");

            
            const isFormFilled = tokenInput.value.trim() !== "" && password.trim() !== "";
            const isPasswordValid = requirements.length === 0;

            if (password.length === 0) {
                strengthText.textContent = "Password must consist of at least 8 characters, a lowercase letter, an uppercase letter, a number, and a special character";
                strengthText.style.color = "red";
            } else if (isPasswordValid) {
                strengthText.textContent = "Password is strong!";
                strengthText.style.color = "green";
            } else {
                strengthText.textContent = "Weak. Missing: " + requirements.join(", ");
                strengthText.style.color = "red";
            }

            // Enable button only if password is valid AND all other fields are filled
            submitButton.disabled = !(isPasswordValid && isFormFilled);
        }

        // Listen for input on EVERY field to ensure button state is always correct
        [tokenInput, passwordInput].forEach(field => {
            field.addEventListener("input", checkPasswordStrength);
        });

        const toggleCheckbox = document.getElementById('togglePassword');

        toggleCheckbox.addEventListener('change', function() {
            // If checked, type is 'text', otherwise 'password'
            const type = this.checked ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        });
        

    </script>

</body>
</html>