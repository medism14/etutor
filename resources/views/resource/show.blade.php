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

<!-- //////////////////////////////////////////////////////////////////////// -->
  @if (Auth()->user()->role == 2) <!-- // Si c'est un etudiant // -->
<!-- //////////////////////////////////////////////////////////////////////// -->

  <div class="container" >

  @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <a href="{{ route('projects_student') }}" class="btn btn-secondary float-left"><i class="fas fa-arrow-left"></i> Retour</a>




<table class="table table-striped table-dark">
  <thead>
    <tr>
      <th scope="col" class="text-center">#</th>
      <th scope="col" class="text-center">Nom du fichier</th>
      <th scope="col" class="text-center">Telecharger</th>
    </tr>
  </thead>
  <tbody>
      @if ($resources->isEmpty())  <!-- // Si tableau vide // -->
        <tr>
            <th scope="row" class="text-center" colspan="2">Aucune ressource</th>
            
        </tr>
      @else <!-- // Si tableau pas vide // -->

      @php
        $i = 0;
      @endphp

    @foreach ($resources as $resource)
        <tr>
            <th scope="row" class="text-center">{{ ++$i }}</th>
            <td class="text-center">
              <?php
                $path = $resource->resource;
                        $filename = basename($path);
                        echo $filename; // Affiche "MRD.txt"
               ?></td>
            <td class="text-center">
                <a href="{{ route('resource.download.student',['id' => $resource->id]) }}" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-cloud-download-alt"></i> Télécharger
                </a>
            </td>
        </tr>
    @endforeach
      @endif <!-- // Si tableau vide ou pas // -->
  </tbody>
</table>
</div>

<!-- //////////////////////////////////////////////////////////////////////// -->
  @else <!-- // Si c'est un professeur // -->
<!-- //////////////////////////////////////////////////////////////////////// -->


<div class="container" style="margin-bottom: 30%;"> 

  @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <a href="{{ route('project_teacher.all.show', ['id' => $project_id ]) }}" class="btn btn-secondary float-left"><i class="fas fa-arrow-left"></i> Retour</a>
    @if ($project->state == 'en cours') <!-- // Si le projet est en cours // -->
  <a href="{{ route('resource.create', ['project_id' => $project_id ]) }}" class="btn btn-primary float-end"><i class="fas fa-plus"></i></a>
    @else <!-- // Si le projet est fini // -->

    @endif <!-- // Si le projet est en cours ou fini // -->


<table class="table table-striped table-dark">
  <thead>
    <tr>
      <th scope="col" class="text-center">#</th>
      <th scope="col" class="text-center">Nom du fichier</th>
      <th scope="col" class="text-center">Telecharger</th>
      <th scope="col" class="text-center">Modifier</th>
      <th scope="col" class="text-center">Supprimer</th>
    </tr>
  </thead>
  <tbody>
      @if ($resources->isEmpty())  <!-- // Si tableau vide // -->
        <tr>
            <th scope="row" class="text-center" colspan="5">Aucune ressource</th>
            
        </tr>
      @else <!-- // Si tableau pas vide // -->
  @php
        $i = 0;
      @endphp
    @foreach ($resources as $resource)
        <tr>
            <th scope="row" class="text-center">{{ ++$i }}</th>
            <td class="text-center">
              <?php
                $path = $resource->resource;
                        $filename = basename($path);
                        echo $filename; // Affiche "MRD.txt"
               ?></td>
            <td class="text-center">
                <a href="{{ route('resource.download',['id' => $resource->id]) }}" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-cloud-download-alt"></i> Télécharger
                </a>
            </td>
            <td class="text-center">
                  @if ($resource->project->state == 'en cours') <!-- // Si le projet est en cours // -->
                    <a href="{{ route('resource.edit',['id' => $resource->id]) }}" class="btn btn-outline-warning btn-sm">
                    <i class="fas fa-pencil-alt"></i> Modifier
                    </a>

                  @else <!-- // Si le projet est fini // -->
                      Projet fini ! 
                  @endif <!-- // Si le projet est en cours ou fini // -->
                
            </td>
            <td class="text-center">
              @if ($resource->project->state == 'en cours') <!-- // Si le projet est en cours // -->
                    <a href="{{ route('resource.delete',['id' => $resource->id]) }}" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-trash-alt"></i> Supprimer
                    </a>
                    
                  @else <!-- // Si le projet est fini // -->
                      Projet fini ! 
                  @endif <!-- // Si le projet est en cours ou fini // -->
                
            </td>
        </tr>
    @endforeach
      @endif <!-- // Si tableau vide ou pas // -->
  </tbody>
</table>
</div>

<!-- //////////////////////////////////////////////////////////////////////// -->
  @endif <!-- // Voir si c'est un etudiant ou professeur // -->
<!-- //////////////////////////////////////////////////////////////////////// -->

<style>
  
  .container {
  max-width: 80%;
  padding: 0;
}

</style>

@endsection
