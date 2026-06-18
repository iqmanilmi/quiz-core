
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz - AI Assisted Generation</title>
    <link rel="stylesheet" href="{{ asset('css/style2.css') }}">
    <style>
        .container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 40px;
            margin: 40px auto;
            width: 95%;
            max-width: 700px;
            display: flex;
            flex-direction: column;
        }

        .score-box {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
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
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 2px solid #1b1f3a; 
            margin-bottom: 10px;
            padding-bottom: 15px;
        }

        .header h2 { 
            color: #1b1f3a; 
            font-size: 28px; 
        }

        .quiz-question {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 10px 25px;
            margin: 10px;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 6px solid #1b1f3a;
            margin-bottom: 20px;
        }

        .quiz-question p {
            font-weight: bold;
            color: #1b1f3a;
            margin-bottom: 10px;
        }
        .option-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: normal !important;
            cursor: pointer;
            padding: 5px 0;
        }

        .form-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            padding-left: 20px;
            padding-right: 20px;
        }


        input[type="submit"],
        input[type="reset"] {
            background-color: #1b1f3a;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s, transform 0.3s;
        }

        input[type="submit"]:hover,
        input[type="reset"]:hover {
            background-color: #ffd700;
            color: #1b1f3a;
            transform: scale(1.02);
        }


    </style>
</head>
<body>
    <header>

    </header>

    <main>
        <div class="container">
        
            <div style="margin-bottom: 20px; text-align: left;">
                <a href="{{ route('document.index') }}" class="btn-edit" style="padding: 5px 15px; font-size: 0.9rem;">← Back</a>
            </div>

            <div class="header">
                <h2>Quiz Session</h2>
            </div>
            
            @if (session('submitted')) 
                <div class="score-box"> 
                    Final Score: {{ session('score') }} / {{ session('total_questions') }} 
                </div> 
                <br> 
            @endif




            <form action='{{ route('document.answer', $document) }}' method="POST" class="login-form">
                @csrf
                @foreach ($mcqs as $index => $mcq)

                <div class="quiz-question">
                    <p>{{($index + 1) . ". " . $mcq['question']}}</p>
                    
                    <label class="option-label">
                        <input type="radio" name="answers[{{ $mcq['id']}}]" value="A" required>
                        {{htmlspecialchars($mcq['option_1'])}}
                    </label>

                    <label class="option-label">
                        <input type="radio" name="answers[{{ $mcq['id']}}]" value="B" required>
                        {{htmlspecialchars($mcq['option_2'])}}
                    </label>
                    
                    <label class="option-label">
                        <input type="radio" name="answers[{{ $mcq['id']}}]" value="C" required>
                        {{htmlspecialchars($mcq['option_3'])}}
                    </label>

                    <label class="option-label">
                        <input type="radio" name="answers[{{ $mcq['id']}}]" value="D" required>
                        {{htmlspecialchars($mcq['option_4'])}}
                    </label>
                    
                </div>
                    
                @endforeach
                    

                    <div class="form-buttons">
                        <input type="reset" value="Clear">
                        <input type="submit" name="submit" value="Submit Quiz">
                    </div>
            </form>
        </div>
    </main>

    <footer>
        <hr>
        &copy; 2026 AI-Assisted Quiz Generation. All rights reserved.
    </footer>
</body>
</html>