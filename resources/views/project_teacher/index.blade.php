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

<!-- ------------------------------------------------------------------------- -->
  @if (isset($search)) <!-- // Si c'est une recherche // -->
<!-- ------------------------------------------------------------------------- -->
    


    <div class="col text-center">
      <a href="/project_teacher_index" class="btn btn-primary">Revenir sur la page index</a>
    </div>

        <div class="container">
  <!-- Bouton "+" en haut à droite -->
  <div class="text-end">
    <a href="{{ route('project_teacher.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
  </div>
</div>

    <div class="container">
  <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
    <table class="table table-hover table-light text-center">
      <thead>
        <tr class="table-primary">
          <th scope="col">#</th>
          <th scope="col">Titre</th>
          <th scope="col">Afficher en détail</th> 
          <th scope="col">Modifier</th>
          <th scope="col">Supprimer</th>
        </tr>
      </thead>
      <tbody>

       @if ($projects == '[]') <!-- // Si le projet est vide // -->
  
    <tr>
          <th scope="row" colspan="5" class="text-center">Aucun projet</th>
      </tr>


        @else <!-- // Si le projet n'est pas vide // -->

    <?php 
    $index = 1;
        foreach($projects as $project)
        {

        echo '<tr>
          <th scope="row">' . $index   . '</th>
          <td>' . $project->title . '</td>
          <td>
              <a href="' . route('project_teacher.all.show',['id' => $project->id]) . '" class="btn btn-outline-primary">
                <i class="fa-solid fa-eye"></i>
              </a>
          </td>
          <td>
              <a href="' . route('project_teacher.edit',['id' => $project->id]) . '" class="btn btn-outline-warning">
                <i class="fas fa-pencil-alt"></i>
              </a>
          </td>
          <td>
            <a href=" ' . route('project_teacher.delete',['id' => $project->id]) . ' " class="btn btn-outline-danger" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.\')">
                <i class="fas fa-trash-alt"></i>
            </a>
          </td>
        </tr>';
        $index++;
        }
    ?>

      
      
    @endif <!-- // Si (recherche ou pas ) // -->
    
    </tbody>
    </table>

    </div>
    </div>

<!-- ------------------------------------------------------------------------- -->
  @else <!-- // Si ce n'est pas une recherche // -->
<!-- ------------------------------------------------------------------------- -->

    <div class="container">
  <!-- Bouton "+" en haut à droite -->
  <div class="text-end">
    <a href="{{ route('project_teacher.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
  </div>
</div>

    <div class="container">
  <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
    <table class="table table-hover table-light text-center">
      <thead>
        <tr class="table-primary">
          <th scope="col">#</th>
          <th scope="col">Titre</th>
          <th scope="col">Afficher en détail</th> 
          <th scope="col">Modifier</th>
          <th scope="col">Supprimer</th>
        </tr>
      </thead>
      <tbody>

       @if ($projects == '[]') <!-- // Si le projet est vide // -->
  
    <tr>
          <th scope="row" colspan="5" class="text-center">Aucun projet</th>
      </tr>


        @else <!-- // Si le projet n'est pas vide // -->

    <?php 
    $index = 1;
        foreach($projects as $project)
        {

        echo '<tr>
          <th scope="row">' . $index   . '</th>
          <td>' . $project->title . '</td>
          <td>
              <a href="' . route('project_teacher.all.show',['id' => $project->id]) . '" class="btn btn-outline-primary">
                <i class="fa-solid fa-eye"></i>
              </a>
          </td>
          <td>
              <a href="' . route('project_teacher.edit',['id' => $project->id]) . '" class="btn btn-outline-warning">
                <i class="fas fa-pencil-alt"></i>
              </a>
          </td>
          <td>
            <a href=" ' . route('project_teacher.delete',['id' => $project->id]) . ' " class="btn btn-outline-danger" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.\')">
                <i class="fas fa-trash-alt"></i>
            </a>
          </td>
        </tr>';
        $index++;
        }
    ?>

      
      
    @endif <!-- // Si (recherche ou pas ) // -->
    
    </tbody>
    </table>

    </div>
    </div>

<!-- ------------------------------------------------------------------------- -->
  @endif <!-- // Si c'est ne recherche ou non // -->
<!-- ------------------------------------------------------------------------- -->


    @endsection
    @section('scripts')

    <script>
        let erreur = <?php echo json_encode(session('erreur', '')) ?>;
      
        if (erreur !== '') {
            alert(erreur);
        }

    </script>

    @endsection
