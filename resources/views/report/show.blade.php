@extends('layouts.app')

@section('content')
<h1 class="mb-5 text-white">Projets du professeur {{ $user->first_name }}</h1>
<div class="container bg-white">
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Titre</th>
      <th scope="col">Description</th>
      <th scope="col">Statut</th>
    </tr>
  </thead>
  <tbody>

    @foreach ($projects as $project)

    <tr>
      <th scope="row">{{ $project->id }}</th>
      <td>{{ $project->title }}</td>
      <td>{{ $project->description }}</td>
      <td>{{ $project->state }}</td>
    </tr>

    @endforeach


  </tbody>
</table>
</div>
@endsection
