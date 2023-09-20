<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Models\Task;

use App\Models\Group;

use App\Models\Project;

use Illuminate\Support\Facades\DB;


class TaskController extends Controller
{
    public function __construct()
    {

        

        $this->creation_validation_rules = [
            'name' => 'required|max:255|string',
            'first_name' => 'required|max:255|string',
            'email' => 'required|unique:tasks|email',
            'phone' => 'required|max:30|number',
            'password' => 'required|min:8',
            // 'role' => [Rule::in(array_keys(task::$enum_role))],
        ];

        $this->task_creation_validation_error_msg = [
            'name.required' => "Veuillez saisir le nom de l'utilisateur.",
            'first_name.required' => "Veuillez saisir le prenom de l'utilisateur.",
            'email.required' => "Veuillez saisir l'email de l'utilisateur.",
            'phone.required' => "Veuillez saisir le numéro de l'utilisateur.",
            // 'role.required' => "Veuillez saisir le rôle de l'utilisateur.",
            'email.unique' => 'Cette email est déjà pris par un autre utilisateur',
        ];

        $this->task_own_edit_validation_error_msg = [
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
    /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */

    public function formulaire()
    {

        return view('task.create');

    }

    public function create(Request $request,$group_id)
    {   

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$group_id)
        {
            return redirect(route('error.all'));
        } 

        $group = Group::where('id',$group_id)->with('project')->first();

            if($group == null)
            {
                $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
                $query->where('role','=',1)
                      ->where('id','=',Auth()->user()->id);
            }])->get(); // Listes des projets

                $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

                session()->flash('erreur',$erreur);

