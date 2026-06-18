<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        *
        {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background-color: #f7f7f7;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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

        .view-container{
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 40px;
            margin: 40px auto;
            width: 95%;
            max-width: 800px;
            min-height: 500px;
        }

        footer {
            text-align: center;
            background-color: #1b1f3a;
            color: white;
            padding: 15px 0;
            font-size: 14px;
            margin-top: auto;
        }

        footer hr {
            width: 80%;
            margin: 0 auto 10px auto;
            border: 0.5px solid #444;
        }

        .btn-edit {
            padding: 10px 25px;
            background-color: #1b1f3a;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;

        }

        .btn-edit:hover {
            background-color: #ffd700;
            color: #1b1f3a;
        }

        .header {
            width: 100%;
            padding: 20px;
            display: flex;
            flex-wrap: wrap; /* Helps if you have many menu items */
            justify-content: center;
            gap: 15px;
            border-bottom: 2px solid #1b1f3a;
        }

        .header h2 { 
            color: #1b1f3a; 
            font-size: 28px; 
        }

        .dashboard {
            padding-top: 30px;
            margin-top: 50px;
            display: flex;
            flex-wrap: wrap; /* Allows items to stack on small screens */
            align-items: stretch; 
            justify-content: center;
            gap: 20px;
        }

        

        .limit, .no-docs {
            flex: 1; /* Grows to fill space */
            min-width: 300px; /* Prevents them from getting too thin */
            border-radius: 15px;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .limit {
            
            border: 1px solid;
        }

        .no-docs {
            
            color: #777;
            background: #fafafa;
            border: 2px dashed #ccc;
        }

        .limit h3 { 
            color: #1b1f3a; 
            font-size: 20px;
        }

        

        .no-docs p {
            font-size: 1.1rem; 
            margin-bottom: 15px;

        }

    </style>
</head>
<body>
    <div class="super">

    
    <header>

        AI-Assisted Quiz Generation System
            
        <a class="btn-edit" href="{{ route('user.manage') }}">manage profile</a>
        <a class="btn-edit" href="{{ route('document.index') }}">start quiz</a>
        <a class="btn-edit" href="{{ route('document.selectresult') }}">view result</a>
        {{-- <a class="btn-edit" href="../FYP_PROJECT/account/logout.php"><p>Log Out</p></a> --}}
        
        <form class="inline" method="POST" action="/logout">
          @csrf
          <button class="btn-edit"
            type="submit">Logout
          </button>
        </form>

    </header>
    <main>
        <div class="view-container">


            <div class="header">
                <h2>Welcome {{$user}}</h2>
            </div>

            <div class="dashboard">
                <div class="limit">
                    <h3>Today upload limit</h3><br>
                    <p>{{$limit}}/{{$max}}</p><br>
                    <p>status: {{{$status}}}</p>

                </div>

                <div class="no-docs">
                    <p>Let's Start!.</p>
                    <a href="{{ route('document.create') }}" class="btn-edit">Create Your First Quiz</a>
                </div>

                <div class="limit">
                    <h3>Total documents:</h3><br>
                    <p style="font-size: larger;">{{$totaldoc}}</p><br>

                </div>
            </div>

        </div>

        

    </main>
    </div>
    <footer>
        <hr>
        &copy; 2026 AI-Assisted Quiz Generation. All rights reserved.
    </footer>

</body>
</html>