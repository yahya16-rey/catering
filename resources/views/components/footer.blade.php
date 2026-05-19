<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Widia Catering' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Footer Styling -->
    <style>
        #footer {
            background-color: #D0E8FF; /* Biru muda */
            color: #000;
            padding: 40px 0;
            text-align: center;
            margin-top: 50px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        .footer-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-container div {
            flex: 1 1 200px;
            margin: 15px;
            text-align: left;
        }

        .footer-container h5 {
            font-size: 1.3em;
            margin-bottom: 12px;
            font-weight: 600;
            color:rgb(0, 0, 0);
        }

        .footer-container p,
        .footer-container ul,
        .footer-container a {
            color: #000;
            font-size: 0.95em;
            line-height: 1.6;
        }

        .footer-container ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .footer-container ul li {
            margin-bottom: 8px;
        }

        .footer-container ul li a {
            color: #000;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-container ul li a:hover {
            color: #0d6efd;
        }

        .footer-social a {
            display: block;
            margin-bottom: 8px;
            text-decoration: none;
            color: #000;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .footer-social a:hover {
            color: #0d6efd;
        }

        .footer-bottom {
            margin-top: 30px;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
            font-size: 0.85em;
            color: #6c757d;
        }

        /* Responsif */
        @media screen and (max-width: 768px) {
            .footer-container {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .footer-container div {
                margin: 20px 0;
            }

            .footer-container div ul {
                text-align: center;
            }
        }
    </style>
</head>

<body>

    <!-- Konten lainnya di atas sini -->

    <!-- FOOTER -->
    <footer id="footer">
        <div class="container footer-container d-flex flex-wrap justify-content-between">
            <!-- Tentang Kami -->
            <div class="footer-about mb-4 col-md-3">
                <h5 class="fw-bold">Tentang Kami</h5>
                <p class="small">
                    <strong>Widia Catering</strong> adalah usaha katering terpercaya sejak 2015. Kami menyajikan hidangan lezat dan berkualitas untuk setiap acara spesial Anda.
                </p>
            </div>

            <!-- Kontak -->
            <div class="footer-contact mb-4 col-md-3">
                <h5 class="fw-bold">Kontak</h5>
                <ul class="list-unstyled small">
                    <li class="mb-1">📧 travelpariwisata@gmail.com</li>
                    <li class="mb-1">📞 +62 812-3456-7890</li>
                    <li class="mb-0">📍 Jl. Raya Wisata No.123, Jakarta</li>
                </ul>
            </div>

            <!-- Tautan Cepat -->
            <div class="footer-links mb-4 col-md-2">
                <h5 class="fw-bold">Tautan Cepat</h5>
                <ul class="list-unstyled small">
                    <li><a href="/" class="text-decoration-none">Home</a></li>
                    <li><a href="/about" class="text-decoration-none">Tentang Kami</a></li>
                    <li><a href="https://wa.me/6283161080128" class="text-decoration-none" target="_blank">Kontak</a></li>
                </ul>
            </div>

            <!-- Ikuti Kami -->
            <div class="footer-social mb-4 col-md-3">
                <h5 class="fw-bold">Ikuti Kami</h5>
                <ul class="list-unstyled small">
                    <li><a href="#" class="text-decoration-none">Facebook</a></li>
                    <li><a href="#" class="text-decoration-none">Instagram</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom text-center pt-3 border-top mt-3">
            <p class="mb-0 small">&copy; {{ date('Y') }} <strong>Widia Catering</strong>. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>
