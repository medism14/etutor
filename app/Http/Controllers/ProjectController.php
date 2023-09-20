<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Models\Project;
use App\Models\Group;
use App\Models\Task;
use App\Models\Report;
use App\Models\Comment;

use App\Models\User;

use App\Models\Resource;

use Illuminate\Support\Facades\DB;


class ProjectController extends Controller
{
    public function __construct()
    {

        

        $this->creation_validation_rules = [
            'name' => 'required|max:255|string',
            'first_name' => 'required|max:255|string',
            'email' => 'required|unique:projects|email',
            'phone' => 'required|max:30|number',
            'password' => 'required|min:8',
            // 'role' => [Rule::in(array_keys(project::$enum_role))],
        ];

        $this->project_creation_validation_error_msg = [
            'name.required' => "Veuillez saisir le nom de l'utilisateur.",
            'first_name.required' => "Veuillez saisir le prenom de l'utilisateur.",
            'email.required' => "Veuillez saisir l'email de l'utilisateur.",
            'phone.required' => "Veuillez saisir le numéro de l'utilisateur.",
            // 'role.required' => "Veuillez saisir le rôle de l'utilisateur.",
            'email.unique' => 'Cette email est déjà pris par un autre utilisateur',
        ];

        $this->project_own_edit_validation_error_msg = [
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
        *****************************PROJECT TEACHER************************************************
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */


    public function index_teacher()
    {


        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        /*$projects = DB::select('SELECT projects.*,users.first_name 
                                FROM projects,users 
                                WHERE projects.user_id = users.id');*/

        $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
            $query->where('role','=',1)
                  ->where('id','=',Auth()->user()->id);
        }])->get();

        
        return view('project_teacher.index',compact('projects'));
    }
    /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */

    public function formulaire_teacher()
    {

        return view('project_teacher.create');

    }

    public function create_teacher(Request $request)
    {   

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        // $users = DB::select('SELECT * FROM users WHERE role = ?', [1]);


        if($request->isMethod('post'))
        {
            /*$validator = Validator::make($request->all(), $this->creation_validation_rules, $this->project_creation_validation_error_msg);

            if ($validator->fails())
            {
                $messages = $validator->messages();
                return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
            }*/


            $new_project = new Project;
            $new_project->title = $request->get('title');
            $new_project->description = $request->get('description');
            $new_project->state = 'en cours';
            $new_project->user_id = Auth()->user()->id;
            $new_project->save();

            session()->flash('result', 'Le projet a bien été créé');

            return redirect(route('projects_teacher'))->with('message.success', 'Enregistrement correctement créé.');
        
        
        }
        return view('project_teacher.create');
    }
    /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */
    public function edit_teacher(Request $request,$id)
    {       

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$id)
        {
            return redirect(route('error.all'));
        } 

            $project = Project::findOrFail($id);

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


        $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
            $query->where('role','=',1)
                  ->where('id','=',Auth()->user()->id);
        }])->get(); // Listes des projets

        $project = Project::with('resource')->findOrFail($id);

        if($request->isMethod('post')){

            $edition_validation_rules = [
                'name' => 'required|max:255|string',
                'first_name' => 'required|max:255|string',
                'email' => 'unique:projects,email,' . $project->id,
                'role' => '',
                'phone' => 'required|max:30|number',
                'password' => 'nullable|min:8',
                // 'role' => [Rule::in(array_keys(project::$enum_role))],
            ];

            /*$validator = Validator::make($request->all(), $edition_validation_rules, $this->project_creation_validation_error_msg);

            if ($validator->fails())
            {
                $messages = $validator->messages();
                // dd($validator->errors());
                return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
            }*/

            $project->title = $request->get('title');
            $project->description = $request->get('description');
            $project->state = $request->get('state');


            $project->save();

            session()->flash('result', 'Le projet a bien été modifié');

            return redirect(route('projects_teacher',compact('projects')));
        }

        return view('project_teacher.edit',compact('project'));
    }
    /*
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */
    public function show_teacher(Request $request,$id)
    {   

        if(auth()->user()->first_connection == 1)
        {
            return redirect(route('modif.mdp'));
        }
        
        if (!$id)
        {
            return redirect(route('error.all'));
        } 

    $currentDateTime = date('Y-m-d H:i:s', strtotime('+3 hours'));

        $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
            $query->where('role','=',1)
                  ->where('id','=',Auth()->user()->id);
        }])->get(); // Listes des projets


        $project = Project::where('id','like',$id)->with(['resource','group' => // Project en questions

            function($project_group) use ($currentDateTime)
            {
                $project_group->with(['tasks' =>

                    function ($tasks) use ($currentDateTime)
                    {
                        $tasks->with(['report' =>

                            function ($report) use ($currentDateTime)
                            {
                                $report->with(['comment']);
                            }

                    ])->orderBy('end_date','ASC');
                    }

                ,'studentgroup' =>

                    function($studentgroup)
                    {
                        $studentgroup->with(['student']);
                    }

                ]);
            }

            ])->first();


            
        if ($project->user_id != Auth()->user()->id)
        {       


            $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

            session()->flash('erreur',$erreur);

            return redirect(route('projects_teacher',compact('projects')));
        }
        else
        {
        return view('project_teacher.show',compact('project'));
        }
    }

    public function validate_teacher(Request $request,$id)
    {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$id)
        {
            return redirect(route('error.all'));
        } 

        $project = Project::findOrFail($id);

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

        $project->state = 'fini';

        $project->save();

        session()->flash('result', 'Le projet est à présent terminé');

        return redirect(route('projects_teacher'))->with('message.success', 'modification correctement sauvegardé.');
    }


    public function delete_teacher(Request $request,$id)
    {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$id)
        {
            return redirect(route('error.all'));
        } 

                    $project = Project::findOrFail($id);

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



            if ($project) {
                    if ($project->group) {
                        $project->group->studentgroup()->delete();
                        
                        if ($project->group->tasks) {

                            foreach ($project->group->tasks as $task)
                            {
                                if ($task->report)
                                {   
                                    if ($task->report->comment)
                                    {
                                        $task->report->comment->delete();
                                    }

                                    $task->report->delete();
                                }
                            }

                            $project->group->tasks()->delete();
                        }

                        $project->group()->delete();
                    }

                    if ($project->resource) { // Vérifie s'il y a des ressources liées au projet
                        $project->resource()->delete(); // Supprime tous les enregistrements liés dans la table resources
                    }

                    $project->delete();
                }

        $project->delete();

        session()->flash('delete', 'Le projet a bien été supprimé');

        return redirect()->back()->with('message.success', 'Enregistrement correctement supprimé');
    }



    /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
        ***************************** PROJECT STUDENT ************************************************
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */

    
    public function index_student()
{

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
    $currentDateTime = date('Y-m-d H:i:s', strtotime('+3 hours'));

    $student_group = User::where('id', '=', auth()->user()->id)
                     ->whereHas('students')
                     ->with('students')
                     ->get();

    if ($student_group == '[]') // Si l'etudiant n'as pas de groupe de projet
    {
        $project = null;

        return view('project_student.index  ',compact('project'));
        
    }
    else // Si l'etudiant a un groupe de projet
    {


$project = Project::whereIn('id',function ($query) use ($currentDateTime){
    $query->select('project_id')
        ->from('groups')
        ->whereIn('id', function($query) use ($currentDateTime){
            $query->select('group_id')
                ->from('studentgroups')
                ->where('user_id','like',auth()->user()->id);
        });
})->with(['group' => // Project en questions
    function($project_group) use ($currentDateTime)
    {
        $project_group->with(['tasks' =>
            function ($tasks) use ($currentDateTime)
            {
                $tasks->with(['report' =>
                    function ($report)  
                    {
                        $report->with(['comment']);
                    }
                ])->where('end_date','>',$currentDateTime);
            },
            'studentgroup' =>
            function($studentgroup)
            {
                $studentgroup->with(['student']);
            }
        ]);
    }
])->first();


        return view('project_student.index',compact('project'));

   
    }



    
}







}
