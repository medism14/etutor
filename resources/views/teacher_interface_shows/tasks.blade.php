@extends('layouts.app')

@section('content')

<section class="full-height intro">
  <div class="container">
    <h1 class="mb-5 text-white text-center">Taches {{ $tk }}</h1>
    <div class="back-button">
<a href="{{ route('accueil') }}" class="btn btn-secondary float-left"><i class="fas fa-arrow-left"></i> Retour</a>

    </div>
    <div class="card">
      <div class="card-body">
        <div class="table-responsive w-100">
          <table class="table table-hover">
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
              @php
                $i = 0;
            @endphp

            @if ($tasks->isEmpty()) <!-- // Si le tâche est vide // -->
              <tr>
              <th scope="row" colspan="5" class="text-center">Aucun résultat</th>
            </tr>
            @else <!-- // Si le tâche n'est pas vide // -->
            @foreach($tasks as $task)
            <tr>
              <th scope="row">{{ ++$i }}</th>
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
</section>

@endsection
