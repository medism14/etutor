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
   
    <div class="container mt-4">
  <a href="{{ route('project_teacher.all.show', ['id' => $task->group->project->id ]) }}" class="btn btn-secondary float-left"><i class="fas fa-arrow-left"></i> Retour</a>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $task->description }}</h3>
        </div>
        <div class="card-body">
            
          <?php 

            if (isset($task->report)) // Si un compte-rendu existe
            {


              echo '<div class="row">
                <div class="col-md-8">
                    <h5>Compte-rendu</h5>
                    <p>' . $task->report->description . '</p>';

                  if($task->report->fichier != null)
                  {

                    echo '<div><a href="' . route('report.download.teacher',['id' => $task->report->id]) . '">
                        <i class="fas fa-download"></i> Télécharger le fichier
                    </a></div>'; 
                    $path = $task->report->fichier;
                        $filename = basename($path);
                        echo "$filename"; // Affiche "MRD.txt"
              }
                echo '</div>

                <div class="col-md-4">';  

                if ($task->group->project->state == 'fini') // Si un projet est fini
                {
                    if(isset($task->report->comment)) // Si un commentaire existe
                    {

                          echo '<h5>Commentaire</h5>
                          <div class="form-group">
                            <textarea class="form-control" rows="5" placeholder="Modifier le commentaire" name="description" disabled>' . $task->report->comment->description . '</textarea>
                          </div>
                            </div>
                        </div>';

                      }
                      else // Si un commentaire existe pas
                      {

                        echo '<h5>Commentaire</h5>
                              <div class="form-group">
                                  <textarea class="form-control" rows="5" placeholder="Ajouter un commentaire" name="description" disabled></textarea>
                              </div>
                            </div>
                        </div>';

                      }
                }
                else // Si un projet est en cours
                {
                    if(isset($task->report->comment)) // Si un commentaire existe
                    {

                          echo '<h5>Commentaire</h5>
                          <form method="POST" action="' . route('comment.edit',['id' => $task->report->comment->id, 'report_id' => $task->report->id]) . '">
                           ' . csrf_field() . '
                          <div class="form-group">
                            <textarea class="form-control" rows="5" placeholder="Modifier le commentaire" name="description">' . $task->report->comment->description . '</textarea>
                          </div>
                          <button type="submit" class="btn btn-primary">Modifier</button>
                        </form>
                            </div>
                        </div>';

                      }
                      else // Si un commentaire existe pas
                      {

                        echo '<h5>Commentaire</h5>
                          <form method="POST" action=" ' . route('comment.create',['report_id' => $task->report->id])  . '">
                          ' . csrf_field() . '
                              <div class="form-group">
                                  <textarea class="form-control" rows="5" placeholder="Ajouter un commentaire" name="description"></textarea>
                              </div>
                              <button type="submit" class="btn btn-primary">Envoyer</button>
                                </form>
                            </div>
                        </div>';

                      }
                }
                      

            }
            else // Si un compte-rendu existe pas
            {

              echo '<div class="row text-center">
                <h2>Le compte-rendu n\'est pas disponible pour l\'instant</h2>
            </div>';

            }

          ?>

        </div>
    </div>
</div>



@endsection
<!--  -->