                return redirect(route('projects_teacher',compact('projects')));
            }
            else if ($group->project->user_id != Auth()->user()->id)
            {       

                $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
                $query->where('role','=',1)
                      ->where('id','=',Auth()->user()->id);
            }])->get(); // Listes des projets

                $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

                session()->flash('erreur',$erreur);

                return redirect(route('projects_teacher',compact('projects')));
            }


        if($request->isMethod('post'))
        {
            /*$validator = Validator::make($request->all(), $this->creation_validation_rules, $this->task_creation_validation_error_msg);

            if ($validator->fails())
            {
                $messages = $validator->messages();
                return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
            }*/


            $new_task = new Task;
            $new_task->description = $request->get('description');
            $new_task->start_date = $request->get('start_date');
            $new_task->end_date = $request->get('end_date');
            $new_task->group_id = $group_id;

            $group = Group::findOrFail($group_id);

            $new_task->save();

            session()->flash('result', 'La tâche a bien été créée');

            return redirect()->route('project_teacher.all.show', ['id' => $group->project_id])->with('message.success', 'Enregistrement correctement créé.');
        
        
        }
        return view('task.create',compact('group_id'));
    }
    /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */
    public function edit(Request $request, $id, $group_id)
    {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$group_id || !$id)
        {
            return redirect(route('error.all'));
        } 

        $task = Task::where('id',$id)->with('group', function($group)
            {
                $group->with('project');
            })->first();

        $group = Group::findOrFail($group_id);

        if ($task->group->project->user_id != Auth()->user()->id || $task->group->id != $group_id)
        {       

            $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
            $query->where('role','=',1)
                  ->where('id','=',Auth()->user()->id);
        }])->get(); // Listes des projets

            $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

            session()->flash('erreur',$erreur);

            return redirect(route('projects_teacher',compact('projects')));
        }


        if($request->isMethod('post')){

            $edition_validation_rules = [
                'name' => 'required|max:255|string',
                'first_name' => 'required|max:255|string',
                'email' => 'unique:tasks,email,' . $task->id,
                'role' => '',
                'phone' => 'required|max:30|number',
                'password' => 'nullable|min:8',
                // 'role' => [Rule::in(array_keys(task::$enum_role))],
            ];

            /*$validator = Validator::make($request->all(), $edition_validation_rules, $this->task_creation_validation_error_msg);

            if ($validator->fails())
            {
                $messages = $validator->messages();
                // dd($validator->errors());
                return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
            }*/

            $task->description = $request->get('description');
            $task->start_date = $request->get('start_date');
            $task->end_date = $request->get('end_date');
            

            $task->save();

            session()->flash('result', 'La tâche a bien été modifiée');

            return redirect()->route('project_teacher.all.show', ['id' => $group->project_id])->with('message.success', 'Enregistrement correctement créé.');
        }

        return view('task.edit',compact('task'));
    }
    /*
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */
    public function show(Request $request,$id)
    {   

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$id)
        {
            return redirect(route('error.all'));
        } 

        $task = Task::where('id',$id)->with('group', function($group)
            {
                $group->with('project');
            })->first();

        if ($task == null)
        {
            $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
                $query->where('role','=',1)
                      ->where('id','=',Auth()->user()->id);
            }])->get(); // Listes des projets

                $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

                session()->flash('erreur',$erreur);

                return redirect(route('projects_teacher',compact('projects')));
        }
        else if ($task->group->project->user_id != Auth()->user()->id)
            {       

                $projects = Project::where('user_id','=',Auth()->user()->id)->with(['teacher' => function($query) {
                $query->where('role','=',1)
                      ->where('id','=',Auth()->user()->id);
            }])->get(); // Listes des projets

                $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

                session()->flash('erreur',$erreur);

                return redirect(route('projects_teacher',compact('projects')));
            }

        $task = Task::with(['report' => function($report) {
            $report->with('comment');
        }])->findOrFail($id);

        return view('task.show',compact('task'));
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

        $task = Task::where('id',$id)->with('group', function($group){
            $group->with('project');
        })->first();

        if ($task->group->project->user_id != Auth()->user()->id)
        {       


            $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres professeurs';

            session()->flash('erreur',$erreur);

            return redirect(route('projects_teacher',compact('projects')));
        }

        $task = Task::findOrFail($id);

 
                                if ($task->report)
                                {   
                                    if ($task->report->comment)
                                    {
                                        $task->report->comment->delete();
                                    }

                                    $task->report->delete();
                                }
                            

        $task->delete();

        session()->flash('delete', 'La tâche a bien été supprimée');

        return redirect()->back()->with('message.success', 'Enregistrement correctement supprimé');
    }

    public function block_task(Request $request,$id,$type)
    {

        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        dd($request->all(),$id,$type);
        $task = Task::findOrFail($id);
        if(intval($type)){
            $task->blocked = 0;
        }else{
            $task->blocked = 1;
        }        
        $task->save();

        return redirect()->back()->with('message.success', 'L\'utilisateur à été correctement blocqué.');
    }

        /* 
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
        ***************************** TASK STUDENT ************************************************
        ******************************************************************************************
        ******************************************************************************************
        ******************************************************************************************
    */

        public function show_student(Request $request,$id)
    {
        
        if(auth()->user()->first_connection == 1)
            {
                return redirect(route('modif.mdp'));
            }
        
        if (!$id)
        {
            return redirect(route('error.all'));
        } 

        $i = 0;
        $project = Project::whereHas('group.tasks', function($tasks) use ($id)
            {
                $tasks->where('id',$id);
            })->with('group.studentgroup.student')->first();

        
        foreach($project->group->studentgroup as $studentgroup)
        {
            if ($studentgroup->student->id == Auth()->user()->id){
                $i = 667;
            }
        }

        if ($project == null)
        {       
                $currentDateTime = date('Y-m-d H:i:s', strtotime('+3 hours'));

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

                    $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres etudiants';

                    session()->flash('erreur',$erreur);

                    return redirect(route('projects_student',compact('project')));
        }else if ($i != 667)
            {       
                    $currentDateTime = date('Y-m-d H:i:s', strtotime('+3 hours'));
                
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

                        $erreur = 'Vous ne pouvez pas accéder aux projets d\'autres etudiants';

                        session()->flash('erreur',$erreur);

                        return redirect(route('projects_student',compact('project')));
            }

        $task = Task::with(['report' => function($report) {
                $report->with('comment');
            }])
            ->findOrFail($id);

        return view('task_student.show',compact('task'));
        
    }

}
