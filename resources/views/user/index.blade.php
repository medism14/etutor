@extends('layouts.app')

@section('content')


  <div class="container">
    
    @if (session('result'))
    <div class="alert alert-success fs-6" role="alert">
        <strong>Succès !</strong> {{ session('result') }}.
    </div>
    @endif

    @if (session('delete'))
    <div class="alert alert-danger fs-6" role="alert">
        <strong>Succès !</strong> {{ session('delete') }}.
    </div>
    @endif

  </div>

  <div class="container d-flex justify-content-start">
  <a class="navbar-brand fs-5" href="/home">
    <i class="bi bi-arrow-left"></i>
    Home
  </a>
</div>
  @if (isset($search)) <!-- // Si une recherche a été passé // -->



    <div class="container bg-dark p-3 rounded mb-3">
      <div class="row">
  <form action="{{ route('user.search') }}" method="POST">
    @csrf
    <div class="input-group mb-3">
      <select class="form-select text-dark" id="roleSelect" name="role">
        <option value="0">Administrateur</option>
        <option value="1">Professeur</option>
        <option value="2">Etudiant</option>
      </select>
      <input type="text" class="form-control" placeholder="Recherche..." name="search" id="searchInput" required>
      <select class="form-select text-dark" id="filterSelect" name="filter">
        <option value="first_name" selected>Prenom</option>
        <option value="email">Email</option>
        <option value="phone">Téléphone</option>
      </select>
      <button class="btn btn-outline-primary text-white" type="submit" id="button-addon2">Rechercher</button>
    </div>
  </form>
</div>
    <div class="row mt-3">
    <div class="col text-center">
      <a href="/user_index" class="btn btn-primary">Revenir sur la page index</a>
    </div>
  </div>

    <div class="container">
  <!-- Bouton "+" en haut à droite -->
  <div class="text-end">
    <a href="{{ route('user.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
  </div>
</div>

<div class="container">
  <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
    <table class="table table-hover table-light text-center">
      <thead>
        <tr class="table-primary">
          <th scope="col">#</th>
          <th scope="col">Prénom</th>
          <th scope="col">Nom</th>
          <th scope="col">Email</th>
          <th scope="col">Phone</th>
          <th scope="col">Rôle</th>
          <th scope="col">Ses projets</th>
          <th scope="col">Son groupe</th>
          <th scope="col">Modifier</th>
          <th scope="col">Supprimer</th>
        </tr>
      </thead>
      <tbody>
        @if ($users->isEmpty()) <!-- // Si c'est vide // -->
        <tr>
          <th scope="row" colspan="10">Aucun résultat</th>
        </tr>
        @else  <!-- // Si ce n'est pas vide // -->
        @foreach($users as $user)
        <tr>
          <th scope="row">{{ $user->id }}</th>
          <td>{{ $user->first_name }}</td>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>{{ $user->phone }}</td>
          <td>
            @if ($user->role == 0)
            Administrateur
            @elseif ($user->role == 1)
            Professeur
            @elseif ($user->role == 2)
            Étudiant
            @endif
          </td>
          <td>
            @if ($user->teachers_projects->isNotEmpty()) <!-- // Voir si l'utilisateur a un projet // -->
            <a href="{{ route('user.projects.show',['id' => $user->id]) }}" class="btn btn-outline-primary">
              <i class="fas fa-eye"></i>
            </a>
            @endif
          </td>
          <td>
            @if (isset($user->students) && $user->students->group != null) <!-- // Voir si l'utilisateur est dans un groupe // -->
            <a href="{{ route('user.group',['group_id' => $user->students->group->id]) }}" class="btn btn-outline-primary">
              <i class="fas fa-eye"></i>
            </a>
            @endif
          </td>
          <td>
            <a href="{{ route('user.edit',['id' => $user->id]) }}" class="btn btn-outline-warning">
              <i class="fas fa-pencil-alt"></i>
            </a>
          </td>
          <td>
            <a href="{{ route('user.delete',['id' => $user->id]) }}" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')">
              <i class="fas fa-trash-alt"></i>
            </a>
          </td>
        </tr>
        @endforeach
        @endif <!-- // Si vide ou pas // -->
      </tbody>
    </table>
  </div>
