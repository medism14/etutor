@extends('layouts.app')

@section('content')
  <div class="container">
    
    @if (session('result'))
    <div class="alert alert-success fs-6" role="alert">
        <strong>Succès !</strong> {{ session('result') }}.
    </div>
    @endif


  </div>
<div class="container" id="cont">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (isset($first))
                <div class="card mt-4">
            @else
                <a href="{{ route('accueil') }}" class="btn btn-secondary float-left"><i class="fas fa-arrow-left"></i>Accueil</a>
                <div class="card">
            @endif
            
            
                <div class="card-header">{{ __('Modification mot de passe') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('modif.mdp') }}" enctype="multipart/form-data" onsubmit="return voirmdp()">
                        @csrf
                        <div id="passwords">

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Mot de passe') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <input type="text" value="👀" class="btn btn-primary" id="pass" readonly style="width:50px;">
                                
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Mot de passe confirmation') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                
                            </div>
                            <div class="col-md-2">
                                <input type="text" value="👀" class="btn btn-primary" id="pass_confirmation" readonly style="width:50px;">
                                
                            </div>
                        </div>

                    </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" id="enregistrer">
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
@section('scripts')
<script type="text/javascript">

    const vue = document.getElementById("passwords");

    const password = document.getElementById("password");

    const password_confirmation = document.getElementById("password-confirm"); 

   vue.addEventListener('click',afficher)

   function afficher(event)
    {

        event.preventDefault()
        
        item = event.target;

        if (item.id === 'pass')
        {   
            password.required = true;
            if (password.type === "text")
            {
                password.type = "password";
            }
            else
            {
                password.type = "text";
            }

            
        }
        if (item.id === 'pass_confirmation')
        {
            password_confirmation.required = true;
            if(password_confirmation.type === "text")
            {
                password_confirmation.type = "password";
            }
            else
            {
                password_confirmation.type = "text";
            }
        }

    }

    function voirmdp()
    {
        if (password_confirmation.value != password.value)
        {   
            alert("Les mot de passe ne correspondent pas");
            return false;
        }
        else if (password.value.length < 5)
        {
            alert("Le mot de passe doit contenir 5 lettre ou plus");
            return false;
        }
        else
        {       
            return true;
        }
    }
      



</script>
@endsection
