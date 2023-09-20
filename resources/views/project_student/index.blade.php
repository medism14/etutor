@extends('layouts.app')

@section('content')

  @isset($project)
   
<h1 class="mb-5 text-white text-center">Informations</h1>


<div class="text-light bg-dark" style="padding:20px;margin:0px 30px;">
    <?php 
      echo '<form method="POST" action=" ' . route('resource.shows.student',['project_id' => $project->id]) . '">
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
                  echo '<table class="table table-striped table-bordered table-primary">
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
                      
                    if ($studentgroup->student->id == auth()->user()->id)
                    {
                      echo '<tr>
                      <th scope="row" class="text-decoration-underline text-danger">' . $studentgroup->student->id . '</th>
                      <td class="text-decoration-underline text-danger">' . $studentgroup->student->first_name . '</td>
                      <td class="text-decoration-underline text-danger">' . $studentgroup->student->name . '</td>
                      <td class="text-decoration-underline text-danger">' . $studentgroup->student->email . '</td>
                      <td class="text-decoration-underline text-danger">' . $studentgroup->student->phone . '</td>
                      <td class="text-decoration-underline text-danger"> Etudiant </td>
                    </tr>';
                    }
                    else
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


            @if ($project->state != 'fini') <!-- Si le projet est en cours -->

              <div class="container mt-5">
                  <div class="row">


                  <div class="table-responsive w-100">
                  <table class="table table-hover table-light text-center">
                  <thead class="table-primary">
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Description de 10 mot</th>
                      <th scope="col">Date-debut</th>
                      <th scope="col">Temps-restant</th>
                      <th scope="col">Compte-rendu</th>
                      <th scope="col">Commentaire</th>
                      <th scope="col">Consulter</th>
                    </tr>
                  </thead>
                  <tbody>

              @if ($project->group->tasks->isEmpty()) <!-- // Si y'a pas des tâches qui existent // -->

                    <tr>
                        <th scope="row" class="fs-2" colspan="8" class="text-center">Aucun résultat</th>
                    </tr> 

              @else <!-- // Si y'a des taches qui existent // -->

                  <?php 
                  $index = 1;
    foreach ($project->group->tasks as $task) {


        $currentDate = new DateTime();
                      $currentDate->add(DateInterval::createFromDateString('+3 hours'));

                                  $endDate = new DateTime($task->end_date);

                                  // Calculer la différence entre les deux dates
                                  $interval = $currentDate->diff($endDate);

                                  // Vérifier si la date est expirée
                                  $isExpired = $currentDate > $endDate;

                                  $remainingTime = $interval->format('%aj, %hh, %im et %ss');
        
        echo '<tr>
                <th scope="row">' . $index . '</th>
                <td>' . (str_word_count($task->description) > 10 ? implode(' ', array_slice(explode(' ', $task->description), 0, 10)) . ' ...' : $task->description) . '</td>
                <td>' . $task->start_date . '</td>
                <td id="expiration">' . ($isExpired ? 'Expiré' : $remainingTime) . '</td>
                <td>' . ($task->report != null ? 'Disponible' : 'Non disponible') . '</td>
                <td>' . (isset($task->report) && $task->report->comment != null ? 'Disponible' : 'Non Disponible') . '</td>
                <td>
                    <a href="' . route('task_student.show', ['id' => $task->id]) . '" class="btn btn-outline-info">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>';
            $index++;
    }
?>

              @endif <!-- // Fin si tâche existe ou non // -->

              


              </tbody>
                </table>
              </div>

            @else <!-- Si le projet est fini -->



            @endif <!-- fin si (projet fini ou pas) -->
          


        </div>
      </div>
    </div>

  @else


    <h1 class="text-center text-light text-decoration-underline">Vous n'êtes pas relié à un projet</h1>


  @endisset































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
    @section('scripts')

    <script>
        let erreur = <?php echo json_encode(session('erreur', '')) ?>;
      
        if (erreur !== '') {
            alert(erreur);
        }
    </script>

    @endsection
