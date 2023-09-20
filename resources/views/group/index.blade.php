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
    
<!-- ------------------------------------------------------------------------- -->
      @if (isset($search)) <!-- // Si une recherche a été passé // -->
<!-- ------------------------------------------------------------------------- -->

          <div class="container bg-dark p-3 rounded mb-3">

  <form action="{{ route('group.search') }}" method="POST">
    @csrf
    <div class="input-group mb-3">

      <select class="form-select text-dark" id="filterSelect" name="filter">
        <option value="teachers" selected>Professeurs</option>
        <option value="students">Etudiants</option>
      </select>
      
      <select class="form-select text-dark rounded-2" id="teachers_select" name="teachers">
        @foreach ($teachers as $teacher)
          <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->name }}</option>
        @endforeach
      </select>
      <select class="form-select text-dark rounded-2" id="students_select" name="students">
        
        @foreach ($students as $student)
          <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->name }}</option>
        @endforeach

      </select>
      <button class="btn btn-outline-primary text-white" type="submit" id="button-addon2">Rechercher</button>
    </div>
  </form>

  <div class="row mt-3">
    <div class="col text-center">
      <a href="/group_index" class="btn btn-primary">Revenir sur la page index</a>
    </div>
  </div>


    <div class="container">
  <!-- Bouton "+" en haut à droite -->
  <div class="text-end">
    <a href="{{ route('group.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
  </div>
</div>

<div class="container">
  <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
<table class="table table-hover table-light text-center">
  <thead>
    <tr class="table-primary">
      <th scope="col">#</th>
      <th scope="col">Projet</th>
      <th scope="col">Professeur</th>
      <th scope="col">Etudiants</th>
      <th scope="col">Modifier</th>
      <th scope="col">Supprimer</th>
    </tr>
  </thead>
  <tbody>

    @if ($groups->isEmpty()) <!-- // Si c'est vide // -->

    <tr>
        <th scope="row" colspan="6">Aucun résultat</th>
    </tr>

    @else  <!-- // Si ce n'est pas vide // -->

      @foreach($groups as $group)
    <tr>
      <th scope="row">{{ $group->id }}</th>
      <td>
        <a href="{{ route('group.show.project',['id' => $group->id]) }}" class="btn btn-outline-primary">
            <i class="fas fa-eye"></i>
          </a>
      </td>
      <td>
        {{ $group->project->teacher->first_name }} {{ $group->project->teacher->name }}
      </td>
      <td>
        <a href="{{ route('group.show.students',['id' => $group->id]) }}" class="btn btn-outline-primary">
            <i class="fas fa-eye"></i>
          </a>
      </td>
      <td>
          <a href="{{ route('group.edit',['id' => $group->id]) }}" class="btn btn-outline-warning">
            <i class="fas fa-pencil-alt"></i>
          </a>
      </td>
      <td>
        <a href="{{ route('group.delete',['id' => $group->id]) }}" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce groupe ? Cette action est irréversible.')">
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
    


      
<!-- ------------------------------------------------------------------------- -->
      @else <!-- // Sans recherche // -->
<!-- ------------------------------------------------------------------------- -->


    <div class="container bg-dark p-3 rounded mb-3">
  <form action="{{ route('group.search') }}" method="POST">
    @csrf
    <div class="input-group mb-3">

      <select class="form-select text-dark" id="filterSelect" name="filter">
        <option value="teachers" selected>Professeurs</option>
        <option value="students">Etudiants</option>
      </select>

      <select class="form-select text-dark rounded-2" id="teachers_select" name="teachers">
        @foreach ($teachers as $teacher)
          <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->name }}</option>
        @endforeach
      </select>
      <select class="form-select text-dark rounded-2" id="students_select" name="students">
        
        @foreach ($students as $student)
          <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->name }}</option>
        @endforeach

      </select>
      <button class="btn btn-outline-primary text-white" type="submit" id="button-addon2">Rechercher</button>
    </div>
  </form>


     <div class="container">
  <!-- Bouton "+" en haut à droite -->
  <div class="text-end">
    <a href="{{ route('group.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
  </div>
</div>

<div class="container">
  <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
<table class="table table-hover table-light text-center">
  <thead>
    <tr class="table-primary">
      <th scope="col">#</th>
      <th scope="col">Projet</th>
      <th scope="col">Professeur</th>
      <th scope="col">Etudiants</th>
      <th scope="col">Modifier</th>
      <th scope="col">Supprimer</th>
    </tr>
  </thead>
  <tbody>

    @if ($groups->isEmpty()) <!-- // Si c'est vide // -->

    <tr>
        <th scope="row" colspan="6">Aucun résultat</th>
    </tr>

    @else  <!-- // Si ce n'est pas vide // -->

      @foreach($groups as $group)
    <tr>
      <th scope="row">{{ $group->id }}</th>
      <td>
        <a href="{{ route('group.show.project',['id' => $group->id]) }}" class="btn btn-outline-primary">
            <i class="fas fa-eye"></i>
          </a>
      </td>
      <td>
        {{ $group->project->teacher->first_name }} {{ $group->project->teacher->name }}
      </td>
      <td>
        <a href="{{ route('group.show.students',['id' => $group->id]) }}" class="btn btn-outline-primary">
            <i class="fas fa-eye"></i>
          </a>
      </td>
      <td>
          <a href="{{ route('group.edit',['id' => $group->id]) }}" class="btn btn-outline-warning">
           <i class="fas fa-pencil-alt"></i>
          </a>
      </td>
      <td>
        <a href="{{ route('group.delete',['id' => $group->id]) }}" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce groupe ? Cette action est irréversible.')">
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



      @endif




@endsection
@section('scripts')

<script>
  const filterSelect = document.getElementById('filterSelect');
  const studentSelect = document.getElementById('students_select');
  const teacherSelect = document.getElementById('teachers_select');

  // fonction appelée au chargement de la page
  function onPageload() {
    const selectedValue = filterSelect.value;
    if (selectedValue === 'students') {
      studentSelect.style.display = 'block';
      teacherSelect.style.display = 'none';
    } else {
      studentSelect.style.display = 'none';
      teacherSelect.style.display = 'block';
    }
  }

  filterSelect.addEventListener('change', function(event) {
    const selectedValue = event.target.value;
    if (selectedValue === 'students') {
      studentSelect.style.display = 'block';
      teacherSelect.style.display = 'none';
    } else {
      studentSelect.style.display = 'none';
      teacherSelect.style.display = 'block';
    }
  });

  // appel de la fonction au chargement de la page
  onPageload();

</script>

@endsection
