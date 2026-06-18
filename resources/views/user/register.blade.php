<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <style>
        /* Disabled state */
        .register-form input[type="submit"]:disabled {
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
    <header>

    </header>
    <main>
        <div class="register-container">
            <h2>Register</h2>
                        
            @error('email')
                <p style="color:red; text-align:center;">{{$message}}</p>
            @enderror

            <form action="{{ route('user.create') }}" method="post" class="register-form">
                @csrf
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="squarepant@gmail.com" required>
            
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" placeholder="Spongebob" required>

                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" placeholder="Squarepants" minlength="2" maxlength="15" required>

                <label for="password">Password:</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" minlength="8" placeholder="minimum length are 8 characters" required>
                    <div class="toggle-container">
                        <input type="checkbox" id="togglePassword"> 
                        <label for="togglePassword" style="display:inline; font-size: 0.8em;">Show Password</label>
                    </div>
                </div>
                <p id="password-strength" style="color: red;">Password must consist of at least 8 characters, a lowercase letter, an uppercase letter, a number, and a special character</p>

                
                <div class="form-buttons">
                    <input type="reset" value="Reset">
                    <input type="submit" value="Register">
                </div>

                <p>Already have an account? <a href="{{ route('user.login') }}">Login here</a></p>
            </form>
        </div>
    </main>
    <footer>
        <hr>
        &copy; 2026 AI-Assisted Quiz Generation. All rights reserved.
    </footer>

    <script>
        const firstname = document.getElementById("firstname");
        const lastname = document.getElementById("lastname");
        const email = document.getElementById("email");
        const passwordInput = document.getElementById("password");
        const strengthText = document.getElementById("password-strength");
        const submitButton = document.querySelector("input[type='submit']");

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

            // Check if other fields are filled
            const isFormFilled = firstname.value.trim() !== "" && 
                                lastname.value.trim() !== "" && 
                                email.value.trim() !== "";

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

        // Listen for input on EVERY field
        [firstname, lastname, email, passwordInput].forEach(field => {
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