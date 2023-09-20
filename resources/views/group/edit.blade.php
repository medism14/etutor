@extends('layouts.app')

@section('content')
<div class="container" id="cont">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="{{ route('groups') }}" class="btn btn-secondary float-left"><i class="fas fa-arrow-left"></i> Retour</a>
            <div class="card">
                <div class="card-header">{{ __('Edition') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('group.edit',['id' => $group->id]) }}" onsubmit="return validerFormulaire()">
                        @csrf



                        <div class="row mb-3">
                           
                            <table class="">
                                <tr>
                                    <td rowspan="2" style="width:250px;" class="text-center" id="le_td">
                                        <label for="students" class="col-md-4 col-form-label text-md-end">{{ __('Etudiants') }}</label>
                                    </td>

                                    <div class="col-md-6">
                                        <td>
                                            <div class="input-group mb-3 select_input">
                                                <select class="custom-select" id="etudiants" style="width:150px;">
                                                    @foreach ($students_nogroup as $student_nogroup)
                                                        <option value="{{ $student_nogroup->id }}">
                                                            {{ $student_nogroup->first_name }} {{ $student_nogroup->name }}
                                                        </option>
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
                                        <td id="pere">
                                            
                                            @foreach ($students_group as $student_group)

                                            <div class="memetruc">

                                                <input type="text" readonly value="{{ $student_group->user_id }}" class="students marge_droite hidden" name="students">
                                        
                                                <span class="marge_droite">{{ $student_group->student->first_name }} {{ $student_group->student->name }} </span>
                                                <input type="text" value="❌" class="btn btn-primary retirer" readonly style="width:50px;height:18px;">
                                            </div>

                                            @endforeach

                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>



                        <div class="row mb-3">
                            <label for="project_id" class="col-md-4 col-form-label text-md-end">{{ __('Projet') }}</label>

                            <div class="col-md-6">

                                <select id="project_id" type="project_id" class="form-control @error('project_id') is-invalid @enderror" name="project_id" required autocomplete="project_id">
                                    
                                    @foreach ($projects as $project)

                                        <option value="{{ $project->id }}" {{ $project->id == $project_id_actuel ? 'selected' : '' }}> {{ $project->title }}</option>

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
@section('scripts')

    <script>

        /////////////////////// PARTIE AJOUT //////////////////////////////////
        

            const ajouter = document.getElementById('aj'); // Bouton pour activer

    const etudiant = document.getElementById('etudiants'); // LE SELECT avec les options

    const td = document.getElementById('le_td'); // td pour changer de rowpsan de "Etudiants"

    const pere = document.querySelector('#pere');

    const divs = pere.children;

    name_students();
    enlever_balise_br();


    //Incrementer le rowspan
        for (let i = 0 ; i < {{ $nombre_students }} ; i++)
        {
            td.rowSpan = td.rowSpan +1;
        }

    //EVENT
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


            //créer un span et mettre le nom de l'option
            const span = document.createElement('span');
            let opt = etudiant.options[etudiant.selectedIndex];
            text = opt.innerText;
            span.innerText = text;
            span.classList.add('marge_droite');

            //créer un input croix
            const input_croix = document.createElement('input');
            input_croix.type = 'text';
            input_croix.value = '❌';
            input_croix.readOnly = true;
            input_croix.classList.add('btn', 'btn-primary', 'retirer');
            input_croix.style.width = '50px';
            input_croix.style.height = '18px';

            //Modif du nouveau input etudiant
            let optionSelected = etudiant.options[etudiant.selectedIndex];
            newEtu.type = 'text';
            name = 'students';
            newEtu.name = name;
            newEtu.classList.add('marge_droite' , 'students' ,'hidden');
            newEtu.readOnly = true;
            newEtu.value = optionSelected.value;
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

                if (divs.length == 5)
                {

                    ajouter.disabled = true;

                }

                //S'il est égale à 5 pour enlever au lieu qu'il s'enleve après qu'il se coche


        }



        name_students();
        enlever_balise_br();
        afficher_console_fin();
        re_arranger_input();
        
            

    }



        /////////////////////// PARTIE EDIT //////////////////////////////////


        
        //Recuperer tout
        

        //supprimer les membres du groupes
        pere.addEventListener('click',retirer);


        //fonction pour retirer
        function retirer(event)
        {

            const item = event.target;

            if (item.classList.contains('retirer'))
            {

                td.rowSpan = td.rowSpan - 1;

                

                if (td.rowSpan-2 > 6)
                {
                    ajouter.disabled = false;
                }



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


            ajouter.disabled = false;
                name_students();
                enlever_balise_br();
            // afficher_console_fin();

            }

            

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

        
        

        function afficher_console_fin()
        {   
            console.log(pere);
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

        function re_arranger_input()
        {

            for (const div of divs) {

            let input = div.querySelector('input');

            input.value = input.value;

            console.log(input);

        }


        }


        //// TOUT AFFICHER
      /*  let page = document.getElementById('cont');

        cont.addEventListener('click',afficher_div);

        function afficher_div(event)
        {

            item = event.target;

            console.log(item);

        }*/

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
