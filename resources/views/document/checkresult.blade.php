
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style2.css') }}">
</head>
<body>

    <div class="view-container">
        <div style="margin-bottom: 20px; text-align: left;">
            <a href="{{ route('document.selectresult') }}" class="btn-edit" style="padding: 5px 15px; font-size: 0.9rem;">← Back</a>
        </div>

        <div class="view-header">
            <h2>Quiz Results</h2>
        </div>

       

        <div class="doc-list">
             @forelse ($results as $result => $item)
            <div class="doc-card">
                <div class="doc-info">
                    <h3>Your Score:  {{$item['score']}}  /   {{$item['total_questions']}}  </h3><br>
                    <span>Date:  {{ date("F j, Y, g:i a", strtotime($item['created_at']))}}.</span>
                </div>
            </div>
                
            @empty

            <p>No results found for this document.</p>
                
            @endforelse
        </div>
    </div>
    </div>

    <footer>
        <hr>
        &copy; 2026 AI-Assisted Quiz Generation. All rights reserved.
    </footer>
    
</body>
</html>

    

