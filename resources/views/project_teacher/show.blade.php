@extends('layouts.app')

@section('content')

<div class="back-button">
<a href="{{ route('projects_teacher') }}" class="btn btn-secondary float-left"><i class="fas fa-arrow-left"></i> Retour</a>
</div>


<h1 class="mb-5 text-white text-center">Informations</h1>

  <?php 
    if ($project->state != 'en cours')
    {
      
    }
    else
    { 
      echo '<form method="POST" action=" ' . route('project_validate',['id' => $project->id]) . '">
      ' . csrf_field() . '
      <div class="d-flex justify-content-end mb-5" style="margin-right:5px;"><button class="btn btn-success" type="submit"><i class="fas fa-check"></i> Valider le projet</button></div>
      </form>';
    }
  ?>




<div class="text-light bg-dark" style="padding:20px;margin:0px 30px;">
  <?php 
      echo '<form method="POST" action=" ' . route('resource.shows',['project_id' => $project->id]) . '">
      ' . csrf_field() . '
      <div class="d-flex justify-content-end mb-5"><button class="btn btn-primary" type="submit">Resources</button></div>
      </form>';
  ?>
      <div class="row">
        <div class="col-sm-6">

            <div class="form-group">
                <label for="results" class="form-label"><span class="fs-4">Titre :</span></label>
                <textarea class="form-control fs-6" rows="2" readonly>{{ $project->title }}</textarea>
            </div>

            <div class="form-group">
                <label for="results" class="form-label"><span class="fs-4">Description :</span></label>
                <textarea class="form-control fs-6" rows="5" readonly>{{ $project->description }}</textarea>
            </div>

            <div class="form-group">
                <label for="results" class="form-label"><span class="fs-4">Statut :</span></label>
                <textarea class="form-control fs-6" rows="2" readonly>{{ $project->state }}</textarea>
            </div>


        </div>
        <div class="col-sm-6 d-flex align-items-center justify-content-center">
            
          <?php 

            if (isset($project->group))
            {
                  echo '<table class="table table-striped table-bordered table-primary" style="width:5!0%;">
                <thead class="table-dark">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Prenom</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Rôle</th>
                  </tr>
                </thead>
                <tbody>
                ';
                  foreach ($project->group->studentgroup as $studentgroup)
                  {
                    
                    echo '<tr>
                      <th scope="row">' . $studentgroup->student->id . '</th>
                      <td>' . $studentgroup->student->first_name . '</td>
                      <td>' . $studentgroup->student->name . '</td>
                      <td>' . $studentgroup->student->email . '</td>
                      <td>' . $studentgroup->student->phone . '</td>
                      <td> Etudiant </td>
                    </tr>';
                    
                  }

              echo '
            </tbody>
          </table>';
            }
            else
            {
              echo '
                <h1>Aucun groupe</h1>
              ';
            } 

          ?>

          


        </div>
      </div>
    </div>

    <!--  ----------------------------------------------------------------------------------  -->
    <!--  ----------------------------------------------------------------------------------  -->
    <!--  ----------------------------------------------------------------------------------  -->
    <!--  ----------------------------------------------------------------------------------  -->
    <!--  ----------------------------------------------------------------------------------  -->
    <!--  ----------------------------------------------------------------------------------  -->


  
    <div class="container mt-5">
    
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
    

        

      <?php 

      if (isset($project->group)) // Si un groupe existe
      {
          

                if ($project->state != 'fini') // Si le projet est en cours
                { 
                  echo '
                  <div class="container mt-5">
                  <div class="row">';

                  echo '<div class="d-flex justify-content-end">
                    <a href="' . route('task.create', ['group_id' => $project->group->id]) . '" class="btn btn-primary" style="width:50px;"><i class="fas fa-plus"></i></a>
                </div>';


                  echo '<div class="table-responsive w-100">
                  <table class="table table-hover table-light text-center">
                  <thead class="table-primary">
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Description de 10 mot</th>
                      <th scope="col">Date-debut</th>
                      <th scope="col">Temps-restant</th>
                      <th scope="col">Compte-rendu</th>
                      <th scope="col">Commentaire</th>
                      <th scope="col">Afficher</th>
                      <th scope="col">Modifier</th>
                      <th scope="col">Supprimer</th>
                    </tr>
                  </thead>
                  <tbody>';
                  $i=1;

                if ($project->group->tasks->isEmpty()) // Si c'est vide //
                { 
                    echo '<tr>
                            <th scope="row" colspan="9" class="text-center">Aucun résultat</th>
                        </tr>';
                }
                else // Si ce n'est pas vide //
                { 
                    foreach ($project->group->tasks as $task)
                    {

                      $currentDate = new DateTime();
                      $currentDate->add(DateInterval::createFromDateString('+3 hours'));

                                  $endDate = new DateTime($task->end_date);

                                  // Calculer la différence entre les deux dates
                                  $interval = $currentDate->diff($endDate);

                                  // Vérifier si la date est expirée
                                  $isExpired = $currentDate > $endDate;

                                  $remainingTime = $interval->format('%aj, %hh, %im et %ss');


                        echo '<tr>
                                  <th scope="row">' . $i . '</th>

                                  <td>' . (str_word_count($task->description) > 10 ? implode(' ', array_slice(explode(' ', $task->description), 0, 10)) . ' ...' : $task->description) . '</td>

                                  <td>' . $task->start_date . '</td>


                                  <td>' . ($isExpired ? 'Expiré' : $remainingTime) . '</td>


                                  <td>' . ($task->report != null ? 'Disponible' : 'Non disponible') . '</td>
                                  <td>' . (isset($task->report) && $task->report->comment != null ? 'Disponible' : 'Non Disponible') . '</td>
                                  <td>
                                      <a href="' . route('task.show', ['id' => $task->id]) . '" class="btn btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                      </a>
                                  </td>
                                  <td>
                                      <a href="' . route('task.edit', ['id' => $task->id , 'group_id' => $task->group->id]) . '" class="btn btn-outline-warning">
                                        <i class="fas fa-pencil-alt"></i>
                                      </a>
                                  </td>
                                  <td>
                                    <a href="' . route('task.delete', ['id' => $task->id]) . '" class="btn btn-outline-danger" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette tâche ? Cette action est irréversible.\')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                  </td>
                                </tr>
                                '; 

                             $i++;   

                    }
                }


          
                echo '</tbody>
                </table>
                </div>';
          

                }
                else // Si le projet est fini
                {

                  echo '
                  <div class="container mt-5">
                  <div class="row">';



                  echo '<div class="table-responsive w-100">
                  <table class="table table-hover table-light text-center">
                  <thead class="table-primary">
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Description de 10 mot</th>
                      <th scope="col">Compte-rendu</th>
                      <th scope="col">Commentaire</th>
                      <th scope="col">Afficher</th>
                    </tr>
                  </thead>
                  <tbody>';
                  $i=1;

                  if ($project->group->tasks->isEmpty()) // Si c'est vide //
                { 
                    echo '<tr>
                            <th scope="row" colspan="5">Aucun résultat</th>
                        </tr>';
                }
                else // Si ce n'est pas vide //
                { 

                    foreach ($project->group->tasks as $task)
                      {
                          echo '<tr>
                                    <th scope="row">' . $i . '</th>

                                    <td>' . (str_word_count($task->description) > 10 ? implode(' ', array_slice(explode(' ', $task->description), 0, 10)) . ' ...' : $task->description) . '</td>

                                    <td>' . ($task->report != null ? 'Disponible' : 'Non disponible') . '</td>
                                    <td>' . (isset($task->report) && $task->report->comment != null ? 'Disponible' : 'Non Disponible') . '</td>
                                    <td>
                                        <a href="' . route('task.show', ['id' => $task->id]) . '" class="btn btn-outline-info">
                                          <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                  </tr>'; 

                                $i++;

                      }

                }
          
                echo '</tbody>
                </table>
                </div>';

                } // Fin else du projet fini ou pas

              

                

      }
      else // S'il n'y a pas de groupe de projet
      {
          echo '
            <div class="container mt-5 bg-dark text-light d-flex justify-content-center">
            <div class="row">
          <h1>Aucune tâche</h1>';
      } 

      ?>


          

      </div>
    </div>







    <style>
      .btn {
        display: inline-block;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        font-size: 16px;
        cursor: pointer;

      }
      .btn i {
        margin-right: 0px;
      }
      .btn.right {
        float: right;
      }
    </style>
@endsection

