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
  <a href="{{ route('projects_student') }}" class="btn btn-secondary float-left"><i class="fas fa-arrow-left"></i> Retour</a>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $task->description }}</h3>
        </div>
        <div class="card-body">
            
            
            @if ($task->group->project->state != 'fini') <!-- // Si le projet est en cours // -->

                @if (isset($task->report)) <!-- // Si un compte rendu existe // -->



                    @if (isset($task->report->comment)) <!-- // Si un commentaire existe // -->

                    <div class="row">
                      <div class="col-md-8">
                        <h5>Compte-rendu</h5>
                        <form method="POST" action="{{ route('report.edit',['id' => $task->report->id, 'task_id' => $task->id]) }}" enctype="multipart/form-data">
                           {{ @csrf_field() }}
                            <textarea class="form-control" rows="7" placeholder="Modifier le compte-rendu" name="description" disabled>{{ $task->report->description }}</textarea>

                            <div class=" mt-3 mb-3">
                          <?php 
                          $path = $task->report->fichier;
                        $filename = basename($path);

                        if ($filename){
                          echo '<p style="margin:20px 0px;" class="d-flex">' . $filename . ' <a href="'. route('report.delete.file',['id' => $task->report->id]) .' "></a></p>';
                        }


                          ?>
                        </div>

                          <button type="submit" class="btn btn-primary" disabled>Modifier</button>
                        </form>
                    </div>
                    <div class="col-md-4">

                      <h5>Commentaire</h5>
                          <div class="form-group">
                            <p>{{ $task->report->comment->description }}</p>
                          </div>
                            </div>
                        </div>

                    @else <!-- // Si un commentaire existe pas // -->

                      <div class="row">
                    <div class="col-md-8">
                        <h5>Compte-rendu</h5>
                        <form method="POST" action="{{ route('report.edit',['id' => $task->report->id, 'task_id' => $task->id]) }}" enctype="multipart/form-data">
                           {{ @csrf_field() }}
                            <textarea class="form-control" rows="7" placeholder="Modifier le compte-rendu" name="description">{{ $task->report->description }}</textarea>

                            <div class=" mt-3 mb-3">
                          <input type="file" name="fichier" class="form-control"> 
                          <?php 
                          $path = $task->report->fichier;
                        $filename = basename($path);

                        if ($filename){
                          echo '<p style="margin:20px 0px;" class="d-flex">' . $filename . ' <a href="'. route('report.delete.file',['id' => $task->report->id]) .' "><i class="fas fa-times" style="color:red;font-size:25px;margin-left:10px;"></i></a></p>';
                        }

                         


                          ?>
                        </div>

                          <button type="submit" class="btn btn-primary">Modifier</button>
                        </form>
                    </div>
                    <div class="col-md-4">

                      <h5>Commentaire</h5>
                          <div class="form-group">
                            <p>Aucun commentaire</p>
                          </div>
                            </div>
                        </div>

                    @endif <!-- // Fin si commentaire existe ou existe pas // -->



                @else <!-- // Si un compte rendu existe pas // -->



                      <div class="row">
                    <div class="col-md-8">
                        <h5>Compte-rendu</h5>
                        <form method="POST" action="{{ route('report.create',['id' => $task->id]) }}" enctype="multipart/form-data">
                           {{ @csrf_field() }}
                            <textarea class="form-control" rows="7" placeholder="Ecrivez un compte-rendu" name="description"></textarea>
                          
                          <div class=" mt-3 mb-3">
                          <input type="file" name="fichier" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </form>
                    </div>
                    <div class="col-md-4">

                      <h5>Commentaire</h5>
                          <div class="form-group">
                            <p>Aucun commentaire</p>
                          </div>
                            </div>
                        </div>





                @endif <!-- // Fin si compte rendu existe ou non // -->



            @else <!-- // Si le projet est fini // -->



                @if (isset($task->report)) <!-- // Si un compte rendu existe // -->



                    @if (isset($task->report->comment)) <!-- // Si un commentaire existe // -->

                    <div class="row">
                    <div class="col-md-8">
                        <h5>Compte-rendu</h5>
                        
                            <textarea class="form-control" rows="5" name="description">{{ $task->report->description }}</textarea>

                            <div class=" mt-3 mb-3">
                          <input type="file" name="fichier" class="form-control"> 
                          <?php 
                          $path = $task->report->fichier;
                        $filename = basename($path);

                        if ($filename){
                          echo '<p style="margin:20px 0px;" class="d-flex">' . $filename . ' <a href="'. route('report.delete.file',['id' => $task->report->id]) .' "><i class="fas fa-times" style="color:red;font-size:25px;margin-left:10px;"></i></a></p>';
                        }

                         


                          ?>
                        </div>

                            
                    </div>
                    <div class="col-md-4">

                      <h5>Commentaire</h5>
                          <div class="form-group">
                            <p>{{ $task->report->comment->description }}</p>
                          </div>
                            </div>
                        </div>

                    @else <!-- // Si un commentaire existe pas // -->

                      <div class="row">
                    <div class="col-md-8">
                        <h5>Compte-rendu</h5>
                            <textarea class="form-control" rows="5" placeholder="Modifier le compte-rendu" name="description">{{ $task->report->description }}</textarea>

                            <div class=" mt-3 mb-3">
                          <input type="file" name="fichier" class="form-control"> 
                          <?php 
                          $path = $task->report->fichier;
                        $filename = basename($path);

                        if ($filename){
                          echo '<p style="margin:20px 0px;" class="d-flex">' . $filename . ' <a href="'. route('report.delete.file',['id' => $task->report->id]) .' "><i class="fas fa-times" style="color:red;font-size:25px;margin-left:10px;"></i></a></p>';
                        }

                         


                          ?>
                        </div>

                          
                    </div>
                    <div class="col-md-4">

                      <h5>Commentaire</h5>
                          <div class="form-group">
                            <p>Aucun commentaire</p>
                          </div>
                            </div>
                        </div>

                    @endif <!-- // Fin si commentaire existe ou existe pas // -->



                @else <!-- // Si un compte rendu existe pas // -->



                      <div class="row">
                    <div class="col-md-8">
                        <h5>Compte-rendu</h5>
                            <textarea class="form-control" rows="5" placeholder="Ecrivez un compte-rendu" name="description"></textarea>

                    </div>
                    <div class="col-md-4">

                      <h5>Commentaire</h5>
                          <div class="form-group">
                            <p>Aucun commentaire</p>
                          </div>
                            </div>
                        </div>





                @endif <!-- // Fin si compte rendu existe ou non // -->



            @endif <!-- // Fin si projet fini ou non // -->


        </div>
    </div>
</div>



@endsection
<!--  -->
                      