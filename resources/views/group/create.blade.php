@extends('layouts.app')

@section('content')
<div class="container" id="cont">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="{{ route('groups') }}" class="btn btn-secondary float-left"><i class="fas fa-arrow-left"></i> Retour</a>
            <div class="card">
                <div class="card-header">{{ __('Enregistrement') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('group.create') }}" onsubmit="return validerFormulaire()">
                        @csrf

                        <div class="row mb-3">
                           
                            <table class="">
                                <tr>
                                    <td rowspan="2" style="width:250px;" class="text-center" id="le_td">
                                        <label for="students" class="col-md-4 col-form-label text-md-end">{{ __('Etudiants') }}</label>
                                    </td>

                                    <div class="col-md-6">
                                        <td>
                                            <div class="input-group mb-3">
                                                <select class="custom-select" id="etudiants" style="width:150px;">
                                                    @foreach ($students as $student)
                                                        <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->name }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="text" value="✔️" class="btn btn-primary" id="aj" readonly style="width:50px;">
                                            </div>
                                        </td>
                                        @error('students')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </tr>
                                    <tr>
                                        <td id="pere"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="project_id" class="col-md-4 col-form-label text-md-end">{{ __('Projet') }}</label>

                            <div class="col-md-6">
                                <select id="project_id" type="project_id" class="form-control @error('project_id') is-invalid @enderror" name="project_id" value="{{ old('project_id') }}" required autocomplete="project_id">
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->title }}</option>
                                    @endforeach
                                </select>

                                @error('project_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4 mb-3">
                                <button type="submit" class="btn btn-primary" id="submit">
                                {{ __('Enregistrer') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

<script type="text/javascript">

    const ajouter = document.getElementById('aj'); // Bouton pour activer

    const etudiant = document.getElementById('etudiants'); // LE SELECT avec les options

    const td = document.getElementById('le_td'); // td pour changer de rowpsan de "Etudiants"

    const pere = document.getElementById('pere'); //Pour avoir les input à l'interieur

    const divs = pere.children;


    ajouter.addEventListener('click',nouveau);

    function nouveau(event)
    {

        //Declaration et création d'un nouveau input etudiant

        let rowspan_increment = td.rowSpan + 1;

        if (divs.length == 5)
        {

            ajouter.disabled = true;

        }
        else
        {    

            //Creer un div
            const div = document.createElement('div');
            div.classList.add('memetruc');
            pere.appendChild(div);

            //Creation nouveau input etudiant
            const newEtu = document.createElement('input');

            //incrementer le rowpspan
            td.rowSpan = rowspan_increment;

            //créer un input croix
            const input_croix = document.createElement('input');
            input_croix.type = 'text';
            input_croix.value = '❌';
            input_croix.readOnly = true;
            input_croix.classList.add('btn', 'btn-primary', 'retirer');
            input_croix.style.width = '50px';
            input_croix.style.height = '18px';

            //créer un span et mettre le nom de l'option
            const span = document.createElement('span');
            var text = etudiant.options[etudiant.selectedIndex];
            text = text.innerText;
            span.innerText = text;
            span.classList.add('marge_droite');

            //Modif du nouveau input etudiant
            newEtu.value = etudiant.value;
            let string = rowspan_increment-2
            newEtu.classList.add('marge_droite','students','hidden');
            newEtu.style.width = '250px';
            newEtu.readOnly = true;
            div.appendChild(newEtu)
            div.appendChild(span);
            div.appendChild(input_croix);

            //Enlever l'option si déjà prise
            for(let i = 0; i < etudiant.children.length; i++) 
            {
          let option = etudiant.children[i];
          
            if(etudiant.value === option.value)
            {
                option.remove();
            }

            }

                //Ajout d'un rien si les options sont vides
                if (etudiant.children.length == 0)
                {

                    const opt = document.createElement('option');
                    opt.text = "---------------------------";
                    etudiant.appendChild(opt);
                    ajouter.disabled = true;

                }

                //S'il est égale à 5 pour enlever au lieu qu'il s'enleve après qu'il se coche
                if (divs.length == 5)
                {

                    ajouter.disabled = true;

                }


        }


        name_students();

        



    }



        // MODIF des etudiants

        pere.addEventListener('click',retirer);


        function retirer(event)
        {       

            td.rowSpan = td.rowSpan - 1;

            item = event.target;

            if (item.classList.contains('retirer'))
            {

                //Obtenir le div, l'input et le span de l'élément selectionné 
                 const div = item.parentElement;
                 const input = div.querySelector('.students');
                 const span = div.querySelector('span');


                //Ajouter dans l'option
                const option = document.createElement('option');
                option.innerText = span.innerText;
                option.value = input.value;
                etudiant.appendChild(option);
                div.remove();

            }

            ajouter.disabled = false;

            name_students();

        }



        function name_students(){

            //Ajouter des name en boucle
        let i = 1;


            td.rowSpan = 2;

        for (const div of divs) {


            let rowspan_increment = td.rowSpan + 1;

            td.rowSpan = rowspan_increment;

            const input = div.querySelector('input');

            input.name = String('student' + i);
            i = i + 1;


        }

        }

        function enlever_balise_br()
        {

            for (const div of divs) {

            let span = div.querySelector('span');

            var brElements = span.querySelectorAll('br');

            // Supprimer chaque balise br trouvée
            for (let i = 0; i < brElements.length; i++) {
              brElements[i].parentNode.removeChild(brElements[i]);
            }

        }


        }

        // SUBMIT FINALE

        function validerFormulaire() {
            let i = 0;
          for (const div of divs) {

            i = i + 1;
                                  }

          if (i < 3) {
            alert("Il doit y avoir au moins 3 étudiants.");
            return false;
          }
          return true;
        }

</script>

<style>

    .memetruc
    {
        display: flex;
        align-items: center;
    }
    .marge_droite
    {
        margin-right: 10px;
    }
    .hidden{
        display: none;
    }

</style>

@endsection
