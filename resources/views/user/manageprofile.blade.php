<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Profile</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <style>
        header {
            background-color: white;
            border-radius: 15px;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            border-bottom: 2px solid #1b1f3a;
            margin-left: 20px;
            margin-right: 20px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin: auto;
            padding: 10px 12px;
            gap: 20px;
        }

        .super {
            background-color: white;
            margin-left: 60px;
            margin-right: 60px;
            margin-bottom: 20px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            padding: 20px;
            
        }

        a.btn-edit {
            text-decoration: none;
            font-size: 14px;
            padding: 10px 20px;
            background-color: #1b1f3a;
            color: white;
            border-radius: 6px;
            transition: 0.3s;
            text-align: center;
        }

    </style>
</head>
<body>

    <div class="super">
        <header>

            AI-Assisted Quiz Generation System
            
            <a class="btn-edit" href="{{ route('dashboard.index') }}">Home</a>
            <a class="btn-edit" href="{{ route('user.manage') }}">Manage Profile</a>
            <a class="btn-edit" href="{{ route('document.index') }}">Start Quiz</a>
            <a class="btn-edit" href="{{ route('document.selectresult') }}">View Result</a>
            {{-- <a class="btn-edit" href="{{ route('user.logout') }}"><p>Log Out</p></a> --}}

            <form class="inline" method="POST" action="/logout">
            @csrf
            <button class="btn-edit"
                type="submit">Logout
            </button>
            </form>

        </header>
    
    <main style="padding: 40px 0;">
        <section class="edit-profile">

            <h2>Information</h2>

            <div class="register-form">
                
                <div class="form-group">
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" name="first-name" 
                           value="{{ $user_data->firstname }}" readonly disabled  
                           style="background-color: #eee; cursor: not-allowed;"/>
                </div>

                <div class="form-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" name="last-name" 
                           value="{{ $user_data->lastname }}" readonly disabled  
                           style="background-color: #eee; cursor: not-allowed;"/>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" 
                           value="{{ $user_data->email }}" readonly disabled 
                           style="background-color: #eee; cursor: not-allowed;" />
                </div>


                <div class="actions">
                    <a href="{{ route('user.update') }}">
                        <button name="save_profile" class="btn-edit">Edit Profile</button>
                    </a>
                </div>
            </div>
        </section>
    </main>
    </div>

    <footer>
        <hr>
        &copy; 2026 AI-Assisted Quiz Generation. All rights reserved.
    </footer>

</body>
</html>