@extends('layouts.app')

@section('content')
  <div class="container">
<h1 class="mb-5 text-white text-center">Etudiants dans le groupe</h1>

    <div class="back-button">
<a href="{{ route('groups') }}" class="btn btn-primary float-left"><i class="fas fa-arrow-left"></i> Retour</a>

    </div>
    <div class="card">
      <div class="card-body">
        <div class="table-responsive w-100">
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Prenom</th>
                <th scope="col">Nom</th>
                <th scope="col">Email</th>
                <th scope="col">Telephone</th>
                <th scope="col">Role</th>
              </tr>
            </thead>
            <tbody>
               @if ($students->isEmpty()) <!-- // Si c'est vide // -->

    <tr>
        <th scope="row" colspan="6" class="text-center">Aucun résultat</th>
    </tr>

    @else  <!-- // Si ce n'est pas vide // -->

      @foreach ($students as $student)


    <tr>
      <th scope="row">{{ $student->student->id }}</th>
      <td>{{ $student->student->first_name }}</td>
      <td>{{ $student->student->name }}</td>
      <td>{{ $student->student->email }}</td>
      <td>{{ $student->student->phone }}</td>
      <td>Etudiant</td>
    </tr>

    @endforeach

    @endif <!-- // Si vide ou pas // -->

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
