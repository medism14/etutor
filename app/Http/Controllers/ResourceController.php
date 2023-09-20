<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Models\Resource;

use App\Models\Project;
use App\Models\User;

use Illuminate\Support\Facades\DB;


class ResourceController extends Controller
{
    public function __construct()
    {

        

        $this->creation_validation_rules = [
            'name' => 'required|max:255|string',
            'first_name' => 'required|max:255|string',
            'email' => 'required|unique:resources|email',
            'phone' => 'required|max:30|number',
            'password' => 'required|min:8',
            // 'role' => [Rule::in(array_keys(resource::$enum_role))],
        ];

        $this->resource_creation_validation_error_msg = [
            'name.required' => "Veuillez saisir le nom de l'utilisateur.",
            'first_name.required' => "Veuillez saisir le prenom de l'utilisateur.",
            'email.required' => "Veuillez saisir l'email de l'utilisateur.",
            'phone.required' => "Veuillez saisir le numéro de l'utilisateur.",
            // 'role.required' => "Veuillez saisir le rôle de l'utilisateur.",
            'email.unique' => 'Cette email est déjà pris par un autre utilisateur',
        ];

        $this->resource_own_edit_validation_error_msg = [
            'name.required' => 'Veuillez entrer votre nom.',
            'first_name.required' => 'Veuillez entrer votre prenom.',
            'email.required' => 'Veuillez saisir votre email.',
            'email.unique' => 'Cette email est déjà pris par un autre utilisateur',
            'password.required' => 'Veuillez saisir un mot de passe supérieur à 8 caractère.',
            // 'role.required' => 'Veuillez définir votre rôle.',
        ];
    }
    /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */
    public function index()
    {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        /*$resources = DB::select('SELECT resources.*,projects.title 
                                FROM resources,projects 
                                WHERE resources.project_id = projects.id');*/

        $resources = Resource::with(['project'])->get();

        return view('resource.index',compact('resources'));
    }
    /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */

    public function formulaire()
    {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        return view('resource.create');

    }

