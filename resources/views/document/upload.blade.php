<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Generator | Upload PDF</title>
    <style>
        .container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 40px;
            margin: 40px auto;
            width: 95%;
            max-width: 800px;
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

        .header h2 { 
            color: #1b1f3a; 
            font-size: 28px; 
        }

        .upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 200px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin-bottom: 15px;
            padding: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 20px;
            padding: 10px;
        }

        input[type="file"] {
            width: 50%;
            padding: 15px;
            border: 2px dashed #ccc;
            border-radius: 10px;
            background: #fafafa;
            cursor: pointer;
            transition: border-color 0.3s;
        }

        input[type="file"]:hover {
            border-color: #1b1f3a;
        }

        .file-hint {
            font-size: 0.85rem;
            color: #777;
            font-style: italic;
            margin-top: 8px;
        }

        button {
            display: block;
            margin: auto;
            background-color: #1b1f3a;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s, transform 0.3s;
            width: 85%;
            height: 60px;
        }

        button:hover {
            background-color: #ffd700;
            color: #1b1f3a;
            transform: scale(1.02);
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            color: #666;
        }

        hr {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div style="margin-bottom: 20px; text-align: left;">
                <a href="javascript:history.back()" class="btn-edit" style="padding: 5px 15px; font-size: 0.9rem;">← Back</a>
            </div>

            <div class="header">
                <h2>Quiz Generator</h2>
            </div>
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('document.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="upload">
                    <label>Upload Lesson Material</label>
                    <input type="file" name="pdf_file" accept=".pdf" required>
                    <p class="file-hint">PDF files only (Max size: 10MB)</p>
                    <p class="file-hint">Please ensure the PDF file contains text only! Images will not be processed.</p>
                </div>
                <button type="submit" onclick="this.innerHTML='Processing... Please wait...';">
                    Generate Quiz
                </button>
            </form>
        </div>
    </div>
    <footer>
        <hr>
        &copy; 2026 AI-Assisted Quiz Generation. All rights reserved.
    </footer>
</body>
</html>