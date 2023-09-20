@extends('layouts.app')

@section('content')
<div class="container" id="cont">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edition') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('.edit',['id' => $->id]) }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="x" class="col-md-4 col-form-label text-md-end">{{ __('x') }}</label>

                            <div class="col-md-6">
                                <input id="x" type="text" class="form-control @error('x') is-invalid @enderror" name="x" value="{{ $project->x }}" required autocomplete="x" autofocus>

                                @error('x')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="x" class="col-md-4 col-form-label text-md-end">{{ __('x') }}</label>

                            <div class="col-md-6">
                                <input id="x" type="text" class="form-control @error('x') is-invalid @enderror" name="x" value="{{ $project->x }}" required autocomplete="x" autofocus>

                                @error('x')
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
