<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['subject'] }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f7f7f7;
            padding: 20px;
            margin: 0;
        }

        h1 {
            color: #007bff;
        }

        p {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div style="max-width: 600px; margin: 0 auto;">
        <h1 style="text-align: center;">{{ $data['subject'] }}</h1>
        <p>{{ $data['message'] }}</p>
    </div>
</body>
</html>
