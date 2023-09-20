@extends('layouts.app')

@section('content')

    <title>Erreur 419 - Session expirée</title>
    <!-- Inclure les fichiers CSS de Bootstrap -->
    <style>
        /* Ajouter du style personnalisé */
        body {
            background-color: #f8f9fa;
            padding: 40px;
            font-family: Arial, sans-serif;
        }

        .error-container {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1 class="text-center mb-4">Erreur 419 - Session expirée</h1>
        <p class="text-center">Votre session a expiré. Veuillez rafraîchir la page et réessayer.</p>
        <div class="text-center mt-4">
            <a href="{{ route('accueil') }}" class="btn btn-secondary">Connexion</a>
        </div>
    </div>
@endsection
