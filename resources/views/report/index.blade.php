@extends('layouts.app')

@section('content')
<div class="container">

	<a href="{{ route('report.create') }}" class="btn btn-primary float-end">+</a>

</div>

<div class="container mt-5">
<table class="table table-hover table-dark text-center">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Date</th>
      <th scope="col">Description</th>
      <th scope="col">tache id</th>
      <th scope="col">Modifier</th>
      <th scope="col">Supprimer</th>
    </tr>
  </thead>
  <tbody>


  	@foreach($reports as $report)
    <tr>
      <th scope="row">{{ $report->id }}</th>
      <td>{{ $report->date }}</td>
      <td>{{ $report->description }}</td>
      <td>{{ $report->task_id }}</td>
      <td>
          <a href="{{ route('report.edit',['id' => $report->id]) }}" class="btn btn-outline-warning">
            Modifier
          </a>
      </td>
      <td>
        <a href="{{ route('report.delete',['id' => $report->id]) }}" class="btn btn-outline-danger">
            Supprimer
        </a>
      </td>
    </tr>
    @endforeach

  </tbody>
</table>
	
</div>
@endsection
