@extends('layouts.app')
@section('content')
<div class="container" id="cont">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Enregistrement') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('report.create') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="date" class="col-md-4 col-form-label text-md-end">{{ __('Date') }}</label>

                            <div class="col-md-6">
                                <input id="date" type="datetime-local" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date') }}" required autocomplete="date" autofocus>

                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="description" class="col-md-4 col-form-label text-md-end">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <textarea id="description" class="form-control @error('name') is-invalid @enderror" name="description" rows="4" required autocomplete="description" autofocus>{{ old('description') }}</textarea>


                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="task_id" class="col-md-4 col-form-label text-md-end">{{ __('Tache') }}</label>

                            <div class="col-md-6">
                                <select id="task_id" type="task_id" class="form-control @error('task_id') is-invalid @enderror" name="task_id" value="{{ old('task_id') }}" required autocomplete="task_id">
                                    @foreach($tasks as $task)
                                        <option value="{{ $task->id }}">{{ $task->description }}</option>
                                    @endforeach
                                </select>

                                @error('task_id')
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