        public function create(Request $request, $project_id)
        {   

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
            if (!$project_id)
            {
                return redirect(route('error.all'));
            } 

            $project = Project::findOrFail($project_id);

         if ($project == null)
            {       

                $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
                $query->where('role','=',1)
                      ->where('id','=',Auth()->user()->id);
            }])->get(); // Listes des projets

                $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

                session()->flash('erreur',$erreur);

                return redirect(route('projects_teacher',compact('projects')));
            }
         else if ($project->user_id != Auth()->user()->id)
            {       

                $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
                $query->where('role','=',1)
                      ->where('id','=',Auth()->user()->id);
            }])->get(); // Listes des projets

                $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

                session()->flash('erreur',$erreur);

                return redirect(route('projects_teacher',compact('projects')));
            }   



            if ($project->state == 'fini')
       {
            return redirect(route('resource.shows', ['project_id' => $resource->project_id]));
       }

            if($request->isMethod('post'))
            {   
                $file = $request->file('resource');
                $originalName = $file->getClientOriginalName();
                
                $filePath = $file->storeAs('resources', $originalName);

                $new_resource = new Resource;
                $new_resource->resource = $filePath;
                $new_resource->project_id = $project_id;
                $new_resource->save();

                $new_resource->delete();

                $new_resource = new Resource;
                $new_resource->resource = $filePath;
                $new_resource->project_id = $project_id;
                $new_resource->save();

                session()->flash('result', 'La ressource a bien été créée');

                return redirect(route('resource.shows', ['project_id' => $project_id]))
                    ->with('message.success', 'Enregistrement correctement créé.');
            }

            return view('resource.create', compact('project_id'));
        }
    /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */
    public function edit(Request $request,$id)
    {   

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$id)
        {
            return redirect(route('error.all'));
        } 

        $resource = Resource::findOrFail($id);

        $project = Project::findOrFail($resource->project_id);

         if ($project == null)
        {       

            $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
            $query->where('role','=',1)
                  ->where('id','=',Auth()->user()->id);
        }])->get(); // Listes des projets

            $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

            session()->flash('erreur',$erreur);

            return redirect(route('projects_teacher',compact('projects')));
        }else if ($project->user_id != Auth()->user()->id)
            {       

                $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
                $query->where('role','=',1)
                      ->where('id','=',Auth()->user()->id);
            }])->get(); // Listes des projets

                $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

                session()->flash('erreur',$erreur);

                return redirect(route('projects_teacher',compact('projects')));
            }


        if ($project->state == 'fini')
       {
            return redirect(route('resource.shows', ['project_id' => $resource->project_id]));
       }

        if($request->isMethod('post')){

            $edition_validation_rules = [
                'name' => 'required|max:255|string',
                'first_name' => 'required|max:255|string',
                'email' => 'unique:resources,email,' . $resource->id,
                'role' => '',
                'phone' => 'required|max:30|number',
                'password' => 'nullable|min:8',
                // 'role' => [Rule::in(array_keys(resource::$enum_role))],
            ];

            /*$validator = Validator::make($request->all(), $edition_validation_rules, $this->resource_creation_validation_error_msg);

            if ($validator->fails())
            {
                $messages = $validator->messages();
                // dd($validator->errors());
                return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
            }*/

                $file = $request->file('resource');
                $originalName = $file->getClientOriginalName();
                
                // Vérifier la taille du fichier
                if ($file->getSize() > 50 * 1024 * 1024) {
                    return redirect()
                        ->back()
                        ->withErrors(['erreur' => 'Le fichier ne doit pas dépasser 50 Mo.'])
                        ->withInput();
                }
                
                $filePath = $file->storeAs('resources', $originalName);

                $resource->resource = $filePath;
                $resource->project_id = $resource->project_id;
                $resource->save();

                session()->flash('result', 'La ressource a bien été modifiée');

            return redirect(route('resource.shows', ['project_id' => $resource->project_id]))
                    ->with('message.success', 'Enregistrement correctement créé.');
        }

        return view('resource.edit',compact('resource'));
    }
    /*
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */
    public function shows(Request $request,$project_id)
    {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$project_id)
        {
            return redirect(route('error.all'));
        } 

       $resources = Resource::whereHas('project', function($query) use ($project_id) {
            $query->where('id', $project_id);
        })->with('project')->get();

       $project = Project::findOrFail($project_id);

       if ($project->user_id != Auth()->user()->id)
        {       

            $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
            $query->where('role','=',1)
                  ->where('id','=',Auth()->user()->id);
        }])->get(); // Listes des projets

            $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

            session()->flash('erreur',$erreur);

            return redirect(route('projects_teacher',compact('projects')));
        }


        return view('resource.show',compact('resources','project_id','project'));

    }

        public function shows_student(Request $request,$project_id)
    {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$project_id)
        {
            return redirect(route('error.all'));
        } 

        $user = User::where('id',auth()->user()->id)->with('students.group.project')->first();

        if( $user->students->group->project->id != $project_id )
        {
            $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

            session()->flash('erreur',$erreur);

            return redirect(route('projects_student'));
        }


       $resources = Resource::whereHas('project', function($query) use ($project_id) {
            $query->where('id', $project_id);
        })->with('project')->get();

       $project = Project::findOrFail($project_id);


        return view('resource.show',compact('resources','project_id','project'));

    }

    public function delete(Request $request,$id)
    {   

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$id)
        {
            return redirect(route('error.all'));
        } 

        $resource = Resource::findOrFail($id);

        $project = Project::findOrFail($resource->project_id);

         if ($project == null)
        {       

            $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
            $query->where('role','=',1)
                  ->where('id','=',Auth()->user()->id);
        }])->get(); // Listes des projets

            $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

            session()->flash('erreur',$erreur);

            return redirect(route('projects_teacher',compact('projects')));
        } else if ($project->user_id != Auth()->user()->id)
            {       

                $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
                $query->where('role','=',1)
                      ->where('id','=',Auth()->user()->id);
            }])->get(); // Listes des projets

                $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

                session()->flash('erreur',$erreur);

                

                return redirect(route('projects_teacher',compact('projects')));
            }

        $resource->delete();

        session()->flash('delete', 'La ressource a bien été supprimée');

        return redirect()->back()->with('message.success', 'Enregistrement correctement supprimé');
    }

    public function block_resource(Request $request,$id,$type)
    {   

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$id)
        {
            return redirect(route('error.all'));
        } 

        dd($request->all(),$id,$type);
        $resource = Resource::findOrFail($id);
        if(intval($type)){
            $resource->blocked = 0;
        }else{
            $resource->blocked = 1;
        }        
        $resource->save();

        return redirect()->back()->with('message.success', 'L\'utilisateur à été correctement blocqué.');
    }

    public function download ($id)
    {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$id)
        {
            return redirect(route('error.all'));
        } 

        $resource = Resource::findOrFail($id);

        $project = Project::findOrFail($resource->project_id);

            if ($project->user_id != Auth()->user()->id)
                {       

                    $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
                    $query->where('role','=',1)
                          ->where('id','=',Auth()->user()->id);
                }])->get(); // Listes des projets

                    $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

                    session()->flash('erreur',$erreur);

                    return redirect(route('projects_teacher',compact('projects')));
                }

            $file_path = storage_path('app/'.$resource->resource);

            return response()->download($file_path);


    }

        public function download_student ($id)
    {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$id)
        {
            return redirect(route('error.all'));
        } 
        
        $user = User::where('id',auth()->user()->id)->with('students.group.project')->first();

        $resource = Resource::where('id',$id)->with('project')->first();

        if( $user->students->group->project->id != $resource->project->id )
        {
            $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

            session()->flash('erreur',$erreur);

            return redirect(route('projects_student'));
        }

            $resource = Resource::findOrFail($id);
            $file_path = storage_path('app/'.$resource->resource);
            return response()->download($file_path);

            return redirect()->back()->with('message.success', 'Téléchargement parfaitementt effectué');
    }



}