</div>


</div>






  @else <!-- // Sans recherche // -->

  <div class="container bg-dark p-3 rounded mb-3">

  <form action="{{ route('user.search') }}" method="POST">
    @csrf
    <div class="input-group mb-3">
      <select class="form-select text-dark" id="roleSelect" name="role">
        <option value="0">Administrateur</option>
        <option value="1">Professeur</option>
        <option value="2">Etudiant</option>
      </select>
      <input type="text" class="form-control" placeholder="Recherche..." name="search" id="searchInput" required>
      <select class="form-select text-dark" id="filterSelect" name="filter">
        <option value="first_name" selected>Prenom</option>
        <option value="email">Email</option>
        <option value="phone">Téléphone</option>
      </select>
      <button class="btn btn-outline-primary text-white" type="submit" id="button-addon2">Rechercher</button>
    </div>
  </form>

  <div class="container">
  <!-- Bouton "+" en haut à droite -->
  <div class="text-end">
    <a href="{{ route('user.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
  </div>
</div>

<div class="container">
  <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
    <table class="table table-hover table-light text-center">
      <thead>
        <tr class="table-primary">
          <th scope="col">#</th>
          <th scope="col">Prénom</th>
          <th scope="col">Nom</th>
          <th scope="col">Email</th>
          <th scope="col">Phone</th>
          <th scope="col">Rôle</th>
          <th scope="col">Ses projets</th>
          <th scope="col">Son groupe</th>
          <th scope="col">Modifier</th>
          <th scope="col">Supprimer</th>
        </tr>
      </thead>
      <tbody>
        @if ($users->isEmpty()) <!-- // Si c'est vide // -->
        <tr>
          <th scope="row" colspan="10">Aucun résultat</th>
        </tr>
        @else  <!-- // Si ce n'est pas vide // -->
        @foreach($users as $user)
        <tr>
          <th scope="row">{{ $user->id }}</th>
          <td>{{ $user->first_name }}</td>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>{{ $user->phone }}</td>
          <td>
            @if ($user->role == 0)
            Administrateur
            @elseif ($user->role == 1)
            Professeur
            @elseif ($user->role == 2)
            Étudiant
            @endif
          </td>
          <td>
            @if ($user->teachers_projects->isNotEmpty()) <!-- // Voir si l'utilisateur a un projet // -->
            <a href="{{ route('user.projects.show',['id' => $user->id]) }}" class="btn btn-outline-primary">
              <i class="fas fa-eye"></i>
            </a>
            @endif
          </td>
          <td>
            @if (isset($user->students) && $user->students->group != null) <!-- // Voir si l'utilisateur est dans un groupe // -->
            <a href="{{ route('user.group',['group_id' => $user->students->group->id]) }}" class="btn btn-outline-primary">
              <i class="fas fa-eye"></i>
            </a>
            @endif
          </td>
          <td>
            <a href="{{ route('user.edit',['id' => $user->id]) }}" class="btn btn-outline-warning">
              <i class="fas fa-pencil-alt"></i>
            </a>
          </td>
          <td>
            <a href="{{ route('user.delete',['id' => $user->id]) }}" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')">
              <i class="fas fa-trash-alt"></i>
            </a>
          </td>
        </tr>
        @endforeach
        @endif <!-- // Si vide ou pas // -->
      </tbody>
    </table>
  </div>
</div>
</div>




</div>

  




@endif

@endsection
@section('scripts')

<style>
  .table-custom {
    background-color: #f8f9fa;
  }
</style>
<script>
    

  const filterSelect = document.getElementById("filterSelect");
const searchInput = document.getElementById("searchInput");

filterSelect.addEventListener("change", function() {


  switch(filterSelect.value) {
    case "first_name":
      searchInput.type = "text";
      searchInput.value = "";
      break;
    case "email":
      searchInput.type = "email";
      searchInput.value = "";
      break;
    case "phone":
      searchInput.type = "number";
      searchInput.value = "";
      break;
  }
});

function confirmDelete(url) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')) {
        window.location.href = url;
    }
}

</script>

@endsection