@extends('layouts.app')

@section('content')



<section class="full-height intro">
  <div class="container">
    <h1 class="mb-5 text-white text-center">Projets {{ $pr }}</h1>
    <div class="back-button">
<a href="{{ route('home') }}" class="btn btn-primary float-left"><i class="fas fa-arrow-left"></i> Retour</a>
    </div>
    <div class="card">
      <div class="card-body">
        <div class="table-responsive w-100">
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Titre</th>
                <th scope="col">Mini-description</th>
                <th scope="col">Statut</th>
              </tr>
            </thead>
            <tbody>
              @if ($projects->isEmpty())
                <tr>
                  <th scope="row" colspan="4" class="text-center">Aucun résultat</th>
                </tr>
              @else
                @foreach($projects as $project)
                  <tr>
                    <th scope="row">{{ $project->id }}</th>
                    <td>{{ $project->title }}</td>
                    <td>{{ str_word_count($project->description) > 10 ? implode(' ', array_slice(explode(' ', $project->description), 0, 10)) . ' ...' : $project->description }}</td>
                    <td>{{ $project->state }}</td>
                  </tr>
                @endforeach
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
