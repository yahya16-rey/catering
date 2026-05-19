<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>404 - Halaman Belum Dibikin</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2e1b17, #5c4033);
            color: #fff8f1;
            font-family: 'Fredoka', sans-serif;
            text-align: center;
            padding: 60px 20px;
        }

        h1 {
            font-size: 80px;
            color: #ffc8a2;
            margin-bottom: 10px;
        }

        p {
            font-size: 22px;
            color: #ffe8d6;
            margin-bottom: 40px;
        }

        .joke {
            font-size: 18px;
            color: #ffdeb4;
            margin-bottom: 50px;
        }

        a {
            display: inline-block;
            text-decoration: none;
            background-color: #d2691e;
            color: white;
            padding: 12px 26px;
            border-radius: 14px;
            font-size: 16px;
            transition: 0.3s;
        }

        a:hover {
            background-color: #b85a1e;
        }

        .blink {
            animation: blink-animation 1.5s steps(2, start) infinite;
        }

        @keyframes blink-animation {
            to {
                visibility: hidden;
            }
        }
    </style>
</head>
<body>
    <h1 class="blink">404</h1>
    <p>Eh? Halamannya belum sempat dibuat, kayak tugas yang masih wacana 😅</p>
    <div class="joke">Tapi tenang... daripada nyasar terus, mending balik aja ke jalan yang benar~</div>
    <a href="{{ url('/') }}">Balik ke Beranda</a>
</body>
</html>
