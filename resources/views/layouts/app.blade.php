<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Suivi de projet tutoré</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style type="text/css">
        .hidden {
            display: none;
        }
    </style>
    
</head>
<body class="bg-secondary">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm ">
            <div class="container">
                @guest
                <a class="navbar-brand" href="{{ url('/') }}">
                   Suivi de projet tutoré
                </a>
                @else
                <a class="navbar-brand" href="{{ url('/home') }}" style="margin-left:-80px;">
                   Suivi de projet tutoré
                </a>
                @endguest
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->

                    @guest

                    @else

                    <ul class="navbar-nav me-auto">

                        <li class="nav-item active">
                            <a class="nav-link {{ Auth::user()->role == 2 ? 'hidden' : '' }}" href="/home" style="float:left;">Home <span class="sr-only"></span></a>
                        </li>

                        <li class="nav-item active {{ Auth::user()->role == 1 || Auth::user()->role == 2 ? 'hidden' : '' }}">
                            <a class="nav-link" href="/user_index" style="float:left;">Utilisateurs<span class="sr-only"></span></a>
                        </li>

                        <li class="nav-item active {{ Auth::user()->role == 0 || Auth::user()->role == 2 ? 'hidden' : '' }}">
                            <a class="nav-link" href="/project_teacher_index" style="float:left;">Projets<span class="sr-only"></span></a>
                        </li>

                        <li class="nav-item active {{ Auth::user()->role == 0 || Auth::user()->role == 1 ? 'hidden' : '' }}">
                            <a class="nav-link" href="/project_student_index" style="float:left;">Projets<span class="sr-only"></span></a>
                        </li>

                        <li class="nav-item active {{ Auth::user()->role == 1 || Auth::user()->role == 2 ? 'hidden' : '' }}">
                            <a class="nav-link" href="/group_index" style="float:left;">Groupes de projet<span class="sr-only"></span></a>
                        </li>

                    </ul>

                    



                    @endguest
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto ">
                        <!-- Authentication Links -->
                        @guest
                        
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Connexion') }}</a>
                                </li>
                            @endif      

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Enregistrement') }}</a>
                                </li>
                            @endif

                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>

                                    @if (Auth::user()->role == 0)

                                        <u>Administrateur</u> | {{ Auth::user()->first_name }}

                                    @endif

                                    @if (Auth::user()->role == 1)

                                        <u>Professeur</u> | {{ Auth::user()->first_name }}

                                    @endif

                                    @if (Auth::user()->role == 2)

                                        <u>Étudiant</u> | {{ Auth::user()->first_name }}

                                    @endif
                                    
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Déconnexion') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('modif.mdp') }}">
                                        {{ __('Modifier le mot de passe') }}
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

        <style>
        /* Ajoutez ces styles dans votre balise <style> ou dans votre fichier CSS */
        html, body {
                    height: 100%;
                    margin: 0;
                    padding: 0;
                     background-image: url('{{ asset('auth/img2.jpg') }}');
                        background-size: cover;
                        background-repeat: no-repeat;
                        background-attachment: fixed;

                }

        #app {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }

        footer {
            padding: 20px;
            color: #333;
            text-align: center;
            margin-top: 0;
        }
        footer p{
            margin: 0;
            padding: 0;
        }

        .intro {
          display: flex;
          padding: 50px;
          align-items: center;
          justify-content: center;
          margin-top: -25px; /* Marge négative pour compenser la marge du container */
          margin-bottom: -25px; /* Marge négative pour compenser la marge du container */
        }

        .card {
          border-radius: .5rem;
        }

        .table-responsive {
          max-height: calc(100vh - 120px);
          overflow-y: auto;
        }

        .table td,
        .table th {
          text-overflow: ellipsis;
          white-space: nowrap;
          overflow: hidden;
        }

        .full-height {
          height: 100vh; /* Hauteur de 100% de la vue */
        }

        .back-button {
          position: absolute;
          top: 70px;
          left: 20px;
        }

        .back-button a {
          display: inline-block;
          padding: 10px 20px;
          border-radius: 5px;
          background-color: #007bff;
          color: #fff;
          text-decoration: none;
          font-weight: bold;
        }

        .back-button a:hover {
          background-color: #0056b3;
        }

    </style>

        <main class="py-4">
            @yield('content')
            @yield('scripts')
        </main>
    </div>

       
  <!-- Footer -->
  <footer
          class="text-center text-lg-start text-white"
          style="background-color: #45526e"
          >
    <!-- Grid container -->
    <div class="container p-4 pb-0">
      <!-- Section: Links -->
      <section class="">
        <!--Grid row-->
        <div class="row">
          <!-- Grid column -->
          <div class="col-md-8 col-lg-9 col-xl-9 mx-auto mt-9">
    <h6 class="text-uppercase mb-4 font-weight-bold text-center">
        DjibYoung
    </h6>

    <div class="row justify-content-center">

        <div class="card text-center" style="width: 9rem;margin-right: 3px;">
            <img src="{{ asset('auth/medism.jpg') }}" class="card-img-top" alt="Mohamed Ismael Otban" style="height: 150px; width: auto;">
            <div class="card-body">
                <p class="card-text text-black">Mohamed <br>Ismael <br>Otban</p>
                <hr style="color:black;">
                <p class="card-text text-black">Chef de projet & Développeur</p>
            </div>
        </div>

        <div class="card text-center" style="width: 9rem;margin-right: 3px;">
            <img src="{{ asset('auth/souhaib.jpg') }}" class="card-img-top" alt="Souhaib Ali Miganeh"  style="height: 150px; width: auto;">
            <div class="card-body">
                <p class="card-text text-black">Souhaib <br> Ali <br>Miganeh</p>
                <hr style="color:black;">
                <p class="card-text text-black">Rédacteur & Développeur</p>
            </div>
        </div>


        <div class="card text-center" style="width: 9rem;margin-right: 3px;">
            <img src="{{ asset('auth/abdink.jpg') }}" class="card-img-top" alt="Abdillahi Mohamed Abdillahi" style="height: 150px; width: auto;">
            <div class="card-body">
                <p class="card-text text-black">Abdillahi <br>Mohamed <br>Abdillahi</p>
                <hr style="color:black;">
                <p class="card-text text-black">Rédacteur & Analyste</p>
            </div>
        </div>

        <div class="card text-center" style="width: 9rem;margin-right: 3px;">
            <img src="{{ asset('auth/mak.jpg') }}" class="card-img-top" alt="Mohamed Ahmed Kamil" style="height: 150px; width: auto;">
            <div class="card-body">
                <p class="card-text text-black">Mohamed <br>Ahmed <br>Kamil</p>
                <hr style="color:black;">
                <p class="card-text text-black">Contrôleur</p>
            </div>
        </div>

        <div class="card text-center" style="width: 9rem;margin-right: 3px;">
            <img src="{{ asset('auth/abdi.jpg') }}" class="card-img-top" alt="Abdillahi Mohamed Abdi" style="height: 150px; width: auto;">
            <div class="card-body">
                <p class="card-text text-black">Abdillahi <br>Mohamed<br> Abdi</p>
                <hr style="color:black;">
                <p class="card-text text-black">Développeur</p>
            </div>
        </div>
    </div>
