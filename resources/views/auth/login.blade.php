@extends('layouts.app')

<style>
.custom-form-width {
  max-width: 450px;
}
</style>

@section('content')

<!-- Section: Design Block -->
<section class="text-center">
  <!-- Background image -->
  <div class="p-5 bg-image" style="
        height: 300px;
        margin-top: -150px;
        "></div>
  <!-- Background image -->

  <div class="d-flex justify-content-center">
    <div class="card mx-4 mx-md-5 shadow-5-strong custom-form-width" style="
          margin-top: -100px;
          background: hsla(0, 0%, 100%, 0.8);
          backdrop-filter: blur(30px);
          ">
          <h6 class="text-danger text-center"></h6>
      <div class="card-body py-5 px-md-5" style="width:400px;">

        <div class="row d-flex justify-content-center" >
          <div class="col-lg-12">
            <h2 class="fw-bold mb-5">Connexion</h2>
            <form method="POST" action="{{ route('login') }}">
              @csrf

              <!-- Email input -->
              <div class="form-outline mb-4">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>Vous avez entré des fausses informations</strong>
                  </span>
                @enderror
                <label class="form-label" for="form3Example3">Adresse Email</label>
              </div>

              <!-- Password input -->
              <div class="row">
                <div class="col-md-10">
              <div class="form-outline mb-4">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                  <span class="invalid-feedback" role="alert">
                    <strong>Vous avez entré des fausses informations</strong>
                  </span>
                @enderror
                <label class="form-label" for="form3Example4">Mot de passe</label>
              </div>
            </div>
            <div class="col-md-2">
                <input type="text" value="👀" class="btn btn-primary" id="pass" readonly style="width:50px;">
            </div>

              </div>

              <!-- Submit button -->
              <button type="submit" class="btn btn-primary btn-block mb-4">
                Se connecter
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
<script>
@if(session('error'))
    
      var errorMessage = '{{ session('error') }}';
      alert(decodeURIComponent(errorMessage));
    
@endif

const password = document.getElementById("password");

const pass = document.getElementById("pass");

pass.addEventListener('click',afficher);

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

    }

</script>
</section>
<!-- Section: Design Block -->
@endsection
