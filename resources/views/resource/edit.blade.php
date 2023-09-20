@extends('layouts.app')

@section('content')
<div class="container" id="cont">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="{{ route('resource.shows',['project_id' => $resource->project->id]) }}" class="btn btn-secondary float-left"><i class="fas fa-arrow-left"></i> Retour</a>
            <div class="card">
                <div class="card-header">{{ __('Enregistrement') }}</div>
                @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
                <div class="card-body">
                    <form method="POST" action="{{ route('resource.edit', ['id' => $resource->id ]) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label for="resource" class="col-md-4 col-form-label text-md-end">{{ __('Ressource') }}</label>
                            <div class="col-md-6">
                                <input id="resource" type="file" class="form-control @error('resource') is-invalid @enderror" name="resource" required autocomplete="resource" autofocus>
                                        <strong>
                                            <?php 
                                              $path = $resource->resource;
                                            $filename = basename($path);
                                            echo $filename;
                                              ?>
                                        </strong>
                                @error('resource')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        

                    </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4 mb-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Enregistrer') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
