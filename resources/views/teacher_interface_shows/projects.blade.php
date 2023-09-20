@extends('layouts.app')

@section('content')

<div class="container-fluid bg-secondary py-3">
  <div class="container">
    <div class="row">
      <div class="col">
        <a class="navbar-brand fs-5 text-white" href="/home">
          <i class="bi bi-arrow-left"></i>
          Home
        </a>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <table class="table table-striped bg-light">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Mini-description</th>
              <th scope="col">Date début</th>
              <th scope="col">Date fin</th>
              <th scope="col">Projet</th>
            </tr>
          </thead>
          <tbody>
            @if ($tasks == '[]') <!-- // Si le tâche est vide // -->
              <tr>
              <th scope="row" colspan="5" class="text-center">Aucun résultat</th>
            </tr>
            @else <!-- // Si le tâche n'est pas vide // -->
            @foreach($tasks as $task)
            <tr>
              <th scope="row">{{ $task->id }}</th>
              <td>{{ str_word_count($task->description) > 10 ? implode(' ', array_slice(explode(' ', $task->description), 0, 10)) . ' ...' : $task->description }}</td>
              <td>{{ $task->start_date }}</td>
              <td>{{ $task->end_date }}</td>
              <td>
                  <a href="{{ route('project_teacher.all.show',['id' => $task->group->project->id]) }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-eye"></i>
                  </a>
            </td>
            </tr>
            @endforeach
            @endif <!-- // Tâche vide ou pas // -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@endsection
