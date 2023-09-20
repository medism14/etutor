@extends('layouts.app')

@section('content')

<style>
html,
body {
  height: 100%;
  margin: 0;
  padding: 0;
}

.intro {
  display: flex;
  padding: 50px;
  align-items: center;
  justify-content: center;
  height: 100%;
  background-image: url('{{ asset('auth/img2.jpg') }}');
  background-color: rgba(25, 185, 234, .25);
  background-size: cover;
  background-position: center;
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

<section class="full-height intro">
  <div class="container">
    <div class="back-button">
<a href="{{ route('users') }}" class="btn btn-primary float-left"><i class="fas fa-arrow-left"></i> Retour</a>
    </div>
    <div class="card">
      <div class="card-body">
        <div class="table-responsive w-100">
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Etudiants</th>
                <th scope="col">Projet</th>
              </tr>
            </thead>
            <tbody>
              @if ($group == null) <!-- // Si c'est vide // -->

    <tr>
        <th scope="row" colspan="4" class="text-center">Aucun résultat</th>
    </tr>

    @else  <!-- // Si ce n'est pas vide // -->


    <tr>
      <th scope="row">{{ $group->id }}</th>
      <td>
          @foreach ($group->studentgroup as $studentgroup)
                    - {{ $studentgroup->student->first_name }} {{ $studentgroup->student->name }} <br>
          @endforeach
      </td>
      <td>{{ $group->project->title }}</td>
    </tr>

    @endif <!-- // Si vide ou pas // -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
