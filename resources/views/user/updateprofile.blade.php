<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Profile</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <style>
        /* Disabled state */
        #newpass:disabled {
            background-color: #eee;
            color: #aaa;
            cursor: not-allowed;
            opacity: 0.7;
        }

        main {
            padding: 40px 0;  
        }

        .container-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            align-items: flex-start;
            background-color: white;
            margin-left: 60px;
            margin-right: 60px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
           
        }
        
        .profile-card {
            flex: 1;
            min-width: 320px;
            max-width: 500px;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 50px;
            margin-top: 50px;
            /*box-shadow: 0 4px 6px rgba(0,0,0,0.1);*/
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

    <main>
        <div class="container-wrapper">
            <div class="profile-card">
        
                <section class="edit-profile">
                    <div style="margin-bottom: 20px; text-align: left;">
                        <a href="{{ route('user.manage') }}" class="btn-edit" style="padding: 5px 15px; font-size: 0.9rem;">← Back</a>
                    </div>

                        <h2>Update Information</h2>
                    
                        <form action="{{ route('user.updateProfile') }}" method="POST" class="register-form">

                            @csrf

                            @if (session('message'))

                            
                                <div class="message-box">{{ session('message')}}</div>
                                
                            @endif
                            
                            @if (session('érror_message'))
                                <div class="message-box" style="border-left-color: #e74c3c; color: #e74c3c;">
                                    {{session('error_message')}}
                                </div>
                            @endif
                            
                                

                            <div class="form-group">
                                <label for="first-name">First Name</label>
                                <input type="text" id="first-name" name="first-name" 
                                    value="{{ $user_data->firstname }}" required />
                            </div>

                            <div class="form-group">
                                <label for="last-name">Last Name</label>
                                <input type="text" id="last-name" name="last-name" 
                                    value="{{ $user_data->lastname }}" minlength="2" maxlength="15" required />
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" 
                                    value="{{ $user_data->email }}" readonly disabled 
                                    style="background-color: #eee; cursor: not-allowed;" />
                            </div>

                            <div class="actions">
                                <button type="submit" name="save_profile" class="btn-edit">Save Changes</button>
                            </div>
                        </form>
                    
                    
                </section>
            </div>

            <div class="profile-card">

                <section class="edit-profile">
                    
                        <h2>Change Password</h2>
                        
                        <form action="{{ route('user.updatePassword') }}" method="POST" class="register-form">

                            @csrf
                            
                            @if (session('password_message'))
                                <div class="message-box">{{session('password_message')}}</div>
                            @endif

                            @if (session('password_error'))
                                <div class="message-box" style="border-left-color: #e74c3c; color: #e74c3c;">
                                    {{session('password_error')}}
                                </div>
                            @endif
                            

                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <div>
                                <input type="password" id="current_password" name="current_password" required />
                                    <div class="toggle-container">
                                        <input type="checkbox" id="togglePassword_current"> 
                                        <label for="togglePassword_current" style="display:inline; font-size: 0.8em;">Show Password</label>
                                    </div>
                                </div>
                                
                                
                            </div>

                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <div class="password-wrapper">
                                <input type="password" id="new_password" name="new_password" minlength="8" placeholder="minimum lenght are 8 characters" required />
                                    <div class="toggle-container">
                                        <input type="checkbox" id="togglePassword"> 
                                        <label for="togglePassword" style="display:inline; font-size: 0.8em;">Show Password</label>
                                    </div>
                                </div>
                                <p id="password-strength" style="color: red; text-align: center">Password must consist of at least 8 characters, a lowercase letter, an uppercase letter, a number, and a special character</p>
                            </div>

                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" required />
                            </div>

                            <div class="actions">
                                <button id="newpass" type="submit" name="update_password" class="btn-edit">Update Password</button>
                            </div>
                        </form>
                    
                </section>
            </div>
        </div>
    </main>

    <footer>
        <hr>
        &copy; 2026 AI-Assisted Quiz Generation. All rights reserved.
    </footer>

    <script>
        
        /*
        // 1. Grab the elements (the objects themselves, not their values yet)
        const passwordInput = document.getElementById("new_password");
        const strengthText = document.getElementById("password-strength");
        const submitButton = document.getElementById("newpass");

        // 2. Disable the button initially
        submitButton.disabled = true;
        

        function checkPasswordStrength() {
            // 3. Get the CURRENT value every time the function is called
            const password = passwordInput.value;
            const requirements = [];

            if (password.length < 8) {
                requirements.push("at least 8 characters");
            }
            if (!/[a-z]/.test(password)) {
                requirements.push("a lowercase letter");
            }
            if (!/[A-Z]/.test(password)) {
                requirements.push("an uppercase letter");
            }
            if (!/[0-9]/.test(password)) {
                requirements.push("a number");
            }
            if (!/[^A-Za-z0-9]/.test(password)) {
                requirements.push("a special character");
            }

            // 4. Update the UI based on requirements
            if (password.length === 0) {
                // Case: Input is empty
                strengthText.textContent = 'Password must consist of at least 8 characters, a lowercase letter, an uppercase letter, a number, and a special character';
                strengthText.style.color = "red";
                submitButton.disabled = true;
                }else if (requirements.length === 0 && password.length > 0) {
                strengthText.textContent = "Password is strong!";
                strengthText.style.color = "green";
                submitButton.disabled = false;
            } else {
                strengthText.textContent = password.length > 0 
                    ? "Weak. Missing: " + requirements.join(", ") 
                    : "";
                strengthText.style.color = "red";
                submitButton.disabled = true;
            }
        }

        // 5. Listen for input changes
        passwordInput.addEventListener("input", checkPasswordStrength);
        */

        const currentPasswordInput = document.getElementById("current_password");
        const passwordInput = document.getElementById("new_password");
        const confirmInput = document.getElementById("confirm_password"); 
        const strengthText = document.getElementById("password-strength");
        const submitButton = document.getElementById("newpass");

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
            const isFormFilled = currentPasswordInput.value.trim() !== "" && 
                                passwordInput.value.trim() !== "" && 
                                confirmInput.value.trim() !== "";

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
        [currentPasswordInput, passwordInput, confirmInput].forEach(field => {
            field.addEventListener("input", checkPasswordStrength);
        });

        const toggleCheckbox = document.getElementById('togglePassword');

        toggleCheckbox.addEventListener('change', function() {
            // If checked, type is 'text', otherwise 'password'
            const type = this.checked ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        });

        const toggleCheckbox_current = document.getElementById('togglePassword_current');

        toggleCheckbox_current.addEventListener('change', function() {
            // If checked, type is 'text', otherwise 'password'
            const type = this.checked ? 'text' : 'password';
            currentPasswordInput.setAttribute('type', type);
        });



        /* try 3

        const passwordInput = document.getElementById("new_password");
        const confirmInput = document.getElementById("confirm_password"); 
        const strengthText = document.getElementById("password-strength");
        const submitButton = document.getElementById("newpass");

        function checkPasswordStrength() {
            const password = passwordInput.value;
            const confirm = confirmInput.value;
            const requirements = [];

            // Validation Logic
            if (password.length < 8) requirements.push("8+ characters");
            if (!/[a-z]/.test(password)) requirements.push("lowercase");
            if (!/[A-Z]/.test(password)) requirements.push("uppercase");
            if (!/[0-9]/.test(password)) requirements.push("number");
            if (!/[^A-Za-z0-9]/.test(password)) requirements.push("special character");

            const isPasswordValid = requirements.length === 0;
            const passwordsMatch = password === confirm && password !== "";

            // Update UI
            if (password.length === 0) {
                strengthText.textContent = "Please enter a new password.";
                strengthText.style.color = "red";
            } else if (!isPasswordValid) {
                strengthText.textContent = "Missing: " + requirements.join(", ");
                strengthText.style.color = "red";
            } else if (!passwordsMatch) {
                strengthText.textContent = "Passwords do not match.";
                strengthText.style.color = "orange";
            } else {
                strengthText.textContent = "Password is strong and matches!";
                strengthText.style.color = "green";
            }

            // Enable button only if valid AND matching
            submitButton.disabled = !(isPasswordValid && passwordsMatch);
        }

        passwordInput.addEventListener("input", checkPasswordStrength);
        confirmInput.addEventListener("input", checkPasswordStrength);
        */
        

    </script>

</body>
</html>