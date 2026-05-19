<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Widia Catering' }} - Authentikasi</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Play CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        olive: {
                            50: '#F4F6F4',
                            100: '#E8EFE9',
                            500: '#5E6F57',
                            600: '#4E5C48',
                            700: '#3D4939',
                        },
                        accent: {
                            500: '#EA580C',
                            600: '#C2410C',
                        },
                        soft: '#F8F9FA'
                    },
                    fontFamily: {
                        outfit: ['Outfit', 'sans-serif'],
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8F9FA;
        }
        .font-title {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-center items-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">

    <!-- Success & Error Alerts -->
    @if(session('successMessage'))
        <div class="max-w-md w-full mb-4">
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl relative text-xs font-semibold" role="alert">
                <span>{{ session('successMessage') }}</span>
            </div>
        </div>
    @endif
    @if(session('errorMessage'))
        <div class="max-w-md w-full mb-4">
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl relative text-xs font-semibold" role="alert">
                <span>{{ session('errorMessage') }}</span>
            </div>
        </div>
    @endif

    <!-- Content -->
    @yield('content')

</body>
</html>
