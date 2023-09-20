@extends('layouts.app')

@section('content')
<div class="container" id="cont">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Enregistrement') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('comment.edit',['id' => $comment->id]) }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="description" class="col-md-4 col-form-label text-md-end">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <textarea id="description" class="form-control @error('name') is-invalid @enderror" name="description" rows="4" required autocomplete="description" autofocus>{{ $comment->description }}</textarea>


                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="report_id" class="col-md-4 col-form-label text-md-end">{{ __('Compte-rendu') }}</label>

                            <div class="col-md-6">
                                <select id="report_id" type="report_id" class="form-control @error('report_id') is-invalid @enderror" name="report_id" value="{{ old('report_id') }}" required autocomplete="report_id">
                                    @foreach($reports as $report)
                                        <option value="{{ $report->id }}" {{ $comment->report_id == $report->id ? 'selected' : ''}}>{{ $report->description }}</option>
                                    @endforeach
                                </select>

                                @error('report_id')
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
