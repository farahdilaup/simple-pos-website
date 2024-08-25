<!-- resources/views/result.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result</title>
    <!-- Include font and stylesheet for VSCode-like appearance -->
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Fira Code', monospace;
            background-color: #1e1e1e;
            color: #dcdcdc;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 240px;
            background-color: #333;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        .editor {
            flex: 1;
            padding: 20px;
        }
        .header {
            font-size: 24px;
            font-weight: bold;
            color: #007acc;
            margin-bottom: 20px;
        }
        .item {
            background-color: #252526;
            border: 1px solid #444;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        .item-header {
            font-size: 18px;
            font-weight: bold;
            color: #9cdcfe;
        }
        .item-content {
            color: #dcdcdc;
        }
        a {
            color: #569cd6;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <a href="/cart">Go Back</a>
        </div>
        <div class="editor">
            <div class="header">Submission Result</div>
            <div class="item">
                <div class="item-header">Token:</div>
                <div class="item-content">{{ $data['token'] }}</div>
            </div>
            <div class="item">
                <div class="item-header">Customer:</div>
                <div class="item-content">{{ $data['customer'] }}</div>
            </div>
            <div class="item">
                <div class="item-header">Items:</div>
                <div class="item-content">
                    <ul>
                        @foreach ($data['items'] as $item)
                            <li>{{ $item['name'] }} - Quantity: {{ $item['quantity'] }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
