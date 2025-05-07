<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Nusatawan</title>
    <style>
        :root {
            --primary: #2D9CDB;
            --secondary: #0C43BA;
            --background: #F2F2F2;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--background);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-container {
            max-width: 500px;
            width: 90%;
            padding: 40px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .error-code {
            font-size: 8rem;
            font-weight: bold;
            color: var(--primary);
            line-height: 1;
        }

        .divider {
            height: 4px;
            width: 80px;
            background-color: var(--secondary);
            margin: 20px auto;
        }

        .error-title {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 15px;
        }

        .error-message {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .home-button {
            display: inline-block;
            background-color: var(--primary);
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .home-button:hover {
            background-color: var(--secondary);
            transform: translateY(-3px);
        }
    </style>
</head>

<body>
    <div class="error-container">
        <h1 class="error-code">@yield('code')</h1>
        <div class="divider"></div>
        <h2 class="error-title">@yield('title')</h2>
        <p class="error-message">@yield('message')</p>
        <a href="{{ url('/') }}" class="home-button">Kembali ke Beranda</a>
    </div>
</body>

</html>
