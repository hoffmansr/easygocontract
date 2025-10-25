<!--
=========================================================
* Material Dashboard 2 - v3.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->

<!DOCTYPE html>
<html lang="{{App::getlocale()}}" @if(App::getlocale() == "ar") dir="rtl" @endif>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{url('assets/img/apple-icon.png')}}">
  <link rel="icon" type="image/png" href="{{url('assets/img/favicon.png')}}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <title>
    Material Dashboard 2 by Creative Tim
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="{{url('assets/css/nucleo-icons.css')}}" rel="stylesheet" />
  <link href="{{url('assets/css/nucleo-svg.css')}}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">



  <!-- Font Awesome Icons -->
 {{--  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script> --}}
  <!-- Material Icons -->
 <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Round" rel="stylesheet">

  <!-- Signature Pad -->
  <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

  <!-- pdf-lib for manipulating PDFs in the browser -->
  <script src="https://cdn.jsdelivr.net/npm/pdf-lib/dist/pdf-lib.min.js"></script>

  
  <!-- CSS Files -->
  <link id="pagestyle" href="{{url('assets/css/material-dashboard.css?v=3.0.0')}}" rel="stylesheet" />

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
/* // Tableau personnalisé */
.table th, .table td {
  vertical-align: top !important;
  text-align: left !important;
  padding: 8px 12px !important;
}
.table td, .table th {
  font-family: 'Roboto', sans-serif;
  font-size: 14px;
}

  </style>

</head>