</div>


          <!-- Grid column -->
          <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
            <h6 class="text-uppercase mb-4 font-weight-bold">Contact</h6>
            <p><i class="fas fa-home mr-3"></i> Saline-Ouest</p>
            <p><i class="fas fa-envelope mr-3"></i> djibyoung@gmail.com</p>
            <p><i class="fas fa-phone mr-3"></i> +253 77 04 54 62</p>
            <p><i class="fas fa-print mr-3"></i> +253 21 26 18 68</p>
          </div>
          <!-- Grid column -->
        </div>
        <!--Grid row-->
      </section>
      <!-- Section: Links -->

      <hr class="my-3">

      <!-- Section: Copyright -->
      <section class="p-3 pt-0">
        <div class="row d-flex align-items-center">
          <!-- Grid column -->
          <div class="col-md-7 col-lg-8 text-center text-md-start">
            <!-- Copyright -->
            <div class="p-3">
              © 2023 Copyright :
              <a class="text-white" href="https://mdbootstrap.com/"
                 >DjibYoung</a
                >
            </div>
            <!-- Copyright -->
          </div>
          <!-- Grid column -->

          <!-- Grid column -->
          <div class="col-md-5 col-lg-4 ml-lg-0 text-center text-md-end">
            <!-- Facebook -->
            <a
               class="btn btn-outline-light btn-floating m-1"
               class="text-white"
               role="button"
               ><i class="fab fa-facebook-f"></i
              ></a>

            <!-- Twitter -->
            <a
               class="btn btn-outline-light btn-floating m-1"
               class="text-white"
               role="button"
               ><i class="fab fa-twitter"></i
              ></a>

            <!-- Google -->
            <a
               class="btn btn-outline-light btn-floating m-1"
               class="text-white"
               role="button"
               ><i class="fab fa-google"></i
              ></a>

            <!-- Instagram -->
            <a
               class="btn btn-outline-light btn-floating m-1"
               class="text-white"
               role="button"
               ><i class="fab fa-instagram"></i
              ></a>
          </div>
          <!-- Grid column -->
        </div>
      </section>
      <!-- Section: Copyright -->
    </div>
    <!-- Grid container -->
  </footer>
  <!-- Footer -->
  <script>

  document.addEventListener('DOMContentLoaded', function() {
    // Sélectionnez les éléments des messages
    var messages = document.querySelectorAll('.alert');
    var delai = 4000; // 4 secondes

    // Masquer les messages après le délai spécifié
    setTimeout(function() {
        messages.forEach(function(message) {
            message.style.display = 'none';
        });
    }, delai);
  });
</script>
</body>
</html>
