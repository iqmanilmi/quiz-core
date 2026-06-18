<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Documents</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style2.css') }}">
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

    <div class="view-container">
        
        
        <div class="view-header">
            <h2>Your Documents</h2>
            <a href="{{ route('document.create') }}" class="btn-upload-new">+ Upload New</a>
        </div>

        <form action="{{ route('document.index') }}" method="GET" class="search-section">
            <input type="search" name="query" placeholder="Search by title..." value="">
            <button type="submit">Search</button>
            <button type="button" onclick="window.location.href='{{ route('document.index') }}'">Clear</button>
        </form>
            
        <div class="doc-list">

            @if(session('error'))
                <script>alert("{{ session('error') }}");</script>
            @endif


            @if ($errors->any())
                <script>alert("{{ $errors->first() }}");</script>
            @endif

            @if(session('success'))
                <p style="color: green;">{{ session('success') }}</p>
            @endif
            
            @if (count($documents) == 0)

            <div class="no-docs">
                <p>No documents found.</p>
                <a href="{{ route('document.create') }}" class="btn-quiz">Create Your First Quiz</a>
            </div>
                
            @else

            
                @foreach ($documents as $document)
                <div class="doc-card">

                <div class="doc-info">
                    <h3>{{ $document -> filename}}</h3>
                    <span>Uploaded: {{date("F j, Y, g:i a", strtotime($document['uploaded_at']))}}</span>
                </div>

                <div class="actions-group">
                    <a class="btn-quiz" href="quiz/{{$document -> id}}">Start Quiz</a>
                    
                
                    <form method="POST" action="{{ route('document.update', $document->id) }}" class="rename-box">
                        @csrf
                        @method('PUT')
                        <input type="text" name="new_name" placeholder="Rename..." required>
                        <button type="submit" style="background:#eee; border:1px solid #ccc; padding:0 10px; cursor:pointer; border-radius:4px;">Save</button>
                    </form>

                    <form method="POST" action="documents/delete={{$document -> id}}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </div>

                </div>

                @endforeach
                
                
        </div>
            
            @endif
                
                
        </div>
    </div>
</div>
    <footer>
        <hr>
        &copy; 2026 AI-Assisted Quiz Generation. All rights reserved.
    </footer>

</body>
</html>