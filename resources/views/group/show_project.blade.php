@extends('layouts.app')

@section('content')
  <div class="container">
<h1 class="mb-5 text-white text-center">Projet du groupe</h1>

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
                <th scope="col">Titre</th>
                <th scope="col">Description</th>
                <th scope="col">Statut</th>
              </tr>
            </thead>
            <tbody>
              
    @if ($project == null ) <!-- // Si c'est vide // -->

    <tr>
        <th scope="row" colspan="4" class="text-center">Aucun résultat</th>
    </tr>

    @else  <!-- // Si ce n'est pas vide // -->

        <tr>
      <th scope="row">{{ $project->id }}</th>
      <td>{{ $project->title }}</td>
      <td>{{ $project->description }}</td>
      <td>{{ $project->state }}</td>
    </tr>

    @endif <!-- // Si vide ou pas // -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
