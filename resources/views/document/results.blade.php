<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Generator | Extracted Text</title>
    <style>
        .container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 40px;
            margin: 40px auto;
            width: 95%;
            max-width: 900px;
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
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .extracted-text {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            max-height: 400px;
            overflow-y: auto;
        }

        .extracted-text pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            font-family: inherit;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div style="margin-bottom: 20px; text-align: left;">
                <a href="{{ route('document.create') }}" class="btn-edit" style="padding: 5px 15px; font-size: 0.9rem;">← Upload Another</a>
            </div>

            <div class="header">
                <h2>Extracted Text from PDF</h2>
            </div>

            <div class="success-message">
                PDF processed successfully!
            </div>

            <div class="extracted-text">
                <h3>Extracted Content:</h3>
                <pre>{{ $extracted_text }}</pre>
            </div>

            @if(strlen($extracted_text) > 100)
            <div style="text-align: center; margin-top: 20px;">
                <a href="#" class="btn-edit" style="background-color: #ffd700; color: #1b1f3a;">
                    Generate Quiz Questions (Coming Soon)
                </a>
            </div>
            @endif
        </div>
    </div>
    <footer>
        <hr>
        &copy; 2026 AI-Assisted Quiz Generation. All rights reserved.
    </footer>
</body>
</html>