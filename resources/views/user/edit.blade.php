@extends('layouts.app')

@section('content')
<div class="container" id="cont">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="{{ route('users') }}" class="btn btn-secondary float-left"><i class="fas fa-arrow-left"></i> Retour</a>
            <div class="card">
                <div class="card-header">{{ __('Enregistrement') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('user.edit',['id' => $user->id]) }}" onsubmit="return voirmdp()">
                        @csrf


                        <div class="row mb-3">
                            <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ __('Prenom') }}</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('name') is-invalid @enderror" name="first_name" value="{{ $user->first_name }}" required autocomplete="first_name" autofocus>

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
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required autocomplete="name" autofocus>

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
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required autocomplete="email">

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
                                <select id="role" type="role" class="form-control @error('role') is-invalid @enderror" name="role" required autocomplete="role">
                                    <option value="0" {{ $user->role == 0 ? 'selected' : ''}}>Administrateur </option>
                                    <option value="1" {{ $user->role == 1 ? 'selected' : ''}}>Professeur</option>
                                    <option value="2" {{ $user->role == 2 ? 'selected' : ''}}>Etudiant</option>
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
                                <input id="phone" type="number" class="form-control @error('name') is-invalid @enderror" name="phone" value="{{ $user->phone }}" required autocomplete="phone" autofocus>

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
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
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
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                                
                            </div>
                            <div class="col-md-2">
                                <input type="text" value="👀" class="btn btn-primary" id="pass_confirmation" readonly style="width:50px;">
                                
                            </div>
                        </div>

                    </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
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

function voirmdp() {
    if (password_confirmation.value.length === 0 && password.value.length === 0) {
        return true;
    } else {
        if (password_confirmation.value !== password.value) {
            alert("Les mots de passe ne correspondent pas");
            return false;
        } else if (password.value.length < 5) {
            alert("Le mot de passe doit contenir 5 lettres ou plus");
            return false;
        } else {
            return true;
        }
    }
}
    


</script>
@endsection
