@extends('layouts.app')

@section('content')
<div class="container" id="cont">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="{{ route('users') }}" class="btn btn-secondary float-left"><i class="fas fa-arrow-left"></i> Retour</a>
            <div class="card">
                <div class="card-header">{{ __('Enregistrement') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('user.create') }}" enctype="multipart/form-data" onsubmit="return voirmdp()">
                        @csrf

                        <div class="form-group row mb-5 ">
                            <label for="csv_file" class="col-md-4 col-form-label text-md-right">{{ __('Fichier CSV') }}</label>
                            <div class="col-md-6">
                                <input type="file" class="form-control" name="csv_file" accept=".csv" id="file">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ __('Prenom') }}</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>

                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Nom') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Adresse mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="role" class="col-md-4 col-form-label text-md-end">{{ __('Role') }}</label>

                            <div class="col-md-6">
                                <select id="role" type="role" class="form-control @error('role') is-invalid @enderror" name="role" value="{{ old('role') }}" required autocomplete="role">
                                    <option value="0">Administrateur </option>
                                    <option value="1">Professeur</option>
                                    <option value="2">Etudiant</option>
                                </select>

                                @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Phone') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="number" class="form-control @error('name') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

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


      const file = document.getElementById("file"); 

      file.addEventListener("change", function() {
    if (this.files.length > 0) {
      // Si un fichier est téléchargé, désactiver l'attribut required des autres champs
      document.getElementById("name").removeAttribute("required");
      document.getElementById("first_name").removeAttribute("required");
      document.getElementById("role").removeAttribute("required");
      document.getElementById("email").removeAttribute("required");
      document.getElementById("phone").removeAttribute("required");
      document.getElementById("password").removeAttribute("required");
      document.getElementById("password-confirm").removeAttribute("required");
    } else {
      // Si aucun fichier n'est téléchargé, réactiver l'attribut required des autres champs
      document.getElementById("name").setAttribute("required", "");
      document.getElementById("first_name").setAttribute("required", "");
      document.getElementById("role").setAttribute("required", "");
      document.getElementById("email").setAttribute("required", "");
      document.getElementById("phone").setAttribute("required", "");
      document.getElementById("password").setAttribute("required", "");
      document.getElementById("password-confirm").setAttribute("required", "");
    }
  });

      // sélectionner les éléments du formulaire


    function voirmdp()
    {
        if(file.files.length > 0)
        {
            return true;
        }
        else if (password_confirmation.value != password.value)
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