<body class="g-sidenav-show @if(App::getlocale() == "ar") rtl @endif bg-gray-200">
  {{-- Success message --}}
  @if(session('success'))
      <div class="alert alert-success position-fixed" style="z-index: 1000; top: 10px; right: 10px; bac color: #155724;">
          {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
  @endif

  {{-- Error message --}}
  @if(session('error'))
      <div class="alert alert-danger position-fixed" style="z-index: 1000; top: 10px; right: 10px; color: #721c24;">
          {{ session('error') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
  @endif

  {{-- Info message --}}
  @if(session('info'))
      <div class="alert alert-info position-fixed" style="z-index: 1000; top: 10px; right: 10px; color: #0c5460;">
          {{ session('info') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
  @endif

  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0  @if(App::getlocale() == "ar") fixed-end me-3 rotate-caret @else fixed-start ms-3 @endif    bg-gradient-dark" id="sidenav-main" >
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute @if(App::getlocale() == "ar") start-0 @else end-0 @endif top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="{{route('dashboard')}}" >
        <img src="{{url('assets/img/logo-ct.png')}}" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-white">EasyContract</span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
  <div class="collapse navbar-collapse @if(App::getlocale() == 'ar') px-0 @endif w-auto max-height-vh-100" id="sidenav-collapse-main">

      <ul class="navbar-nav">
         <form action="{{ route('contrats.storeDraft') }}" method="POST" class="d-inline">
                  @csrf
                  <button type="submit" class="btn bg-gradient-info me-1 mb-1 mt-4 w-100">@lang('sidebar.generate_contract')</button>
              </form>
      <li class=" text-uppercase small mt-2 mb-1 @if(App::getLocale() == 'ar') pe-4 @else ps-4 @endif"> @lang('sidebar.analytics')</li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"  href="{{route('dashboard')}}">
            <div class="text-white text-center @if(App::getlocale() == "ar") ms-2 @else me-2 @endif d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text @if(App::getlocale() == "ar") me-1 @else ms-1 @endif "> @lang('sidebar.dashboard')</span>
          </a>
        </li>
        <li class=" text-uppercase small mt-2 mb-1 @if(App::getLocale() == 'ar') pe-4 @else ps-4 @endif"> @lang('sidebar.configuration')</li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('parametrage') ? 'active' : '' }}" href="{{route('parametrage')}}">
            <div class="text-white text-center @if(App::getlocale() == "ar") ms-2 @else me-2 @endif d-flex align-items-center justify-content-center">
             <i class="material-icons opacity-10">settings</i>
            </div>
            <span class="nav-link-text @if(App::getlocale() == "ar") me-1 @else ms-1 @endif"> @lang('sidebar.settings')</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="{{route('users.index')}}">
            <div class="text-white text-center @if(App::getlocale() == "ar") ms-2 @else me-2 @endif d-flex align-items-center justify-content-center">
             <i class="material-icons opacity-10">group</i>

            </div>
            <span class="nav-link-text @if(App::getlocale() == "ar") me-1 @else ms-1 @endif">@lang('sidebar.users')</span>
          </a>
        </li>
         <li class="nav-item">
          <a class="nav-link text-white " href="{{ route('roles.index') }}">
            <div class="text-white text-center @if(App::getlocale() == "ar") ms-2 @else me-2 @endif d-flex align-items-center justify-content-center">
             <i class="material-icons opacity-10">group</i>

            </div>
            <span class="nav-link-text @if(App::getlocale() == "ar") me-1 @else ms-1 @endif">@lang('sidebar.roles')</span>
          </a>
        </li>
        <li class=" text-uppercase small mt-2 mb-1 @if(App::getLocale() == 'ar') pe-4 @else ps-4 @endif">@lang('sidebar.contractants')</li>
        <li class="nav-item">
        <a class="nav-link text-white d-flex align-items-center" 
          data-bs-toggle="collapse" 
          href="#submenuGestion" 
          role="button" 
          aria-expanded="false" 
          aria-controls="submenuGestion">

          <i class="material-icons opacity-10 @if(App::getLocale() == 'ar') ms-2 @else me-2 @endif">folder</i>
          <span class="flex-grow-1">@lang('sidebar.gestion')</span>
          <i class="material-icons opacity-10 expand-icon">expand_more</i>

         
        </a>

        <div class="collapse @if(App::getLocale() == 'ar') pe-4 @else ps-4 @endif" id="submenuGestion">
          <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link text-white" href="{{route('contractants.index')}}">@lang('sidebar.list_contractants')</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('contractants.create') }}">@lang('sidebar.add_contractant')</a></li>
          </ul>
        </div>
      </li>


        <li class=" text-uppercase small mt-2 mb-1 @if(App::getLocale() == 'ar') pe-4 @else ps-4 @endif">@lang('sidebar.settings')</li>
        <li class="nav-item">
            <a class="nav-link text-white d-flex align-items-center" 
              data-bs-toggle="collapse" 
              href="#submenuParam" 
              role="button" 
              aria-expanded="false" 
              aria-controls="submenuParam">

              <i class="material-icons opacity-10 @if(App::getLocale() == 'ar') ms-2 @else me-2 @endif">description</i>

              <span class="flex-grow-1">@lang('sidebar.settings')</span>

              <i class="material-icons opacity-10 expand-icon">expand_more</i>
            </a>

            <div class="collapse @if(App::getLocale() == 'ar') pe-4 @else ps-4 @endif" id="submenuParam">
              <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link text-white" href="{{route('types_contrats.index')}}">@lang('sidebar.types_contrats')</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{route('clausiers.index')}}">@lang('sidebar.clausiers')</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{route('modeles_contrats.index')}}">@lang('sidebar.modeles')</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{route('workflows.index')}}">@lang('sidebar.workflow')</a></li>
              </ul>
            </div>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white d-flex align-items-center" 
            data-bs-toggle="collapse" 
            href="#submenuProduction" 
            role="button" 
            aria-expanded="false" 
            aria-controls="submenuProduction">

            <i class="material-icons opacity-10 @if(App::getLocale() == 'ar') ms-2 @else me-2 @endif">edit_note</i>

            <span class="flex-grow-1">@lang('sidebar.production')</span>

            <i class="material-icons opacity-10 expand-icon">expand_more</i>
          </a>

          <div class="collapse @if(App::getLocale() == 'ar') pe-4 @else ps-4 @endif" id="submenuProduction">
            <ul class="nav flex-column">
              <li class="nav-item"><a class="nav-link text-white" href="{{route('contrats.index')}}">@lang('sidebar.list_contrats')</a></li>
              <li class="nav-item"><a class="nav-link text-white" href="{{ LaravelLocalization::localizeURL(route('contrats.approbation')) }}">@lang('sidebar.approve_contrat')</a></li>
            </ul>
          </div>
        </li>

        
        <li class=" text-uppercase small mt-2 mb-1 @if(App::getLocale() == 'ar') pe-4 @else ps-4 @endif">@lang('sidebar.search')</li>
         <li class="nav-item">
          <a class="nav-link text-white " href="../pages/virtual-reality.html">
            <div class="text-white text-center @if(App::getlocale() == "ar") ms-2 @else me-2 @endif d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">search</i>
            </div>
            <span class="nav-link-text @if(App::getlocale() == "ar") me-1 @else ms-1 @endif">@lang('sidebar.search_ocr')</span>
          </a>
        </li>
        <li class=" text-uppercase small mt-2 mb-1 @if(App::getLocale() == 'ar') pe-4 @else ps-4 @endif">@lang('sidebar.report')</li>
         <li class="nav-item">
          <a class="nav-link text-white " href="../pages/virtual-reality.html">
            <div class="text-white text-center @if(App::getlocale() == "ar") ms-2 @else me-2 @endif d-flex align-items-center justify-content-center">
             <i class="material-icons opacity-10">assessment</i>
            </div>
            <span class="nav-link-text @if(App::getlocale() == "ar") me-1 @else ms-1 @endif">@lang('sidebar.report')</span>
          </a>
        </li>
        <li class=" text-uppercase small mt-2 mb-1 @if(App::getLocale() == 'ar') pe-4 @else ps-4 @endif">@lang('sidebar.integration')</li>
         <li class="nav-item">
          <a class="nav-link text-white " href="../pages/virtual-reality.html">
            <div class="text-white text-center @if(App::getlocale() == "ar") ms-2 @else me-2 @endif d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">integration_instructions</i>

            </div>
            <span class="nav-link-text @if(App::getlocale() == "ar") me-1 @else ms-1 @endif">@lang('sidebar.api_integration')</span>
          </a>
        </li>

      </ul>



    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">
      <div class="mx-3">
        <a class="btn bg-gradient-info me-1 mb-1 mt-4 w-100" href="https://www.creative-tim.com/product/material-dashboard-pro?ref=sidebarfree" type="button">Upgrade to pro</a>
      </div>
    </div>
  </aside>
  <main class="main-content position-relative bg-white border-radius-lg @if(App::getlocale() == "ar") overflow-x-hidden @endif" ,style="min-height:100vh; overflow:auto;">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-xl px-0 mx-4  border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
       
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group input-group-outline">
              <label class="form-label">Type here...</label>
              <input type="text" class="form-control">
            </div>
          </div>
          <ul class="navbar-nav  justify-content-end">

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                      <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">
                              🔔 Notifications
                              @if($unreadCount > 0)
                                  <span class="badge bg-danger">{{ $unreadCount }}</span>
                              @endif
                          </a>
                          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                              @forelse($notifications as $notif)
                                  <li>
                                      <a class="dropdown-item{{ $notif->read_at ? '' : ' fw-bold' }}" 
                                        href="{{ route('contrats.show', ['contrat' => $notif->data['contrat_id'], 'notification_id' => $notif->id]) }}">
                                          {{ $notif->data['message'] }}
                                          <br><small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                      </a>

                                  </li>
                              @empty
                                  <li class="dropdown-item text-muted">Aucune nouvelle notification</li>
                              @endforelse
                          </ul>
                      </li>


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





                <li @if(App::getlocale() != "ar") style="margin-left: 10px" @endif class="nav-item d-flex align-items-center">
                    <a href="{{ route('lang.switch', 'fr') }}">Français</a> |
                    <a href="{{ route('lang.switch', 'en') }}">English</a> |
                    <a href="{{ route('lang.switch', 'ar') }}">Arabe</a>
                </li>



          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->

    <div class="container py-4 bg-white" >
        @yield('content')
    </div>

  </main>

  <!--   Core JS Files   -->
  <script src="{{url('assets/js/core/popper.min.js')}}"></script>
  <script src="{{url('assets/js/core/bootstrap.min.js')}}"></script>
  <script src="{{url('assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
  <script src="{{url('assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{url('assets/js/material-dashboard.min.js?v=3.0.0')}}"></script>
  @stack('custom-scripts')
  @stack('scripts')

</body>
</html>
