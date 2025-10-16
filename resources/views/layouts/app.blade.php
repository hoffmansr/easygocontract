<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{url('bootstrap/bootstrap.min.css')}}">

    <!-- Scripts -->
   {{--  @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
   <style>
       /* Couleur de fond personnalisée pour la sidebar */
        #sidenav-main {
        background: #e6f4ff !important;
        background-image: none !important;
        }

        /* Adapter la couleur du texte et des icônes pour rester lisibles */
        #sidenav-main .nav-link,
        #sidenav-main .nav-item,
        #sidenav-main .material-icons,
        #sidenav-main .nav-link span {
        color: #111827 !important; /* texte sombre sur fond clair */
        }

        /* Lors du survol */
        #sidenav-main .nav-link:hover {
        background-color: #d0eaff !important;
        color: #000 !important;
        }

            /* Rétablir un contour visible sur les champs focus */
        .form-control:focus, 
        .form-select:focus {
        border-color: #007bff !important;   /* contour bleu clair */
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25) !important;
        outline: none !important;
        }

        /* Garder une bordure normale même sans focus */
        .form-control, 
        .form-select {
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem;
        background-color: #fff !important;
        color: #333 !important;
        }

            
            #sidenav-main {
        display: flex;
        flex-direction: column;
        height: 100vh; /* toute la hauteur de la fenêtre */
        
        overflow: hidden; /* évite les débordements */
        }

        /* La partie scrollable du menu */
        #sidenav-collapse-main {
        flex: 1;
        overflow-y: auto;
        padding-bottom: 5rem; /* un petit espace en bas */
        }

        /* Le footer reste collé en bas */
        .sidenav-footer {
        position: relative; /* plus de position absolute */
        
        padding: 2rem;
        text-align: center;
        }

        /* Optionnel : améliore le style du bouton */
        .sidenav-footer .btn {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        color: white;
        font-weight: 600;
        border: none;
        }
        /* Header de la sidebar */
        .sidenav-header {

        border-bottom: 1px solid #c8e1ff;
        text-align: center;
        }

        /* Rendre le texte et les icônes visibles */
        .sidenav-header .navbar-brand span,
        .sidenav-header i {
        color: #111827 !important;
        }

        /* Logo bien centré et visible */
        .sidenav-header .navbar-brand img {
        height: 40px;
        }


            .form-check-input {
        appearance: auto !important;
        -webkit-appearance: checkbox !important;
        -moz-appearance: checkbox !important;
        background: none !important;
        background-image: none !important;
        box-shadow: none !important;
        }

        .form-check-input:checked {
        background: none !important;
        background-image: none !important;
        border-color: #ccc !important;
        }
        input.form-control,
        select.form-control,
        textarea.form-control {
            padding: 8px 12px; /* espace intérieur (haut-bas, gauche-droite) */
            border-radius: 6px; /* coins légèrement arrondis */
            box-sizing: border-box;
        }



   </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    	<script src="{{url('bootstrap/bootstrap.min.js')}}"></script>
        <script src="{{url('bootstrap/bootstrap.bundle.min.js')}}"></script>


</body>
</html>
