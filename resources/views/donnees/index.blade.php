@extends('layouts.app')

@section('content')
<div class="container">

	<a href="{{ route('project.create') }}" class="btn btn-primary float-end">+</a>

</div>

<div class="container mt-5">
<table class="table table-hover table-dark text-center">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col"></th>
      <th scope="col">Modifier</th>
      <th scope="col">Supprimer</th>
    </tr>
  </thead>
  <tbody>


  	@foreach($s as $)
    <tr>
      <th scope="row">{{ $->id }}</th>
      <td>{{ $-> }}</td>
          <a href="{{ route('.edit',['id' => $->id]) }}" class="btn btn-outline-warning">
            Modifier
          </a>
      </td>
      <td>
        <a href="{{ route('project.delete',['id' => $->id]) }}" class="btn btn-outline-danger">
            Supprimer
        </a>
      </td>
    </tr>
    @endforeach

  </tbody>
</table>
	
</div>
@endsection
