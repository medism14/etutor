@extends('layouts.app')

@section('content')

    @if (auth()->user()->role == 0) <!-- // Si c'est un administrateur // -->

        <!---------------------- PREMIERE COLONNE ---------------------->

  <h1 class="text-light text-decoration-underline text-center mb-4 text-black" style="margin-bottom:50px;">Tableau de bord</h1>

<div class="container">
  <div class="row row-cols-1 row-cols-md-3 g-4">
    <div class="col">
      <div class="card bg-primary text-white border-0">
        <div class="card-body">
          <h5 class="card-title text-center fs-4">Projets en cours</h5>
          <p class="card-text text-center fs-2 fw-bold">{{ $count_projects_proceed }}</p>
          <a href="/affichage_projects_proceed" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-right me-1"></i> Plus d'info
          </a>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card bg-success text-white border-0">
        <div class="card-body">
          <h5 class="card-title text-center fs-4">Projets terminés</h5>
          <p class="card-text text-center fs-2 fw-bold">{{ $count_projects_finish }}</p>
          <a href="/affichage_projects_finish" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-right me-1"></i> Plus d'info
          </a>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card bg-danger text-white border-0">
        <div class="card-body">
          <h5 class="card-title text-center fs-4">Projets sans groupe</h5>
          <p class="card-text text-center fs-2 fw-bold">{{ $count_projects_whithout_groups }}</p>
          <a href="/affichage_projects_without_groups" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-right me-1"></i> Plus d'info
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="row row-cols-1 row-cols-md-3 g-4 mt-3">
    <div class="col">
      <div class="card bg-danger text-white border-0">
        <div class="card-body">
          <h5 class="card-title text-center fs-4">Professeurs</h5>
          <p class="card-text text-center fs-2 fw-bold">{{ $count_teachers }}</p>
          <a href="/user_teachers" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-right me-1"></i> Plus d'info
          </a>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card bg-success text-white border-0">
        <div class="card-body">
          <h5 class="card-title text-center fs-4">Étudiants</h5>
          <p class="card-text text-center fs-2 fw-bold">{{ $count_students }}</p>
          <a href="/user_students" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-right me-1"></i> Plus d'info
          </a>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card bg-primary text-white border-0">
        <div class="card-body">
          <h5 class="card-title text-center fs-4">Groupes</h5>
          <p class="card-text text-center fs-2 fw-bold">{{ $count_groups }}</p>
          <a href="/group_index" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-right me-1"></i> Plus d'info
          </a>
        </div>
      </div>
    </div>
  </div>
</div>





    

    @else <!-- // Si c'est pas un administrateur // -->

        @if (auth()->user()->role == 1) <!-- // Si c'est un professeur // -->

  <h1 class="text-light text-decoration-underline text-center mb-4 text-dark" style="margin-bottom:50px;">Tableau de bord</h1>

<div class="container">
  <div class="row row-cols-1 row-cols-md-3 g-4">
    <div class="col">
      <div class="card bg-primary text-white border-0">
        <div class="card-body">
          <h5 class="card-title text-center fs-4">Tâches sans compte-rendu</h5>
          <p class="card-text text-center fs-2 fw-bold">{{ $count_tasks_proceed }}</p>
          <a href="/tasks_proceed" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-right me-1"></i> Plus d'info
          </a>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card bg-success text-white border-0">
        <div class="card-body">
          <h5 class="card-title text-center fs-4">Tâches avec compte-rendu sans commentaire</h5>
          <p class="card-text text-center fs-2 fw-bold">{{ $count_tasks_finish }}</p>
          <a href="/tasks_finish" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-right me-1"></i> Plus d'info
          </a>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card bg-danger text-white border-0">
        <div class="card-body">
          <h5 class="card-title text-center fs-4">Tâches non traité (expiré)</h5>
          <p class="card-text text-center fs-2 fw-bold">{{ $count_tasks_expirate }}</p>
          <a href="/tasks_expirate" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-right me-1"></i> Plus d'info
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="row row-cols-1 row-cols-md-3 g-4 mt-3">
    <div class="col">
      <div class="card bg-danger text-white border-0">
        <div class="card-body">
          <h5 class="card-title text-center fs-4">Groupes encadré</h5>
          <p class="card-text text-center fs-2 fw-bold">{{ $count_teacher_groups }}</p>
          <a href="/teacher_groups" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-right me-1"></i> Plus d'info
          </a>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card bg-success text-white border-0">
        <div class="card-body">
          <h5 class="card-title text-center fs-4">Projets en cours</h5>
          <p class="card-text text-center fs-2 fw-bold">{{ $count_teacher_proceed_project }}</p>
          <a href="/teacher_project_proceed" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-right me-1"></i> Plus d'info
          </a>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card bg-primary text-white border-0">
        <div class="card-body">
          <h5 class="card-title text-center fs-4">Projets fini</h5>
          <p class="card-text text-center fs-2 fw-bold">{{ $count_teacher_finish_project }}</p>
          <a href="/teacher_project_finish" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-right me-1"></i> Plus d'info
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
        @else <!-- // Si c'est un etudiant // -->

        @endif <!-- // Si c'est un professeur ou un etudiant // -->

    @endif <!-- // Si c'est un administrateur ou pas // -->


@endsection
@section('scripts')
<style>
    
</style>
@endsection
