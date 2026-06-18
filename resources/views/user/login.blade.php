
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <style>
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
        <div class="login-container">
            <h2>Login</h2>

            @if (session('message'))
                <p style="color:green; text-align:center;">' {{ session('message')}}  </p>
            @endif


            <form action="{{ route('user.authenticate') }}" method="post" class="login-form">
                @csrf
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="squarepant@gmail.com" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                @enderror

                <label for="password">Password:</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" minlength="8" required>
                    <div class="toggle-container">
                        <input type="checkbox" id="togglePassword"> 
                        <label for="togglePassword" style="display:inline; font-size: 0.8em;">Show Password</label>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>

                <p>Don’t have an account? <a href={{ route('user.register') }}>Click here</a></p>
                <p><a href="{{ route('user.forgetpassword') }}">forget password?</a></p>

                <div class="form-buttons">
                    <input type="reset" value="Reset">
                    <input type="submit" value="Login">
                </div>
            </form>
        </div>
    </main>

    <footer>
        <hr>
        &copy; 2026 AI-Assisted Quiz Generation. All rights reserved.
    </footer>

    <script>
        const passwordInput = document.getElementById('password');
        const toggleCheckbox = document.getElementById('togglePassword');

        toggleCheckbox.addEventListener('change', function() {
            // If checked, type is 'text', otherwise 'password'
            const type = this.checked ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        });
    </script>
</body>
</html>