@extends('layouts.app')

@section('content')
<div class="container" id="cont">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="{{ route('projects_teacher') }}" class="btn btn-secondary float-left"><i class="fas fa-arrow-left"></i> Retour</a>
            <div class="card">
                <div class="card-header">{{ __('Edition') }}</div>
                
                <div class="card-body">
                    <form method="POST" action="{{ route('project_teacher.edit',['id' => $project->id]) }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="title" class="col-md-4 col-form-label text-md-end">{{ __('Titre') }}</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ $project->title }}" required autocomplete="title" autofocus>

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="description" class="col-md-4 col-form-label text-md-end">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <textarea id="description" class="form-control @error('name') is-invalid @enderror" name="description" rows="4" required autocomplete="description" autofocus>{{ $project->description }}</textarea>


                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="state" class="col-md-4 col-form-label text-md-end">{{ __('Statut') }}</label>

                            <div class="col-md-6">

                                <select id="state" type="state" class="form-control @error('state') is-invalid @enderror" name="state" value="{{ $project->state }}" required autocomplete="state">
                                    <option value="en cours" {{ $project->state == 'en cours' ? 'selected' : '' }}>en cours</option>
                                    <option value="fini" {{ $project->state == 'fini' ? 'selected' : '' }}>fini</option>
                                </select>   

                                @error('state')
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
                                    {{ __('Editer') }}
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
