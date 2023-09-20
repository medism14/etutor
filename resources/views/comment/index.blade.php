@extends('layouts.app')

@section('content')
<div class="container">

	<a href="{{ route('comment.create') }}" class="btn btn-primary float-end">+</a>

</div>

<div class="container mt-5">
<table class="table table-hover table-dark text-center">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Description</th>
      <th scope="col">Report_Id</th>
      <th scope="col">Modifier</th>
      <th scope="col">Supprimer</th>
    </tr>
  </thead>
  <tbody>


  	@foreach($comments as $comment)
    <tr>
      <th scope="row">{{ $comment->id }}</th>
      <td>{{ $comment->description }}</td>
      <td>{{ $comment->report_id }}</td>
      <td>
          <a href="{{ route('comment.edit',['id' => $comment->id]) }}" class="btn btn-outline-warning">
            Modifier
          </a>
      </td>
      <td>
        <a href="{{ route('comment.delete',['id' => $comment->id]) }}" class="btn btn-outline-danger">
            Supprimer
        </a>
      </td>
    </tr>
    @endforeach

  </tbody>
</table>
	
</div>
@endsection
