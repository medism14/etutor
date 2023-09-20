@extends('layouts.app')

@section('content')
<section class="full-height intro">
  <div class="container">
    <h1 class="mb-5 text-white text-center">Groupes encadré</h1>
    <div class="back-button">
<a href="{{ route('accueil') }}" class="btn btn-primary float-left"><i class="fas fa-arrow-left"></i> Retour</a>

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
               @php
                $i = 0;
            @endphp

            @if ($groups->isEmpty()) <!-- // Si le groupe est vide // -->
              <tr>
              <th scope="row" colspan="3" class="text-center">Aucun résultat</th>
            </tr>
            @else <!-- // Si le groupe n'est pas vide // -->

              @foreach ($groups as $group)
              <tr>
                <th scope="row">{{ ++$i }}</th>
                <td>
                  @foreach ($group->studentgroup as $studentgroup)
                    - {{ $studentgroup->student->first_name }} {{ $studentgroup->student->name }} <br>
                  @endforeach
                </td>
                <td>
                  <a href="{{ route('project_teacher.all.show',['id' => $group->project->id]) }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-eye"></i>
                  </a>
                </td>
              </tr>
              @endforeach

            @endif <!-- // groupe vide ou pas // -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
