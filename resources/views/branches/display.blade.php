<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="3">
    <title>Token Display</title>
    <style>
        body {
            background-image: radial-gradient(circle, #ffffff, #e8e8e8, #d1d1d1, #bababa, #a4a4a4);
            font-family: 'Nunito', sans-serif;
        }
        .number {
            font-size: 1500%;
            margin: auto;
            width: 50%;
            padding: 10px;
            text-align: center;
            margin-top: 15%;
        }
    </style>
</head>
<body>
    <div class="number">{{ $branch->display }}</div>
</body>
</html>