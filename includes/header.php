<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Custom color variables */
        :root {
            --primary-color: #2c3e50;
            --accent-color: #e74c3c;
            --hover-color: #f1c40f;
            --bg-light: #ecf0f1;
        }

        /* Body styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-light);
        }

        /* Navbar styles */
        .navbar {
            background: var(--primary-color);
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            color: white !important;
            font-size: 1.5rem;
            font-weight: bold;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            background: rgba(255,255,255,0.1);
            color: var(--hover-color) !important;
        }

        .nav-item {
            margin: 0 5px;
        }

        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 5px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--hover-color) !important;
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        /* Remove permanent hover effect from active items */
        .nav-item.active .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: normal;
        }

        /* Only show hover effect when actually hovering */
        .nav-item.active .nav-link:hover {
            color: var(--hover-color) !important;
            font-weight: 600;
        }

        /* Remove permanent indicator line */
        .nav-item.active .nav-link::after {
            display: none;
        }

        /* Show indicator line only on hover */
        .nav-link:hover::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 3px;
            background-color: var(--hover-color);
            border-radius: 3px;
        }

        /* Navbar toggle button */
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
            border-radius: 5px;
            background: rgba(255,255,255,0.1);
        }

        .navbar-toggler:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(255,255,255,0.2);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255,255,255, 1)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 8h24M4 16h24M4 24h24'/%3E%3C/svg%3E");
        }

        /* Main content area */
        main {
            padding: 2rem;
            min-height: calc(100vh - 76px);
        }

        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: var(--primary-color);
                padding: 1rem;
                border-radius: 10px;
                margin-top: 1rem;
            }

            .nav-item {
                margin: 5px 0;
            }

            .nav-link {
                padding: 0.7rem 1rem !important;
            }
        }

        /* Animation for navbar items */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .navbar-nav .nav-item {
            animation: fadeIn 0.5s ease forwards;
            opacity: 0;
        }

        .navbar-nav .nav-item:nth-child(1) { animation-delay: 0.1s; }
        .navbar-nav .nav-item:nth-child(2) { animation-delay: 0.2s; }
        .navbar-nav .nav-item:nth-child(3) { animation-delay: 0.3s; }
        .navbar-nav .nav-item:nth-child(4) { animation-delay: 0.4s; }
        .navbar-nav .nav-item:nth-child(5) { animation-delay: 0.5s; }
        .navbar-nav .nav-item:nth-child(6) { animation-delay: 0.6s; }
        .navbar-nav .nav-item:nth-child(7) { animation-delay: 0.7s; }
        .navbar-nav .nav-item:nth-child(8) { animation-delay: 0.8s; }
        .navbar-nav .nav-item:nth-child(9) { animation-delay: 0.9s; }
    </style>
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        .footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); /* Changed gradient colors */
            color: #ecf0f1; /* Lightened text color */
            padding: 50px 0 20px;
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #e74c3c, #f1c40f, #2ecc71, #3498db);
        }

        .footer-heading {
            color: #f39c12; /* Changed heading color to match the theme */
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .footer-heading::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -8px;
            width: 50px;
            height: 3px;
            background-color: #e74c3c; /* Retained the same underline color */
            border-radius: 2px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: #ecf0f1; /* Lightened link color */
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            padding: 3px 0;
            position: relative;
        }

        .footer-links a::after {
            content: '';
            display: block;
            width: 0;
            height: 2px;
            background: #f39c12; /* Changed hover underline color */
            transition: width 0.3s;
        }

        .footer-links a:hover::after {
            width: 100%;
        }

        .footer-social {
            margin-top: 1.5rem;
        }

        .footer-social a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            color: #ecf0f1; /* Lightened icon color */
            margin: 0 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .footer-social a:hover {
            transform: translateY(-3px);
            background: #f39c12; /* Changed hover background color */
            color: #34495e; /* Darkened color on hover */
        }

        .footer-contact {
            margin-top: 2rem;
        }

        .footer-contact p {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer-contact i {
            margin-right: 10px;
            color: #f39c12; /* Changed icon color */
        }

        .footer-contact a {
            color: #ecf0f1; /* Lightened link color */
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-contact a:hover {
            color: #f39c12; /* Changed hover link color */
        }

        .footer-bottom {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 768px) {
            .footer {
                padding: 40px 0 20px;
            }

            .footer-content {
                text-align: center;
            }

            .footer-heading::after {
                left: 50%;
                transform: translateX(-50%);
            }

            .footer-links {
                margin-bottom: 2rem;
            }
        }

        /* Animation for links */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .footer-links li {
            animation: fadeInUp 0.5s ease forwards;
            opacity: 0;
        }

        .footer-links li:nth-child(1) { animation-delay: 0.1s; }
        .footer-links li:nth-child(2) { animation-delay: 0.2s; }
        .footer-links li:nth-child(3) { animation-delay: 0.3s; }
        .footer-links li:nth-child(4) { animation-delay: 0.4s; }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="../../index.php">
                <i class="fas fa-box-open mr-2"></i>Sembako
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="../../index.php">
                            <i class="fas fa-home mr-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../modules/suppliers/index.php">
                            <i class="fas fa-truck mr-1"></i>Pemasok
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../modules/barang/index.php">
                            <i class="fas fa-boxes mr-1"></i>Barang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../modules/penerima/index.php">
                            <i class="fas fa-users mr-1"></i>Penerima
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../modules/distribusi/index.php">
                            <i class="fas fa-truck-loading mr-1"></i>Distribusi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../modules/kategori/index.php">
                            <i class="fas fa-tags mr-1"></i>Kategori-Barang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../modules/barang-masuk/index.php">
                            <i class="fas fa-download mr-1"></i>Barang-Masuk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../modules/paket/index.php">
                            <i class="fas fa-download mr-1"></i>paket
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../logout.php">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
